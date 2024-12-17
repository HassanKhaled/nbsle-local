<?php

namespace App\Http\Controllers;

use App\Models\devices;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\LabStaff;
use App\Models\services;
use App\Models\Staff;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use function PHPUnit\Framework\isNull;

class UniversityController extends Controller
{
    function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(Gate::allows('university') , 403);
        $universitys = universitys::all();
        return view('Universitys.index',compact('universitys'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Gate::allows('university') , 403);
        return view('Universitys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('university') , 403);
//        dd($request);
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'type' => 'required',
            'website' => 'required',
        ]);
        if ($request->missing('ImagePath')){
            $request['ImagePath']= 'images/universities/No_Image.png';
            universitys::create($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities'),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.$ImageName;
//            dd($input);
            universitys::create($input);
        }
        session()->flash('message' ,'University added successfully.' );
        return redirect()->route('Universitys.index');
    }
    /*** Display the specified resource.*/
    public function show(universitys $univerity)
    {
        abort_unless(Gate::allows('university') , 403);

        return view('Universitys.show',compact('univerity'));
    }
    /*** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        abort_unless(Gate::allows('university') , 403);
        $university = universitys::find($id);
        return view('Universitys.edit',compact('university'));
    }
    /*** Update the specified resource in storage*/
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('university') , 403);
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'type' => 'required',
            'website' => 'required',
        ]);
        $university = universitys::find($id);
        $old_img = $university->ImagePath;
//            dd($request->file('ImagePath'));
        if($request->missing('ImagePath') or $request['ImagePath']==null) {
            $request['ImagePath']=$old_img;
            $university->update($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities'),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.$ImageName;
            $university->update($input);
        }

        return redirect()->route('Universitys.index')
            ->with('success','University updated successfully');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy(Request $request,$id)
    {
        abort_unless(Gate::allows('university') , 403);
//        dd($id);
//        universitys::destroy($id);
//        $uni = universitys::find($id);
//        $uni->delete();
        $user = Auth()->user();

        $lab = Labs::where('uni_id',$id)->pluck('id');
        $uni_lab = UniLabs::where('uni_id',$id)->pluck('id');
        Labs::where('uni_id',$id)->delete();
        UniLabs::where('uni_id',$id)->delete();
        $coor_lab = LabStaff::whereIn('lab_id',$lab)->whereIn('lab_id',$uni_lab)->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::whereIn('lab_id',$lab)->whereIn('lab_id',$uni_lab)->delete();
        $devices = devices::whereIn('lab_id',$lab)->pluck('id');
        $uni_devices = UniDevices::whereIn('lab_id',$uni_lab)->pluck('id');
        devices::whereIn('lab_id',$lab)->delete();
        UniDevices::whereIn('lab_id',$uni_lab)->delete();
        services::whereIn('device_id',$devices)->orwhereIn('device_id',$uni_devices)->delete();

        $faculty = fac_uni::where('uni_id',$id)->delete();
        $fac_coors = User::where('uni_id',$id)->delete();
//        User::destroy($fac_coors->toArray());
        universitys::destroy($id);
        return redirect()->route('Universitys.index')
            ->with('success','University deleted successfully');
    }
}
