<?php

namespace App\Http\Controllers;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\devices;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\LabStaff;
use App\Models\services;
use App\Models\Staff;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LabController extends Controller
{

    /*** Display a listing of the resource.*/
    public function index()
    {
        abort_unless(Gate::allows('lab') , 403);
        $logged_user = Auth()->user();
        if ($logged_user->hasRole('admin')){
            $labs = labs::all();
            $lab_coors = LabStaff::whereIn('lab_id',$labs->pluck('id'))->where('central','0')->select('lab_id','manager_id')->get();
            $coors = Staff::whereIn('id',$lab_coors->pluck('manager_id'))->get();
            return view('Labs.index',compact('labs','coors','lab_coors'))
                ->with('i', (request()->input('page', 1) - 1) * 5);

        }
        elseif ($logged_user->hasRole('university')){
            $labs = labs::where('uni_id',$logged_user->uni_id);
        }
        elseif ($logged_user->hasRole('faculty')){
            $labs = labs::where('uni_id',$logged_user->uni_id)
            ->where('fac_id',$logged_user->fac_id);
        }
        else{
            $labs = labs::where('uni_id',$logged_user->uni_id)
            ->where('fac_id',$logged_user->fac_id)
            ->where('dept_id',$logged_user->dept_id);
        }
        $lab_ids = $labs->pluck('id');
        $lab_coors = LabStaff::whereIn('lab_id',$lab_ids)->where('central','0')->select('lab_id','manager_id')->get();
        $coors = Staff::whereIn('id',$lab_coors->pluck('manager_id'))->get();
//        $labs = $labs->paginate(5);
        $labs = $labs->get();
        return view('Labs.index',compact('labs','lab_ids','coors','lab_coors'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /*** Show the form for creating a new resource.  */
    public function create()
    {
        abort_unless(Gate::allows('lab') , 403);
        if (Auth()->user()->hasRole('admin')){
            $universities = universitys::all();
            $facultys = fac_uni::all();
            $deptfac = dept_fac::whereIn('fac_id',$facultys->pluck('fac_id'))->get();
            $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();
//            dd($departments,$deptfac);
            return view('Labs.create',compact('departments','facultys','deptfac','universities'));
        }
        elseif (Auth()->user()->hasRole('university')){
            $facultys = fac_uni::where('uni_id',Auth()->user()->uni_id)->get();
            $deptfac = dept_fac::where('uni_id',Auth()->user()->uni_id)->whereIn('fac_id',$facultys->pluck('fac_id'))->get();
            $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();
//            dd($departments,$deptfac);
            return view('Labs.create',compact('departments','facultys','deptfac'));
        }
        else {
            $deptfac = dept_fac::where('uni_id', Auth()->user()->uni_id)->where('fac_id', Auth()->user()->fac_id)->pluck('dept_id');
            $departments = departments::wherein('id', $deptfac)->get();
        }
//        dd($departments);
        return view('Labs.create',compact('departments'));
    }

    /*** Store a newly created resource in storage.    */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('lab') , 403);
//        dd($request);
        $logged_user = Auth()->user();
        $coord = collect();
        request()->validate([
            'name'=>'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'dept_id'=>'nullable',
            'uni_id'=>'nullable',
            'fac_id'=>'nullable',
//            'pic'=>'nullable',
            'coor_name.*'=>'required',
            'coor_telephone.*'=>'required|numeric|min:11',
            'coor_email.*'=>'nullable|email',
            'coor_staff.*'=>'required',
        ]);
        for ($i = 0; $i < count($request['coor_name']); $i++)
        {
            if ($request['coor_name'][$i]==null)
                continue;
            else
               $coord->push(['name'=>$request['coor_name'][$i],'telephone'=>$request['coor_telephone'][$i],'email'=>$request['coor_email'][$i],'staff'=>$request['coor_staff'][$i]]);
        }
        if (! $logged_user->hasRole('admin')) {
            $request->request->add(['uni_id' => $logged_user->uni_id]);
        }
        if ( $logged_user->hasRole(['faculty','department'])){
            $request->request->add(['fac_id'=>$logged_user->fac_id]);
        }
        if ($logged_user->hasRole('department')) {
            $request['dept_id'] = $logged_user->dept_id;
        }
        if ($request['dept_id']==null)$request['dept_id']=0;
//        dd($request);
        $lab = Labs::create($request->all());
        foreach ($coord as $coor) {
            $staff = Staff::create($coor);
            $lab_staff = LabStaff::create(['lab_id'=>$lab->id,'manager_id'=>$staff->id,'central'=>'0']);
        }
        session()->flash('message' ,'Lab added successfully.' );
        return redirect()->route('Lab.index');
    }

    /*** Display the specified resource.*/
    public function show($id)
    {

    }

    /*** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        abort_unless(Gate::allows('lab') , 403);
        $logged_user = Auth()->user();
        $lab = Labs::find($id);
        $coor = LabStaff::where('lab_id',$lab->id)->pluck('manager_id');
        $coors = Staff::whereIn('id',$coor)->get();

        if ($logged_user->hasRole('admin')) {
            $university = universitys::where('id',$lab->uni_id)->first()->name;
            $faculty = fac_uni::where('fac_id',$lab->fac_id)->first()->name;
            $deptfac = dept_fac::all();
            $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();
            return view('Labs.edit',compact('departments','lab','coors','deptfac','faculty','university'));
        }
        if ($logged_user->hasRole('university')){
            $facultys = fac_uni::where('uni_id',Auth()->user()->uni_id)->get();
            $deptfac = dept_fac::where('uni_id',Auth()->user()->uni_id)->whereIn('fac_id',$facultys->pluck('fac_id'))->get();
        }
        else {
            $deptfac = dept_fac::where('uni_id', Auth()->user()->uni_id)->where('fac_id', Auth()->user()->fac_id)->get();
        }
        $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();

        if ($logged_user->hasRole('university'))
            return view('Labs.edit',compact('departments','lab','coors','deptfac','facultys'));
        else
            return view('Labs.edit',compact('departments','lab','coors','deptfac'));
//        return $logged_user->hasRole('university')?
//            view('Labs.edit',compact('departments','lab','coors','deptfac','facultys'))
//            : view('Labs.edit',compact('departments','lab','coors','deptfac'));
    }

    /*** Update the specified resource in storage.*/
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('lab') , 403);
        $logged_user = Auth()->user();

        request()->validate([
            'name'=>'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'dept_id'=>'nullable',
            'uni_id'=>'nullable',
            'fac_id'=>'nullable',
            'pic'=>'nullable',
            'coor_name.*'=>'required',
            'coor_telephone.*'=>'required|numeric|min:11',
            'coor_email.*'=>'nullable|email',
            'coor_staff.*'=>'required',
//            'coor_name'=>'required',
//            'coor_email'=>'nullable|email',
//            'coor_telephone'=>'required|integer|min:11|max:11',
//            'coor_staff'=>'required',
        ]);
//        dd(request()->all());
        $coord = collect();
        for ($i = 0; $i < count($request['coor_name']); $i++)
        {
            if ($request['coor_name'][$i]==null) continue;
            else $coord->push(['name'=>$request['coor_name'][$i],'telephone'=>$request['coor_telephone'][$i],'email'=>$request['coor_email'][$i],'staff'=>$request['coor_staff'][$i]]);
        }


        if (! $logged_user->hasRole('admin')) { // if not system admin
            $request->request->add(['uni_id' => $logged_user->uni_id]);
        }
        if ( $logged_user->hasRole(['faculty','department'])){ // if faculty or department admin
            $request->request->add(['fac_id'=>$logged_user->fac_id]);
        }
        if ($logged_user->hasRole('department')) { // if department admin
            $request['dept_id'] = $logged_user->dept_id;
        }
        if ($request['dept_id']==null)$request['dept_id']=0;

//        $request->request->add(['uni_id'=>$logged_user->uni_id]);
//        if ($request['dept_id']==null)$request['dept_id']=$logged_user->dept_id;
//        dd($request);

        $lab = labs::find($id);
        $lab->update($request->all());

        $coor_lab = LabStaff::where('lab_id',$id)->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::where('lab_id',$id)->delete();

        foreach ($coord as $coor) {
            $staff = Staff::create($coor);
            $lab_staff = LabStaff::create(['lab_id'=>$lab->id,'manager_id'=>$staff->id,'central'=>'0']);
        }

        session()->flash('message' ,'Lab added successfully.' );
        return redirect()->route('Lab.index');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        abort_unless(Gate::allows('lab') , 403);
//        dd(Labs::find($id)->staff);
        services::join('devices','services.device_id','devices.id')->where('devices.lab_id',$id)->delete();
        $lab = Labs::find($id)->delete();
        $coor_lab = LabStaff::where('lab_id',$id)->where('central','0')->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::where('lab_id',$id)->where('central','0')->delete();

        return redirect()->route('Lab.index')->with('success','Lab deleted successfully');

    }
}
