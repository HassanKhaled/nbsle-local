<?php

namespace App\Exports;

use App\Models\devices;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;


class DevicesExport implements FromCollection,WithHeadings, ShouldAutoSize, WithStrictNullComparison, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

protected $request;
    public function __construct(Request $request, string $what)
    {
        $this->request = $request;
        $this->exportwhat = $what;
    }
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 20,
            'L' => 20,
            'M' => 30,
            'N' => 20,
        ];
    }

    public function stables(){
        //Auth()->user()->hasRole('admin')
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
        elseif ($user->hasRole('department'))
        {
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id',$user->fac_id)->where('dept_id',$user->dept_id);
            $devices = devices::whereIn('lab_id',$labs->pluck('id'));
            return compact('user','labs','devices');
        }
        else return null;
    }
    public function collection()
    {
        $stables = $this->stables();
        $selectedID = $this->request->selectOption;
        $start_date = $this->request->start_date;
        $end_date = $this->request->end_date;
        $price = $this->request->price;
        if ($this->exportwhat=='everything'){
            if ($stables['user']->hasRole('admin')) {
                $central_dev='';
                $selectedtype = $this->request->selectOption;
                $selected_uni = $this->request->uni_selected;
                $selected_fac = $this->request->fac_selected;
                if ($selectedtype) {
                    $university_names = $stables['universities']->where('type', $selectedtype)->pluck('name', 'id');
                } else {
                    $university_names = $stables['universities']->pluck('name', 'id');
                }
                $x = array_values($university_names->toArray());
                $university_types = $stables['universities']->pluck('type', 'id');
                $uni_types = array_values($university_types->toArray());
                if (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac == null)
                    //DONE  //devices in a university at all time
                {
                    $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->join('fac_uni','labs.fac_id','=','fac_uni.fac_id')
                        ->select('universitys.name as University Name','fac_uni.name as FacultyName','labs.name as Lab Name', 'devices.name','devices.model'
                            ,'devices.num_units','devices.services','devices.cost','devices.state','devices.price','devices.description'
                            ,'devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear','devices.ManufactureCountry'
                            ,'devices.ManufactureWebsite','devices.entry_date')
                        ->where('labs.uni_id', $selected_uni)
                        ->get();

                    $central_dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.name as University Name', DB::raw("'Central Lab' as FacultyName"),'uni_labs.name as Lab Name','uni_devices.name','uni_devices.model'
                            ,'uni_devices.num_units','uni_devices.services','uni_devices.cost','uni_devices.state','uni_devices.price','uni_devices.description'
                            ,'uni_devices.AdditionalInfo','uni_devices.manufacturer','uni_devices.ManufactureYear'
                            ,'uni_devices.ManufactureCountry','uni_devices.ManufactureWebsite','uni_devices.entry_date')
                        ->where('uni_labs.uni_id',$selected_uni)
                        ->get();
//                    dd($dev[0],$central_dev);
                    $dev=$central_dev->concat($dev);

                }
                elseif (($start_date == null and $end_date == null) and $selected_uni == null and $selectedtype != null)
                    // DONE // devices in a type all university at all time
                {
                    $dev = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                        ->join('labs',function ($join){
                            $join->on('fac_uni.fac_id','=','labs.fac_id');
                            $join->on('fac_uni.uni_id','=','labs.uni_id');
                        })->join('devices','labs.id','devices.lab_id')
                        ->select('universitys.name as University Name','fac_uni.name as FacultyName','labs.name as Lab Name','devices.name'
                            ,'devices.model','devices.num_units','devices.services','devices.cost','devices.state','devices.price','devices.description'
                            ,'devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear','devices.ManufactureCountry'
                            ,'devices.ManufactureWebsite','devices.entry_date')
                        ->get();

                    $central_dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.name as University Name',DB::raw("'Central Lab' as FacultyName"),'uni_labs.name as Lab Name'
                            ,'uni_devices.name','uni_devices.model','uni_devices.num_units','uni_devices.services','uni_devices.cost','uni_devices.state'
                            ,'uni_devices.price','uni_devices.description','uni_devices.AdditionalInfo','uni_devices.manufacturer','uni_devices.ManufactureYear'
                            ,'uni_devices.ManufactureCountry','uni_devices.ManufactureWebsite','uni_devices.entry_date')
                        ->get();
                    $dev=$central_dev->concat($dev);
//                    dd($dev[0]);

                }
                elseif (($start_date != null or $end_date != null) and $selected_uni == null and $selectedtype != null)
                    // DONE // devices in all university in certain time
                {
                    $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                    $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;

                    $dev = universitys::where('type',$selectedtype)->join('fac_uni','universitys.id','=','fac_uni.uni_id')
                        ->join('labs',function ($join){
                            $join->on('fac_uni.fac_id','=','labs.fac_id');
                            $join->on('fac_uni.uni_id','=','labs.uni_id');
                        })->join('devices','labs.id','devices.lab_id')->whereBetween('devices.entry_date',[$start_date,$end_date])
                        ->select('universitys.name as University Name','fac_uni.name as FacultyName','labs.name as Lab Name','devices.name'
                            ,'devices.model','devices.num_units','devices.services','devices.cost','devices.state','devices.price','devices.description'
                            ,'devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear','devices.ManufactureCountry'
                            ,'devices.ManufactureWebsite','devices.entry_date')
                        ->get();

                    $central_dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.name as University Name',DB::raw("'Central Lab' as FacultyName"),'uni_labs.name as Lab Name'
                            , 'uni_devices.name','uni_devices.model','uni_devices.num_units','uni_devices.services','uni_devices.cost','uni_devices.state'
                            ,'uni_devices.price','uni_devices.description','uni_devices.AdditionalInfo','uni_devices.manufacturer','uni_devices.ManufactureYear'
                            ,'uni_devices.ManufactureCountry','uni_devices.ManufactureWebsite','uni_devices.entry_date')
                        ->where('universitys.type',$selectedtype)
                        ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                        ->get();
                    $dev=$central_dev->concat($dev);

                }
                elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                    // DONE // devices in a university in certain time
                {
                    $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                    $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                    $selected_name = universitys::find($selected_uni);

                    $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')->join('fac_uni','labs.fac_id','=','fac_uni.id')
                        ->join('universitys','fac_uni.uni_id','universitys.id')
                        ->select('universitys.name as University Name','fac_uni.name as FacultyName','labs.name as Lab Name','devices.name','devices.model','devices.num_units'
                            ,'devices.services','devices.cost','devices.state','devices.price','devices.description'
                            ,'devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear','devices.ManufactureCountry'
                            ,'devices.ManufactureWebsite','devices.entry_date')
                        ->where('fac_uni.uni_id',$selected_uni)
                        ->whereBetween('devices.entry_date',[$start_date,$end_date])
                        ->get();

                    $central_dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.name as University Name',DB::raw("'Central Lab' as FacultyName"),'uni_labs.name as Lab Name'
                            ,'uni_devices.name','uni_devices.model','uni_devices.num_units','uni_devices.services','uni_devices.cost','uni_devices.state'
                            ,'uni_devices.price','uni_devices.description','uni_devices.AdditionalInfo','uni_devices.manufacturer','uni_devices.ManufactureYear'
                            ,'uni_devices.ManufactureCountry','uni_devices.ManufactureWebsite','uni_devices.entry_date')
                        ->where('uni_labs.uni_id',$selected_uni)
                        ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                        ->get();
                    $dev=$central_dev->concat($dev);

                }
                elseif (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac != null)
                    //devices in a faculty at all time
                {
                    $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->where('labs.uni_id', $selected_uni)
                        ->where('labs.fac_id', $selected_fac)
                        ->select('universitys.name as University Name','labs.name as Lab Name', 'devices.name', 'devices.model', 'devices.num_units', 'devices.services', 'devices.cost'
                            , 'devices.state', 'devices.price', 'devices.description', 'devices.AdditionalInfo', 'devices.manufacturer'
                            , 'devices.ManufactureYear', 'devices.ManufactureCountry', 'devices.ManufactureWebsite', 'devices.entry_date')
                        ->get();
                    $selected_name = fac_uni::where('uni_id',$selected_uni)->where('fac_id', $selected_fac);
                    foreach ($dev as $device) {$device->FacultyName = $selected_name->pluck('name')->first();}
//                    dd($dev);
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                    // devices in a faculty in certain time
                {
                    $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                    $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                    $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->where('labs.uni_id', $selected_uni)
                        ->where('labs.fac_id', $selected_fac)
                        ->select('universitys.name as University Name','labs.name as Lab Name', 'devices.name', 'devices.model', 'devices.num_units', 'devices.services', 'devices.cost'
                            , 'devices.state', 'devices.price', 'devices.description', 'devices.AdditionalInfo', 'devices.manufacturer'
                            , 'devices.ManufactureYear', 'devices.ManufactureCountry', 'devices.ManufactureWebsite', 'devices.entry_date')
                        ->whereBetween('devices.entry_date', [$start_date, $end_date])
                        ->get();
                    $selected_name = fac_uni::where('uni_id',$selected_uni)->where('fac_id', $selected_fac);
                    foreach ($dev as $device) {$device->FacultyName = $selected_name->pluck('name')->first();}
                }

//                if ($central_dev!=''){
//                    $dev = $dev->toArray();
//                    $central_dev = $central_dev->toArray();
//                    $merged_dev = array_merge($dev,$central_dev);
//                    $dev = collect($merged_dev);
//                }

                $dev->transform(function($item,$key) {
                    return $item->only($this->request->columns);
                });
//                dd($dev);
                return collect($dev);
            }
            if ($stables['user']->hasRole('university')) {
//                dd($selectedID,$this->request);
                if ($selectedID == 'Central Labs'){
                    if ($start_date == null and $end_date == null){
                        $dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                            ->select('universitys.name as University Name',DB::raw("'Central Lab' as FacultyName"),'uni_labs.name as Lab name'
                                ,'uni_devices.name','uni_devices.model','uni_devices.num_units','uni_devices.services','uni_devices.cost','uni_devices.state'
                                ,'uni_devices.price','uni_devices.description','uni_devices.AdditionalInfo','uni_devices.manufacturer','uni_devices.ManufactureYear'
                                ,'uni_devices.ManufactureCountry','uni_devices.ManufactureWebsite','uni_devices.entry_date')
                            ->where('uni_labs.uni_id',$stables['user']->uni_id)
                            ->get();
                    }
                    else{
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                            ->select(DB::raw("'Central Lab' as FacultyName"),'uni_labs.name as Lab name'
                                ,'uni_devices.name','uni_devices.model','uni_devices.num_units','uni_devices.services','uni_devices.cost','uni_devices.state'
                                ,'uni_devices.price','uni_devices.description','uni_devices.AdditionalInfo','uni_devices.manufacturer','uni_devices.ManufactureYear'
                                ,'uni_devices.ManufactureCountry','uni_devices.ManufactureWebsite','uni_devices.entry_date')
                            ->where('uni_labs.uni_id',$stables['user']->uni_id)
                            ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                            ->get();
                    }
                }
                else {
                    if ($selectedID != null and ($start_date == null and $end_date == null))
                        // Devices in a faculty at all time
                    {
                        $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->where('labs.uni_id', $stables['user']->uni_id)
                            ->where('labs.fac_id', $selectedID)
                            ->select(DB::raw("'fac' as FacultyName"), 'labs.name as Lab Name', 'devices.name', 'devices.model', 'devices.num_units'
                                , 'devices.services', 'devices.cost', 'devices.state', 'devices.price', 'devices.description', 'devices.AdditionalInfo', 'devices.manufacturer'
                                , 'devices.ManufactureYear', 'devices.ManufactureCountry', 'devices.ManufactureWebsite', 'devices.entry_date')
                            ->get();
                        $selected_name = fac_uni::where('uni_id', $stables['user']->uni_id)->where('fac_id', $selectedID);
                        foreach ($dev as $device) $device->FacultyName = $selected_name->pluck('name')->first();
//                    dd($dev);
                    }
                    elseif (($start_date != null or $end_date != null) and $selectedID == null)
                        // number of devices in all faculties in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->join('fac_uni', 'labs.fac_id', '=', 'fac_uni.fac_id')
                            ->where('labs.uni_id', $stables['user']->uni_id)
                            ->select('fac_uni.name as FacultyName', 'labs.name as Lab Name', 'devices.name', 'devices.model', 'devices.num_units'
                                , 'devices.services', 'devices.cost', 'devices.state', 'devices.price', 'devices.description', 'devices.AdditionalInfo'
                                , 'devices.manufacturer', 'devices.ManufactureYear', 'devices.ManufactureCountry', 'devices.ManufactureWebsite', 'devices.entry_date')
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->distinct()->get();
//
                        $central_dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                            ->select(DB::raw("'Central Lab' as FacultyName"), 'uni_labs.name as Lab Name', 'uni_devices.name'
                                , 'uni_devices.model', 'uni_devices.num_units', 'uni_devices.services', 'uni_devices.cost', 'uni_devices.state', 'uni_devices.price'
                                , 'uni_devices.description', 'uni_devices.AdditionalInfo', 'uni_devices.manufacturer', 'uni_devices.ManufactureYear'
                                , 'uni_devices.ManufactureCountry', 'uni_devices.ManufactureWebsite', 'uni_devices.entry_date')
                            ->where('uni_labs.uni_id', $stables['user']->uni_id)
                            ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                            ->get();
                        $dev=$central_dev->concat($dev);


                    }
                    elseif (($start_date != null or $end_date != null) and $selectedID != null)
                        // number of devices in a faculty in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->where('labs.uni_id', $stables['user']->uni_id)
                            ->where('labs.fac_id', $selectedID)
                            ->select(DB::raw("'fac' as FacultyName"), 'labs.name as Lab Name', 'devices.name', 'devices.model', 'devices.num_units'
                                , 'devices.services', 'devices.cost', 'devices.state', 'devices.price', 'devices.description', 'devices.AdditionalInfo'
                                , 'devices.manufacturer', 'devices.ManufactureYear', 'devices.ManufactureCountry', 'devices.ManufactureWebsite', 'devices.entry_date')
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->get();
//                    dd($dev);

                        $selected_name = fac_uni::where('uni_id', $stables['user']->uni_id)->where('fac_id', $selectedID);
                        foreach ($dev as $device) $device->FacultyName = $selected_name->pluck('name')->first();
                    }
                    elseif (($start_date == null or $end_date == null) and $selectedID == null){
                        $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->join('fac_uni', 'labs.fac_id', '=', 'fac_uni.fac_id')
                            ->where('labs.uni_id', $stables['user']->uni_id)
                            ->select('fac_uni.name as FacultyName', 'labs.name as Lab Name', 'devices.name', 'devices.model', 'devices.num_units'
                                , 'devices.services', 'devices.cost', 'devices.state', 'devices.price', 'devices.description', 'devices.AdditionalInfo', 'devices.manufacturer'
                                , 'devices.ManufactureYear', 'devices.ManufactureCountry', 'devices.ManufactureWebsite', 'devices.entry_date')
                            ->distinct()->get();
//
                        $central_dev = UniDevices::join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                            ->select(DB::raw("'Central Lab' as FacultyName"), 'uni_labs.name as Lab Name', 'uni_devices.name'
                                , 'uni_devices.model', 'uni_devices.num_units', 'uni_devices.services', 'uni_devices.cost', 'uni_devices.state', 'uni_devices.price'
                                , 'uni_devices.description', 'uni_devices.AdditionalInfo', 'uni_devices.manufacturer', 'uni_devices.ManufactureYear'
                                , 'uni_devices.ManufactureCountry', 'uni_devices.ManufactureWebsite', 'uni_devices.entry_date')
                            ->where('uni_labs.uni_id', $stables['user']->uni_id)
                            ->get();
                        $dev=$central_dev->concat($dev);
                    }
                }
                $dev->transform(function($item,$key) {
                    return $item->only($this->request->columns);
                });
                return collect($dev);
            }
            elseif ($stables['user']->hasRole('faculty')) {
                $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;

                $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->where('labs.uni_id', $stables['user']->uni_id)
                    ->where('labs.fac_id', $stables['user']->fac_id)
                    ->select('labs.name as Lab Name','devices.name','devices.model','devices.num_units','devices.services','devices.cost'
                        ,'devices.state','devices.price','devices.description','devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear'
                        ,'devices.ManufactureCountry','devices.ManufactureWebsite','devices.entry_date')
                    ->whereBetween('devices.entry_date', [$start_date, $end_date])
                    ->get();
//                $dev = DB::table('devices')
//                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
//                    ->select('labs.name as Lab name','devices.name','devices.model','devices.num_units','devices.services','devices.cost'
//                        ,'devices.state','devices.description','devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear','devices.ManufactureCountry'
//                        ,'devices.ManufactureWebsite','devices.entry_date')
//                    ->where('labs.uni_id', $stables['user']->uni_id)
//                    ->where('labs.fac_id', $stables['user']->fac_id)
//                    ->whereBetween('devices.entry_date', [$start_date, $end_date])
//                    ->get();

//                $dev->toArray();
//                dd($dev,$this->request->columns);
                $dev->transform(function($item,$key) {
                    return $item->only($this->request->columns);
                });
                return collect($dev);
            }
            elseif ($stables['user']->hasRole('department')) {
                $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;

                $dev = devices::join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.name as Lab Name','devices.name','devices.model','devices.num_units','devices.services','devices.cost','devices.state'
                        ,'devices.price','devices.description','devices.AdditionalInfo','devices.manufacturer','devices.ManufactureYear'
                        ,'devices.ManufactureCountry','devices.ManufactureWebsite','devices.entry_date')
                    ->where('labs.uni_id', $stables['user']->uni_id)
                    ->where('labs.fac_id', $stables['user']->fac_id)
                    ->where('labs.dept_id',$stables['user']->dept_id)
                    ->whereBetween('devices.entry_date', [$start_date, $end_date])
                    ->get();
                $dev->transform(function($item,$key) {
                    return $item->only($this->request->columns);
                });
                return collect($dev);
            }
        }
        else {
            if ($stables['user']->hasRole('admin')) {
                $selectedtype = $this->request->selectOption;
                $selected_uni = $this->request->uni_selected;
                $selected_fac = $this->request->fac_selected;
                $count = $this->request->count;
                if ($selectedtype) {
                    $university_names = $stables['universities']->where('type', $selectedtype)->pluck('name', 'id');
                } else {
                    $university_names = $stables['universities']->pluck('name', 'id');
                }
                $x = array_values($university_names->toArray());
                $university_types = $stables['universities']->pluck('type', 'id');
                $uni_types = array_values($university_types->toArray());
                if ($count == 'units'){
                    if (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac == null)
                        //DONE  //devices in a university at all time
                    {
                        $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                        $x = array_values($faculty_names->toArray());
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->select('labs.fac_id', DB::raw('sum(devices.num_units) as total'))
//                            ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
                            ->where('labs.uni_id', $selected_uni)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })
                            ->groupBy('labs.fac_id')
                            ->get()->pluck('total', 'fac_id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select(DB::raw('sum(uni_devices.num_units) as total'))
//                            ->select(DB::raw('count(uni_devices.id) as total'))
                            ->where('uni_labs.uni_id',$selected_uni)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()->pluck('total');

                        $dev_fac = array();
                        foreach ($faculty_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        array_push($dev_fac,$central_dev[0]);
                        array_push($x,'Central Labs');
                        $y = array_values($dev_fac);
                    }
                    elseif (($start_date == null and $end_date == null) and $selected_uni == null and $selectedtype != null)
                        // DONE // devices in all university at all time
                    {
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id', 'universitys.type', DB::raw('sum(devices.num_units) as total'))
//                            ->select('universitys.id', 'universitys.type', DB::raw('count(devices.id) as total'))
                            ->groupBy('universitys.id', 'universitys.type')
                            ->where('universitys.type', $selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()
                            ->pluck('total', 'id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys','uni_labs.uni_id','=','universitys.id')
                        ->select('universitys.id','universitys.type',DB::raw('sum(uni_devices.num_units) as total'))
//                            ->select('universitys.id','universitys.type',DB::raw('count(uni_devices.id) as total'))
                            ->groupBy('universitys.id','universitys.type')
                            ->where('universitys.type',$selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()
                            ->pluck('total','id');

                        $dev_uni = array();
                        foreach ($university_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_uni[$id] = (int)$dev[$id];
                            } else {
                                $dev_uni[$id] = 0;
                            }
                        }
                        foreach ($central_dev as $id=>$count){$dev_uni[$id]+=$count;}
                        $y = array_values($dev_uni);
                    }
                    elseif (($start_date != null or $end_date != null) and $selected_uni == null)
                        // DONE // devices in all university in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = DB::table('devices')
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id', 'universitys.type', DB::raw('sum(devices.num_units) as total'))
//                            ->select('universitys.id', 'universitys.type', DB::raw('count(devices.id) as total'))
                            ->groupBy('universitys.id', 'universitys.type')
                            ->where('universitys.type', $selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()
                            ->pluck('total', 'id');
                        $central_dev = DB::table('uni_devices')
                            ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys','uni_labs.uni_id','=','universitys.id')
                        ->select('universitys.id','universitys.type',DB::raw('sum(uni_devices.num_units) as total'))
//                            ->select('universitys.id','universitys.type',DB::raw('count(uni_devices.id) as total'))
                            ->groupBy('universitys.id','universitys.type')
                            ->where('universitys.type',$selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()
                            ->pluck('total','id');

                        $dev_uni = array();
                        foreach ($university_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_uni[$id] = (int)$dev[$id];
                            } else {
                                $dev_uni[$id] = 0;
                            }
                        }
                        foreach ($central_dev as $id=>$count){$dev_uni[$id]+=$count;}
                        $y = array_values($dev_uni);
                    }
                    elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                        // DONE // devices in a university in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                        $x = array_values($faculty_names->toArray());
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->select('labs.fac_id', DB::raw('sum(devices.num_units) as total'))
//                            ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
                            ->where('labs.uni_id', $selected_uni)
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->groupBy('labs.fac_id')
                            ->get()->pluck('total', 'fac_id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select(DB::raw('sum(uni_devices.num_units) as total'))
//                            ->select(DB::raw('count(uni_devices.id) as total'))
                            ->where('uni_labs.uni_id',$selected_uni)
                            ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()->pluck('total');
                        $dev_fac = array();
                        foreach ($faculty_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        array_push($dev_fac,$central_dev[0]);
                        array_push($x,'Central Labs');
                        $y = array_values($dev_fac);
//                    dd($dev_fac);
                    }
                    elseif (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac != null )
                        // Devices in a faculty at all time
                    {
                        $selected = fac_uni::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name')[0];
                        $title = 'Devices in '.(string)$selected;

                        $devv = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.id')
                            ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                            ->where('uni_id',$selected_uni)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()->groupBy('fac_id');
                        if (!isset($devv[$selected_fac])) {
                            $x = null;
                            $y = '';
                            return view('loggedTemp/export', compact('stables', 'selected', 'price','count','selectedtype','selected_uni','selected_fac', 'x', 'y', 'title', 'start_date', 'end_date'));
                        }

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
                    }
                    elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                        // number of devices in a faculty in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $selected = fac_uni::where('uni_id', $selected_uni)->where('fac_id', $selected_fac)->pluck('name')[0];
                        $devv = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.name', DB::raw('sum(devices.num_units) as total'), 'labs.uni_id', 'labs.fac_id', 'labs.id')
                            ->groupBy('labs.uni_id', 'labs.fac_id', 'labs.name', 'labs.id')
                            ->where('uni_id', $selected_uni)
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                            })->get()->groupBy('fac_id');
                        $error = 'No Devices Found';
                        $x = 0;
                        $y = 0;
                        if (!isset($devv[$selected_fac])) return view('loggedTemp/export', compact('stables','selectedtype','selected_uni', 'price', 'count','selected_fac', 'start_date', 'end_date', 'selected',  'error'))->withErrors(["error" => "No Devices found!"]);
                        else {
                            $dev_labsInFaculty = $devv[$selected_fac]->pluck('total', 'id');  // Total devices in Labs in Selected faculty
                            $labsInFaculty = labs::where('uni_id', $selected_uni)->where('fac_id', $selected_fac)->pluck('name', 'id');

                            $dev_fac = array();
                            foreach ($labsInFaculty as $id => $name) {
                                if (isset($dev_labsInFaculty[$id])) {
                                    $dev_fac[$id] = (int)$dev_labsInFaculty[$id];
                                } else {
                                    $dev_fac[$id] = 0;
                                }
                            }
                            $y = array_values($dev_fac);
                            $x = array_values($labsInFaculty->toArray());
                            $error = '';
                        }
                    }
                }
                else{
                    if (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac == null)
                        //DONE  //devices in a university at all time
                    {
                        $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                        $x = array_values($faculty_names->toArray());
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
//                        ->select('labs.fac_id', DB::raw('sum(devices.num_units) as total'))
                            ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
                            ->where('labs.uni_id', $selected_uni)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })
                            ->groupBy('labs.fac_id')
                            ->get()->pluck('total', 'fac_id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
//                        ->select(DB::raw('sum(uni_devices.num_units) as total'))
                            ->select(DB::raw('count(uni_devices.id) as total'))
                            ->where('uni_labs.uni_id',$selected_uni)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()->pluck('total');

                        $dev_fac = array();
                        foreach ($faculty_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        array_push($dev_fac,$central_dev[0]);
                        array_push($x,'Central Labs');
                        $y = array_values($dev_fac);
                    }
                    elseif (($start_date == null and $end_date == null) and $selected_uni == null and $selectedtype != null)
                        // DONE // devices in all university at all time
                    {
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
//                        ->select('universitys.id', 'universitys.type', DB::raw('sum(devices.num_units) as total'))
                            ->select('universitys.id', 'universitys.type', DB::raw('count(devices.id) as total'))
                            ->groupBy('universitys.id', 'universitys.type')
                            ->where('universitys.type', $selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()
                            ->pluck('total', 'id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys','uni_labs.uni_id','=','universitys.id')
//                        ->select('universitys.id','universitys.type',DB::raw('sum(uni_devices.num_units) as total'))
                            ->select('universitys.id','universitys.type',DB::raw('count(uni_devices.id) as total'))
                            ->groupBy('universitys.id','universitys.type')
                            ->where('universitys.type',$selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()
                            ->pluck('total','id');

                        $dev_uni = array();
                        foreach ($university_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_uni[$id] = (int)$dev[$id];
                            } else {
                                $dev_uni[$id] = 0;
                            }
                        }
                        foreach ($central_dev as $id=>$count){$dev_uni[$id]+=$count;}
                        $y = array_values($dev_uni);
                    }
                    elseif (($start_date != null or $end_date != null) and $selected_uni == null)
                        // DONE // devices in all university in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = DB::table('devices')
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
//                        ->select('universitys.id', 'universitys.type', DB::raw('sum(devices.num_units) as total'))
                            ->select('universitys.id', 'universitys.type', DB::raw('count(devices.id) as total'))
                            ->groupBy('universitys.id', 'universitys.type')
                            ->where('universitys.type', $selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()
                            ->pluck('total', 'id');
                        $central_dev = DB::table('uni_devices')
                            ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->join('universitys','uni_labs.uni_id','=','universitys.id')
//                        ->select('universitys.id','universitys.type',DB::raw('sum(uni_devices.num_units) as total'))
                            ->select('universitys.id','universitys.type',DB::raw('count(uni_devices.id) as total'))
                            ->groupBy('universitys.id','universitys.type')
                            ->where('universitys.type',$selectedtype)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()
                            ->pluck('total','id');

                        $dev_uni = array();
                        foreach ($university_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_uni[$id] = (int)$dev[$id];
                            } else {
                                $dev_uni[$id] = 0;
                            }
                        }
                        foreach ($central_dev as $id=>$count){$dev_uni[$id]+=$count;}
                        $y = array_values($dev_uni);
                    }
                    elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                        // DONE // devices in a university in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                        $x = array_values($faculty_names->toArray());
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
//                        ->select('labs.fac_id', DB::raw('sum(devices.num_units) as total'))
                            ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
                            ->where('labs.uni_id', $selected_uni)
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->groupBy('labs.fac_id')
                            ->get()->pluck('total', 'fac_id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
//                        ->select(DB::raw('sum(uni_devices.num_units) as total'))
                            ->select(DB::raw('count(uni_devices.id) as total'))
                            ->where('uni_labs.uni_id',$selected_uni)
                            ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()->pluck('total');
                        $dev_fac = array();
                        foreach ($faculty_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        array_push($dev_fac,$central_dev[0]);
                        array_push($x,'Central Labs');
                        $y = array_values($dev_fac);
//                    dd($dev_fac);
                    }
                    elseif (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac != null )
                        // Devices in a faculty at all time
                    {
                        $selected = fac_uni::where('uni_id',$selected_uni)->where('fac_id',$selected_fac)->pluck('name')[0];
                        $title = 'Devices in '.(string)$selected;

                        $devv = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.id')
                            ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                            ->where('uni_id',$selected_uni)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()->groupBy('fac_id');
                        if (!isset($devv[$selected_fac])) {
                            $x = null;
                            $y = '';
                            return view('loggedTemp/export', compact('stables','selectedtype','selected_uni', 'selected', 'price','count','selected_fac', 'x', 'y', 'title', 'start_date', 'end_date'));
                        }

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
                    }
                    elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                        // number of devices in a faculty in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $selected = fac_uni::where('uni_id', $selected_uni)->where('fac_id', $selected_fac)->pluck('name')[0];
                        $title = 'Number of Devices in ' . (string)$selected . ' from ' . $start_date . ' to ' . $end_date;
                        $devv = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.id')
                            ->groupBy('labs.uni_id', 'labs.fac_id', 'labs.name', 'labs.id')
                            ->where('uni_id', $selected_uni)
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                            })->get()->groupBy('fac_id');
                        $error = 'No Devices Found';
                        $x = 0;
                        $y = 0;
                        if (!isset($devv[$selected_fac])) return view('loggedTemp/export', compact('stables','selectedtype','selected_uni', 'price', 'count','selected_fac', 'start_date', 'end_date', 'selected', 'title', 'error'))->withErrors(["error" => "No Devices found!"]);
                        else {
                            $dev_labsInFaculty = $devv[$selected_fac]->pluck('total', 'id');  // Total devices in Labs in Selected faculty
                            $labsInFaculty = labs::where('uni_id', $selected_uni)->where('fac_id', $selected_fac)->pluck('name', 'id');

                            $dev_fac = array();
                            foreach ($labsInFaculty as $id => $name) {
                                if (isset($dev_labsInFaculty[$id])) {
                                    $dev_fac[$id] = (int)$dev_labsInFaculty[$id];
                                } else {
                                    $dev_fac[$id] = 0;
                                }
                            }
                            $y = array_values($dev_fac);
                            $x = array_values($labsInFaculty->toArray());
                            $error = '';
                        }
                    }
                }
            }
            elseif ($stables['user']->hasRole('university')) {
                if ($selectedID == 'Central Labs'){
                    if ($start_date == null and $end_date == null){
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->select('uni_labs.id', DB::raw('count(uni_devices.id) as total'))
//                            ->select('uni_labs.id', DB::raw('sum(uni_devices.num_units) as total'))
                            ->where('uni_labs.uni_id', $stables['user']->uni_id)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->groupBy('uni_labs.id')
                            ->get()->pluck('total', 'id');
                        $dev_fac = array();
                        $lab_names = UniLabs::where('uni_id', $stables['user']->uni_id)->get()->pluck('name', 'id');
                        foreach ($lab_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        $y = array_values($dev_fac);
                        $x = array_values($lab_names->toArray());
                    }
                    else{
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->select('uni_labs.id', DB::raw('count(uni_devices.id) as total'))
//                            ->select('uni_labs.id', DB::raw('sum(uni_devices.num_units) as total'))
                            ->where('uni_labs.uni_id', $stables['user']->uni_id)
                            ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->groupBy('uni_labs.id')
                            ->get()->pluck('total', 'id');
                        $dev_fac = array();
                        $lab_names = UniLabs::where('uni_id', $stables['user']->uni_id)->get()->pluck('name', 'id');
                        foreach ($lab_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        $y = array_values($dev_fac);
                        $x = array_values($lab_names->toArray());
                    }
                }
                else {
                    if ($selectedID != null and ($start_date == null and $end_date == null))
                        // Devices in a faculty at all time
                    {
                        $devv = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.name', DB::raw('count(devices.id) as total'), 'labs.uni_id', 'labs.fac_id', 'labs.id')
//                            ->select('labs.name', DB::raw('sum(devices.num_units) as total'), 'labs.uni_id', 'labs.fac_id', 'labs.id')
                            ->groupBy('labs.uni_id', 'labs.fac_id', 'labs.name', 'labs.id')
                            ->where('uni_id', $stables['user']->uni_id)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()->groupBy('fac_id');

                        $dev_labsInFaculty = $devv[$selectedID]->pluck('total', 'id');  // Total devices in Labs in Selected faculty
                        $labsInFaculty = labs::where('uni_id', $stables['user']->uni_id)->where('fac_id', $selectedID)->pluck('name', 'id');
                        $dev_fac = array();
                        foreach ($labsInFaculty as $id => $name) {
                            if (isset($dev_labsInFaculty[$id])) {
                                $dev_fac[$id] = (int)$dev_labsInFaculty[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        $y = array_values($dev_fac);
                        $x = array_values($labsInFaculty->toArray());
                        $selected = fac_uni::where('uni_id', $stables['user']->uni_id)->where('fac_id', $selectedID)->pluck('name')[0];
                        $title = 'Devices in ' . (string)$selected;
                        $down = collect(array_map(function ($x, $y) {
                            return [
                                'Faculty' => $x,
                                'Num of devices' => $y,
                            ];
                        }, $x, $y));
                    }
                    elseif (($start_date != null or $end_date != null) and $selectedID == null)
                        // number of devices in all faculties in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $faculty_names = $stables['faculties']->pluck('name', 'fac_id');
                        $x = array_values($faculty_names->toArray());
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
//                            ->select('labs.fac_id', DB::raw('sum(devices.num_units) as total'))
                            ->where('labs.uni_id', $stables['user']->uni_id)
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->groupBy('labs.fac_id')
                            ->get()->pluck('total', 'fac_id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->select(DB::raw('count(uni_devices.id) as total'))
//                            ->select(DB::raw('sum(uni_devices.num_units) as total'))
                            ->where('uni_labs.uni_id',$stables['user']->uni_id)
                            ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()->pluck('total');

                        $dev_fac = array();
                        foreach ($faculty_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        array_push($dev_fac,$central_dev[0]);
                        array_push($x,'Central Labs');
                        $y = array_values($dev_fac);
                    }
                    elseif (($start_date != null or $end_date != null) and $selectedID != null)
                        // number of devices in a faculty in certain time
                    {
                        $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                        $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                        $selected = fac_uni::where('uni_id', $stables['user']->uni_id)->where('fac_id', $selectedID)->pluck('name')[0];
                        $devv = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.name', DB::raw('count(devices.id) as total'), 'labs.uni_id', 'labs.fac_id', 'labs.id')
//                            ->select('labs.name', DB::raw('sum(devices.num_units) as total'), 'labs.uni_id', 'labs.fac_id', 'labs.id')
                            ->groupBy('labs.uni_id', 'labs.fac_id', 'labs.name', 'labs.id')
                            ->where('uni_id', $stables['user']->uni_id)
                            ->whereBetween('devices.entry_date', [$start_date, $end_date])
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->get()->groupBy('fac_id');
                        $x = 0;
                        $y = 0;
                        if (!isset($devv[$selectedID])) {
                            $x = [];
                            $y = [];
                        } else {
                            $dev_labsInFaculty = $devv[$selectedID]->pluck('total', 'id');  // Total devices in Labs in Selected faculty
                            $labsInFaculty = labs::where('uni_id', $stables['user']->uni_id)->where('fac_id', $selectedID)->pluck('name', 'id');

                            $dev_fac = array();
                            foreach ($labsInFaculty as $id => $name) {
                                if (isset($dev_labsInFaculty[$id])) {
                                    $dev_fac[$id] = (int)$dev_labsInFaculty[$id];
                                } else {
                                    $dev_fac[$id] = 0;
                                }
                            }
                            $y = array_values($dev_fac);
                            $x = array_values($labsInFaculty->toArray());
                            $error = '';
                        }
                    }
                    elseif (($start_date == null or $end_date == null) and $selectedID == null){
                        $faculty_names = $stables['faculties']->pluck('name', 'fac_id');
                        $x = array_values($faculty_names->toArray());
                        $dev = DB::table('devices')
                            ->join('labs', 'devices.lab_id', '=', 'labs.id')
                            ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
//                            ->select('labs.fac_id', DB::raw('sum(devices.num_units) as total'))
                            ->where('labs.uni_id', $stables['user']->uni_id)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                            })->groupBy('labs.fac_id')
                            ->get()->pluck('total', 'fac_id');
                        $central_dev = DB::table('uni_devices')
                            ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                            ->select(DB::raw('count(uni_devices.id) as total'))
//                            ->select(DB::raw('sum(uni_devices.num_units) as total'))
                            ->where('uni_labs.uni_id',$stables['user']->uni_id)
                            ->when($price, function ($query, $price) {
                                if ($price == 'less100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                                elseif ($price == 'more100k')
                                    return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                            })->get()->pluck('total');

                        $dev_fac = array();
                        foreach ($faculty_names as $id => $name) {
                            if (isset($dev[$id])) {
                                $dev_fac[$id] = (int)$dev[$id];
                            } else {
                                $dev_fac[$id] = 0;
                            }
                        }
                        array_push($dev_fac,$central_dev[0]);
                        array_push($x,'Central Labs');
                        $y = array_values($dev_fac);
                    }
                }
            }
            elseif ($stables['user']->hasRole('faculty')) {
                $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                $lab_names = $stables['labs']->get()->pluck('name', 'id');
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.id', DB::raw('count(devices.id) as total'))
//                    ->select('labs.id', DB::raw('sum(devices.num_units) as total'))
                    ->where('labs.uni_id', $stables['user']->uni_id)
                    ->where('labs.fac_id', $stables['user']->fac_id)
                    ->whereBetween('devices.entry_date', [$start_date, $end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->groupBy('labs.id')
                    ->get()->pluck('total', 'id');
//            dd($start_date,$end_date);
                $dev_fac = array();
                foreach ($lab_names as $id => $name) {
                    if (isset($dev[$id])) {
                        $dev_fac[$id] = (int)$dev[$id];
                    } else {
                        $dev_fac[$id] = 0;
                    }
                }
                $y = array_values($dev_fac);
                $x = array_values($lab_names->toArray());
            }
            elseif ($stables['user']->hasRole('department')) {
                $start_date = ($this->request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $this->request->start_date;
                $end_date = ($this->request->end_date == null) ? date('Y-m-d') : $this->request->end_date;
                $lab_names = $stables['labs']->get()->pluck('name', 'id');
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.id', DB::raw('count(devices.id) as total'))
//                    ->select('labs.id', DB::raw('sum(devices.num_units) as total'))
                    ->where('labs.uni_id', $stables['user']->uni_id)
                    ->where('labs.fac_id', $stables['user']->fac_id)
                    ->where('labs.dept_id', $stables['user']->dept_id)
                    ->whereBetween('devices.entry_date', [$start_date, $end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->groupBy('labs.id')
                    ->get()->pluck('total', 'id');
//            dd($start_date,$end_date);
                $dev_fac = array();
                foreach ($lab_names as $id => $name) {
                    if (isset($dev[$id])) {
                        $dev_fac[$id] = (int)$dev[$id];
                    } else {
                        $dev_fac[$id] = 0;
                    }
                }
                $y = array_values($dev_fac);
                $x = array_values($lab_names->toArray());
            }
            $data = array_map(function ($x, $y) {
                return ['name' => $x, 'count' => $y,];
            }, $x, $y);
            return collect($data);
        }
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        if ($this->exportwhat=='everything'){
            return $this->request->columns;
        }
        else {
            if (Auth()->user()->hasRole('admin')) {
                return ['', 'Number of devices'];
            } elseif (Auth()->user()->hasRole('university')) {
                return ['Lab', 'Number of devices'];
            } else {
                return ['Department', 'Number of devices'];
            }
        }
    }
}
