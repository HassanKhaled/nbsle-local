@extends('loggedTemp.head')
@section('loggedContent')
    @if ($errors->any())
        <div class="alert alert-danger">
            There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <a class="h-25" href="{{ url('/userReservation') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card"> 
                                <div class="card-header">
                                    <h5>Edit reservation</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ url('/userReservation/update', ['id' => $reservation->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')

                                        <div class="form-group form-primary">
                                            <p class="booking-title">Date</p>
                                            <div class="form-wrap form-wrap-icon">
                                            <input  class="form-input" id="booking-date" type="text" name="date" value="{{ $reservation->date }}" data-time-picker="date">
                                            </div>
           
                                        </div>
                                        <div class="form-group form-primary">
                                            <p class="booking-title">Time</p>
                                            <div class="form-wrap">
                                            <select name="time" data-placeholder="00:00:00" data-time-picker="time">
                                                <option disabled>Select time</option>
                                                <option value='09:00:00' {{old('time',$reservation->time)=='09:00:00'?'selected':''}}>09:00:00 AM</option>
                                                <option value='10:00:00' {{old('time',$reservation->time)=='10:00:00'?'selected':''}}>10:00:00 AM</option>
                                                <option value='11:00:00' {{old('time',$reservation->time)=='11:00:00'?'selected':''}}>11:00:00 AM</option>
                                                <option value='12:00:00' {{old('time',$reservation->time)=='12:00:00'?'selected':''}}>12:00:00 PM</option>
                                                <option value='01:00:00' {{old('time',$reservation->time)=='01:00:00'?'selected':''}}>01:00:00 PM</option>
                                                <option value='02:00:00' {{old('time',$reservation->time)=='02:00:00'?'selected':''}}>02:00:00 PM</option>
                                                <option value='03:00:00' {{old('time',$reservation->time)=='03:00:00'?'selected':''}}>03:00:00 PM</option>
                                            </select>
                                            </div>
                                        </div>

                                        
                                        <div class="form-group form-primary">
                                            
                                            <p class="booking-title">Service</p>
                                            <div class="form-wrap">
                                                <select name="service_id" id="service_id" required>
                                                    <option value="" disabled selected >Choose Service</option>
                                                    @foreach($services as $service)
                                                        <option value="{{$service->id}}" {{old('service_id',$reservation->service_id)==$service->id?'selected':''}}> {{$service->service_name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>    
                                        </div>
                                
                                    
                                        <div class="form-group form-primary">
                                            <p class="booking-title">Samples</p>
                                            <div class="form-wrap">
                                            <input class="form-input" id="booking-sample" type="int" name="samples" value="{{old('samples',$reservation->samples) }}" data-constraints="@Numeric">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Save edits</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/script.js')}}"></script>
  <script src="{{asset('assets/js/main.js')}}"></script>
  
  <script src="{{asset('assets/js/core.min.js')}}"></script>
 

@endsection
