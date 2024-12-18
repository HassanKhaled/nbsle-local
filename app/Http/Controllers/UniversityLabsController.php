<?php

namespace App\Http\Controllers;

use App\Models\LabStaff;
use App\Models\services;
use App\Models\Staff;
use App\Models\UniCoor;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UniversityLabsController extends Controller
{
    /*** Display a listing of the resource.*/
    public function index()
    {
        abort_unless(Gate::allows('faculty') , 403);
        $unis = universitys::all()->keyBy('id');
        if (auth()->user()->hasRole('admin'))
        {
            $labs = UniLabs::whereIn('uni_id',$unis->keys())->get();

        }
        else {
            $labs = UniLabs::where('uni_id', Auth()->user()->uni_id)->get();
        }
//        dd($unis);
        $labcoors = LabStaff::where('central',1)->pluck('manager_id');
        $coors = Staff::whereIn('id',$labcoors)->get();
//        dd($labs,$coors);
        return view('UniLabs.index',compact('labs','coors','unis'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /*** Show the form for creating a new resource.*/
    public function create()
    {
        abort_unless(Gate::allows('faculty') , 403);
        $universities = universitys::all(); // for system admin to choose
        return view('UniLabs.create',compact('universities'));
    }

    /*** Store a newly created resource in storage.*/
    public function store(Request $request)
    {
        abort_unless(Gate::allows('faculty') , 403);
//        dd($request);
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'uni_id' => 'nullable',
            'location'=> 'nullable',
            'names.*' => 'required',
            'phones.*' => 'required|numeric|min:11',
            'emails.*' => 'nullable|email',
            'staffs.*'=>'required'
            ]);
        if (!auth()->user()->hasRole('admin')) {
            $request->request->add(['uni_id' => Auth()->user()->uni_id]);
        }
        $lab = UniLabs::firstOrCreate(array('name'=>$request['name'],'Arabicname'=>$request['Arabicname'],'uni_id'=>$request['uni_id'],'location'=>$request['location']));
        $coord = collect();
        for ($i = 0; $i < count($request['names']); $i++)
        {
            if ($request['names'][$i]==null)
                continue;
            else {
                $staff = Staff::create(['name' => $request['names'][$i], 'telephone' => $request['phones'][$i], 'email' => $request['emails'][$i], 'staff' => $request['staffs'][$i]]);
                $lab_staff = LabStaff::create(['lab_id'=>$lab->id,'manager_id'=>$staff->id,'central'=>'1']);
            }
        }
        session()->flash('message' ,'Lab added successfully.' );
        return redirect()->route('UniLab.index');
    }

    /*** Display the specified resource.*/
    public function show($id)
    {    }

    /*** Show the form for editing the specified resource*/
    public function edit($id)
    {
        abort_unless(Gate::allows('faculty') , 403);

        $universities = universitys::all(); // for system admin to choose
        $lab = UniLabs::find($id);
        $staff = LabStaff::where('lab_id',$id)->where('central',1)->pluck('manager_id');
        $coors = Staff::whereIn('id',$staff)->get();

        return view('UniLabs.edit',compact('lab','coors','universities'));
    }

    /*** Update the specified resource in storage.*/
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('faculty') , 403);

        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'uni_id' => 'nullable',
            'location'=> 'nullable',
            'names.*' => 'required',
            'phones.*' => 'required|numeric|min:11',
            'emails.*' => 'nullable|email',
            'staffs.*'=>'required'
        ]);

        $lab = UniLabs::find($id);
        $lab->update($request->all());
        $coor_lab = LabStaff::where('lab_id',$id)->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::where('lab_id',$id)->delete();
        for ($i = 0; $i < count($request['names']); $i++)
        {
            if ($request['names'][$i]==null)
                continue;
            else {
                $staff = Staff::create(['name' => $request['names'][$i], 'telephone' => $request['phones'][$i], 'email' => $request['emails'][$i], 'staff' => $request['staffs'][$i]]);
                $lab_staff = LabStaff::create(['lab_id'=>$lab->id,'manager_id'=>$staff->id,'central'=>'1']);
            }
        }
        return redirect()->route('UniLab.index')
            ->with('success','Lab updated successfully');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        abort_unless(Gate::allows('faculty') , 403);
//dd($id);
        services::join('uni_devices','services.device_id','uni_devices.id')->where('uni_devices.lab_id',$id)->delete();
        UniLabs::find($id)->delete();
        $coor_lab = LabStaff::where('lab_id',$id)->where('central','1')->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::where('lab_id',$id)->where('central','1')->delete();
//        $devices = UniDevices::where('lab_id',$id)->pluck('id');
//        UniDevices::where('lab_id',$id)->delete();
//        services::whereIn('device_id',$devices)->where('central','1')->delete();
        return redirect()->route('UniLab.index')
            ->with('success','Lab deleted successfully');
    }
}
