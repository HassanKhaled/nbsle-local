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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use function Complex\add;
use function PHPUnit\Framework\isNull;

use App\Models\Reservation;
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

            // services of services table belong to this device
           // $services = services::select('service_name')->where('device_id',$dev_id)->get();
        }
        if (!$central and $facName==null) {
            $facID = $lab->fac_id;
            $fac = fac_uni::where('fac_id', $lab->fac_id)->where('uni_id',$uni_id)->get()->first();
            $facName = $fac->name;
        }
        return view('templ/reservation',compact('dev','uni_id','uniname','facID','facName','lab','central'));

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
        //
     //   abort_unless(Gate::allows('user') , 403);
       // $logged_user = auth()->user();
        //dd($request);
       
        $this->validate($request, [
            'visitor_name' => 'required',
            'visitor_phone'=>'nullable|numeric|min:11',
            'date' => 'required|date',
            'time'=>  'required|date_format:H:i',
  
        ]);
       


    $reserv = new Reservation;

    $reserv->device_id      = $request->device_id;
    $reserv->visitor_name   = $request->visitor_name;
    $reserv->visitor_phone  = $request->visitor_phone;
    $reserv->date           = $request->date;
    $reserv->time           = $request->time;




    $reserv->save();

   // Session::flash('message', 'Reservation Is Created');
  //session()->flash('message' ,'Reservation added successfully.' );

    return redirect('/');

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
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
