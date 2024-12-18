<?php

namespace App\Http\Controllers;

use App\Models\services;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use function PHPUnit\Framework\isNull;

class UniversityDevicesController extends Controller
{
    /**
     * Display a listing of the resource.*/
    public function index()
    {
        abort_unless(Gate::allows('faculty') , 403);
        $user = Auth()->user();
        $universities = universitys::all()->keyBy('id');
        if ($user->hasRole('admin'))
            $labs = UniLabs::all()->pluck('id');
        else
            $labs = UniLabs::where('uni_id',$user->uni_id)->pluck('id');
        $devices = UniDevices::whereIn('lab_id',$labs)->get();
//        $labs = universitys::find($user->uni_id)->labs;
//        $devices = UniDevices::whereIn('lab_id',$labs)->latest()->paginate(5);
        return view('UniDevices.index',compact('devices','labs','universities'));
    }

    /**
     * Show the form for creating a new resource.*/
    public function create()
    {
        abort_unless(Gate::allows('faculty') , 403);
        $user = Auth()->user();
        if ($user->hasRole('admin'))
            $labs = UniLabs::all();
        else
            $labs = UniLabs::where('uni_id',$user->uni_id)->get();
        $universities = universitys::all();
        return view('UniDevices.create',compact('labs','universities'));
    }

    /**
     * Store a newly created resource in storage.*/
    public function store(Request $request)
    {
        abort_unless(Gate::allows('faculty') , 403);
        $costs = $request['cost'];
        $services = $request['services'];
        $costsArabic = $request['costArabic'];
        $servicesArabic = $request['servicesArabic'];
        $request['cost'] = implode(',',$request['cost']);
        $request['services'] = implode(',',$request['services']);
        $request['costArabic'] = implode(',',$request['costArabic']);
        $request['servicesArabic'] = implode(',',$request['servicesArabic']);
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
            'cost'=>'nullable',
            'services'=>'nullable',
            'costArabic'=>'nullable',
            'servicesArabic'=>'nullable',
            'pic' => 'nullable',
            'uni_id'=>'nullable',
            'lab_id'=>'nullable',
            'ManufactureWebsite'=>'nullable',
            'state'=>'required',
        ]);
        $user = auth()->user();
        if (! $user->hasRole('admin'))
            $request['uni_id'] = auth()->user()->uni_id;
//        dd($request);
        if ($request->missing('ImagePath')){
            $request['ImagePath']= 'images/universities/No_Image.png';
            $dev = UniDevices::create($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities/'.$request['uni_id']),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.$request['uni_id'].'/'.$ImageName;
            $dev = UniDevices::create($input);
        }
        foreach ($services as $key=>$value){
            services::create(['device_id'=>$dev->id, 'service_name'=>$value, 'cost'=>$costs[$key]
                , 'service_arabic'=>$servicesArabic[$key], 'cost_arabic'=>$costsArabic[$key], 'central'=>'1']);
        }
        session()->flash('message' ,'Device added successfully.' );
        return redirect()->route('UniDevice.index');
    }

    /*** Display the specified resource.*/
    public function show($id){    }

    /*** Show the form for editing the specified resource.*/
    public function edit($id)
    {
        abort_unless(Gate::allows('faculty') , 403);

        $device = UniDevices::find($id);
        $university = UniLabs::where('uni_labs.id',$device->lab_id)->join('universitys','universitys.id','uni_labs.uni_id')->first()->name;
//        dd($university);
        $cost = explode(',',$device->cost);
        $services = explode(',',$device->services);
        $costArabic = explode(',',$device->costArabic);
        $servicesArabic = explode(',',$device->servicesArabic);
        $labs = UniLabs::where('uni_id',$device->lab->uni_id)->get();
        return view('UniDevices.edit',compact('device','university','labs','cost','services','costArabic','servicesArabic'));
    }

    /*** Update the specified resource in storage.     */
    public function update(Request $request, $id)
    {
        abort_unless(Gate::allows('faculty') , 403);
        $costs = $request['cost'];
        $services = $request['services'];
        $costsArabic = $request['costArabic'];
        $servicesArabic = $request['servicesArabic'];
        $request['cost'] = implode(',',$request['cost']);
        $request['services'] = implode(',',$request['services']);
        $request['costArabic'] = implode(',',$request['costArabic']);
        $request['servicesArabic'] = implode(',',$request['servicesArabic']);

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
            'cost'=>'nullable',
            'services'=>'nullable',
            'costArabic'=>'nullable',
            'servicesArabic'=>'nullable',
            'pic' => 'nullable',
            'lab_id'=>'nullable',
            'ManufactureWebsite'=>'nullable',
            'state'=>'required',

        ]);

        $device = UniDevices::find($id);
        $uni_id = auth()->user()->hasRole('admin')?  UniLabs::find($device->lab_id)->uni_id : auth()->user()->uni_id;
        $request['uni_id'] = $uni_id;
//        if (auth()->user()->hasRole('admin'))
//            $request['uni_id'] = UniLabs::find($dl->lab_id)->uni_id;
//        else
//            $request['uni_id']=auth()->user()->uni_id;
        $old_img = $device->pic;
        if($request->missing('ImagePath') or $request['ImagePath']==null) {
            $request['ImagePath']=$old_img;
            $device->update($request->all());
        }
        else{
            $ImageName = $request->file('ImagePath')->getClientOriginalName();
            $request->file('ImagePath')->move(public_path('images/universities/'.$uni_id),$ImageName);
            $input = $request->all();
            $input['ImagePath'] = 'images/universities/'.$uni_id.'/'.$ImageName;
            $device->update($input);
        }
        $old_services = services::where('device_id',$id)->where('central','1')->get();
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
                else{services::create(['device_id'=>$id, 'service_name'=>$value, 'cost'=>$costs[$index],
                    'service_arabic'=>$servicesArabic[$index], 'cost_arabic'=>$costsArabic[$index], 'central'=>'1']);}
            }
        }
        session()->flash('message' ,'Device edited successfully.' );
        return redirect()->route('UniDevice.index');
    }

    /*** Remove the specified resource from storage.*/
    public function destroy($id)
    {
        $device = UniDevices::find($id);
        $device->delete();
        services::where('device_id',$id)->where('central','1')->delete();
        return redirect()->route('UniDevice.index')
            ->with('success','Device deleted successfully');
    }
}
