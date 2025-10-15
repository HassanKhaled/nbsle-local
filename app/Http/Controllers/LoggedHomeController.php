<?php

namespace App\Http\Controllers;

use App\Models\departments;
use App\Models\dept_fac;
//use App\Models\device_lab;
use App\Models\devices;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use App\Models\User;
use App\Models\services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Sodium\add;
use Illuminate\Support\Arr;

class loggedHomeController extends Controller
{
    /*** Display a listing of the resource.*/
    // statistics for system admin (Gets called in index function)
    public function AdminStats(){
        $stables = $this->stables();
        $public=[];$private=[];$ahli=[];$institute=[];

        $public['unis'] = $stables['universities']->where('type','public')->pluck('id');
        $public['labs'] = $stables['labs']->whereIn('uni_id',$public['unis'])->pluck('id');
        $public['central_labs'] = $stables['central_labs']->whereIn('uni_id',$public['unis'])->pluck('id');
        // count rows
        $public['devices']=$stables['devices']->whereIn('lab_id',$public['labs'])->pluck('id');
        $public['central_devices']=$stables['central_devices']->whereIn('lab_id',$public['central_labs'])->pluck('id');
        // count all units
        //        $public['num_devices']=$stables['devices']->whereIn('lab_id',$public['labs'])->sum('num_units');
        //        $public['num_central_devices']=$stables['central_devices']->whereIn('lab_id',$public['central_labs'])->sum('num_units');

        $private['unis'] = $stables['universities']->where('type','private')->pluck('id');
        ///////// only universities that has entered labs
        $private['unis'] = $stables['labs']->whereIn('uni_id',$private['unis'])->pluck('uni_id')->unique();
        $private['labs'] = $stables['labs']->whereIn('uni_id',$private['unis'])->pluck('id');
        $private['central_labs'] = $stables['central_labs']->whereIn('uni_id',$private['unis'])->pluck('id');
        // count rows
        $private['devices']=$stables['devices']->whereIn('lab_id',$private['labs'])->pluck('id');
        $private['central_devices']=$stables['central_devices']->whereIn('lab_id',$private['central_labs'])->pluck('id');
        // count all units
        //        $private['num_devices']=$stables['devices']->whereIn('lab_id',$private['labs'])->sum('num_units');
        //        $private['num_central_devices']=$stables['central_devices']->whereIn('lab_id',$private['central_labs'])->sum('num_units');

        $ahli['unis'] = $stables['universities']->where('type','ahli')->pluck('id');
        ///////// only universities that has entered labs
        $ahli['unis'] = $stables['labs']->whereIn('uni_id',$ahli['unis'])->pluck('uni_id')->unique();
///////// only universities that has entered labs
        $ahli['labs'] = $stables['labs']->whereIn('uni_id',$ahli['unis'])->pluck('uni_id')->unique();
//         $ahli['labs'] = $stables['labs']->whereIn('uni_id',$ahli['unis'])->pluck('id');
        $ahli['central_labs'] = $stables['central_labs']->whereIn('uni_id',$ahli['unis'])->pluck('id');
        // count rows
        $ahli['devices']=$stables['devices']->whereIn('lab_id',$ahli['labs'])->pluck('id');
        $ahli['central_devices']=$stables['central_devices']->whereIn('lab_id',$ahli['central_labs'])->pluck('id');
        // count all units
        //        $ahli['num_devices']=$stables['devices']->whereIn('lab_id',$ahli['labs'])->sum('num_units');
        //        $ahli['num_central_devices']=$stables['central_devices']->whereIn('lab_id',$ahli['central_labs'])->sum('num_units');

        $institute['unis'] = $stables['universities']->where('type','Institution')->pluck('id');
        ///////// only universities that has entered labs
        $institute['unis'] = $stables['labs']->whereIn('uni_id',$institute['unis'])->pluck('uni_id')->unique();
        $institute['labs'] = $stables['labs']->whereIn('uni_id',$institute['unis'])->pluck('id');
        $institute['central_labs'] = $stables['central_labs']->whereIn('uni_id',$institute['unis'])->pluck('id');
        // count rows
        $institute['devices']=$stables['devices']->whereIn('lab_id',$institute['labs'])->pluck('id');
        $institute['central_devices']=$stables['central_devices']->whereIn('lab_id',$institute['central_labs'])->pluck('id');
        // count all units
        //        $institute['num_devices']=$stables['devices']->whereIn('lab_id',$institute['labs'])->sum('num_units');
        //        $institute['num_central_devices']=$stables['central_devices']->whereIn('lab_id',$institute['central_labs'])->sum('num_units');
        return compact('public','private','ahli','institute');
    }
/*
    public function index_univ()
    {

        $user = Auth()->user();
        if (auth()->user()->hasRole('university')){
        //if ($user->role_id == 2){  
    
            //$faculties = \App\Models\fac_uni::where('uni_id',$user->uni_id)->get();
            //$labs = \App\Models\labs::where('uni_id',$user->uni_id)->pluck('id');
           // $central_labs = \App\Models\UniLabs::where('uni_id',$user->uni_id)->get();
          // $central_devices =\App\Models\UniDevices::where('uni_id',$user->uni_id)->get();
          
            $faculties = \App\Models\fac_uni::where('uni_id',$user->uni_id)->count();
            $labs = \App\Models\labs::where('uni_id',$user->uni_id)->pluck('id')->count();
            $devices = \App\Models\devices::whereIn('lab_id',$labs)->count();
            $central_labs = \App\Models\UniLabs::where('uni_id',$user->uni_id)->count();
            $central_devices =\App\Models\UniDevices::where('uni_id',$user->uni_id)->count();
            // count all units
           // $num_central_units = $central_devices->sum('num_units');
          //  $num_units = $devices->sum('num_units');
           // return compact('user','faculties','labs','devices','central_labs','central_devices','num_central_units','num_units');
    
           return view('templ/index_univ',compact('faculties','labs','central_labs','devices','central_devices'));
    
    
    
        } 
    }
*/

public function index_homepage()
{
    if (auth()->user()->hasRole('visitor')){  return redirect()->route('home');}
    if (auth()->user()->hasRole('admin')){  return redirect()->route('home');}
    $stables = $this->stables();


    if ($stables['user']->hasRole('university'))
    {
        // for labs in each faculty chart
        $faculty_names = $stables['faculties']->pluck('name','fac_id');
        $lab_count = labs::select('fac_id', DB::raw('count(*) as total'))
            ->where('uni_id',$stables['user']->uni_id)
            ->groupBy('fac_id')
            ->get()->pluck('total','fac_id');
        $labss = array();
        // if there's a missing faculty id then it's empty so adds the faculty id and '0' as value
        foreach ($faculty_names as $id=>$name)
        {
            if(isset($lab_count[$id]))
            {$labss[$id]=$lab_count[$id];}
            else{$labss[$id]=0; }
        }
        $lab_y = array_values($labss);
        $fac_x = array_values($faculty_names->toArray());

        // for number of devices' units in each faculty chart
        $dev = DB::table('devices')
            ->join('labs', 'devices.lab_id', '=', 'labs.id')
            ->select('labs.fac_id',DB::raw('count(devices.id) as total')) // count rows
    //                ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total')) // count all units
            ->where('labs.uni_id',$stables['user']->uni_id)
            ->groupBy('labs.fac_id')
            ->get()->pluck('total','fac_id');
        $dev_fac = array();
        // if there's a missing faculty id then it's empty so adds the faculty id and '0' as value
        foreach ($faculty_names as $id=>$name)
        {
            if(isset($dev[$id]))
            {$dev_fac[$id]=(int)$dev[$id];}
            else{$dev_fac[$id]=0; }
        }
        $dev_y = array_values($dev_fac);
        return view('templ/indexHomepage', compact('stables','fac_x','lab_y','dev_y'));
        
    }


    elseif ($stables['user']->hasRole('faculty'))
    {
        $lab_names = $stables['labs']->pluck('name','id');
        // for number of devices in labs chart
        $dev = DB::table('devices')
            ->join('labs', 'devices.lab_id', '=', 'labs.id')
            ->select('labs.id',DB::raw('count(devices.id) as total')) // count rows
    //                ->select('labs.id',DB::raw('sum(devices.num_units) as total')) // count all units
            ->where('labs.uni_id',$stables['user']->uni_id)
            ->where('labs.fac_id',$stables['user']->fac_id)
            ->groupBy('labs.id')
            ->get()->pluck('total','id');
        $dev_fac = array();
        // if there's a missing lab id then it's empty so adds the lab id and '0' as value
        foreach ($lab_names as $id=>$name)
        {
            if(isset($dev[$id]))
            {$dev_fac[$id]=(int)$dev[$id];}
            else{$dev_fac[$id]=0; }
        }
        $dev_y = array_values($dev_fac);
        $fac_x = array_values($lab_names->toArray());
        $labs = $stables['labs']->get();
        $deptsInFac = dept_fac::where('uni_id',$stables['user']->uni_id)->where('fac_id',$stables['user']->fac_id)->pluck('dept_id');
        $depts = departments::whereIn('id',$deptsInFac)->pluck('name','id');
        return view('templ/indexHomepage', compact('stables','fac_x','dev_y','labs','depts'));
    }

    elseif ($stables['user']->hasRole('department'))
    {
        $lab_names = $stables['labs']->pluck('name','id');
        // for number of devices in labs chart
        $dev = DB::table('devices')
            ->join('labs', 'devices.lab_id', '=', 'labs.id')
            ->select('labs.id',DB::raw('count(devices.id) as total')) // count rows
    //                ->select('labs.id',DB::raw('sum(devices.num_units) as total')) // count all units
            ->where('labs.uni_id',$stables['user']->uni_id)
            ->where('labs.fac_id',$stables['user']->fac_id)
            ->where('labs.dept_id',$stables['user']->dept_id)
            ->groupBy('labs.id')
            ->get()->pluck('total','id');
        $dev_fac = array();
        // if there's a missing lab id then it's empty so adds the lab id and '0' as value
        foreach ($lab_names as $id=>$name)
        {
            if(isset($dev[$id]))
            {$dev_fac[$id]=(int)$dev[$id];}
            else{$dev_fac[$id]=0; }
        }
        $dev_y = array_values($dev_fac);
        $fac_x = array_values($lab_names->toArray());
        return view('templ/indexHomepage', compact('stables','fac_x','dev_y'));
    }

}

    public function index()
    {
        // if (auth()->user()->hasRole('visitor')){  return redirect()->route('home');}
        if (auth()->user()->hasRole('visitor')){  return redirect()->route('home');}
        $stables = $this->stables();
        if ($stables['user']->hasRole('admin')){
            $university_names = $stables['universities']->pluck('name','id');
            $university_types = $stables['universities']->pluck('type','id');
            // university names in an array
            $fac_x = array_values($university_names->toArray());
            $uni_types = array_values($university_types->toArray());

            // number of devices' units in each university for the chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.uni_id',DB::raw('count(devices.id) as total')) // count rows
        //                ->select('labs.uni_id',DB::raw('sum(devices.num_units) as total')) // count all units
                ->groupBy('labs.uni_id')
                ->get()->pluck('total','uni_id');


                $central_dev = DB::table('uni_devices')
                ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                ->select('universitys.id', DB::raw('count(uni_devices.id) as total'))
                ->groupBy('universitys.id')
             ->get()->pluck('total', 'id');


            $dev_uni = array();

            // if there's a missing university id then it's empty and it adds the uni_id and '0' as value
            foreach ($university_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_uni[$id]=(int)$dev[$id];}
                else{$dev_uni[$id]=0; }
            }

            foreach ($central_dev as $id => $count) {
                $dev_uni[$id] += $count;
            }

            $dev_y = array_values($dev_uni);

            //dd($dev_y);
  
            // System admin stats for top stats
            $admin_stats = $this->AdminStats();
            $count='devices'; // for select option by default devices
            return view('loggedTemp/index', compact('stables','fac_x','dev_y','uni_types','admin_stats','count'));
        }
        elseif ($stables['user']->hasRole('university'))
        {
            // for labs in each faculty chart
            $faculty_names = $stables['faculties']->pluck('name','fac_id');
            $lab_count = labs::select('fac_id', DB::raw('count(*) as total'))
                ->where('uni_id',$stables['user']->uni_id)
                ->groupBy('fac_id')
                ->get()->pluck('total','fac_id');
            $labss = array();
            // if there's a missing faculty id then it's empty so adds the faculty id and '0' as value
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($lab_count[$id]))
                {$labss[$id]=$lab_count[$id];}
                else{$labss[$id]=0; }
            }
            $lab_y = array_values($labss);
            $fac_x = array_values($faculty_names->toArray());

            // for number of devices' units in each faculty chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.fac_id',DB::raw('count(devices.id) as total')) // count rows
        //                ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total')) // count all units
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
            $dev_fac = array();
            // if there's a missing faculty id then it's empty so adds the faculty id and '0' as value
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=(int)$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $dev_y = array_values($dev_fac);
            return view('loggedTemp/index', compact('stables','fac_x','lab_y','dev_y'));
        }
        elseif ($stables['user']->hasRole('faculty'))
        {
            $lab_names = $stables['labs']->pluck('name','id');
            // for number of devices in labs chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total')) // count rows
        //                ->select('labs.id',DB::raw('sum(devices.num_units) as total')) // count all units
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->groupBy('labs.id')
                ->get()->pluck('total','id');
            $dev_fac = array();
            // if there's a missing lab id then it's empty so adds the lab id and '0' as value
            foreach ($lab_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=(int)$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $dev_y = array_values($dev_fac);
            $fac_x = array_values($lab_names->toArray());
            $labs = $stables['labs']->get();
            $deptsInFac = dept_fac::where('uni_id',$stables['user']->uni_id)->where('fac_id',$stables['user']->fac_id)->pluck('dept_id');
            $depts = departments::whereIn('id',$deptsInFac)->pluck('name','id');
            return view('loggedTemp/index', compact('stables','fac_x','dev_y','labs','depts'));
        }
        elseif ($stables['user']->hasRole('department'))
        {
            $lab_names = $stables['labs']->pluck('name','id');
            // for number of devices in labs chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total')) // count rows
        //                ->select('labs.id',DB::raw('sum(devices.num_units) as total')) // count all units
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$stables['user']->dept_id)
                ->groupBy('labs.id')
                ->get()->pluck('total','id');
            $dev_fac = array();
            // if there's a missing lab id then it's empty so adds the lab id and '0' as value
            foreach ($lab_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=(int)$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $dev_y = array_values($dev_fac);
            $fac_x = array_values($lab_names->toArray());
            return view('loggedTemp/index', compact('stables','fac_x','dev_y'));
        }
    }

    //dashboard filter results for system admin (detailed and charts)
    public function getUniDevices(Request $request){
        $stables = $this->stables();
        $selectedtype = $request->selectOption;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $selected_uni = $request->uni_selected;
        $selected_fac = $request->fac_selected;
        $admin_stats = $this->AdminStats();
        if ($selectedtype) {$university_names = $stables['universities']->where('type',$selectedtype)->pluck('name','id');}
        else { $university_names = $stables['universities']->pluck('name','id');}
        $x = array_values($university_names->toArray());
        $university_types = $stables['universities']->pluck('type','id');
        $uni_types = array_values($university_types->toArray());
        $price = $request->price;
        $count = $request->count;
        if ($count=='units'){
            if(($start_date==null and $end_date==null) and $selected_uni != null and $selected_fac == null)
                //devices in a university at all time
            {
                $selected_name = universitys::find($selected_uni);
                // for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                    ->where('fac_uni.uni_id',$selected_uni)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy(['facname','dept_id','labname']);
                $devvName = [$selected_name->name=>$devvName];
                $faculty_names = $stables['all_faculties']->where('uni_id',$selected_uni)->pluck('name','fac_id');
                $x = array_values($faculty_names->toArray());
                // for chart
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
                    ->where('labs.uni_id',$selected_uni)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->groupBy('labs.fac_id')
                    ->get()->pluck('total','fac_id');
                $dev_fac = array();
                foreach ($faculty_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_fac[$id]=(int)$dev[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $selected_name = $university_names[$selected_uni];
                $title = 'Devices in '.$selected_name;
                return view('loggedTemp/FacDetails', compact('stables','x','y','title','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date==null and $end_date==null) and $selected_uni == null and $selectedtype != null)
                // devices in a type's all universities at all time
            {
                // for details
                $devvName = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                    ->join('labs',function ($join){
                        $join->on('fac_uni.fac_id','=','labs.fac_id');
                        $join->on('fac_uni.uni_id','=','labs.uni_id');
                    })->join('devices','labs.id','devices.lab_id')
                    ->select('universitys.name as uniName','fac_uni.name as facName','fac_uni.uni_id','fac_uni.fac_id as facid'
                        ,'labs.dept_id as deptid','labs.name as labName','labs.id as labid','devices.*')
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy(['uniName','facid','dept_id','labid']);
                // formatting details for view
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
                // for chart
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->join('universitys','labs.uni_id','=','universitys.id')
                    ->select('universitys.id','universitys.type',DB::raw('sum(devices.num_units) as total'))
                    ->groupBy('universitys.id','universitys.type')
                    ->where('universitys.type',$selectedtype)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->pluck('total','id');
                foreach ($university_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_uni[$id]=(int)$dev[$id];}
                    else{$dev_uni[$id]=0; }
                }
                $y = array_values($dev_uni);
                $title= $selectedtype == 'Institution' ? 'Devices in institutions' : 'Devices in '.$selectedtype.' universities';
                return view('loggedTemp/FacDetails', compact('stables','title','x','y','uni_types','request','devvName','admin_stats'));
            }
            elseif (($start_date != null or $end_date != null) and $selected_uni == null)
                // devices in a type all university in certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                // for details
                $devvName = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                    ->join('labs',function ($join){
                        $join->on('fac_uni.fac_id','=','labs.fac_id');
                        $join->on('fac_uni.uni_id','=','labs.uni_id');
                    })->join('devices','labs.id','devices.lab_id')
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->select('universitys.name as uniName','fac_uni.name as facName','fac_uni.uni_id','fac_uni.fac_id as facid'
                        ,'labs.dept_id as deptid','labs.name as labName','labs.id as labid','devices.*')
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy(['uniName','facid','dept_id','labid']);
                // formatting details for view
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
                //for chart
                $dev = DB::table('devices')
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->join('universitys','labs.uni_id','=','universitys.id')
                    ->select('universitys.id','universitys.type',DB::raw('sum(devices.num_units) as total'))
                    ->groupBy('universitys.id','universitys.type')
                    ->where('universitys.type',$selectedtype)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()
                    ->pluck('total','id');
                $dev_uni = array();
                foreach ($university_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_uni[$id]=(int)$dev[$id];}
                    else{$dev_uni[$id]=0; }
                }
                $y = array_values($dev_uni);
                $title = 'Devices in '.$selectedtype.' universities from '.$start_date.' to '.$end_date;
                return view('loggedTemp/FacDetails', compact('stables','title','x','y','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                // devices in a university in certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $selected_name = universitys::find($selected_uni);
                // for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                    ->where('fac_uni.uni_id',$selected_uni)
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('price','>', 100000);
                    })
                    ->get()->groupBy(['facname','dept_id','labname']);
                $devvName = [$selected_name->name=>$devvName];
                $faculty_names = $stables['all_faculties']->where('uni_id',$selected_uni)->pluck('name','fac_id');
                $x = array_values($faculty_names->toArray());
                // for chart
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
                    ->where('labs.uni_id',$selected_uni)
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->groupBy('labs.fac_id')
                    ->get()->pluck('total','fac_id');
                $dev_fac = array();
                foreach ($faculty_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_fac[$id]=(int)$dev[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $selected_name = $university_names[$selected_uni];
                $title = 'Device in '.$selected_name.' from '.$start_date.' to '.$end_date;
                return view('loggedTemp/FacDetails', compact('stables','x','y','title','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date==null and $end_date==null) and $selected_uni != null and $selected_fac != null)
                //devices in a faculty at all time
            {
                $devv = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                    ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                    ->where('uni_id',$selected_uni)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy('fac_id');
                //for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                    ->where('fac_uni.uni_id',$selected_uni)->where('fac_id',$selected_fac)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy(['facname','dept_id','labname']);
                if (!$devv->has($selected_fac))
                {
                    $title='No Devices';
                    return view('loggedTemp/FacDetails', compact('stables','title','request','selected_fac','devvName','admin_stats'))->withErrors(["error"=>"No Devices found!"]);
                }
                $dev_labsInFaculty = $devv[$selected_fac]->pluck('total','id');  // Total devices in Labs in Selected faculty
                $labsInFaculty = labs::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selected_fac)->pluck('name','id');
                $dev_fac = array();
                foreach ($labsInFaculty as $id=>$name)
                {
                    if(isset($dev_labsInFaculty[$id]))
                    {$dev_fac[$id]=(int)$dev_labsInFaculty[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInFaculty->toArray());
                $selected = fac_uni::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name')[0];
                $title = 'Number of Devices in '.(string)$selected;
                return view('loggedTemp/FacDetails', compact('stables','x','y','title','selected_fac','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                //devices in a faculty at certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $selected = fac_uni::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name')[0];
                $title = 'Number of Devices in '.(string)$selected.' from '.$start_date.' to '.$end_date;
                $selected_name = universitys::find($selected_uni);
                // for chart
                $devv = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.id')
                    ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                    ->where('uni_id',$selected_uni)
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy('fac_id');
                // for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','fac_uni.name as facname')
                    ->where('labs.uni_id',$selected_uni)->where('labs.fac_id',$selected_fac)->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy(['facname','dept_id','labname']);

                $error = 'No Devices Found';
                $x=0;$y=0;
                if (! isset($devv[$selected_fac])) return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','error','devv','devvName','request'))->withErrors(["error"=>"No Devices found!"]);
                else{
                    $dev_labsInFaculty = $devv[$selected_fac]->pluck('total','id');  // Total devices in Labs in Selected faculty
                    $labsInFaculty = labs::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name','id');

                    $dev_fac = array();
                    foreach ($labsInFaculty as $id=>$name)
                    {
                        if(isset($dev_labsInFaculty[$id]))
                        {$dev_fac[$id]=(int)$dev_labsInFaculty[$id];}
                        else{$dev_fac[$id]=0; }
                    }
                    $y = array_values($dev_fac);
                    $x = array_values($labsInFaculty->toArray());
                    $error='';
                    return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','error','devv','devvName','request'));
                }
            }
        }
        else{
            if(($start_date==null and $end_date==null) and $selected_uni != null and $selected_fac == null)
                //devices in a university at all time
            {
                $selected_name = universitys::find($selected_uni);
                // for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                    ->where('fac_uni.uni_id',$selected_uni)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy(['facname','dept_id','labname']);
                $devvName = [$selected_name->name=>$devvName];
                $faculty_names = $stables['all_faculties']->where('uni_id',$selected_uni)->pluck('name','fac_id');
                $x = array_values($faculty_names->toArray());
                // for chart
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                    ->where('labs.uni_id',$selected_uni)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->groupBy('labs.fac_id')
                    ->get()->pluck('total','fac_id');
                $dev_fac = array();
                foreach ($faculty_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_fac[$id]=(int)$dev[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $selected_name = $university_names[$selected_uni];
                $title = 'Devices in '.$selected_name;
                return view('loggedTemp/FacDetails', compact('stables','x','y','title','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date==null and $end_date==null) and $selected_uni == null and $selectedtype != null)
                // devices in a type's all universities at all time
            {
                // for details
                $devvName = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                    ->join('labs',function ($join){
                        $join->on('fac_uni.fac_id','=','labs.fac_id');
                        $join->on('fac_uni.uni_id','=','labs.uni_id');
                    })->join('devices','labs.id','devices.lab_id')
                    ->select('universitys.name as uniName','fac_uni.name as facName','fac_uni.uni_id','fac_uni.fac_id as facid'
                        ,'labs.dept_id as deptid','labs.name as labName','labs.id as labid','devices.*')
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy(['uniName','facid','dept_id','labid']);
                // formatting details for view
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
                // for chart
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->join('universitys','labs.uni_id','=','universitys.id')
                    ->select('universitys.id','universitys.type',DB::raw('count(devices.id) as total'))
                    ->groupBy('universitys.id','universitys.type')
                    ->where('universitys.type',$selectedtype)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->pluck('total','id');
                foreach ($university_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_uni[$id]=(int)$dev[$id];}
                    else{$dev_uni[$id]=0; }
                }
                $y = array_values($dev_uni);
                $title= $selectedtype == 'Institution' ? 'Devices in institutions' : 'Devices in '.$selectedtype.' universities';
                return view('loggedTemp/FacDetails', compact('stables','title','x','y','uni_types','request','devvName','admin_stats'));
            }
            elseif (($start_date != null or $end_date != null) and $selected_uni == null)
                // devices in a type all university in certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                // for details
                $devvName = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                    ->join('labs',function ($join){
                        $join->on('fac_uni.fac_id','=','labs.fac_id');
                        $join->on('fac_uni.uni_id','=','labs.uni_id');
                    })->join('devices','labs.id','devices.lab_id')->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->select('universitys.name as uniName','fac_uni.name as facName','fac_uni.uni_id','fac_uni.fac_id as facid'
                        ,'labs.dept_id as deptid','labs.name as labName','labs.id as labid','devices.*')
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy(['uniName','facid','dept_id','labid']);
                // formatting details for view
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
                //for chart
                $dev = DB::table('devices')
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->join('universitys','labs.uni_id','=','universitys.id')
                    ->select('universitys.id','universitys.type',DB::raw('count(devices.id) as total'))
                    ->groupBy('universitys.id','universitys.type')
                    ->where('universitys.type',$selectedtype)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()
                    ->pluck('total','id');
                $dev_uni = array();
                foreach ($university_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_uni[$id]=(int)$dev[$id];}
                    else{$dev_uni[$id]=0; }
                }
                $y = array_values($dev_uni);
                $title = 'Devices in '.$selectedtype.' universities from '.$start_date.' to '.$end_date;
                return view('loggedTemp/FacDetails', compact('stables','title','x','y','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                // devices in a university in certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $selected_name = universitys::find($selected_uni);
                // for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                    ->where('fac_uni.uni_id',$selected_uni)
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('price','>', 100000);
                    })
                    ->get()->groupBy(['facname','dept_id','labname']);
                $devvName = [$selected_name->name=>$devvName];
                $faculty_names = $stables['all_faculties']->where('uni_id',$selected_uni)->pluck('name','fac_id');
                $x = array_values($faculty_names->toArray());
                // for chart
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                    ->where('labs.uni_id',$selected_uni)
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->groupBy('labs.fac_id')
                    ->get()->pluck('total','fac_id');
                $dev_fac = array();
                foreach ($faculty_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_fac[$id]=(int)$dev[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $selected_name = $university_names[$selected_uni];
                $title = 'Device in '.$selected_name.' from '.$start_date.' to '.$end_date;
                return view('loggedTemp/FacDetails', compact('stables','x','y','title','uni_types','request','devvName','admin_stats'));
            }
            elseif(($start_date==null and $end_date==null) and $selected_uni != null and $selected_fac != null)
                //devices in a faculty at all time
            {
                $devv = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                    ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                    ->where('uni_id',$selected_uni)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy('fac_id');
                //for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                    ->where('fac_uni.uni_id',$selected_uni)->where('fac_uni.fac_id',$selected_fac)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->get()->groupBy(['facname','dept_id','labname']);
                dd($devvName);
                if (!$devv->has($selected_fac))
                {
                    $title='No Devices';
                    return view('loggedTemp/FacDetails', compact('stables','title','request','selected_fac','devvName','admin_stats'))->withErrors(["error"=>"No Devices found!"]);
                }
                $dev_labsInFaculty = $devv[$selected_fac]->pluck('total','id');  // Total devices in Labs in Selected faculty
                $labsInFaculty = labs::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selected_fac)->pluck('name','id');
                $dev_fac = array();
                foreach ($labsInFaculty as $id=>$name)
                {
                    if(isset($dev_labsInFaculty[$id]))
                    {$dev_fac[$id]=(int)$dev_labsInFaculty[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInFaculty->toArray());
                $selected = fac_uni::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name')[0];
                $title = 'Number of Devices in '.(string)$selected;
                return view('loggedTemp/FacDetails', compact('stables','x','y','title','selected_fac','uni_types','request','devvName','admin_stats'));

            }
            elseif(($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                //devices in a faculty at certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $selected = fac_uni::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name')[0];
                $title = 'Number of Devices in '.(string)$selected.' from '.$start_date.' to '.$end_date;
                $selected_name = universitys::find($selected_uni);
                // for chart
                $devv = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.id')
                    ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                    ->where('uni_id',$selected_uni)
                    ->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy('fac_id');
                // for details
                $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                    ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','fac_uni.name as facname')
                    ->where('labs.uni_id',$selected_uni)->where('labs.fac_id',$selected_fac)->whereBetween('devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy(['facname','dept_id','labname']);

                $error = 'No Devices Found';
                $x=0;$y=0;
                if (! isset($devv[$selected_fac])) return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','error','devv','devvName','request'))->withErrors(["error"=>"No Devices found!"]);
                else{
                    $dev_labsInFaculty = $devv[$selected_fac]->pluck('total','id');  // Total devices in Labs in Selected faculty
                    $labsInFaculty = labs::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name','id');

                    $dev_fac = array();
                    foreach ($labsInFaculty as $id=>$name)
                    {
                        if(isset($dev_labsInFaculty[$id]))
                        {$dev_fac[$id]=(int)$dev_labsInFaculty[$id];}
                        else{$dev_fac[$id]=0; }
                    }
                    $y = array_values($dev_fac);
                    $x = array_values($labsInFaculty->toArray());
                    $error='';
                    return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','error','devv','devvName','request'));
                }
            }
        }
    }

    //dashboard filter results for university admin (detailed and charts)
    public function getLabDevices(Request $request){
        $stables = $this->stables();
        $selectedID = $request->selectOption;
        $start_date = $request->start_date;
        $end_date = $request->end_date ;
        $price = $request->price;
        $selected = $selectedID;

        if ($selectedID=='Central Labs'){
            if ($start_date==null and $end_date==null){
                //chart
                $devv = DB::table('uni_devices')
                    ->join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
        //                    ->select('uni_labs.name',DB::raw('sum(uni_devices.num_units) as total'),'uni_labs.uni_id','uni_labs.id')
                    ->select('uni_labs.name',DB::raw('count(uni_devices.id) as total'),'uni_labs.uni_id','uni_labs.id')
                    ->groupBy('uni_labs.id')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                    })
                    ->get()->keyBy('id');
                $labsInUni = UniLabs::where('uni_id',$stables['user']->uni_id)->pluck('name','id');
                $dev_fac = array();
                foreach ($labsInUni as $id=>$name)
                {
                    if(isset($devv[$id]))
                    {$dev_fac[$id]=(int)$devv[$id]->total;}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInUni->toArray());
                //details
                $devvName = UniDevices::join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
                    ->select('uni_devices.*','uni_labs.name as labname')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                    })
                    ->get()->groupBy('labname');
                $selected='Central Labs';
                $title='';
                return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','devvName','request'));
            }
            else{
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                //chart
                $devv = DB::table('uni_devices')
                    ->join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
            //                    ->select('uni_labs.name',DB::raw('sum(uni_devices.num_units) as total'),'uni_labs.uni_id','uni_labs.id')
                    ->select('uni_labs.name',DB::raw('count(uni_devices.id) as total'),'uni_labs.uni_id','uni_labs.id')
                    ->groupBy('uni_labs.id')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                    })
                    ->get()->keyBy('id');
                $labsInUni = UniLabs::where('uni_id',$stables['user']->uni_id)->pluck('name','id');
                $dev_fac = array();
                foreach ($labsInUni as $id=>$name)
                {
                    if(isset($devv[$id]))
                    {$dev_fac[$id]=(int)$devv[$id]->total;}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInUni->toArray());
                //details
                $devvName = UniDevices::join('uni_labs','uni_devices.lab_id','=','uni_labs.id')
                    ->select('uni_devices.*','uni_labs.name as labname')
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                    })
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
            
            $selected = fac_uni::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name')[0];
            //for chart
            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
            //                ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                ->where('uni_id',$stables['user']->uni_id)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy('fac_id');
                /*
            //for details
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                ->select('devices.*','labs.name as labname','labs.dept_id','fac_uni.name as facname')
                ->where('fac_uni.uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['facname','dept_id','labname']);
                */

               // for details
               $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
               ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','fac_uni.name as facname')->distinct()
               ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$selectedID)
               ->when($price, function ($query, $price) {
                   if ($price == 'less100k')
                       return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                   elseif ($price == 'more100k')
                       return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
               })->get()->groupBy(['facname','dept_id','labname']);    
            if (!$devv->has($selectedID))
            {
                $title='No Devices';
                return view('loggedTemp/FacDetails', compact('stables','title','request','selected','devvName'))->withErrors(["error"=>"No Devices found!"]);
            }
            $dev_labsInFaculty = $devv[$selectedID]->pluck('total','id');  // Total devices in Labs in Selected faculty
            $labsInFaculty = labs::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name','id');
            $dev_fac = array();
            foreach ($labsInFaculty as $id=>$name)
            {
                if(isset($dev_labsInFaculty[$id]))
                {$dev_fac[$id]=(int)$dev_labsInFaculty[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($labsInFaculty->toArray());
            $title = 'Number of Devices in '.(string)$selected;
            //dd($devv);
            return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','devvName','request'));
        }
        elseif(($start_date != null or $end_date != null) and $selectedID == null)
            // number of devices in all faculties in certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            // for chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
            //                ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
                ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
            $selected_name = universitys::find($stables['user']->uni_id);
            // for details
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','fac_uni.name as facname')->distinct()
                ->where('fac_uni.uni_id',$stables['user']->uni_id)->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['facname','dept_id','labname']);
            $dev_fac = array();
            $faculty_names = fac_uni::where('uni_id',$stables['user']->uni_id)->get()->pluck('name','fac_id');
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=(int)$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($faculty_names->toArray());
            $title = 'Devices in Faculties from '.(string)$start_date.' to '.(string)$end_date;
            return view('loggedTemp/FacDetails',compact('stables','x','y','title','request','devvName','selected'));
        }
        elseif (($start_date != null or $end_date != null) and $selectedID != null)
            // number of devices in a faculty in certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $selected = fac_uni::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name')[0];
            $title = 'Number of Devices in '.(string)$selected.' from '.$start_date.' to '.$end_date;
            $selected_name = universitys::find($stables['user']->uni_id);
            // for chart
            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
        //                ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.id')
                ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                ->where('uni_id',$stables['user']->uni_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })->get()->groupBy('fac_id');
            // for details
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','fac_uni.name as facname')->distinct()
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$selectedID)->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })->get()->groupBy(['facname','dept_id','labname']);

            $error = 'No Devices Found';
            $x=0;$y=0;
            if (! isset($devv[$selectedID])) return view('loggedTemp/FacDetails', compact('stables','request','devvName','selected','title','error'))->withErrors(["error"=>"No Devices found!"]);
            else{
                $dev_labsInFaculty = $devv[$selectedID]->pluck('total','id');  // Total devices in Labs in Selected faculty
                $labsInFaculty = labs::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name','id');

                $dev_fac = array();
                foreach ($labsInFaculty as $id=>$name)
                {
                    if(isset($dev_labsInFaculty[$id]))
                    {$dev_fac[$id]=(int)$dev_labsInFaculty[$id];}
                    else{$dev_fac[$id]=0; }
                }
                $y = array_values($dev_fac);
                $x = array_values($labsInFaculty->toArray());
                $error='';
                return view('loggedTemp/FacDetails', compact('stables','selected','x','y','title','error','devv','devvName','request'));
            }
        }
        elseif (($start_date == null and $end_date == null) and $selectedID == null)
        {
            // for chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
            //                ->select('labs.fac_id',DB::raw('sum(devices.num_units)) as total'))
                ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->groupBy('labs.fac_id')
                ->get()->pluck('total','fac_id');
            $selected_name = universitys::find($stables['user']->uni_id);
            // for details
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                ->select('devices.*','labs.name as labname','labs.id','labs.fac_id as facid','fac_uni.name as facname')->distinct()
                ->where('fac_uni.uni_id',$stables['user']->uni_id)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['facname','dept_id','labname']);
            $dev_fac = array();
            $faculty_names = fac_uni::where('uni_id',$stables['user']->uni_id)->get()->pluck('name','fac_id');
            foreach ($faculty_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=(int)$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($faculty_names->toArray());
            $title = 'Devices in Faculties ';
            return view('loggedTemp/FacDetails',compact('stables','x','y','title','request','devvName','selected'));
        }
    }

    //dashboard filter results for faculty admin (detailed and charts)
    public function getFacDevices(Request $request){
        $stables = $this->stables();
        $lab_selected = $request->selectOption;
        $dept_selected = $request->deptChosen;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $price = $request->price;
        $labs = $stables['labs']->get();
        $deptsInFac = dept_fac::where('uni_id',$stables['user']->uni_id)->where('fac_id',$stables['user']->fac_id)->pluck('dept_id');
        $depts = departments::whereIn('id',$deptsInFac)->pluck('name','id');
        if ($dept_selected != null and $lab_selected==null and ($start_date==null and $end_date==null))
            // devices in a department at all time
        {
            $devv = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
        //                ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->groupBy('labs.name')
                ->where('uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->keyBy('id');
            $labs = $labs->where('uni_id',$stables['user']->uni_id)
                ->where('fac_id',$stables['user']->fac_id)->where('dept_id',$dept_selected)->pluck('name','id');
            $dev_fac = array();
            foreach ($labs as $id=>$name)
            {
                if(isset($devv[$id]))
                {$dev_fac[$id]=(int)$devv[$id]->total;}
                else{$dev_fac[$id]=0; }
            }
            $x = array_values($labs->toArray());
            $y = array_values($dev_fac);
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('devices.*','labs.name as labname','labs.dept_id')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
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
        //                ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.dept_id','labs.id')
                ->groupBy('labs.name')
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->where('uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->keyBy('id');
            $labs = $labs->where('uni_id',$stables['user']->uni_id)
                ->where('fac_id',$stables['user']->fac_id)->where('dept_id',$dept_selected)->pluck('name','id');
            $dev_fac = array();
            foreach ($labs as $id=>$name)
            {
                if(isset($devv[$id]))
                {$dev_fac[$id]=(int)$devv[$id]->total;}
                else{$dev_fac[$id]=0; }
            }
            $x = array_values($labs->toArray());
            $y = array_values($dev_fac);

            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('devices.*','labs.name as labname','labs.dept_id')
                ->where('labs.uni_id',$stables['user']->uni_id)->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$dept_selected)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['labname']);
            $labs = $stables['labs']->get();
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));
        }
        elseif ($lab_selected and ($start_date==null and $end_date==null))
            // devices in a lab at all time
        {
            //for details
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('devices.*','labs.name as labname','labs.id')
                ->where('labs.id',$lab_selected)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['labs.id']);
            $x=[];
            $y=[];
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));
        }
        elseif ($lab_selected and ($start_date!=null or $end_date!=null))
            // devices in a lab at a certain time
        {
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $devvName = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('devices.*','labs.name as labname','labs.id')
                ->where('labs.id',$lab_selected)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['labs.id']);
            $x=[];
            $y=[];
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
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->get()->groupBy(['labname']);
            $lab_names = $stables['labs']->pluck('name','id');
            // for devices chart
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total'))
        //                ->select('labs.id',DB::raw('sum(devices.num_units) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })
                ->groupBy('labs.id')
                ->get()->pluck('total','id');
            $dev_fac = array();
            foreach ($lab_names as $id=>$name)
            {
                if(isset($dev[$id]))
                {$dev_fac[$id]=(int)$dev[$id];}
                else{$dev_fac[$id]=0; }
            }
            $y = array_values($dev_fac);
            $x = array_values($lab_names->toArray());
            $labs = $stables['labs']->get();
            return view('loggedTemp/FacDetails', compact('stables','labs','devvName','request','depts','x','y'));

        }
    }

    //dashboard filter results for department admin (detailed and charts)
    public function getDeptDevices(Request $request){
        $stables = $this->stables();
        $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
        $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
        $price = $request->price;
        $lab_names = $stables['labs']->get()->pluck('name','id');
        $dev = DB::table('devices')
            ->join('labs', 'devices.lab_id', '=', 'labs.id')
            ->select('labs.id',DB::raw('count(devices.id) as total'))
        //            ->select('labs.id',DB::raw('sum(devices.num_units) as total'))
            ->where('labs.uni_id',$stables['user']->uni_id)
            ->where('labs.fac_id',$stables['user']->fac_id)
            ->where('labs.dept_id',$stables['user']->dept_id)
            ->whereBetween('devices.entry_date',[$start_date,$end_date])
            ->when($price, function ($query, $price) {
                if ($price == 'less100k')
                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                elseif ($price == 'more100k')
                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
            })
            ->groupBy('labs.id')
            ->get()->pluck('total','id');
        $dev_fac = array();
        foreach ($lab_names as $id=>$name)
        {
            if(isset($dev[$id]))
            {$dev_fac[$id]=(int)$dev[$id];}
            else{$dev_fac[$id]=0; }
        }
        $y = array_values($dev_fac);
        $x = array_values($lab_names->toArray());
        $title = 'Device in the labs';
        return view('loggedTemp/FacDetails', compact('stables','x','y','title'));
    }

    public function getServices()
    {
        $services = services::all();
        return view('services.index', compact('services'));
    }

    // stable queries
    public function stables(){
        $user = Auth()->user();
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
            $devices = devices::whereIn('lab_id',$labs);
            $central_labs = UniLabs::where('uni_id',$user->uni_id)->get();
            $central_devices = UniDevices::where('uni_id',$user->uni_id)->get();
            // count all units
            $num_central_units = $central_devices->sum('num_units');
            $num_units = $devices->sum('num_units');
            return compact('user','faculties','labs','devices','central_labs','central_devices','num_central_units','num_units');
        }
        elseif ($user->hasRole('faculty'))
        {
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id);
            $devices = devices::whereIn('lab_id',$labs->pluck('id'));
            //count all units
            $num_units = $devices->sum('num_units');
            return compact('user','labs','devices','num_units');
        }
        elseif($user->hasRole('department')){
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->where('dept_id',$user->dept_id);
            $devices = devices::whereIn('lab_id',$labs->pluck('id'));
            //count all units
            $num_units = $devices->sum('num_units');
            return compact('user','labs','devices','num_units');
        }
        else return null;
    }

    // fetches university coordinators' username and password for System Admin in Left Panel
    public function getThisUser($uniID)
    {
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
