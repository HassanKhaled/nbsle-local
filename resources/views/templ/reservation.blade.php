@extends('templ.head')
@section('tmplt-contnt')

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

    <div class="ie-panel"></div>
    <div class="preloader">
      <div class="preloader-body">
        <div class="cssload-container">
          <div class="cssload-speeding-wheel"></div>
        </div>
        <p>Loading...</p>
      </div>
    </div>
    <div class="page">
      
    
        <!-- Swiper-->
        <section class="section section-lg section-main-bunner section-main-bunner-filter text-center">
          <div class="main-bunner-img" style="background-image: url('{{asset($dev->ImagePath)}}'); background-repeat: no-repeat; background-size: 100% 100%;">
           
          </div>
     
        </section>
        <div class="bg-gray-1">
          <section class="section-transform-top">
            <div class="container">
              <div class="box-booking">

                <form class="booking-form" method="post" action="{{url('/booking/store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-wrap" hidden>
                      <input class="form-input" id="device_id" type="bigint" value="{{$dev->id}}" name="device_id" hidden>
                      
                    </div>  
                  <div>
                    <p class="booking-title">Name</p>
                    <div class="form-wrap">
                      <input class="form-input" id="booking-name" type="text" name="visitor_name" data-constraints="@Required">
                      <label class="form-label" for="booking-name">Your name</label>
                    </div>
                  </div>
                  <div>
                    <p class="booking-title">Phone</p>
                    <div class="form-wrap">
                      <input class="form-input" id="booking-phone" type="text" name="visitor_phone" data-constraints="@Numeric">
                      <label class="form-label" for="booking-phone">Your phone number</label>
                    </div>
                  </div>
                  <div>
                    <p class="booking-title">Date</p>
                    <div class="form-wrap form-wrap-icon">
                      <!--<span class="icon mdi mdi-calendar-text"></span> -->
                     <!-- <input  class="form-input" id="booking-date" type="text" name="date" data-constraints="@Required" data-time-picker="date"> -->
                     <input  class="form-input" id="booking-date" type="date" name="date" data-constraints="@Required">
                    </div>
                  </div>
                  <div>
                    <p class="booking-title">Time</p>
                    <div class="form-wrap">
                      <select name="time" data-placeholder="00:00">
                        <option label="placeholder"></option>
                        <option value='09:00'>09:00</option>
                        <option value='10:00'>10:00</option>
                        <option value='11:00'>11:00</option>
                        <option value='12:00'>12:00</option>
                        <option value='01:00'>01:00</option>
                        <option value='02:00'>02:00</option>
                        <option value='03:00'>03:00</option>
                      </select>
                    </div>
                  </div>
                  <div>
                    <button class="button button-lg button-gray-600" type="submit">Book</button>
                  </div>
                </form>
              </div>
            </div>
          </section>
  
        </div>
      
  
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



{{--  <script src="assets/vendor/aos/aos.js"></script>--}}
{{--  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>--}}
{{--  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>--}}
{{--  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>--}}
{{--  <script src="assets/vendor/php-email-form/validate.js"></script>--}}
{{--  <script src="assets/vendor/purecounter/purecounter.js"></script>--}}
{{--  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>--}}
{{--  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>--}}

{{--  <!-- Template Main JS File -->--}}
{{--  <script src="assets/js/main.js"></script>--}}
@endsection
