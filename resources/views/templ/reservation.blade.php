@extends('templ.head')
@section('tmplt-contnt')



  <main id="main">

    

    <!-- ======= Our Portfolio Section ======= -->
    <section class="breadcrumbs">
      <div class="container">
          <div class="d-flex justify-content-between align-items-center">
              <h2>{{$dev->name!=null?$dev->name:$dev->Arabicname}}</h2>
              <ol>
{{--                  <li><a href="/">Home</a></li>--}}
                  <li><a href="{{route('browseuniversity',[$uni_id,$uniname])}}">{{$uniname}}</a></li>
                  @if($facName != null)
                      <li><a href="{{route('browsefaculty',[$uni_id, $uniname, $facID,$facName])}}">{{$facName}}</a></li>
                  @else
                      <li><a href="{{route('browsecentrallab',[$uni_id, $uniname])}}">Central Labs</a></li>
                  @endif
              </ol>
          </div>
      </div>
    </section><!-- End Our Portfolio Section -->


    <!-- Reservation -->
















      <div class="container">
      @include('templ.flash-message')
            <section class="login">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-center fw-bold text-light fs-4">Reservation</div>
                            <div class="card-body">
                                <form method="POST" action="{{url('/booking/store')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-wrap" hidden>
                                      <input class="form-input" id="device_id" type="bigint" value="{{$dev->id}}" name="device_id" hidden>
                                      <input class="form-input" id="user_id"   type="bigint" value="{{Auth()->user()->id}}" name="user_id" hidden>
                                      <input class="form-input" id="device_id" type="bigint" value="{{$lab->id}}" name="lab_id" hidden>
                                      <input class="form-input" id="device_id" type="bigint" value="{{$facID}}"  name="fac_id" hidden>
                                      <input class="form-input" id="device_id" type="bigint" value="{{$uni_id}}" name="uni_id" hidden>
                                    </div> 

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control user" name="name" value="{{Auth()->user()->username}}" disabled autocomplete="name" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="booking-phone" class="col-md-4 col-form-label text-md-right">Phone</label>

                                        <div class="col-md-6">
                                            <input id="booking-phone" type="text" class="form-control" name="visitor_phone" data-constraints="@Numeric" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="date" class="col-md-4 col-form-label text-md-right">Date</label>
                                        <div class="col-md-6">
                                            <input class="form-control" id="booking-date" type="text" name="date" data-constraints="@Required" data-time-picker="date" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="time" class="col-md-4 col-form-label text-md-right">Time</label>
                                        <div class="col-md-6">
                                        <select class="form-control border" name="time">
                                            <option>Select Time</option>
                                            <option value='09:00:00'>09:00:00 AM</option>
                                            <option value='10:00:00'>10:00:00 AM</option>
                                            <option value='11:00:00'>11:00:00 AM</option>
                                            <option value='12:00:00'>12:00:00 PM</option>
                                            <option value='01:00:00'>01:00:00 PM</option>
                                            <option value='02:00:00'>02:00:00 PM</option>
                                            <option value='03:00:00'>03:00:00 PM</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="services" class="col-md-4 col-form-label text-md-right">Services</label>
                                        <div class="col-md-6">
                                        <select class="form-control" id="booking-service" name="service_id" data-placeholder="">
                                          <!-- <option value="" disabled selected>Select Services</option>-->
                                          <option label="Select Service"></option>
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}"> {{$service->service_name}} </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="samples" class="col-md-4 col-form-label text-md-right">Samples</label>
                                        <div class="col-md-6">
                                        <input class="form-input" id="booking-sample" type="number" name="samples" data-constraints="@Numeric">
                                        </div>
                                    </div>
                
                               

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                        <button class="button button-lg button-gray-600" type="submit">Book</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
      </div>
   










    


  

  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}'"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter.js')}}'"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}'"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>
  
  <script src="{{asset('assets/js/core.min.js')}}"></script>
  <script src="{{asset('assets/js/script.js')}}"></script>


@endsection
