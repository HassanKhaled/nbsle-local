<?php

namespace App\Http\Controllers;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\fac_uni;
use App\Models\universitys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use function Sodium\add;

class UserController extends Controller
{


    public function index(Request $request)
    {
        abort_unless(Gate::allows('user') , 403);
        $user_logged = Auth()->user();
        $users = $user_logged;
        if ($user_logged->hasRole('admin')){
            $users = User::where('id','!=',$user_logged->id)->get();  // role_id =>[(1,admin),(2,university)]
            $university = universitys::all();

            return view('users.index',compact('users','university','user_logged'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
        elseif ($user_logged->hasRole('university')){
            $users = User::where('uni_id','=',$user_logged->uni_id)->where('id','!=',$user_logged->id)->get();
            $faculty = fac_uni::where('uni_id','=',$user_logged->uni_id);
//            dd($faculty);
            return view('users.index',compact('users','faculty','user_logged'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
        elseif ($user_logged->hasRole('faculty')){
            $users = User::where('uni_id','=',$user_logged->uni_id)->where('fac_id','=',$user_logged->fac_id)->where('id','!=',$user_logged->id)->get();
            $department = dept_fac::all();
            return view('users.index',compact('users','department','user_logged'))->with('i', ($request->input('page', 1) - 1) * 5);
        }
        elseif ($user_logged->hasRole('department')){
            $users = User::where('uni_id','=',$user_logged->uni_id)->where('fac_id','=',$user_logged->fac_id)
                ->where('dept_id','=',$user_logged->dept_id)->where('id','!=',$user_logged->id)->get();
            $department = dept_fac::all();
            return view('users.index',compact('users','department','user_logged'))->with('i', ($request->input('page', 1) - 1) * 5);
        }
        return view('users.index',compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        abort_unless(Gate::allows('user') , 403);

        $roles = Role::pluck('id','name');
        $user = Auth()->user();
        $depts = null;
        if ($user->hasRole('admin')){
            $unis = universitys::all();
            $facs = fac_uni::all();
            return view('users.create',compact('roles','user','unis','facs'));
        }
        elseif ($user->hasRole('university')){
            $facs = fac_uni::where('uni_id',$user->uni_id)->get();
            return view('users.create',compact('roles','user','facs'));
        }
        if ($user->hasRole('faculty'))
            $depts = dept_fac::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->join('departments','dept_fac.dept_id','=','departments.id')->get();
            //dd($roles,$user,$depts);
            $facs = [];//fac_uni::all();
            //$facs = fac_uni::where('uni_id',$user->uni_id)->get();

            return view('users.create',compact('roles','user','depts','facs'));
    }

    public function store(Request $request)
    {
        abort_unless(Gate::allows('user') , 403);
        $logged_user = auth()->user();
//        dd($request);
        $this->validate($request, [
            'username' =>'required|unique:users,username',
            'name' => 'required',
            'phone'=>'nullable|numeric|min:11',
            'email' => 'required|email',
            'log_email'=>'nullable|email',
            'password' => 'required|min:4',
            'password_hashed' => 'nullable',
            'role_id' => 'required',
            'uni_id'=>'required_if:role_id,>=,2',
            'fac_id'=>'required_if:role_id,=,3',
            'dept_id'=>'required_if:role_id,=,4',
            'lab_id'=>'nullable',
            'ImagePath'=>'nullable',
        ]);
        $input = $request->all();
        if ($request->missing('ImagePath')){
            $input['ImagePath']= 'images/users/No_Image.png';
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/users'),$ImageName);
//            $input = $request->all();
            $input['ImagePath'] = 'images/users/'.$ImageName;
//            dd($input);
//            universitys::create($input);
        }
        $input['log_email'] = $input['email'];
        $input['password_hashed'] = $input['password'];
        $input['password'] = Hash::make($input['password']);
        if ($input['role_id'] =='1'){
            $input['uni_id']  = null ;
        }
        if ($input['role_id']=='2' and $logged_user->role_id=='2'){ //add uni coor by uni coor
            $input['uni_id'] = $logged_user->uni_id;
        }
        if ($input['role_id']=='3' and $logged_user->hasRole('university')){ //add faculty coor by university admin
            $input['uni_id'] = $logged_user->uni_id;
        }
        if ($input['role_id']=='4'){ //department
            $input['uni_id'] = $logged_user->uni_id;
            $input['fac_id']  = $logged_user->fac_id;
        }
        $role = Role::find($input['role_id']);
        $new_user = User::create($input);
        $new_user->assignRole($role->name);
        if($new_user->role->name =='university' && $logged_user->role_id =='1'){universitys::where('id',$new_user->uni_id)->update(['coordinator_id'=>$new_user->id]);}
        elseif ($new_user->role->name=='faculty') { fac_uni::where('uni_id',$new_user->uni_id)->where('fac_id', $input['fac_id'])->update(['coor_id' => $new_user->id]);}
        elseif ($new_user->role->name=='department')
        {
            dept_fac::where('uni_id',$new_user->uni_id)
                ->where('fac_id', $input['fac_id'])
                ->where('dept_id', $input['dept_id'])
                ->update(['coor_id' => $new_user->id]);
        }
        return redirect()->route('Users.index')->with('success','User created successfully');
    }
    public function show($id) // view logged user profile
    {
        abort_unless(Gate::allows('user') , 403);
        $user = User::find($id);
        return view('users.editpass',compact('user'));
    }
    public function edit($id)
    {
        abort_unless(Gate::allows('user') , 403);

        $user = User::find($id);
        $roles = Role::pluck('id','name');
        $userRole = $user->role;
        $depts = null;
        $logged_user = Auth()->user();

//        if ($logged_user->hasRole('admin')){
//            $unis = universitys::all();
//            $facs = fac_uni::all();
//            return view('users.create',compact('roles','user','userRole','unis','facs'));
//        }
// update13 Dec 24        
//if ($logged_user->hasRole('university'))
//{
            $facs = fac_uni::where('uni_id',$user->uni_id)->get();
            $depts = dept_fac::where('uni_id',$logged_user->uni_id)->where('fac_id',$logged_user->fac_id)
                ->join('departments','dept_fac.dept_id','=','departments.id')->get();
//}
        return view('users.edit',compact('user','roles','userRole','depts','facs'));
    }
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('user') , 403);
        $this->validate($request, [

            'log_email'=>'nullable|email',
            'username' =>'required',
            'name' => 'required',
            'email' => 'required|email',
            'password_hashed' => 'nullable',
            'password' => 'required|min:4',
            'phone'=>'numeric|min:11',
            'role_id' => 'required',
            'uni_id'=>'required_if:role_id,>=,2',
            'fac_id'=>'nullable',
            'dept_id'=>'nullable',
            'lab_id'=>'nullable',
            'ImagePath'=>'nullable',
        ]);

        $user = User::find($id);
        $input = $request->all();
        $input['log_email'] = $input['email'];
        $input['password_hashed'] = $input['password'];
        $input['password'] = Hash::make($input['password']);
        $old_img = $user->ImagePath;
        if($request->missing('ImagePath') or $request['ImagePath']==null) {
            $input['ImagePath']=$old_img;
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/users'),$ImageName);
            $input['ImagePath'] = 'images/users/'.$ImageName;
        }

        if ($input['role_id'] =='1'){
            $input['uni_id']  = null ;
        }
        elseif ($input['role_id']=='3'){ //faculty or department
            $input['uni_id'] = auth()->user()->uni_id;
        }
        elseif ($input['role_id']=='4'){ //department
            $input['uni_id'] = auth()->user()->uni_id;
            $input['fac_id']  = auth()->user()->fac_id;
        }
        $role = Role::findById($input['role_id']);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assign_role($role);

        if($user->role->name =='university'&& auth()->user()->role_id=='1'){universitys::where('id',$user->uni_id)->update(['coordinator_id'=>$user->id]);}
        if ($user->role->name == 'faculty') {
            $update_facs = fac_uni::where('uni_id',$input['uni_id'])->where('fac_id', $input['fac_id']);
            $update_facs->update(['coor_id'=>$user->id]);}
        if ($user->role->name == 'department') {
            $update_depts = dept_fac::where('uni_id',$input['uni_id'])->where('fac_id', $input['fac_id'])->where('dept_id',$input['dept_id']);
            $update_depts->update(['coor_id'=>$user->id]);}
        return redirect()->route('Users.index')
            ->with('success','User updated successfully');
    }
    public function destroy($id)
    {
        abort_unless(Gate::allows('user') , 403);
        $user = User::find($id);
        // update (Uni, FacUni, DeptFac) row with null when their coordinator is deleted
        if ($user->hasRole('admin')){
            $user->delete();
        }
        if($user->role_id == 2)
        {
            $uni_id = $user->uni_id;
            $user->delete();
            $eq_coor = User::where('uni_id',$uni_id)->where('role_id','2')->pluck('id');
            if (($eq_coor)) {   $ad = universitys::where('id',$uni_id)->update(['coordinator_id'=>$eq_coor[0]]);    }
            else {   $ad = universitys::where('id',$uni_id)->update(['coordinator_id'=>null]);   }
        }
        if($user->role_id == 3)
        {
            $f = fac_uni::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->update(['coor_id'=>null]);
            $user->delete();
        }
        if ($user->role_id == 4){
            $f = dept_fac::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->where('dept_id',$user->dept_id)->update(['coor_id'=>null]);
            $user->delete();
        }
        return redirect()->route('Users.index')
            ->with('success','User deleted successfully');
    }

//    Update Logged User password
    public function updatePass(Request $request)
    {
        abort_unless(Gate::allows('user') , 403);
        $user = Auth::user();
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|different:password|min:4',
            'new_confirm_password' => 'required|same:new_password',
        ]);
        if (Hash::check($request->password, $user->password) == false)
        {
            return back()->withErrors(['message'=>'Incorrect Password']);
        }
        User::find($user->id)->update(['password'=>Hash::make($request->new_password),'password_hashed'=>$request->new_password]);
        return back()->with('success','User updated successfully');
    }
//    Update Logged User Info
    public function updatePInfo(Request $request)
    {
        abort_unless(Gate::allows('user') , 403);
        $user = Auth::user();
        $request->validate([
            'username' =>'nullable',
            'name' => 'nullable',
            'email' => 'nullable|email',
            'phone'=>'nullable|numeric|min:11',
            'ImagePath'=>'nullable'
        ]);

        $old_img = $user->ImagePath;
        $req = $request->all();
        if($request->missing('ImagePath') or $request['ImagePath']==null) {
            $req['ImagePath']=$old_img;
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/users'),$ImageName);
            $req['ImagePath'] = 'images/users/'.$ImageName;
        }
//        dd($request,$req,$request->all());
        User::find($user->id)->update($req);
        return back()->with('success','User updated successfully');
    }
//    public function showUserInfo(Request $request)
//    {
//        abort_unless(Gate::allows('university'),403);
//        $users = User::where('uni_id',$request->uni_id)->where('role_id','2')->get();
//        return back()->with('users');
//    }


}
