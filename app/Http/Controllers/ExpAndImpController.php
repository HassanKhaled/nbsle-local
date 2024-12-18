<?php


namespace App\Http\Controllers;
use App\Exports\DevicesExport;
use App\Exports\templates;
use App\Imports\DevicesImport;
use App\Imports\LabsImport;
use App\Models\devices;
use App\Models\fac_uni;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Collection;
use Maatwebsite\Excel\Excel as ExcelType;
use Symfony\Component\Console\Input\Input;
use ZipStream\File;


class ExpAndImpController extends Controller
{
    public function viewExport(){
        if (auth()->user()==null or auth()->user()->hasRole('visitor')){  return redirect()->route('login');}
        $stables = $this->stables();
        $x=null;
        $selected_uni  = null;
        $selected_fac = null;
        $start_date  = null;
        $end_date = null;
        $selectedtype = 'public';
        $price='all';
        $count='devices';
        $selected ='';
        return view('loggedTemp/export',compact('stables','x','selected_uni','selected_fac','selectedtype','selected','start_date','end_date','price','count'));
    }
    public function generateSheet(Request $request){
        if (auth()->user()==null or auth()->user()->hasRole('visitor')){  return redirect()->route('login');}
        $stables = $this->stables();

        $selectedID = $request->selectOption;
        $start_date = $request->start_date;
        $end_date = $request->end_date ;
        $price = $request->price;
        $count = $request->count;
        if ($stables['user']->hasRole('admin'))
        {
            $selectedtype = $request->selectOption;
            $selected_uni = $request->uni_selected;
            $selected_fac = $request->fac_selected;
            if ($selectedtype) {$university_names = $stables['universities']->where('type',$selectedtype)->pluck('name','id');}
            else { $university_names = $stables['universities']->pluck('name','id');}
            $x = array_values($university_names->toArray());
            $university_types = $stables['universities']->pluck('type','id');
            $uni_types = array_values($university_types->toArray());
            if ($count=='units'){
                if (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac == null)
                    //DONE  //devices in a university at all time
                {
                    $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                    $x = array_values($faculty_names->toArray());
                    $dev = DB::table('devices')
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
                        ->where('labs.uni_id', $selected_uni)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })
                        ->groupBy('labs.fac_id')
                        ->get()->pluck('total', 'fac_id');

                    $central_dev = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select(DB::raw('sum(uni_devices.num_units) as total'))
                        ->where('uni_labs.uni_id', $selected_uni)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()->pluck('total');
                    $dev_fac = array();
                    foreach ($faculty_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_fac[$id] = (int)$dev[$id];
                        } else {
                            $dev_fac[$id] = 0;
                        }
                    }
                    array_push($dev_fac, $central_dev[0]);
                    array_push($x, 'Central Labs');
                    $y = array_values($dev_fac);
                    $selected_name = $university_names[$selected_uni];
                    $title = 'Devices in ' . $selected_name;
                    return view('loggedTemp/export', compact('stables','selected_fac', 'x', 'y', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
                }
                elseif (($start_date == null and $end_date == null) and $selected_uni == null and $selectedtype != null)
                    // DONE // devices in all universities in a type at all time
                {
                    $dev = DB::table('devices')
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id','universitys.type',DB::raw('sum(devices.num_units) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })->get()
                        ->pluck('total', 'id');

                    $central_dev = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id','universitys.type',DB::raw('sum(uni_devices.num_units) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()
                        ->pluck('total', 'id');

                    $dev_uni = array();
                    foreach ($university_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_uni[$id] = (int)$dev[$id];
                        } else {
                            $dev_uni[$id] = 0;
                        }
                    }
                    foreach ($central_dev as $id => $count) {
                        $dev_uni[$id] += $count;
                    }
                    $y = array_values($dev_uni);
                    $title = $selectedtype == 'Institution' ? 'Devices in institutions' : 'Devices in ' . $selectedtype . ' universities';
                    return view('loggedTemp/export', compact('stables', 'x', 'y','selected_fac', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni == null)
                    // DONE // devices in all universities in a type in certain time
                {
                    $start_date = ($request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $request->start_date;
                    $end_date = ($request->end_date == null) ? date('Y-m-d') : $request->end_date;
                    $dev = DB::table('devices')
                        ->whereBetween('devices.entry_date', [$start_date, $end_date])
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id','universitys.type',DB::raw('sum(devices.num_units) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })->get()->pluck('total', 'id');

                    $central_dev = DB::table('uni_devices')
                        ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id','universitys.type',DB::raw('sum(uni_devices.num_units) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()->pluck('total', 'id');
                    $dev_uni = array();
                    foreach ($university_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_uni[$id] = (int)$dev[$id];
                        } else {
                            $dev_uni[$id] = 0;
                        }
                    }
                    foreach ($central_dev as $id => $count) {
                        $dev_uni[$id] += $count;
                    }
                    $y = array_values($dev_uni);
                    $title = 'Devices in ' . $selectedtype . ' universities from ' . $start_date . ' to ' . $end_date;
                    return view('loggedTemp/export', compact('stables', 'x', 'y', 'selected_fac','title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                    // DONE // devices in a university in certain time
                {
                    $start_date = ($request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $request->start_date;
                    $end_date = ($request->end_date == null) ? date('Y-m-d') : $request->end_date;
                    $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                    $x = array_values($faculty_names->toArray());
                    $dev = DB::table('devices')
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
                        ->where('labs.uni_id', $selected_uni)
                        ->whereBetween('devices.entry_date', [$start_date, $end_date])
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })->groupBy('labs.fac_id')
                        ->get()->pluck('total', 'fac_id');

                    $central_dev = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select(DB::raw('sum(uni_devices.num_units) as total'))
                        ->where('uni_labs.uni_id', $selected_uni)
                        ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()->pluck('total');
                    $dev_fac = array();
                    foreach ($faculty_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_fac[$id] = (int)$dev[$id];
                        } else {
                            $dev_fac[$id] = 0;
                        }
                    }
                    array_push($dev_fac, $central_dev[0]);
                    array_push($x, 'Central Labs');
                    $y = array_values($dev_fac);
                    $selected_name = $university_names[$selected_uni];
                    $title = 'Device in ' . $selected_name . ' from ' . $start_date . ' to ' . $end_date;
                    return view('loggedTemp/export', compact('stables', 'x', 'y','selected_fac', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
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
                        return view('loggedTemp/export', compact('stables', 'selected','selected_fac', 'price','count','selectedtype','selected_uni','selected_fac', 'x', 'y', 'title', 'start_date', 'end_date'));
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
                    $down =collect(array_map(function ($x, $y) {
                        return [
                            'Faculty' => $x,
                            'Num of devices' => $y,
                        ];
                    }, $x, $y));
                    return view('loggedTemp/export', compact('stables','price','selected_fac','count','selectedtype','selected_uni','selected','selected_fac','x','y','title','start_date','end_date'));
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                    // number of devices in a faculty in certain time
                {
                    $start_date = ($request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $request->start_date;
                    $end_date = ($request->end_date == null) ? date('Y-m-d') : $request->end_date;
                    $selected = fac_uni::where('uni_id', $selected_uni)->where('fac_id', $selected_fac)->pluck('name')[0];
                    $title = 'Number of Devices in ' . (string)$selected . ' from ' . $start_date . ' to ' . $end_date;
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
                    if (!isset($devv[$selected_fac])) return view('loggedTemp/export', compact('stables','selected_fac','selectedtype','selected_uni', 'price', 'count','selected_fac', 'start_date', 'end_date', 'selected', 'title', 'error'))->withErrors(["error" => "No Devices found!"]);
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
                        return view('loggedTemp/export', compact('stables', 'selected_fac','price', 'count','selectedtype','selected_uni', 'selected_fac', 'start_date', 'end_date', 'selected', 'x', 'y', 'title', 'error'));
                    }
                }
            }
            else {
                if (($start_date == null and $end_date == null) and $selected_uni != null and $selected_fac == null)
                    //DONE  //devices in a university at all time
                {
                    $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                    $x = array_values($faculty_names->toArray());
                    $dev = DB::table('devices')
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
                        ->where('labs.uni_id', $selected_uni)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })
                        ->groupBy('labs.fac_id')
                        ->get()->pluck('total', 'fac_id');

                    $central_dev = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select(DB::raw('count(uni_devices.id) as total'))
                        ->where('uni_labs.uni_id', $selected_uni)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()->pluck('total');
                    $dev_fac = array();
                    foreach ($faculty_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_fac[$id] = (int)$dev[$id];
                        } else {
                            $dev_fac[$id] = 0;
                        }
                    }
                    array_push($dev_fac, $central_dev[0]);
                    array_push($x, 'Central Labs');
                    $y = array_values($dev_fac);
                    $selected_name = $university_names[$selected_uni];
                    $title = 'Devices in ' . $selected_name;
                    return view('loggedTemp/export', compact('stables', 'selected_fac','x', 'y', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
                }
                elseif (($start_date == null and $end_date == null) and $selected_uni == null and $selectedtype != null)
                    // DONE // devices in all university at all time
                {
                    $dev = DB::table('devices')
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id', 'universitys.type', DB::raw('count(devices.id) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })->get()
                        ->pluck('total', 'id');

                    $central_dev = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id', 'universitys.type', DB::raw('count(uni_devices.id) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()
                        ->pluck('total', 'id');

                    $dev_uni = array();
                    foreach ($university_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_uni[$id] = (int)$dev[$id];
                        } else {
                            $dev_uni[$id] = 0;
                        }
                    }
                    foreach ($central_dev as $id => $count) {
                        $dev_uni[$id] += $count;
                    }
                    $y = array_values($dev_uni);
                    $title = $selectedtype == 'Institution' ? 'Devices in institutions' : 'Devices in ' . $selectedtype . ' universities';
                    return view('loggedTemp/export', compact('stables', 'selected_fac','x', 'y', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni == null)
                    // DONE // devices in all universities in a type in certain time
                {
                    $start_date = ($request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $request->start_date;
                    $end_date = ($request->end_date == null) ? date('Y-m-d') : $request->end_date;
                    $dev = DB::table('devices')
                        ->whereBetween('devices.entry_date', [$start_date, $end_date])
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->join('universitys', 'labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id', 'universitys.type', DB::raw('count(devices.id) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })->get()->pluck('total', 'id');

                    $central_dev = DB::table('uni_devices')
                        ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->join('universitys', 'uni_labs.uni_id', '=', 'universitys.id')
                        ->select('universitys.id', 'universitys.type', DB::raw('count(uni_devices.id) as total'))
                        ->groupBy('universitys.id', 'universitys.type')
                        ->where('universitys.type', $selectedtype)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()->pluck('total', 'id');
                    $dev_uni = array();
                    foreach ($university_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_uni[$id] = (int)$dev[$id];
                        } else {
                            $dev_uni[$id] = 0;
                        }
                    }
                    foreach ($central_dev as $id => $count) {
                        $dev_uni[$id] += $count;
                    }
                    $y = array_values($dev_uni);
                    $title = 'Devices in ' . $selectedtype . ' universities from ' . $start_date . ' to ' . $end_date;
                    return view('loggedTemp/export', compact('stables', 'selected_fac','x', 'y', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac == null)
                    // DONE // devices in a university in certain time
                {
                    $start_date = ($request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $request->start_date;
                    $end_date = ($request->end_date == null) ? date('Y-m-d') : $request->end_date;
                    $faculty_names = $stables['all_faculties']->where('uni_id', $selected_uni)->pluck('name', 'fac_id');
                    $x = array_values($faculty_names->toArray());
                    $dev = DB::table('devices')
                        ->join('labs', 'devices.lab_id', '=', 'labs.id')
                        ->select('labs.fac_id', DB::raw('count(devices.id) as total'))
                        ->where('labs.uni_id', $selected_uni)
                        ->whereBetween('devices.entry_date', [$start_date, $end_date])
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('devices.price')->where('devices.price', '>', 100000);
                        })->groupBy('labs.fac_id')
                        ->get()->pluck('total', 'fac_id');

                    $central_dev = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select(DB::raw('count(uni_devices.id) as total'))
                        ->where('uni_labs.uni_id', $selected_uni)
                        ->whereBetween('uni_devices.entry_date', [$start_date, $end_date])
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price', '>', 100000);
                        })->get()->pluck('total');
                    $dev_fac = array();
                    foreach ($faculty_names as $id => $name) {
                        if (isset($dev[$id])) {
                            $dev_fac[$id] = (int)$dev[$id];
                        } else {
                            $dev_fac[$id] = 0;
                        }
                    }
                    array_push($dev_fac, $central_dev[0]);
                    array_push($x, 'Central Labs');
                    $y = array_values($dev_fac);
                    $selected_name = $university_names[$selected_uni];
                    $title = 'Device in ' . $selected_name . ' from ' . $start_date . ' to ' . $end_date;
                    return view('loggedTemp/export', compact('stables', 'selected_fac','x', 'y', 'title', 'price','count', 'uni_types', 'selectedtype', 'selected_uni', 'start_date', 'end_date'));
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
                    $down =collect(array_map(function ($x, $y) {
                        return [
                            'Faculty' => $x,
                            'Num of devices' => $y,
                        ];
                    }, $x, $y));
                    return view('loggedTemp/export', compact('stables','selected_fac','price','count','selectedtype','selected_uni','selected','selected_fac','x','y','title','start_date','end_date'));
                }
                elseif (($start_date != null or $end_date != null) and $selected_uni != null and $selected_fac != null)
                    // number of devices in a faculty in certain time
                {
                    $start_date = ($request->start_date == null) ? date('Y-m-d', mktime(0, 0, 0, 01, 01, 2016)) : $request->start_date;
                    $end_date = ($request->end_date == null) ? date('Y-m-d') : $request->end_date;
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
                    if (!isset($devv[$selected_fac])) return view('loggedTemp/export', compact('stables','request','selectedtype','selected_uni', 'price', 'count','selected_fac', 'start_date', 'end_date', 'selected', 'title', 'error'))->withErrors(["error" => "No Devices found!"]);
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
                        return view('loggedTemp/export', compact('stables','selected_fac','selectedtype','selected_uni', 'price', 'count', 'selected_fac', 'start_date', 'end_date', 'selected', 'x', 'y', 'title', 'error'));
                    }
                }
            }
        }
        elseif ($stables['user']->hasRole('university')){

            if ($selectedID == 'Central Labs'){
                if ($start_date == null and $end_date == null){
                    $selected = 'Central Labs';
                    $selected_fac = 'Central Labs';
                    $title='';
                    $devv = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select('uni_labs.name',DB::raw('count(uni_devices.id) as total'),'uni_labs.uni_id','uni_labs.id')
//                        ->select('uni_labs.name',DB::raw('sum(uni_devices.num_units) as total'),'uni_labs.uni_id','uni_labs.id')
                        ->groupBy('uni_labs.id')
                        ->where('uni_labs.uni_id',$stables['user']->uni_id)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                        })->get()->keyBy('id');
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
                    $down =collect(array_map(function ($x, $y) {
                        return [
                            'Faculty' => $x,
                            'Num of devices' => $y,
                        ];
                    }, $x, $y));
                    return view('loggedTemp/export', compact('stables','selected','selected_fac','x','y','title','start_date','end_date','price'));
                }
                else{
                    $selected = 'Central Labs';
                    $selected_fac = 'Central Labs';
                    $title='';
                    $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                    $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                    $devv = DB::table('uni_devices')
                        ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                        ->select('uni_labs.name',DB::raw('count(uni_devices.id) as total'),'uni_labs.uni_id','uni_labs.id')
//                        ->select('uni_labs.name',DB::raw('sum(uni_devices.num_units) as total'),'uni_labs.uni_id','uni_labs.id')
                        ->groupBy('uni_labs.id')
                        ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                        ->where('uni_labs.uni_id',$stables['user']->uni_id)
                        ->when($price, function ($query, $price) {
                            if ($price == 'less100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                            elseif ($price == 'more100k')
                                return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                        })->get()->keyBy('id');
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
                    $down =collect(array_map(function ($x, $y) {
                        return [
                            'Faculty' => $x,
                            'Num of devices' => $y,
                        ];
                    }, $x, $y));
                    return view('loggedTemp/export', compact('stables','selected','selected_fac','x','y','title','start_date','end_date','price'));

                }
            }
            if($selectedID != null and ($start_date==null and $end_date==null))
            // Devices in a faculty at all time
            {
                $selected = fac_uni::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name')[0];
                $title = 'Devices in '.(string)$selected;
                $selected_fac = $selectedID;

                $devv = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.name',DB::raw('count(devices.id) as total'),'labs.uni_id','labs.fac_id','labs.id')
//                    ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.id')
                    ->groupBy('labs.uni_id','labs.fac_id','labs.name','labs.id')
                    ->where('uni_id',$stables['user']->uni_id)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })->get()->groupBy('fac_id');
                if (!isset($devv[$selectedID])) {
                    $x = null;
                    $y = '';
                    return view('loggedTemp/export', compact('stables', 'selected', 'price','selected_fac', 'x', 'y', 'title', 'start_date', 'end_date'));
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
                $down =collect(array_map(function ($x, $y) {
                    return [
                        'Faculty' => $x,
                        'Num of devices' => $y,
                    ];
                }, $x, $y));
                $selected_fac = $selectedID;
                return view('loggedTemp/export', compact('stables','price','selected','selected_fac','x','y','title','start_date','end_date'));
            }
            elseif(($start_date != null or $end_date != null) and $selectedID == null)
            // number of devices in all faculties in certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
//                    ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
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

                $central_dev = DB::table('uni_devices')
                    ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                    ->select(DB::raw('count(uni_devices.id) as total'))
//                    ->select(DB::raw('sum(uni_devices.num_units) as total'))
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->whereBetween('uni_devices.entry_date',[$start_date,$end_date])
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                    })
                    ->get()->pluck('total');

                $dev_fac = array();
                $faculty_names = fac_uni::where('uni_id',$stables['user']->uni_id)->get()->pluck('name','fac_id');
                foreach ($faculty_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_fac[$id]=(int)$dev[$id];}
                    else{$dev_fac[$id]=0; }
                }
//                $y = array_values($dev_fac);
                $x = array_values($faculty_names->toArray());

                array_push($dev_fac,$central_dev[0]);
                array_push($x,'Central Labs');
                $y = array_values($dev_fac);
                $title = 'Devices in Faculties from '.(string)$start_date.' to '.(string)$end_date;
                $selected_fac = $selectedID;
                return view('loggedTemp/export',compact('stables','price','selected_fac','start_date','end_date','x','y','title'));
            }
            elseif (($start_date != null or $end_date != null) and $selectedID != null)
            // number of devices in a faculty in certain time
            {
                $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
                $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
                $selected = fac_uni::where('uni_id',$stables['user']->uni_id)->where('fac_id',$selectedID)->pluck('name')[0];
                $title = 'Number of Devices in '.(string)$selected.' from '.$start_date.' to '.$end_date;
                $devv = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
//                    ->select('labs.name',DB::raw('sum(devices.num_units) as total'),'labs.uni_id','labs.fac_id','labs.id')
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
                $error = 'No Devices Found';
                $x=0;$y=0;
                $selected_fac = $selectedID;
                if (! isset($devv[$selectedID])) return view('loggedTemp/export', compact('stables','price','selected_fac','start_date','end_date','selected','title','error'))->withErrors(["error"=>"No Devices found!"]);
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
                    return view('loggedTemp/export', compact('stables','price','selected_fac','start_date','end_date','selected','x','y','title','error'));
                }
            }
            else
            // number of devices in all faculties in all time
            {
                $dev = DB::table('devices')
                    ->join('labs', 'devices.lab_id', '=', 'labs.id')
                    ->select('labs.fac_id',DB::raw('count(devices.id) as total'))
//                    ->select('labs.fac_id',DB::raw('sum(devices.num_units) as total'))
                    ->where('labs.uni_id',$stables['user']->uni_id)
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                    })
                    ->groupBy('labs.fac_id')
                    ->get()->pluck('total','fac_id');

                $central_dev = DB::table('uni_devices')
                    ->join('uni_labs', 'uni_devices.lab_id', '=', 'uni_labs.id')
                    ->select(DB::raw('count(uni_devices.id) as total'))
//                    ->select(DB::raw('sum(uni_devices.num_units) as total'))
                    ->when($price, function ($query, $price) {
                        if ($price == 'less100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','<', 100000);
                        elseif ($price == 'more100k')
                            return $query->whereNotNull('uni_devices.price')->where('uni_devices.price','>', 100000);
                    })
                    ->where('uni_labs.uni_id',$stables['user']->uni_id)
                    ->get()->pluck('total');

                $dev_fac = array();
                $faculty_names = fac_uni::where('uni_id',$stables['user']->uni_id)->get()->pluck('name','fac_id');
                foreach ($faculty_names as $id=>$name)
                {
                    if(isset($dev[$id]))
                    {$dev_fac[$id]=(int)$dev[$id];}
                    else{$dev_fac[$id]=0; }
                }
//                $y = array_values($dev_fac);
                $x = array_values($faculty_names->toArray());

                array_push($dev_fac,$central_dev[0]);
                array_push($x,'Central Labs');
                $y = array_values($dev_fac);
                $title = 'Devices in all faculties';
                $selected_fac = $selectedID;
                return view('loggedTemp/export',compact('stables','price','selected_fac','start_date','end_date','x','y','title'));
            }
        }
        elseif($stables['user']->hasRole('faculty')){
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $lab_names = $stables['labs']->get()->pluck('name','id');
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total'))
//                ->select('labs.id',DB::raw('sum(devices.num_units) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })->groupBy('labs.id')
                ->get()->pluck('total','id');
//            dd($start_date,$end_date);
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
            return view('loggedTemp/export', compact('stables','x','y','price','title','start_date','end_date'));
        }
        elseif($stables['user']->hasRole('department')){
            $start_date = ($request->start_date == null)? date('Y-m-d',mktime(0,0,0,01,01,2016)):$request->start_date;
            $end_date = ($request->end_date == null)? date('Y-m-d'):$request->end_date ;
            $lab_names = $stables['labs']->get()->pluck('name','id');
            $dev = DB::table('devices')
                ->join('labs', 'devices.lab_id', '=', 'labs.id')
                ->select('labs.id',DB::raw('count(devices.id) as total'))
//                ->select('labs.id',DB::raw('sum(devices.num_units) as total'))
                ->where('labs.uni_id',$stables['user']->uni_id)
                ->where('labs.fac_id',$stables['user']->fac_id)
                ->where('labs.dept_id',$stables['user']->dept_id)
                ->whereBetween('devices.entry_date',[$start_date,$end_date])
                ->when($price, function ($query, $price) {
                    if ($price == 'less100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','<', 100000);
                    elseif ($price == 'more100k')
                        return $query->whereNotNull('devices.price')->where('devices.price','>', 100000);
                })->groupBy('labs.id')
                ->get()->pluck('total','id');
//            dd($start_date,$end_date);
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
            return view('loggedTemp/export', compact('stables','x','y','title','start_date','end_date','price'));
        }
    }

    public function exporttoExcel(Request $request,string $what)
    {
//        dd($request->columns);
        return Excel::download(new DevicesExport($request,$what), 'devices.xlsx');
    }

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
            return compact('user','faculties','labs','devices','central_labs','central_devices');
        }
        elseif ($user->hasRole('faculty'))
        {
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

    public function downloadTemplate($template){
        return Excel::download(new templates($template), $template=='labs'?'labsImportTemplate.xlsx':'devicesImportTemplate.xlsx');
    }

    public function import(Request $request, string $item){
        abort_unless(Gate::allows('import'),'403');
        if($item == 'labs') {
            try {
                $filePath = request()->file('importfile');
                $rows = Excel::toArray(new LabsImport($request['fac_id']), $filePath);
        
                if (empty($rows) || count($rows[0]) === 0) {
                    return back()->with('error', 'The file contains no rows to import.');
                }
                $request['fac_id'] ?
                    Excel::import(new LabsImport($request['fac_id']), $filePath) :
                    Excel::import(new LabsImport(Auth()->user()->fac_id), $filePath);
                return back()->with('success', 'Labs imported successfully');
            } catch (\Exception $e) {
                return back()->with('error', 'There was a problem: ' . $e->getMessage());
            }
        }
        elseif ($item == 'devices')
        {
            foreach (request()->file('photos') as $photo)
            {
                $ImageName = $photo->getClientOriginalName();
                $photo->move(public_path('images/universities/'.auth()->user()->uni_id),$ImageName);
            }
            try {
                $filePath = request()->file('importfile');
                $rows = Excel::toArray(new DevicesImport($request['fac_id']), $filePath);
        
                if (empty($rows) || count($rows[0]) === 0) {
                    return back()->with('error', 'The file contains no rows to import.');
                }
                $request['fac_id']?
                    Excel::import(new DevicesImport($request['fac_id']), $filePath) :
                    Excel::import(new DevicesImport(Auth()->user()->fac_id), $filePath);
                return back()->with('success','Devices imported successfully');
            } catch(\Exception $e) {
                return back()->with('error', 'There was a problem: ' . $e->getMessage());
            }

        }
    }

}
