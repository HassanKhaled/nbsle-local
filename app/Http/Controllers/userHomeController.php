<?php

namespace App\Http\Controllers;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\device_lab;
use App\Models\devices;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Sodium\add;
use Illuminate\Support\Arr;

class userHomeController extends Controller
{
    /*** Display a listing of the resource.*/
    // admin stats
    public function AdminStats(){
        $stables = $this->stables();
        $public=[];$private=[];$ahli=[];$institute=[];
        $public['unis'] = $stables['universities']->where('type','public')->pluck('id');
        $public['labs'] = $stables['labs']->whereIn('uni_id',$public['unis'])->pluck('id');
        $public['central_labs'] = $stables['central_labs']->whereIn('uni_id',$public['unis'])->pluck('id');
        $public['devices']=$stables['devices']->whereIn('lab_id',$public['labs'])->pluck('id');
        $public['central_devices']=$stables['central_devices']->whereIn('lab_id',$public['central_labs'])->pluck('id');
        $private['unis'] = $stables['universities']->where('type','private')->pluck('id');
        $private['labs'] = $stables['labs']->whereIn('uni_id',$private['unis'])->pluck('id');
        $private['central_labs'] = $stables['central_labs']->whereIn('uni_id',$private['unis'])->pluck('id');
        $private['devices']=$stables['devices']->whereIn('lab_id',$private['labs'])->pluck('id');
        $private['central_devices']=$stables['central_devices']->whereIn('lab_id',$private['central_labs'])->pluck('id');
        $ahli['unis'] = $stables['universities']->where('type','ahli')->pluck('id');
        $ahli['labs'] = $stables['labs']->whereIn('uni_id',$ahli['unis'])->pluck('id');
        $ahli['central_labs'] = $stables['central_labs']->whereIn('uni_id',$ahli['unis'])->pluck('id');
        $ahli['devices']=$stables['devices']->whereIn('lab_id',$ahli['labs'])->pluck('id');
        $ahli['central_devices']=$stables['central_devices']->whereIn('lab_id',$ahli['central_labs'])->pluck('id');
        $institute['unis'] = $stables['universities']->where('type','Institution')->pluck('id');
        $institute['labs'] = $stables['labs']->whereIn('uni_id',$institute['unis'])->pluck('id');
        $institute['central_labs'] = $stables['central_labs']->whereIn('uni_id',$institute['unis'])->pluck('id');
        $institute['devices']=$stables['devices']->whereIn('lab_id',$institute['labs'])->pluck('id');
        $institute['central_devices']=$stables['central_devices']->whereIn('lab_id',$institute['central_labs'])->pluck('id');
        return compact('public','private','ahli','institute');
    }
    public function index()
    {
        if (auth()->user()->hasRole('visitor')){  return redirect()->route('home');}
        $stables = $this->stables();
//        dd( Auth()->user());
        if ($stables['user']->hasRole('admin')){
            $university_names = $stables['universities']->pluck('name','id');
            $university_types = $stables['universities']->pluck('type','id');
            // labs in each university
            $fac_x = array_values($university_names->toArray());
            $uni_types = array_values($university_types->toArray());
            // devices in each university
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.uni_id',DB::raw('count(devices.id) as total'))
                ->groupBy('labs.uni_id')
                ->get()->pluck('total','uni_id');
//            dd($dev);
            $dev_uni = array();
            foreach ($university_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_uni[$id]=$dev[$id];}
                else{$dev_uni[$id]=0; }
            }
            $dev_y = array_values($dev_uni);

            $admin_stats = $this->AdminStats();
//            dd($stables['universities']->where('type','public'));
//            dd($dev,$university_types);
//            dd($fac_x,$dev_y,$uni_types);
//            return view('userHome', compact('stables','fac_x','dev_y','uni_types'));
//            dd($admin_stats['public']['unis']);
            return view('loggedTemp/index', compact('stables','fac_x','dev_y','uni_types','admin_stats'));
        }
        elseif ($stables['user']->hasRole('university'))
        {
            // for lab chart
            $faculty_names = $stables['faculties']->pluck('name','fac_id');
            $lab_count = labs::select('fac_id', DB::raw('count(*) as total'))
                ->where('uni_id',$stables['user']->uni_id)
                ->groupBy('fac_id')
                ->get()->pluck('total','fac_id');
            $labss = array();
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($lab_count[$id]))
                {$labss[$id]=$lab_count[$id];}
                else{$labss[$id]=0; }
            }
            $lab_y = array_values($labss);
            $fac_x = array_values($faculty_names->toArray());

            // for devices chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
//            dd($dev);
            $dev_fac = array();
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $dev_y = array_values($dev_fac);
//            dd($fac_x,$lab_y,$dev_y);
            return view('loggedTemp/index', compact('stables','fac_x','lab_y','dev_y'));
        }
        elseif ($stables['user']->hasRole('faculty'))
        {
            $lab_names = $stables['labs']->pluck('name','id');
            // for devices chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->groupBy('labs.id')
                ->get()->pluck('total','id');
//            dd($dev);
            $dev_fac = array();
            foreach ($lab_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $dev_y = array_values($dev_fac);
            $fac_x = array_values($lab_names->toArray());
            $labs = $stables['labs']->get();
//            dd($labs);
            $deptsInFac = dept_fac::where('uni_id',$stables['user']->uni_id)->where('fac_id',$stables['user']->fac_id)->pluck('dept_id');
            $depts = departments::whereIn('id',$deptsInFac)->pluck('name','id');
//dd($depts);
//            dd($fac_x,$lab_y,$dev_y);
//            dd($dev,$dev_y,$fac_x);
//            return view('userHome', compact('stables','fac_x','dev_y'));
            return view('loggedTemp/index', compact('stables','fac_x','dev_y','labs','depts'));
        }
        elseif ($stables['user']->hasRole('department'))
        {
            $lab_names = $stables['labs']->pluck('name','id');
            // for devices chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$stables['user']->dept_id)
                ->groupBy('labs.id')
                ->get()->pluck('total','id');
//            dd($dev);
            $dev_fac = array();
            foreach ($lab_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $dev_y = array_values($dev_fac);
            $fac_x = array_values($lab_names->toArray());
//            dd($fac_x,$lab_y,$dev_y);
//            dd($dev,$dev_y,$fac_x);
//            return view('userHome', compact('stables','fac_x','dev_y'));
            return view('loggedTemp/index', compact('stables','fac_x','dev_y'));
        }
    }


    // for system admin
    public function getUniDevices(Request $request){
        $stables = $this->stables();
        $selectedtype = $request->selectOption;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $selected_uni = $request->uni_selected;
//        dd($request);
        $admin_stats = $this->AdminStats();
        if ($selectedtype) {$university_names = $stables['universities']->where('type',$selectedtype)->pluck('name','id');}
        else { $university_names = $stables['universities']->pluck('name','id');}
//        $fac_x = array_values($university_names->toArray());
        $x = array_values($university_names->toArray());
        $university_types = $stables['universities']->pluck('type','id');
        $uni_types = array_values($university_types->toArray());

        if(($start_date==null and $end_date==null) and $selected_uni != null)
            //DONE  //devices in a university at all time
        {
            $selected_name = universitys::find($selected_uni);
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname')
                ->where('uni_id',$selected_uni)
                ->get()->groupBy(['facname','dept_id','labname']);
            $devvName = [$selected_name->name=>$devvName];
//            dd($devvName);
//            return view('loggedTemp/FacDetails', compact('stables','x','devvName','uni_types','request'));
            $faculty_names = $stables['all_faculties']->where('uni_id',$selected_uni)->pluck('name','fac_id');
            $x = array_values($faculty_names->toArray());
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$selected_uni)
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
            $dev_fac = array();
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $selected_name = $university_names[$selected_uni];
            $title = 'Devices in '.$selected_name;
//            dd($x,$y);
            return view('loggedTemp/FacDetails', compact('stables','x','y','title','uni_types','request','devvName','admin_stats'));
        }
        elseif(($start_date==null and $end_date==null) and $selected_uni == null and $selectedtype != null)
            // DONE // devices in a type's all universities at all time
        {
            $devvName = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                ->join('labs',function ($join){
                    $join->on('fac_uni.fac_id','=','labs.fac_id');
                    $join->on('fac_uni.uni_id','=','labs.uni_id');
                })->join('devices','labs.id','devices.lab_id')
                ->select('universitys.name as uniName','fac_uni.name as facName','fac_uni.uni_id','fac_uni.fac_id as facid'
                    ,'labs.dept_id as deptid','labs.name as labName','labs.id as labid','devices.*')
                ->get()->groupBy(['uniName','facid','dept_id','labid']);

            foreach ($devvName as $uniname=>$univalues)
            {
                foreach ($univalues as $facname=>$facvalues)
                {
                    foreach ($facvalues as $deptname=>$deptvalues)
                    {
                        $facvalues[$deptname] = $deptvalues->mapWithKeys(function ($value,$key){return [labs::where('id',$key)->pluck('name')->first() => $value];});
                    }
                    $univalues[$facname] = $facvalues->mapWithKeys(function ($value,$key){return [departments::where('id',$key)->pluck('name')->first() => $value];});
                }
                $devvName[$uniname] = $univalues->mapWithKeys(function ($value,$key){return [fac_uni::where('fac_id',$key)->pluck('name')->first() => $value];});
            }
//            dd($devvName);
//            return view('loggedTemp/FacDetails', compact('stables','devvName','uni_types','request'));

            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->join('universitys','labs.uni_id','=','universitys.id')
                ->select('universitys.id','universitys.type',DB::raw('count(devices.id) as total'))
                ->groupBy('universitys.id','universitys.type')
                ->where('universitys.type',$selectedtype)
                ->get()
                ->pluck('total','id');
//            foreach ($devvName as $labid=>$values){dd($devvName->);}
                devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->join('universitys','labs.uni_id','=','universitys.id')
                ->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname','universitys.name')
                ->where('universitys.type',$selectedtype)
                ->get()->groupBy(['facname','dept_id','labname']);
            $dev_uni = array();
            foreach ($university_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_uni[$id]=$dev[$id];}
                else{$dev_uni[$id]=0; }
            }
            $y = array_values($dev_uni);
            $title= $selectedtype == 'Institution' ? 'Devices in institutions' : 'Devices in '.$selectedtype.' universities';
            return view('loggedTemp/FacDetails', compact('stables','title','x','y','uni_types','request','devvName','admin_stats'));
        }
        elseif (($start_date != null or $end_date != null) and $selected_uni == null)
            // DONE // devices in a type all university in certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;

            $devvName = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                ->join('labs',function ($join){
                    $join->on('fac_uni.fac_id','=','labs.fac_id');
                    $join->on('fac_uni.uni_id','=','labs.uni_id');
                })->join('devices','labs.id','devices.lab_id')->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->select('universitys.name as uniName','fac_uni.name as facName','fac_uni.uni_id','fac_uni.fac_id as facid'
                    ,'labs.dept_id as deptid','labs.name as labName','labs.id as labid','devices.*')
                ->get()->groupBy(['uniName','facid','dept_id','labid']);

            foreach ($devvName as $uniname=>$univalues)
            {
                foreach ($univalues as $facname=>$facvalues)
                {
                    foreach ($facvalues as $deptname=>$deptvalues)
                    {
                        $facvalues[$deptname] = $deptvalues->mapWithKeys(function ($value,$key){return [labs::where('id',$key)->pluck('name')->first() => $value];});
                    }
                    $univalues[$facname] = $facvalues->mapWithKeys(function ($value,$key){return [departments::where('id',$key)->pluck('name')->first() => $value];});
                }
                $devvName[$uniname] = $univalues->mapWithKeys(function ($value,$key){return [fac_uni::where('fac_id',$key)->pluck('name')->first() => $value];});
            }
//            return view('loggedTemp/FacDetails', compact('stables','devvName','uni_types','request'));

            $dev = DB::table('devices')
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->join('universitys','labs.uni_id','=','universitys.id')
                ->select('universitys.id','universitys.type',DB::raw('count(devices.id) as total'))
                ->groupBy('universitys.id','universitys.type')
                ->where('universitys.type',$selectedtype)
                ->get()
                ->pluck('total','id');
//            dd($request,$devvName);
            $dev_uni = array();
            foreach ($university_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_uni[$id]=$dev[$id];}
                else{$dev_uni[$id]=0; }
            }
            $y = array_values($dev_uni);
            $title = 'Devices in '.$selectedtype.' universities from '.$start_date.' to '.$end_date;
//            return view('FacDetails', compact('stables','title','x','y','uni_types'));
            return view('loggedTemp/FacDetails', compact('stables','title','x','y','uni_types','request','devvName','admin_stats'));
        }
        elseif(($start_date != null or $end_date != null) and $selected_uni != null)
            // DONE // devices in a university in certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $selected_name = universitys::find($selected_uni);
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname')
                ->where('uni_id',$selected_uni)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->get()->groupBy(['facname','dept_id','labname']);
            $devvName = [$selected_name->name=>$devvName];
//            dd($devvName);
//            return view('loggedTemp/FacDetails', compact('stables','x','devvName','uni_types','request'));

            $faculty_names = $stables['all_faculties']->where('uni_id',$selected_uni)->pluck('name','fac_id');
            $x = array_values($faculty_names->toArray());
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$selected_uni)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
//            dd($dev);
            $dev_fac = array();
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $selected_name = $university_names[$selected_uni];
            $title = 'Device in '.$selected_name.' from '.$start_date.' to '.$end_date;
////            $title = 'Device in the univeristy';
////            return view('FacDetails', compact('stables','x','y','title','uni_types'));
            return view('loggedTemp/FacDetails', compact('stables','x','y','title','uni_types','request','devvName','admin_stats'));
        }
    }

    // for university admin
    public function getLabDevices(Request $request){
        $stables = $this->stables();
//        $faculties = fac_uni::where('uni_id',$stables['user']->uni_id)->get();
        $selectedID = $request->selectOption;
        $start_date = $request->start_date;
        $end_date = $request->end_date ;
        if ($selectedID=='Central Labs'){
//        dd($request);
            if ($start_date==null and $end_date==null){
                $devv = DB::table('uni_devices')
                    ->join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
                    ->select('uni_labs.name',DB::raw('count(uni_devices.id) as total'),'uni_labs.uni_id','uni_labs.id')
                    ->groupBy('uni_labs.id')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->get()->keyBy('id');
                $labsInUni = UniLabs::where('uni_id',$stables['user']->uni_id)->pluck('name','id');
                $dev_fac = array();
                foreach ($labsInUni as $id=>$name)
                {
                    if(isset($devv[$id]))
                    {$dev_fac[$id]=$devv[$id]->total;}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInUni->toArray());
                $devvName = UniDevices::join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
                    ->select('uni_devices.*','uni_labs.name as labname')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->get()->groupBy('labname');
                $selected='Central Labs';
                $title='';
                return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','devvName','request'));
            }
            else{
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $devv = DB::table('uni_devices')
                    ->join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
                    ->select('uni_labs.name',DB::raw('count(uni_devices.id) as total'),'uni_labs.uni_id','uni_labs.id')
                    ->groupBy('uni_labs.id')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                    ->get()->keyBy('id');
                $labsInUni = UniLabs::where('uni_id',$stables['user']->uni_id)->pluck('name','id');
                $dev_fac = array();
                foreach ($labsInUni as $id=>$name)
                {
                    if(isset($devv[$id]))
                    {$dev_fac[$id]=$devv[$id]->total;}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInUni->toArray());
                $devvName = UniDevices::join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
                    ->select('uni_devices.*','uni_labs.name as labname')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                    ->get()->groupBy('labname');
                $selected='Central Labs';
                $title='';
                return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','devvName','request'));

            }
        }
        // Faculties chart
        if($selectedID != null and ($start_date==null and $end_date==null))
            // Devices in a faculty at all time
        {
            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                ->where('uni_id',$stables['user']->uni_id)->get()->groupBy('fac_id');
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname')
                ->where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)
                ->get()->groupBy(['facname','dept_id','labname']);
            if (!$devv->has($selectedID))
            {
                $title='No Devices';
                return view('loggedTemp/FacDetails', compact('stables','title'))->withErrors(["error"=>"No Devices found!"]);
            }
            $dev_labsInFaculty = $devv[$selectedID]->pluck('total','id');  // Total devices in Labs in Selected faculty
            $labsInFaculty = labs::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name','id');
            $dev_fac = array();
            foreach ($labsInFaculty as $id=>$name)
            {
                if(isset($dev_labsInFaculty[$id]))
                {$dev_fac[$id]=$dev_labsInFaculty[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($labsInFaculty->toArray());
            $selected = fac_uni::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name')[0];
            $title = 'Number of Devices in '.(string)$selected;
//dd($x,$y);
//            dd( $devvName);
            return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','devvName','request'));
        }
        elseif(($start_date != null or $end_date != null) and $selectedID == null)
            // number of devices in all faculties in certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
            $selected_name = universitys::find($stables['user']->uni_id);
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','facultys.name as facname')
                ->where('uni_id',$stables['user']->uni_id)->whereBetween('devices.entry_date',[$start_date,$end_date])
//                ->get()->groupBy('labname');
                ->get()->groupBy(['facname','dept_id','labname']);
//            $depts = $devvName->groupBy('facid');
//            $devvName = [$selected_name->name=>$devvName];

//            dd($devvName);
//            return view('loggedTemp/FacDetails',compact('stables','devvName','request'));
            $dev_fac = array();
            $faculty_names = fac_uni::where('uni_id',$stables['user']->uni_id)->get()->pluck('name','fac_id');
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($faculty_names->toArray());
            $title = 'Devices in Faculties from '.(string)$start_date.' to '.(string)$end_date;
            return view('loggedTemp/FacDetails',compact('stables','x','y','title','request','devvName'));
        }
        elseif (($start_date != null or $end_date != null) and $selectedID != null)
            // number of devices in a faculty in certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $selected = fac_uni::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name')[0];
            $title = 'Number of Devices in '.(string)$selected.' from '.$start_date.' to '.$end_date;
            $selected_name = universitys::find($stables['user']->uni_id);
            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.id')
                ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                ->where('uni_id',$stables['user']->uni_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])->get()->groupBy('fac_id');

            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','facultys.name as facname')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$selectedID)->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->get()->groupBy(['facname','dept_id','labname']);
//            $devvName = [$selected_name->name=>$devvName];

//            dd($devvName);
//            return view('loggedTemp/FacDetails', compact('stables','selected','devvName','request'));

            $error = 'No Devices Found';
            $x=0;$y=0;
            if (! isset($devv[$selectedID])) return view('FacDetails', compact('stables','selected','title','error'))->withErrors(["error"=>"No Devices found!"]);
            else{
                $dev_labsInFaculty = $devv[$selectedID]->pluck('total','id');  // Total devices in Labs in Selected faculty
                $labsInFaculty = labs::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name','id');

                $dev_fac = array();
                foreach ($labsInFaculty as $id=>$name)
                {
                    if(isset($dev_labsInFaculty[$id]))
                    {$dev_fac[$id]=$dev_labsInFaculty[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInFaculty->toArray());
                $error='';
                return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','error','devvName','request'));
//
            }
        }
    }

    // for faculty admin
    public function getFacDevices(Request $request){
        $stables = $this->stables();
        $lab_selected = $request->selectOption;
        $dept_selected = $request->deptChosen;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $labs = $stables['labs']->get();
        $deptsInFac = dept_fac::where('uni_id',$stables['user']->uni_id)->where('fac_id',$stables['user']->fac_id)->pluck('dept_id');
        $depts = departments::whereIn('id',$deptsInFac)->pluck('name','id');
//        dd($request);
        if ($dept_selected != null and $lab_selected==null and ($start_date==null and $end_date==null))
            // devices in a department at all time
        {
            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->groupBy('labs.name')
                ->where('uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->get()->keyBy('id');
            $labs = $labs->where('uni_id',$stables['user']->uni_id)
                ->where('fac_id',$stables['user']->fac_id)->where('dept_id',$dept_selected)->pluck('name','id');
//            dd($devv,$labs);
            $dev_fac = array();
            foreach ($labs as $id=>$name)
            {
                if(isset($devv[$id]))
                {$dev_fac[$id]=$devv[$id]->total;}
                else{$dev_fac[$id]=0; }
            }
            $x = array_values($labs->toArray());
            $y = array_values($dev_fac);
//            dd($x,$y);
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('devices.*','labs.name as labname','labs.dept_id')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->get()->groupBy(['labname']);
            $labs = $stables['labs']->get();
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));
        }
        elseif ($dept_selected != null and $lab_selected==null and ($start_date!=null or $end_date!=null))
//             devices in a department at a certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;

            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->groupBy('labs.name')
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->where('uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->get()->keyBy('id');
            $labs = $labs->where('uni_id',$stables['user']->uni_id)
                ->where('fac_id',$stables['user']->fac_id)->where('dept_id',$dept_selected)->pluck('name','id');
            $dev_fac = array();
            foreach ($labs as $id=>$name)
            {
                if(isset($devv[$id]))
                {$dev_fac[$id]=$devv[$id]->total;}
                else{$dev_fac[$id]=0; }
            }
            $x = array_values($labs->toArray());
            $y = array_values($dev_fac);

            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('devices.*','labs.name as labname','labs.dept_id')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->get()->groupBy(['labname']);
            $labs = $stables['labs']->get();
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));
        }
        elseif ($lab_selected and ($start_date==null and $end_date==null))
            // devices in a lab at all time
        {

            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.id',$lab_selected)
                ->get()->groupBy(['labname']);
//            $selected_name = universitys::find($stables['user']->uni_id);
//            $devvName = [$selected_name->name=>$devvName];
//            dd($devvName);
            $x=[];
            $y=[];
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));
        }
        elseif ($lab_selected and ($start_date!=null or $end_date!=null))
            // devices in a lab at a certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname')
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.id',$lab_selected)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->get()->groupBy(['labname']);
            $x=[];
            $y=[];
//                ->get()->groupBy(['facname','dept_id','labname']);
//            dd($devvName);
//            $selected_name = universitys::find($stables['user']->uni_id);
//            $devvName = [$selected_name->name=>$devvName];
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));
        }
        else
            // devices at a certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('facultys','labs.fac_id','=','facultys.id')
                ->select('devices.*','labs.name as labname','labs.dept_id','facultys.name as facname')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$stables['user']->fac_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->get()->groupBy(['labname']);
            $lab_names = $stables['labs']->pluck('name','id');
            // for devices chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->groupBy('labs.id')
                ->get()->pluck('total','id');
//            dd($dev);
            $dev_fac = array();
            foreach ($lab_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($lab_names->toArray());
            $labs = $stables['labs']->get();

//            $x=[];
//            $y=[];
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));

        }

//        $dev = DB::table('devices')
//            ->join('labs', 'devices.lab_id', '=', 'labs.id')
//            ->select('labs.id',DB::raw('count(devices.id) as total'))
//            ->where('labs.uni_id',$stables['user']->uni_id)
//            ->where('labs.fac_id',$stables['user']->fac_id)
//            ->whereBetween('devices.entry_date',[$start_date,$end_date])
//            ->groupBy('labs.id')
//            ->get()->pluck('total','id');
//            dd($dev);
//        $dev_fac = array();
//        foreach ($lab_names as $id=>$name)
//        {
//            if(isset($dev[$id]))
//            {$dev_fac[$id]=$dev[$id];}
//            else{$dev_fac[$id]=0; }
//        }
//        $y = array_values($dev_fac);
//        $x = array_values($lab_names->toArray());
//        $title = 'Device in the labs';
//        dd($x,$y);
//        return view('loggedTemp/FacDetails', compact('stables','x','y','lab_names'));
    }

    // for department admin
    public function getDeptDevices(Request $request){
        $stables = $this->stables();
        $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
        $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
        $lab_names = $stables['labs']->get()->pluck('name','id');
        $dev = DB::table('devices')
            ->join('labs', 'devices.lab_id', '=', 'labs.id')
            ->select('labs.id',DB::raw('count(devices.id) as total'))
            ->where('labs.uni_id',$stables['user']->uni_id)
            ->where('labs.fac_id',$stables['user']->fac_id)
            ->where('labs.dept_id',$stables['user']->dept_id)
            ->whereBetween('devices.entry_date',[$start_date,$end_date])
            ->groupBy('labs.id')
            ->get()->pluck('total','id');
//            dd($dev);
        $dev_fac = array();
        foreach ($lab_names as $id=>$name)
        {
            if(isset($dev[$id]))
            {$dev_fac[$id]=$dev[$id];}
            else{$dev_fac[$id]=0; }
        }
        $y = array_values($dev_fac);
        $x = array_values($lab_names->toArray());
        $title = 'Device in the labs';
//        dd($x,$y);
//        return view('FacDetails', compact('stables','x','y','title'));
        return view('loggedTemp/FacDetails', compact('stables','x','y','title'));
    }

    public function stables(){
        $user = Auth()->user();
//        dd($user);
        if($user->hasRole('admin')){
            $universities = universitys::all();
            $labs = labs::all();
            $devices = devices::all();
            $central_labs = UniLabs::all();
            $central_devices = UniDevices::all();
            $all_faculties = fac_uni::all();
            return compact('user','universities','all_faculties','labs','devices','central_labs','central_devices');
        }
        elseif ($user->hasRole('university'))
        {
            $faculties = fac_uni::where('uni_id',$user->uni_id)->get();
            $labs = labs::where('uni_id',$user->uni_id)->pluck('id');
//            $devices = device_lab::whereIn('lab_id',$labs);
            $devices = devices::whereIn('lab_id',$labs);
            $central_labs = UniLabs::where('uni_id',$user->uni_id)->get();
            $central_devices = UniDevices::where('uni_id',$user->uni_id)->get();
            return compact('user','faculties','labs','devices','central_labs','central_devices');
        }
        elseif ($user->hasRole('faculty'))
        {
//            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->get();
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id);
            $devices = devices::whereIn('lab_id',$labs->pluck('id'));
            return compact('user','labs','devices');
        }
        elseif($user->hasRole('department')){
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->where('dept_id',$user->dept_id);
            $devices = devices::whereIn('lab_id',$labs->pluck('id'));
            return compact('user','labs','devices');
        }
        else return null;
    }

    public function getThisUser($uniID){
//        dd($uniID);
        $user = User::where('uni_id',$uniID)->where('role_id','2')->select('*')->get();
        $userData['data'] = $user;
        echo json_encode($userData);
        exit;
    }


    /*** Show the form for creating a new resource.*/
    public function create()
    {
        //
    }

    /*** Store a newly created resource in storage.*/
    public function store(Request $request)
    {
        //
    }

    /*** Display the specified resource.*/
    public function show($id)
    {
        //
    }

    /** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        //
    }

    /*** Update the specified resource in storage.*/
    public function update(Request $request, $id)
    {
        //
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        //
    }
}
