@extends('templ.head')
@section('tmplt-contnt')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <main id="main">

    <!-- ======= Our Portfolio Section ======= -->
    <section class="breadcrumbs">
      <div class="container">
      @include('templ.flash-message')
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

                        <li class="row">
                            <div class="col-md-12 bg-light text-right">
                                <a href="{{route('reservation',[$dev->id,$dev->lab_id,$central,$uni_id,$uniname])}}"><input type="button" name="booking" id="booking" value="Booking" class="btn btn-round btn-warning" /> </a>
                                <!--<a hidden href="{{route('user-reservations',[$dev->id,$dev->lab_id,$central,$uni_id,$uniname])}}"><input type="button" name="cancel" id="cancel" value="Cancel" class="btn btn-round btn-success" /> </a>-->
                             </div>
                           
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
    </section>
<section id="portfolio-details" class="portfolio-details py-5">
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-primary mb-2">Laboratory Equipment Details</h2>
                <p class="lead text-muted">Complete information about this device</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Device Image -->
            <div class="col-lg-4 h-25">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-camera me-2"></i>Device Gallery
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="position-relative">
                            <img src="{{asset($dev->ImagePath)}}" alt="{{$dev->name}}" 
                                 class="img-fluid w-100 rounded-bottom" style="min-height: 300px; object-fit: cover;">
                            <div class="text-center p-3">
                                <span class="badge bg-{{$dev->state=='available'?'success':'danger'}} fs-6 px-3 py-2">
                                    <i class="fas fa-{{$dev->state=='available'?'check-circle':'times-circle'}} me-1"></i>
                                    {{ucfirst($dev->state)}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Information -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>Device Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Basic Details Row 1 -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-tag text-primary me-1"></i>Device Name
                                    </div>
                                    <div class="fw-bold text-dark">{{$dev->name}}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-cog text-primary me-1"></i>Model
                                    </div>
                                    <div class="fw-bold text-dark">{{$dev->model ?? '---'}}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-calculator text-primary me-1"></i>Units Available
                                    </div>
                                    <div class="fw-bold text-dark">{{$dev->num_units}}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Details Row 2 -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>Manufacture Year
                                    </div>
                                    <div class="fw-bold text-dark">{{$dev->ManufactureYear}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        <i class="fas fa-external-link-alt text-primary me-1"></i>Manufacturer Website
                                    </div>
                                    <div class="fw-bold">
                                        <a href="{{$dev->ManufactureWebsite}}" target="_blank" 
                                           class="text-primary text-decoration-none">
                                            <i class="fas fa-link me-1"></i>Visit Website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small mb-2">
                                    <i class="fas fa-file-alt text-primary me-1"></i>Description
                                </div>
                                <div class="text-dark">{{$dev->description}}</div>
                            </div>
                        </div>

                        <!-- Services & Costs Table -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="fas fa-list-ul me-2"></i>Services & Pricing
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-success">
                                        <tr>
                                            <th class="fw-bold">
                                                <i class="fas fa-cogs me-1"></i>Service
                                            </th>
                                            <th class="fw-bold text-end">
                                                <i class="fas fa-dollar-sign me-1"></i>Cost
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $key=>$service)
                                        <tr>
                                            <td class="fw-medium">{{$service ?? '-'}}</td>
                                            <td class="text-end">
                                                @if($cost[$key])
                                                    <span class="badge bg-info fs-6 px-3 py-2">
                                                        ${{$cost[$key]}}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        @if($dev->AdditionalInfo)
                        <div class="mb-4">
                            <div class="alert alert-info border-0">
                                <h6 class="alert-heading fw-bold">
                                    <i class="fas fa-plus-circle me-2"></i>Additional Information
                                </h6>
                                <p class="mb-0">{{$dev->AdditionalInfo}}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Booking Button -->
                        <div class="text-center">
                            <a href="{{route('reservation',[$dev->id,$dev->lab_id,$central,$uni_id,$uniname])}}" 
                               class="btn btn-warning btn-lg px-5 py-3 rounded-pill shadow-sm">
                                <i class="fas fa-calendar-plus me-2"></i>
                                <span class="fw-bold">BOOK NOW</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-address-book me-2"></i>Contact Information & Reservations
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Lab Staff/Person -->
                        @foreach($coords as $coord)
                        <div class="mb-4">
                            <div class="bg-success text-white text-center py-2 rounded-top mb-3">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-{{$coord->staff ? 'user-md' : 'user'}} me-2"></i>
                                    {{$coord->staff ? 'Lab Staff' : 'Lab Person'}}
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-user text-success fs-4 mb-2"></i>
                                        <div class="fw-bold text-dark">{{$coord->name}}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-envelope text-success fs-4 mb-2"></i>
                                        <div class="fw-bold">
                                            <a href="mailto:{{$coord->mail}}" class="text-decoration-none text-dark">
                                                {{$coord->mail}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-phone text-success fs-4 mb-2"></i>
                                        <div class="fw-bold">
                                            <a href="tel:{{$coord->telephone}}" class="text-decoration-none text-dark">
                                                {{$coord->telephone}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Faculty Coordinator -->
                        @if(count($fac_coor)>=1 && $fac_coor[0]->name)
                        <div class="mb-4">
                            <div class="bg-success text-white text-center py-2 rounded-top mb-3">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-graduation-cap me-2"></i>Faculty Coordinator
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-user text-success fs-4 mb-2"></i>
                                        <div class="fw-bold text-dark">{{$fac_coor[0]->name}}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-envelope text-success fs-4 mb-2"></i>
                                        <div class="fw-bold" style="font-size: 12px;">
                                            <a href="mailto:{{$fac_coor[0]->email}}" class="text-decoration-none text-dark">
                                                {{$fac_coor[0]->email}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-phone text-success fs-4 mb-2"></i>
                                        <div class="fw-bold">
                                            <a href="tel:0{{$fac_coor[0]->phone}}" class="text-decoration-none text-dark">
                                                0{{$fac_coor[0]->phone}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- University Coordinator -->
                        @if(count($uni_coor)>=1)
                        <div class="mb-0">
                            <div class="bg-success text-white text-center py-2 rounded-top mb-3">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-university me-2"></i>University Coordinator
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-user text-success fs-4 mb-2"></i>
                                        <div class="fw-bold text-dark">{{$uni_coor[0]->name}}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-envelope text-success fs-4 mb-2"></i>
                                        <div class="fw-bold" style="font-size: 12px;">
                                            <a href="mailto:{{$uni_coor[0]->email}}" class="text-decoration-none text-dark">
                                                {{$uni_coor[0]->email}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded bg-light">
                                        <i class="fas fa-phone text-success fs-4 mb-2"></i>
                                        <div class="fw-bold">
                                            <a href="tel:0{{$uni_coor[0]->phone}}" class="text-decoration-none text-dark">
                                                0{{$uni_coor[0]->phone}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
