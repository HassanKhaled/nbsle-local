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

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">

        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="portfolio-details-slider swiper-container">
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide">  <img src="{{asset($dev->ImagePath)}}" alt="">  </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">

{{--            </div>--}}
{{--            <div class="col-lg-4">--}}
                <div class="portfolio-info">
                    <h3>Device information</h3>
                    <ul>
                        <li class="row flex-row d-flex">
                            <div class="col-4"><strong>Name</strong>: {{$dev->name}}</div>
                            <div class="col-4"><strong>Model</strong>: {{$dev->model==null? ' ---':$dev->model}}</div>
                            <div class="col-4"><strong>Num of units</strong>: {{$dev->num_units}}</div>
                        </li>
                        <li class="row flex-row d-flex">
                            <div class="col-5"><strong>Manufacture Year</strong>: {{$dev->ManufactureYear}}</div>
                            <div class="col-7"><strong>Manufacturer Website</strong>:
                                <a class="" href="{{$dev->ManufactureWebsite}}">{{$dev->ManufactureWebsite}}</a>
                            </div>
                        </li>
                        <li class="row">
                            <div>
                                <strong>Description</strong>: {{$dev->description}}
                            </div>
                        </li>
                        <li class="row">
                            <div  style=" color: {{$dev->state=='available'?'green':'red'}}">
                                <strong>Availability</strong>: {{$dev->state}}
                            </div>
                        </li>
                        <li class="row">
                            <div class="row flex-row d-flex">
                                <div class="col-6"><strong>Services</strong></div>
                                <div class="col-6"><strong>Cost</strong></div>
                            </div>
                            @foreach($services as $key=>$service)
                                <div class="row flex-row d-flex">
                                    <div class="col-6">{{$service==null? ' -':$service}}</div>
                                    <div class="col-6">{{$cost[$key]==null? ' -': $cost[$key]}}</div>
                                </div>
                            @endforeach
                        </li>
                        <li class="row" hidden>
                            <div class="col-6"><strong>Total Cost</strong></div>
                            <div style="color:red;"><strong>{{array_sum($cost)}}</strong></div>
                        </li>

                        @if($dev->AdditionalInfo!=null)
                        <li class="row">
                            <div>
                            <strong>Additional Info</strong>: {{$dev->AdditionalInfo}}
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
{{--            </div>--}}

{{--            <div class="col-lg-4">--}}
                <div class="portfolio-info">
                    <h3>For more info and reservation contact:</h3>
                    <ul class="list-unstyled contact-info">
                        @foreach($coords as $coord)
                            @if($coord->staff) <li class="text-center" style="background-color: #A7DA30">Lab Staff </li>
                            @else <li class="text-center" style="background-color: #A7DA30">Lab Person</li>
                            @endif
                        <div class="flex-row d-flex">
                            <li class="text-center col-4"> <i style="font-size: larger">{{$coord->name}}</i></li>
                            <li class="text-center col-4"> <i style="font-size: larger">{{$coord->mail}}</i></li>
                            <li class="text-center col-4"> <i style="font-size: larger">{{$coord->telephone}}</i></li>
                        </div>
                        @endforeach
                        @if(count($fac_coor)>=1 and $fac_coor[0]->name!=null)
                            {{--                        @empty($coords) --}}
                            <li class="text-center" style="background-color: #A7DA30">Faculty Coordinator</li>
                            <div class="flex-row d-flex">
                            <li class="text-center col-4"> <i style="font-size: larger">{{$fac_coor[0]->name}}</i></li>
                            <li class="text-center col-4"> <i style="font-size: larger">{{$fac_coor[0]->email}}</i></li>
                            <li class="text-center col-4"> <i style="font-size: larger">0{{$fac_coor[0]->phone}}</i></li>
                            </div>{{--                        @endempty--}}
                        @endif
                        @if(count($uni_coor)>=1)
                            <li class="text-center" style="background-color: #A7DA30">University Coordinator</li>
                            <div class="flex-row d-flex">
                            <li class="text-center col-4"> <i style="font-size: larger">{{$uni_coor[0]->name}}</i></li>
                            <li class="text-center col-4"> <i style="font-size: larger">{{$uni_coor[0]->email}}</i></li>
                            <li class="text-center col-4"> <i style="font-size: larger">0{{$uni_coor[0]->phone}}</i></li>
                            </div>
                        @endif
                    </ul>
                </div>
            </div>

        </div>

      </div>
    </section><!-- End Portfolio Details Section -->

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
