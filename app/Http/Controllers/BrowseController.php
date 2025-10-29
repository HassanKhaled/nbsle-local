<?php

namespace App\Http\Controllers;

use App\Models\devices;
use App\Models\labs;
use App\Models\UniDevices;
use App\Models\UniLabs;
use App\Models\universitys;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Collection;

class BrowseController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        // Browse a list of all universities in view
    public function __invoke(Request $request)
    {
        // 1) Get all public universities
        $publicUnis = universitys::where('type', '=', 'public')->get();

        // 2) Get unique university IDs from labs
        $uniqueUnis = \App\Models\labs::select('uni_id')->distinct()->pluck('uni_id');

        // 3) Get unique university IDs from UniLabs
        $uniqueUnisFromUniLabs = \App\Models\UniLabs::select('uni_id')->distinct()->pluck('uni_id');

        // Merge the IDs from labs + UniLabs
        $allUniqueUniIds = $uniqueUnis->merge($uniqueUnisFromUniLabs)->unique();
        // 4) Get universities with those IDs (excluding Institution + public to avoid duplication)
        $otherUnis = universitys::whereIn('id', $allUniqueUniIds)
            ->whereNotIn('type', ['Institution', 'public'])
            ->get();
        // 5) Merge results
        $unis = $publicUnis->concat($otherUnis);

        return view('templ/browse', compact('unis'));
    }

        // Browse a list of all institutes in view
    public function getInstitutions(Request $request)
    {
        $unis = universitys::where('type', 'Institution')->get();
        return view('templ/institutions', compact('unis'));
    }

    public function search(Request $request){
        $searchFor =$request->search;
        $unis=[];
        ///////////////////////////     Search Bar in top NavBar     ///////////////////////////
        if($searchFor != "")
        {
            $unis = universitys::query()->where('name', 'like', '%' . $request->search . '%')->get();
            /*
            $devs = devices::query()->where('devices.name', 'like', '%' . $request->search . '%')
                                    ->orWhere('services.service_name', 'like', '%' . $request->search . '%');
                                    
            $devices = $devs->join('labs','devices.lab_id','labs.id')
                            ->join('services','services.device_id','devices.id')
                ->select('devices.id','devices.lab_id','devices.ImagePath','devices.name','labs.uni_id','labs.fac_id')
                ->orderBy('labs.uni_id')->get();
                */
               // $devs = devices::query()->where('devices.name', 'like', '%' . $request->search . '%');
                
               // $devices = $devs->join('labs','devices.lab_id','labs.id')
             //   ->select('devices.id','devices.lab_id','devices.ImagePath','devices.name','labs.uni_id','labs.fac_id')
             //   ->orderBy('labs.uni_id')->get();
              
             $devs = devices::query()->where('devices.name', 'like', '%' . $request->search . '%')
                                     ->orWhere('devices.Arabicname', 'like', '%' . $request->search . '%')
                                     ->orWhere('devices.services', 'like', '%' . $request->search . '%')
                                     ->orWhere('devices.servicesArabic', 'like', '%' . $request->search . '%');
                                     
             
                $devices = $devs->join('labs','devices.lab_id','labs.id')
                ->select('devices.id','devices.lab_id','devices.ImagePath','devices.name','labs.uni_id','labs.fac_id')
                ->orderBy('labs.uni_id')->get(); 

            $unidevs = UniDevices::query()->where('uni_devices.name', 'like', '%' . $request->search . '%')
                                          ->orwhere('uni_devices.Arabicname', 'like', '%' . $request->search . '%')
                                          ->orwhere('uni_devices.services', 'like', '%' . $request->search . '%')
                                          ->orwhere('uni_devices.servicesArabic', 'like', '%' . $request->search . '%')
                                          ->withCount('reservations');

                                          
            $unidevices = $unidevs->join('uni_labs','uni_devices.lab_id','uni_labs.id')
                ->select('uni_devices.id','uni_devices.lab_id','uni_devices.ImagePath','uni_devices.name','uni_labs.uni_id')
                ->orderBy('uni_labs.uni_id')->withCount('reservations')->get();
            $faculties = \App\Models\fac_uni::all();
            return view('templ/searchResult',compact('unis','devices','searchFor','request','faculties','unidevices'));
        }
        //////////////////////////      Advanced search       ////////////////////////////////
        elseif ($request->has('device_name'))
        {
            $labs = new labs;
            $unilabs = new UniLabs;
            if ($request->has('uni_id') or $request->has('fac_id')) {
                if ($request->uni_id != '') {
                    $labs = $labs->where('uni_id', $request['uni_id']);
                    $unilabs = UniLabs::where('uni_id',$request['uni_id']);
                }
                if ($request->fac_id != '') {
                    $labs = $labs->where('fac_id', $request['fac_id']);
                }
                $labs = $labs->pluck('id');
                $unilabs = $unilabs->pluck('id');
            }
            else {
                $labs = null;
                $unilabs = null;
            }
            $devices = new devices;
            $unidevices = new UniDevices;
            if ($labs != null){ $devices = $devices->whereIn('lab_id',$labs);}
            if ($unilabs != null){$unidevices = $unidevices->whereIn('lab_id',$unilabs);}
            if ($request->device_name != "" or $request->model != "" or $request->services != ""){
                if ($request->device_name != "") {
                    $devices = $devices->where('devices.name', 'like', '%'.$request['device_name'].'%');
                    $unidevices = $unidevices->where('uni_devices.name', 'like', '%'.$request['device_name'].'%');
                }
                if ($request->model != "") {
                    $devices = $devices->where('devices.model', 'like', '%'.$request['model'].'%');
                    $unidevices = $unidevices->where('uni_devices.model', 'like', '%'.$request['model'].'%');
                }
                if ($request->services != "") {
                    $devices = $devices->where('devices.services', 'like', '%'.$request['services'].'%');
                    $unidevices = $unidevices->where('uni_devices.services', 'like', '%'.$request['services'].'%');
                }
            }
            $devices = $devices->join('labs','devices.lab_id','labs.id')
                ->select('devices.id','devices.lab_id','devices.ImagePath','devices.name','labs.uni_id','labs.fac_id')
                ->orderBy('labs.uni_id');
            $devices = $devices->get();
            $unidevices = $unidevices->join('uni_labs','uni_devices.lab_id','uni_labs.id')
                ->select('uni_devices.id','uni_devices.lab_id','uni_devices.ImagePath','uni_devices.name','uni_labs.uni_id')
                ->orderBy('uni_labs.uni_id');
            $unidevices = $unidevices->get();

            $faculties = \App\Models\fac_uni::all();
            if ($request->fac_id != null){$unidevices=[];}
            return view('templ/searchResult',compact('unis','devices','searchFor','request','faculties','unidevices'));
        }
        else{
            $unis = universitys::all();
            return view('templ/browse',compact('unis'));
        }
    }
}
