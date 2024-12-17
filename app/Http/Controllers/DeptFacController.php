<?php

namespace App\Http\Controllers;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\LabStaff;
use App\Models\services;
use App\Models\Staff;
use App\Models\universitys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use function PHPUnit\Framework\isEmpty;

class DeptFacController extends Controller
{
    /*** Display a listing of the resource.*/
    public function index()
    {
        abort_unless(Gate::allows('department') , 403);
        $user = Auth()->user();
        $i =0;
        if ($user->hasRole('admin')) {
            $dep_fac = dept_fac::all();
            $departments = departments::whereIn('id',$dep_fac->pluck('dept_id'))->latest()->paginate(5);
            //$departments = departments::whereIn('id',$dep_fac->pluck('dept_id'))->get();
            $dep_fac = $dep_fac->keyBy('dept_id');
            $faculties = fac_uni::all()->keyBy('fac_id');
            $universities = universitys::all()->keyBy('id');
            return view('Departments.index',compact('departments','dep_fac','user','faculties','universities'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        elseif ($user->hasRole('university')){
            $dep_fac = dept_fac::where('uni_id',$user->uni_id)->get();
            $departments = departments::whereIn('id',$dep_fac->pluck('dept_id'))->latest()->paginate(5);
            //$departments = departments::whereIn('id',$dep_fac->pluck('dept_id'))->get();
            $dep_fac = $dep_fac->keyBy('dept_id');
            $faculties = fac_uni::all()->keyBy('fac_id');
            return view('Departments.index',compact('departments','dep_fac','faculties'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else{
        $dep_fac = dept_fac::where('uni_id',$user->uni_id)->where('fac_id','=',$user->fac_id)->pluck('dept_id');
        $departments = departments::whereIn('id',$dep_fac)->latest()->paginate(5);
        //$departments = departments::whereIn('id',$dep_fac)->get();
        }
//        dd($dep_fac,$departments);
        return view('Departments.index',compact('departments','dep_fac'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /*** Show the form for creating a new resource.*/
    public function create()
    {
        abort_unless(Gate::allows('department') , 403);

        $user = Auth()->user();
        if ($user->role->name == 'admin')
        {
            $universities = universitys::all();
            $facs = fac_uni::whereIn('uni_id',$universities->pluck('id'))->get();
            return view('Departments.create',compact('user','universities','facs'));
        }
        if ($user->role->name == 'university')
        {
            $facs = fac_uni::where('uni_id',$user->uni_id)->get();
            return view('Departments.create',compact('user','facs'));
        }
            $facs = [];
            return view('Departments.create',compact('user','facs'));
    }

    /*** Store a newly created resource in storage.*/
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
//            'pic' => 'nullable',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'uni_id'=>'nullable',
            'coor_id'=>'nullable',
            'fac_id'=>'nullable',
        ]);
        $logged_user = Auth()->user();
        $dept = departments::create(array('name' => $request['name'],'Arabicname'=>$request['Arabicname']));
        $dept_id = $dept->id;
        $request->request->add(['dept_id'=>$dept_id]);
        if (! $logged_user->hasRole('admin')){
            if ( !$logged_user->hasRole('university')) {$request->request->add(['fac_id' => $logged_user->fac_id]);}
            $request->request->add(['uni_id'=> $logged_user->uni_id]);
            $request->request->add(['coor_id'=> $logged_user->id]);
        }
        dept_fac::create($request->all());
        session()->flash('message' ,'Department added successfully.' );
        return redirect()->route('DeptFac.index');
    }

    /*** Display the specified resource.*/
    public function show($department)
    {
        abort_unless(Gate::allows('department') , 403);
        return view('Departments.show',compact('department'));
    }

    /*** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        abort_unless(Gate::allows('department') , 403);
        $user = Auth()->user();
        if ($user->hasRole('admin')){
            $users = User::all();
            $dept = departments::find($id);
            $department = dept_fac::where('dept_id',$id)->first();
            $university = universitys::where('id',$department->uni_id)->first()->name;
            $faculty = fac_uni::where('uni_id',$department->uni_id)->where('fac_id',$department->fac_id)->first()->name;
//            dd($department);
            return view('Departments.edit',compact('department', 'users' ,'dept','university','faculty'));
        }
        elseif ($user->hasRole('university')){
            $users = User::where('uni_id','=',$user->uni_id)->get();
            $dept = departments::find($id);
            $department = dept_fac::where('uni_id','=',$user->uni_id)->where('dept_id',$id)->first();
        }
        else {
            $users = User::where('uni_id', '=', $user->uni_id)->where('fac_id', '=', $user->fac_id)->get();
            $dept = departments::find($id);
            $department = dept_fac::where('uni_id', '=', $user->uni_id)->where('fac_id', '=', $user->fac_id)->where('dept_id', $id)->first();
        }
//        dd($dept,$department);
        return view('Departments.edit',compact('department', 'users' ,'dept'));
    }

    /*** Update the specified resource in storage.     */
    public function update(Request $request, $id)
    { //id is dept_id
        abort_unless(Gate::allows('faculty') , 403);
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'pic' => 'nullable',
            'website' => 'nullable',
            'fac_id' =>'nullable',
            'uni_id'=>'nullable',
            'coor_id'=>'nullable',
        ]);
//        dd($request->all(),$id);
//        dd($id);
        $department = departments::find($id);
        $department->update($request->all());
//        dd($request);

//        $dep = dept_fac::where('dept_id',$id)->where('uni_id',auth()->user()->uni_id)->where('fac_id',$request['fac_id'])->update(['dept_id'=>$department->id,'fac_id'=>$request['fac_id'],'coor_id'=>$request['coor_id'],'updated_at'=>$request['updated_at']]);
//        dd($request['coor_id']==null);
//        $request['coor_id'] = $request['coor_id']==null?auth()->user()->id:$request['coor_id'];
//        $user = User::find($request['coor_id']);
//        $dep = dept_fac::where('dept_id',$id)->where('uni_id',auth()->user()->uni_id)
//        $old_coor = $dep->coor_id;
//        if ($old_coor != $request['coor_id']) {
//            $user = User::find($request['coor_id']);
//            $user->update(['dept_id'=>$request['dept_id']]);
//        }

        return redirect()->route('DeptFac.index')
            ->with('success','Department updated successfully');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        abort_unless(Gate::allows('department') , 403);

        $user = Auth()->user();
        $lab = Labs::where('dept_id',$id)->pluck('id');
        $sevices = services::join('devices','services.device_id','devices.id')->whereIn('devices.lab_id',$lab)->delete();
        $coor_lab = LabStaff::whereIn('lab_id',$lab)->where('central','0')->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::whereIn('lab_id',$lab)->where('central','0')->delete();
        $lab = Labs::where('dept_id',$id)->where('uni_id',auth()->user()->uni_id)->delete();


        $dept_coors = User::where('uni_id',$user->uni_id)
            ->where('fac_id',$user->fac_id)
            ->where('dept_id',$id)
            ->get('id');
        User::destroy($dept_coors->toArray());
        $depart = dept_fac::where('dept_id',$id);
        $depart->delete();
//        dd($dept_coors);
        return redirect()->route('DeptFac.index')
            ->with('success','Department deleted successfully');
    }
}
