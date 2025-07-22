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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use function Complex\add;
use function PHPUnit\Framework\isNull;

use Carbon\Carbon;

use Illuminate\Http\Request;

class ReservationController extends Controller
{

    // Get reservation details for visitor
    public function getReservation($dev_id,$lab_id,$central,$uni_id,$uniname,$facID=null,$facName=null)   /// visitor View
    {
    

        if ($central=='1'){
            $lab = UniLabs::find($lab_id);
            $dev = UniDevices::find($dev_id);
        }
        else {
            $lab = labs::find($lab_id);
            $dev = devices::find($dev_id);

        }
        // services of services table belong to this device
        $services = services::select('id','service_name')->where('device_id',$dev_id)->get();

       //dd($services);

        if (!$central and $facName==null) {
            $facID = $lab->fac_id;
            $fac = fac_uni::where('fac_id', $lab->fac_id)->where('uni_id',$uni_id)->get()->first();
            $facName = $fac->name;
        }
        return view('templ/reservation',compact('dev','services','uni_id','uniname','facID','facName','lab','central'));
  
    }

   // Get reservations for this user / visitor
   public function userReservation()   /// visitor View
   {
       $user = Auth()->user();

       if ($user->hasRole('visitor')) {

     

            //  $reservations = Reservation::where('user_id',$user->id)->get()->keyBy('id');


           $reservations = Reservation::join('devices','devices.id','reservations.device_id')
                                            ->join('users','users.id','reservations.user_id')
                                            ->join('universitys','universitys.id','reservations.uni_id')
                                            ->join('facultys','facultys.id','reservations.fac_id')
                                            ->join('labs','labs.id','reservations.lab_id')
                                            ->join('services','services.id','reservations.service_id')
                                            ->select('devices.name as devname','users.username as username','universitys.name as uniname','facultys.name as facname','labs.name as labname','services.service_name as servname','reservations.*')
                                            ->where('user_id',$user->id)
                                            ->orderBy('reservations.date', 'ASC')
                                            ->get()->keyBy('id');

            
           // $currentDate = Carbon::now()->isoFormat('YYYY-MM-DD');
            foreach ($reservations as $key=>$value){

                $bookingDate = Carbon::parse($reservations[$key]->date);
                if ($bookingDate->isPast()) {
                   $reservations[$key]->status = 'expire';
                   $reservations[$key]->save();
                } else {
                   $reservations[$key]->status = 'valid';
                   $reservations[$key]->save();
                }

            }
            return view('Reservations/index',compact('reservations'));

           
       }     

   }


   // Get reservations for all users / visitors
   public function adminReservation()   /// Faculty Admin View
   {
       $user = Auth()->user();

       if ($user->hasRole('faculty')) {

            $facultyId = auth()->user()->fac_id;  
            $universityId = $user->uni_id;   
            //dd($universityId);

            $reservations = Reservation::where('fac_id', $facultyId)
                    ->where('uni_id', $universityId)
                    ->with(['user', 'service', 'device'])->get();
           // dd($reservations);
        } elseif ($user->hasRole('university')) {
        // University sees all reservations for their university
        $universityId = $user->uni_id;

        $reservations = Reservation::where('uni_id', $universityId)
            // ->with(['user.university', 'user.faculty', 'service', 'device.lab'])
               ->with(['user', 'university', 'faculty', 'lab', 'service', 'device'])
            ->get();

    } elseif ($user->hasRole('admin')) {
        // Admin sees all reservations
        $reservations = Reservation::with(['user', 'university', 'faculty', 'lab', 'service', 'device'])->get();
    } else {
            // Admins or other roles see all reservations
           // $reservations = Reservation::with(['user', 'service', 'device', 'university', 'faculty', 'lab'])->get();
           $reservations = collect();
        }  
            return view('Reservations/index',compact('reservations'));

           
      // }     

   }

   public function confirm(Request $request, $id)
   {
       $reservation= Reservation::findOrFail($id);

       // Update the reservation confirmation field
       $reservation->confirmation = 'Confirmed';
       //$reservation->confirmed_by = auth()->user()->id;
       $reservation->save();

       return redirect()->route('admin-reservations');
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        // $this->validate($request, [
        //     'visitor_phone'=> 'nullable|min:11',
        //     'samples'      => 'required|numeric',
        //     'date'         => 'required|date',
        //     'time'         => 'required|date_format:H:i:s',
            
  
        // ]);

          
        // Check if this date is exist
        $exists = Reservation::where(['date' => $request->date,'time' => $request->time])->exists();
        // dd($exists);

        if($exists == false)
        {
            //dd("xxxxxxxxxxxxxxxx");
            $reserv = new Reservation;

            $reserv->device_id      = $request->device_id;
            $reserv->user_id        = $request->user_id;
            $reserv->fac_id         = $request->fac_id;
            $reserv->uni_id         = $request->uni_id;
            $reserv->lab_id         = $request->lab_id;
            $reserv->visitor_phone  = $request->visitor_phone;
            $reserv->service_id     = $request->service_id;
            $reserv->samples        = $request->samples;
            $reserv->date           = $request->date;
            $reserv->time           = $request->time;

            $reserv->save();
        }  

        else
        {
            return back()->with('error','Date & Time Is Reserved');
        }  
    
       // Session::flash('message', 'Reservation Is Created');
    
       return back()->with('success','Reservation Is Created');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reservation = Reservation::find($id);
        $services = services::where('device_id',$reservation->device_id)->get();
        return view('Reservations/edit',compact('reservation','services'));
        //dd($services);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'samples'      => 'required|numeric',
            'date'         => 'required|date',
            'time'         => 'required|date_format:H:i:s',
        ]);

        //$services = $request['services'];

        $reservation = Reservation::find($id);
        $reservation->update($request->all());
        /*
           // Check if this date is exist
           $exists = Reservation::where(['date' => $request->date,'time' => $request->time])->exists();
           if($exists == false)
           {
             $reservation->update($request->all());
           }
           else
           {
            return redirect()->route('user-reservations')
                             ->with('error','This Time Is Reserved');
           }  
          */
          return redirect()->route('user-reservations')
                           ->with('success','Reservation Is Updated');

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = Reservation::find($id);
        $res->delete();
        return redirect()->back()
            ->with('success','Reservation deleted successfully');
    }
}
