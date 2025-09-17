@extends('templ.head')
@section('tmplt-contnt')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <main id="main">

    <!-- ======= Our Portfolio Section ======= -->
    <section class="breadcrumbs bg-white shadow-lg">
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

<section id="portfolio-details" class="portfolio-details">
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-dark mb-2">Laboratory Equipment Details</h2>
                <p class="lead text-muted">Complete information about this device</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Device Image -->
            <div class="col-lg-5 h-25">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-header bg-dark text-white text-center py-3">
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
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg border-0 rounded-3 mt-4">
                    <div class="card-body">
                       <div class="d-flex justify-content-center gap-4 mb-4">
    <!-- Reservations Card -->
    <div class="text-center p-3">
        <i class="fas fa-calendar-check text-primary fs-3 mb-2"></i>
        <h6 class="mb-0">Reservations</h6>
        <strong class="fs-5 text-dark">{{ $reservationCount }}</strong>
    </div>

    <!-- Views Card -->
    <div class="text-center p-3">
        <i class="fas fa-eye text-success fs-3 mb-2"></i>
        <h6 class="mb-0">Views</h6>
        <strong class="fs-5 text-dark">{{ $dev->views }}</strong>
    </div>
</div>
                        {{-- Main overall rating (service_quality) --}}
                        <div class="text-center mb-5">
                            <h5 class="fw-bold">{{ __("ratings.service_quality") }}</h5>
                            <div class="d-flex justify-content-center align-items-center mt-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($averages['service_quality']))
                                        <i class="bi bi-star-fill text-warning fs-4"></i>
                                    @elseif ($i - $averages['service_quality'] < 1)
                                        <i class="bi bi-star-half text-warning fs-4"></i>
                                    @else
                                        <i class="bi bi-star text-warning fs-4"></i>
                                    @endif
                                @endfor
                                <span class="ms-2 fs-5 text-muted">{{ $averages['service_quality'] }}/5</span>
                            </div>
                        </div>

                        {{-- Remaining ratings --}}
                        <div class="row">
                            @foreach ($averages as $field => $value)
                                @if ($field !== 'service_quality')
                                    <div class="col-md-6 mb-3">
                                        <div class="p-3 border rounded bg-light">
                                            <strong>{{ __("ratings.$field") }}</strong>
                                            <div class="d-flex align-items-center mt-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($value))
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @elseif ($i - $value < 1)
                                                        <i class="bi bi-star-half text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 text-muted">{{ $value }}/5</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Device Information -->
            <div class="col-lg-7">
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
                                                        {{$cost[$key]}} EGP
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
                    <div class="card-header bg-secondary text-white py-3">
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
  <!-- <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script> -->
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}'"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter.js')}}'"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}'"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>
@endsection
