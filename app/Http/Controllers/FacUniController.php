<?php

namespace App\Http\Controllers;

use App\Models\devices;
use App\Models\fac_uni;
use App\Models\facultys;
use App\Models\labs;
use App\Models\LabStaff;
use App\Models\services;
use App\Models\Staff;
use App\Models\universitys;
use App\Models\User;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

//use League\CommonMark\Inline\Element\Image;

class FacUniController extends Controller
{
    /*** Display a listing of the resource. */
    public function index()
    {
        abort_unless(Gate::allows('faculty') , 403);
        $user = Auth()->user();
        $facultys = $user->role->name == 'admin'?
            fac_uni::join('universitys','universitys.id','fac_uni.uni_id')->select('universitys.name as Uniname','fac_uni.*')->get()
            :fac_uni::where('uni_id',$user->uni_id)->latest()->paginate(5);
        return view('Facultys.index',compact('facultys'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /*** Show the form for creating a new resource.     */
    public function create()
    {
        abort_unless(Gate::allows('faculty') , 403);

        $user = Auth()->user();
        if ($user->role->name == 'admin') {
            $universities = universitys::get();
            return view('Facultys.create', compact('user', 'universities'));
        }
        else
            return view('Facultys.create',compact('user'));
    }

    /*** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        abort_unless(Gate::allows('faculty') , 403);

        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'website' => 'required',
            'uni_id'=>'nullable',
            'coor_id'=>'nullable',
            'fac_id'=>'nullable',
            'ImagePath' => 'required|mimes:jpg,png,gif'
        ]);
        $user = Auth()->user();
        $uni_id = $user->role->name =='admin'? $request['uni_id']:$user->uni_id;
        $request->request->add(['uni_id'=> $uni_id]);
        $faculty = facultys::firstOrCreate(array('name' => $request['name']));
        $fac_id = $faculty->id;
        $request->request->add(['fac_id'=>$fac_id]);
        if ($request->missing('ImagePath')){
            $request['ImagePath']= 'images/universities/No_Image.png';
            fac_uni::create($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities/'.$uni_id),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.$uni_id.'/'.$ImageName;
            fac_uni::create($input);
        }
        session()->flash('message' ,'Faculty added successfully.' );
        return redirect()->route('FacUni.index');
    }

    /*** Display the specified resource.*/
    public function show(facultys $faculty)
    {
        abort_unless(Gate::allows('faculty') , 403);
        return view('Facultys.show',compact('faculty'));
    }

    /*** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        abort_unless(Gate::allows('faculty') , 403);
        $faculty = fac_uni::find($id);
        $users = User::where('uni_id','=',$faculty->uni_id)->get();
        $user = Auth()->user();
        if ($user->role->name == 'admin') {
            $universities = universitys::get();
            return view('Facultys.edit', compact('users','faculty', 'universities'));
        }
        return view('Facultys.edit',compact('faculty', 'users' ));
    }

    /*** Update the specified resource in storage.*/
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('faculty') , 403);
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'website' => 'nullable',
            'fac_id' =>'nullable',
            'uni_id'=>'nullable',
            'coor_id'=>'nullable',
        ]);
        $user = Auth()->user();
//        dd($request);
//        $faculty = facultys::firstOrCreate(array('name' => $request['name']));
        $faculty = fac_uni::find($id);
        $old_coor = $faculty->coor_id;
        $old_img = $faculty->ImagePath;
        if($request->missing('ImagePath') or $request['ImagePath']==null) {
            $request['ImagePath']=$old_img;
            $faculty->update($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities/'.$user->uni_id),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.$user->uni_id.'/'.$ImageName;
            $faculty->update($input);
        }

        if ($old_coor != $request['coor_id']) {
            $old = User::where('id',$old_coor)->update(['fac_id'=>null]);
            $user = User::where('id',$request['coor_id'])->update(['fac_id'=>$request['fac_id']]);
        }
//        if ($user->roles == 'faculty') {
//            $user->update(['fac_id' => $request['fac_id']]);
//        }
//        else{
//            $user->update(['fac_id' => null]);
//        }

        return redirect()->route('FacUni.index')
            ->with('success','Faculty updated successfully');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        abort_unless(Gate::allows('faculty') , 403);
        $user = Auth()->user();
        $faculty = fac_uni::find($id);

        $lab = Labs::where('uni_id',$faculty->uni_id)->where('fac_id',$id)->pluck('id');
        Labs::where('uni_id',$faculty->uni_id)->where('fac_id',$id)->delete();
        $coor_lab = LabStaff::whereIn('lab_id',$lab)->pluck('manager_id');
        $coor = Staff::whereIn('id',$coor_lab)->delete();
        $coor_lab = LabStaff::whereIn('lab_id',$lab)->delete();
        $devices = devices::whereIn('lab_id',$lab)->pluck('id');
        devices::whereIn('lab_id',$lab)->delete();
        services::whereIn('device_id',$devices)->delete();

        $fac_coors = User::where('uni_id',$faculty->uni_id)->where('fac_id',$id)->get('id');
        User::destroy($fac_coors->toArray());
        $faculty->delete();
        return redirect()->route('FacUni.index')
            ->with('success','Faculty deleted successfully');
    }
}
