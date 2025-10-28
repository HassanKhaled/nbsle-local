<?php

namespace App\Http\Controllers;

use App\Models\departments;
use App\Models\dept_fac;
use App\Models\device_lab;
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
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use function Complex\add;
use function PHPUnit\Framework\isNull;
use App\Models\DeviceRating;
use App\Models\DeviceRatingUniLab;
use App\Models\ReservationUniLab;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class DeviceLabController extends Controller
{
    // Get device details for guests
    public function getDevice($dev_id,$lab_id,$central,$uni_id,$uniname,$facID=null,$facName=null)   /// Guest View
    {
        // get selected lab and device
        if ($central=='1'){
            $lab = UniLabs::find($lab_id);
            $dev = UniDevices::find($dev_id);
            $ratings = DeviceRatingUniLab::where('device_id', $dev_id)->get();
            $reservationCount = ReservationUniLab::where('device_id', $dev_id)->count();

        }
        else {
            $lab = labs::find($lab_id);
            $dev = devices::find($dev_id);
            $ratings = DeviceRating::where('device_id', $dev_id)->get();
            $reservationCount = Reservation::where('device_id', $dev_id)->count();


            // services of services table belong to this device
           // $services = services::select('service_name')->where('device_id',$dev_id)->get();
        }
        // get selected device's costs and services
        $cost = explode(',',$dev->cost);
        $services = explode(',',$dev->services);
        while(count($services) > count($cost))
            {array_push($cost,last($cost));}
        // get lab, faculty and university coordinators
        $lab_cors = LabStaff::where('lab_id',$lab_id)->where('central',$central)->pluck('manager_id');
        $coords = Staff::whereIn('Id',$lab_cors)->orderBy('staff', 'desc')->get();
        $fac_coor = User::where('uni_id',$lab->uni_id)->where('fac_id',$lab->fac_id)->get();
        $uni_coor = User::where('uni_id',$lab->uni_id)->where('role_id',2)->get();
        // تظبيت الداتا علشان لازم تتبعت
        if (!$central and $facName==null) {
            $facID = $lab->fac_id;
            $fac = fac_uni::where('fac_id', $lab->fac_id)->where('uni_id',$uni_id)->get()->first();
            $facName = $fac->name;
        }
      

        // Calculate averages for each field
        $averages = [
            'service_quality'         => round($ratings->avg('service_quality'), 1),
            'device_info_clarity'     => round($ratings->avg('device_info_clarity'), 1),
            'search_interface'        => round($ratings->avg('search_interface'), 1),
            'request_steps_clarity'   => round($ratings->avg('request_steps_clarity'), 1),
            'device_condition'        => round($ratings->avg('device_condition'), 1),
            'research_results_quality'=> round($ratings->avg('research_results_quality'), 1),
            'device_availability'     => round($ratings->avg('device_availability'), 1),
            'response_speed'          => round($ratings->avg('response_speed'), 1),
            'technical_support'       => round($ratings->avg('technical_support'), 1),
            'research_success'        => round($ratings->avg('research_success'), 1),
            'recommend_service'       => round($ratings->avg('recommend_service'), 1),
        ];

        // Count reservations for this device
        $dev->timestamps = false;   
        $dev->increment('views');
        $dev->timestamps = true;

        return view('templ/device',compact('dev','cost','services','coords','uni_id','uniname','facID','facName','lab','fac_coor','uni_coor','central',
            'lab_id','ratings','averages','reservationCount'));
    }

    // Get all devices
    // public function getAllDevices()
    // {
    //     // get all normal + central devices
    //     $normalDevices  = devices::with('lab')->get();
    //     $centralDevices = UniDevices::with('lab')->get();
    //     $allDevices = $normalDevices->merge($centralDevices);
        
    //     //dd($centralDevices);
    //     return view('templ.all_devices', compact('allDevices'));
    // }
    public function getAllDevices()
    {
        $perPage = 15; // number of devices per page
        $page = request()->get('page', 1);

        // Fetch devices separately
        $normalDevices  = devices::with('lab')->get();
        $centralDevices = UniDevices::with('lab')->get();
        $allDevices = $normalDevices->merge($centralDevices);

        // Sort by name or id (optional, otherwise order is mixed)
        $allDevices = $allDevices->sortBy('name')->values();

        // Paginate manually
        $pagedDevices = new LengthAwarePaginator(
            $allDevices->forPage($page, $perPage),
            $allDevices->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('templ.all_devices', [
            'allDevices' => $pagedDevices
        ]);
    }
  
    /*** Display a listing of the resource.*/
    public function index()
    {
        abort_unless(Gate::allows('device') , 403);
        $user = Auth()->user();
        if ($user->hasRole('admin')) {
            $unis = universitys::all()->keyBy('id');
            $facs = fac_uni::all()->keyBy('fac_id');
            $departments = departments::all()->keyBy('id');
            $labs = labs::all()->pluck('id');
//            $devlab = devices::whereIn('lab_id',$labs)->get();
            $devlab = devices::whereIn('lab_id',$labs)->withCount('reservations')->paginate(50);
            $labs = labs::all()->keyBy('id');
            // dd($devlab);

            return view('Devices.index',compact('labs','devlab','unis','facs','departments'));
        }
        elseif ($user->hasRole('university')){
            $labs = labs::where('uni_id',$user->uni_id)->pluck('id');
//            $devlab = devices::whereIn('lab_id',$labs)->paginate(5);
            $devlab = devices::whereIn('lab_id',$labs)->get();
            $labs = labs::where('uni_id',$user->uni_id)->get()->keyBy('id');
        }
        elseif($user->hasRole('department'))
        {
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id','=',$user->fac_id)->where('dept_id',$user->dept_id)->pluck('id');
//            $devlab = devices::whereIn('lab_id',$labs)->paginate(5);
            $devlab = devices::whereIn('lab_id',$labs)->get();
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id','=',$user->fac_id)->where('dept_id',$user->dept_id)->pluck('name','id');
        }
        else
        {
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id','=',$user->fac_id)->pluck('id');
//            $devlab = devices::whereIn('lab_id',$labs)->paginate(5);
            $devlab = devices::whereIn('lab_id',$labs)->get();
            $labs = labs::where('uni_id',$user->uni_id)->where('fac_id','=',$user->fac_id)->pluck('name','id');
        }
        return view('Devices.index',compact('labs','devlab'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /*** Show the form for creating a new resource.*/
    public function create()
    {
        abort_unless(Gate::allows('device') , 403);
        $user = Auth()->user();
        if ($user->hasRole('admin')){
            $universities = universitys::all();
            $facultys = fac_uni::all();
            $deptfac = dept_fac::whereIn('fac_id',$facultys->pluck('fac_id'))->get();
            $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();
            $labs = labs::all();
            return view('Devices.create',compact('departments','labs','facultys','deptfac','universities'));
        }
        elseif ($user->hasRole('university')){
            $facultys = fac_uni::where('uni_id',$user->uni_id)->get();
            $deptfac = dept_fac::where('uni_id',$user->uni_id)->whereIn('fac_id',$facultys->pluck('fac_id'))->get();
            $departments = departments::wherein('id', $deptfac->pluck('dept_id'))->get();
//            if (count($deptfac)>0) {
//                $labs = labs::where('uni_id', $user->uni_id)->wherein('fac_id', $facultys->pluck('fac_id'))->wherein('dept_id', $deptfac->pluck('dept_id'))->get();
//            }
//            else{
                $labs = labs::where('uni_id', $user->uni_id)->wherein('fac_id', $facultys->pluck('fac_id'))->get();
//            }
            return view('Devices.create',compact('departments','labs','facultys','deptfac'));
        }
        elseif ($user->hasRole('department')) {
            $depts = dept_fac::where('coor_id', $user->id)->pluck('dept_id');
            $departments = departments::wherein('id',$depts)->get();
            $labs = labs::where('uni_id', $user->uni_id)->where('fac_id', $user->fac_id)->wherein('dept_id',$depts)->get();
            return view('Devices.create',compact('departments','labs'));
        }
        else{
            $depts = dept_fac::where('uni_id', $user->uni_id)->where('fac_id', $user->fac_id)->pluck('dept_id');
            $departments = departments::wherein('id', $depts)->get();
            $labs = labs::where('uni_id', $user->uni_id)->where('fac_id', $user->fac_id)->orderby('dept_id')->get();
        }
        return view('Devices.create',compact('labs','departments'));
    }

    /*** Store a newly created resource in storage.*/
    public function store(Request $request)
    {
        abort_unless(Gate::allows('device') , 403);

        $costs = $request['cost'];
        $services = $request['services'];
        $costsArabic = $request['costArabic'];
        $servicesArabic = $request['servicesArabic'];
        $desc_service = $request['desc_service']; 
        $request['cost'] = implode(',',$request['cost']);
        $request['services'] = implode(',',$request['services']);
        $request['costArabic'] = implode(',',$request['costArabic']);
        $request['servicesArabic'] = implode(',',$request['servicesArabic']);
        $request['desc_service'] = implode(',',$request['desc_service']);
        $request['entry_date'] = date('Y-m-d H:i:s');
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'model'=>'required',
            'description'=>'nullable',
            'ArabicDescription'=>'nullable',
            'AdditionalInfo'=>'nullable',
            'ArabicAddInfo'=>'nullable',
            'manufacturer'=>'nullable',
            'num_units'=>'required',
            'entry_date'=>'nullable',
            'state'=>'required',
            'lab_id'=>'required',
            'ManufactureWebsite'=>'nullable',

        ]);
        $request['dept_id'] = $request['dept_id']==null? labs::find($request['lab_id'])->dept_id:$request['dept_id'];
        // ---------- if image is not provided  -------------------------
        if ($request->missing('ImagePath')){
            $request['ImagePath']= 'images/universities/No_Image.png';
            $input = $request->all();
            $dev = devices::create($input);
            //$dev = devices::create($request->all());
            //dd($dev);
         
        }
        // --------- save provided image in a folder named by university ID --------------------
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities/'.auth()->user()->uni_id),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.auth()->user()->uni_id.'/'.$ImageName;
            $dev = devices::create($input);
            //dd($dev);
        }
        
        foreach ($services as $key=>$value){
            services::create(['device_id'=>$dev->id, 'service_name'=>$value, 'cost'=>$costs[$key],
                'service_arabic'=>$servicesArabic[$key], 'cost_arabic'=>$costsArabic[$key], 'desc_service'=>$desc_service[$key], 'central'=>'0']);
        }
        
        session()->flash('message' ,'Device added successfully.' );
        return redirect()->route('DeviceLab.index');
        
    }

    /*** Display the specified resource.*/
    public function show($id)
    {
    }

    /*** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        abort_unless(Gate::allows('device') , 403);
        $user = Auth()->user();
        $dev = devices::find($id);
        $cost = explode(',',$dev->cost);
        $services = explode(',',$dev->services);
        $costArabic = explode(',',$dev->costArabic);
        $servicesArabic = explode(',',$dev->servicesArabic);
        if ($user->hasRole('admin')){
            $lab = labs::find($dev->lab_id);
            $university = universitys::where('id',$lab->uni_id)->first()->name;
            $facultys = fac_uni::where('uni_id',$lab->uni_id)->get();
            $deptfac = dept_fac::where('uni_id',$lab->uni_id)->whereIn('fac_id',$facultys->pluck('fac_id'))->get();
            $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();
            $labs = labs::where('uni_id',$lab->uni_id)->whereIn('fac_id',$facultys->pluck('fac_id'))->get()->keyBy('id');

            return view('Devices.edit',compact('departments','dev','cost','services','costArabic','servicesArabic','university','facultys','labs','deptfac'));

        }
        elseif ($user->hasRole('university')){
            $facultys = fac_uni::where('uni_id',Auth()->user()->uni_id)->get();
            $deptfac = dept_fac::where('uni_id',Auth()->user()->uni_id)->whereIn('fac_id',$facultys->pluck('fac_id'))->get();
            $departments = departments::wherein('id',$deptfac->pluck('dept_id'))->get();
            $dept_labs=[];
//            if (count($deptfac)>0)
//                $dept_labs = labs::where('uni_id', $user->uni_id)->wherein('fac_id', $facultys->pluck('fac_id'))->wherein('dept_id',$deptfac->pluck('dept_id'))->get()->keyBy('id');
            $labs = labs::where('uni_id', $user->uni_id)->wherein('fac_id', $facultys->pluck('fac_id'))->get()->keyBy('id');
//            $labs = labs::where('uni_id', $user->uni_id)->wherein('fac_id', $facultys->pluck('fac_id'))->wherein('dept_id',$deptfac->pluck('dept_id'))->get();
            return view('Devices.edit',compact('departments','labs','dev','cost','services','costArabic','servicesArabic','facultys','deptfac'));
        }
        elseif ($user->hasRole('department')) {
            $depts = dept_fac::where('coor_id', $user->id)->pluck('dept_id');
            $departments = departments::wherein('id',$depts)->get();
            $labs = labs::where('uni_id', $user->uni_id)->where('fac_id', $user->fac_id)->wherein('dept_id',$depts)->get()->keyBy('id');
        }
        else{
            $depts = dept_fac::where('uni_id', $user->uni_id)->where('fac_id', $user->fac_id)->pluck('dept_id');
            $departments = departments::wherein('id', $depts)->get();
            $labs = labs::where('uni_id', $user->uni_id)->where('fac_id', $user->fac_id)->orderby('dept_id')->get()->keyBy('id');
        }
        return view('Devices.edit',compact('departments','labs','dev','cost','services','costArabic','servicesArabic'));
    }

    /*** Update the specified resource in storage.*/
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('device') , 403);
        request()->validate([
            'name' => 'required',
            'Arabicname'=>'nullable|regex:/^[؀-ۿ]/',
            'model'=>'required',
            'description'=>'nullable',
            'ArabicDescription'=>'nullable',
            'AdditionalInfo'=>'nullable',
            'ArabicAddInfo'=>'nullable',
            'manufacturer'=>'nullable',
            'num_units'=>'required',
            'state'=>'required',
            'lab_id'=>'required',
            'ManufactureWebsite'=>'nullable',
        ]);
        $costs = $request['cost'];
        $services = $request['services'];
        $costsArabic = $request['costArabic'];
        $servicesArabic = $request['servicesArabic'];
        $request['cost'] = implode(',',$request['cost']);
        $request['services'] = implode(',',$request['services']);
        $request['costArabic'] = implode(',',$request['costArabic']);
        $request['servicesArabic'] = implode(',',$request['servicesArabic']);

        $device = devices::find($id);
        $old_img = $device->ImagePath;
        if($request->missing('ImagePath') or $request['ImagePath']==null) {
            $request['ImagePath']=$old_img;
            $device->update($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities/'.auth()->user()->uni_id),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.auth()->user()->uni_id.'/'.$ImageName;
            $device->update($input);
        }
        $old_services = services::where('device_id',$device->id)->where('central','0')->get();
        $old_serv_len = count($old_services);
        $new_serv_len = count($services);
        if($old_serv_len>$new_serv_len){
            foreach ($old_services as $index=>$old_serv){
                if ($index<$new_serv_len){
                    services::where('id', $old_serv->id)->update(['service_name'=>$services[$index], 'cost'=>$costs[$index]
                        , 'service_arabic'=>$servicesArabic[$index], 'cost_arabic'=>$costsArabic[$index]]);
                }
                else{services::destroy($old_serv->id);}
            }
        }
        else{
            foreach ($services as $index=>$value){
                if ($index<$old_serv_len){
                    services::where('id', $old_services[$index]->id)->update(['service_name'=>$value, 'cost'=>$costs[$index]
                        , 'service_arabic'=>$servicesArabic[$index], 'cost_arabic'=>$costsArabic[$index]]);
                }
                else{services::create(['device_id'=>$device->id, 'service_name'=>$value, 'cost'=>$costs[$index],
                    'service_arabic'=>$servicesArabic[$index], 'cost_arabic'=>$costsArabic[$index], 'central'=>'0']);}
            }
        }
        session()->flash('message' ,'Device edited successfully.' );
        return redirect()->route('DeviceLab.index');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        $dev = devices::find($id);
        $dev->delete();
        services::where('device_id',$id)->where('central','0')->delete();
        return redirect()->route('DeviceLab.index')
            ->with('success','Device deleted successfully');
    }
}
