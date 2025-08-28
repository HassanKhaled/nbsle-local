@extends('templ.head')
@section('tmplt-contnt')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <main id="main">

    

    <!-- ======= Our Portfolio Section ======= -->
    <section class="breadcrumbs bg-white shadow-lg">
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



<!-- <section class="position-relative py-5">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(71, 204, 38, 0.15); z-index: 0;"></div>

  <div class="container position-relative" style="z-index: 1;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-header bg-success text-white text-center rounded-top-4">
            <h5 class="mb-0 py-2">Reservation</h5>
          </div>
          <div class="card-body p-4">
            @include('templ.flash-message')

            <form method="POST" action="{{ url('/booking/store') }}" enctype="multipart/form-data">
              @csrf

              <input type="hidden" name="device_id" value="{{ $dev->id }}">
              <input type="hidden" name="user_id" value="{{ Auth()->user()->id }}">
              <input type="hidden" name="lab_id" value="{{ $lab->id }}">
              <input type="hidden" name="fac_id" value="{{ $facID }}">
              <input type="hidden" name="uni_id" value="{{ $uni_id }}">

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label">Your Name</label>
                  <input type="text" class="form-control" value="{{ Auth()->user()->username }}" disabled>
                </div>
                <div class="col-md-6">
                  <label for="booking-phone" class="form-label">Phone</label>
                  <input type="text" id="booking-phone" name="visitor_phone" class="form-control" required>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="booking-date" class="form-label">Reservation Date</label>
                  <input type="text" id="booking-date" name="date" class="form-control" placeholder="Select date" required>
                </div>
                <div class="col-md-6">
                  <label for="booking-time" class="form-label">Reservation Time</label>
                  <input type="text" id="booking-time" name="time" class="form-control" placeholder="Select time" required>
                </div>
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label for="booking-service" class="form-label">Service</label>
                  <select id="booking-service" name="service_id" class="form-select" required>
                    <option value="">Select Service</option>
                    @foreach($services as $service)
                      <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="booking-sample" class="form-label">Number of Samples</label>
                  <input type="number" id="booking-sample" name="samples" class="form-control" min="1" required>
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg rounded-pill">Book Reservation</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->

    
  <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row justify-content-center ">
                <div class="col-lg-8 text-center">
                    <div class="bg-success text-white p-4 rounded-top-4 shadow-sm">
                        <i class="fas fa-calendar-check fs-1 mb-3"></i>
                        <h2 class="fw-bold mb-2">Laboratory Equipment Reservation</h2>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-bottom-4">
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="{{ url('/booking/store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                
                                <!-- Hidden Fields -->
                                <input type="hidden" name="device_id" value="{{ $dev->id }}">
                                <input type="hidden" name="user_id" value="{{ Auth()->user()->id }}">
                                <input type="hidden" name="lab_id" value="{{ $lab->id }}">
                                <input type="hidden" name="fac_id" value="{{ $facID }}">
                                <input type="hidden" name="uni_id" value="{{ $uni_id }}">
                                <input type="hidden" name="central" value="{{ $central }}">

                                <!-- Personal Information -->
                                <div class="mb-5">
                                    <h5 class="text-success fw-bold mb-4 border-bottom border-success pb-2">
                                        <i class="fas fa-user-circle me-2"></i>Personal Information
                                    </h5>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark">
                                                <i class="fas fa-user text-success me-1"></i>Your Name
                                            </label>
                                            <input type="text" class="form-control form-control-lg bg-light border-2" 
                                                   value="{{ Auth()->user()->username }}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="booking-phone" class="form-label fw-semibold text-dark">
                                                <i class="fas fa-phone text-success me-1"></i>Phone Number *
                                            </label>
                                            <input type="tel" id="booking-phone" name="visitor_phone" 
                                                   class="form-control form-control-lg border-2" 
                                                   placeholder="Enter your phone number" required>
                                            <div class="invalid-feedback">
                                                Please provide a valid phone number.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="mb-5">
                                    <h5 class="text-success fw-bold mb-4 border-bottom border-success pb-2">
                                        <i class="fas fa-calendar-alt me-2"></i>Booking Schedule
                                    </h5>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="booking-date" class="form-label fw-semibold text-dark">
                                                <i class="fas fa-calendar text-success me-1"></i>Reservation Date *
                                            </label>
                                            <input type="date" id="booking-date" name="date" 
                                                   class="form-control form-control-lg border-2" required>
                                            <div class="invalid-feedback">
                                                Please select a reservation date.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="booking-time" class="form-label fw-semibold text-dark">
                                                <i class="fas fa-clock text-success me-1"></i>Reservation Time *
                                            </label>
                                            <input type="time" id="booking-time" name="time" 
                                                   class="form-control form-control-lg border-2" required>
                                            <div class="invalid-feedback">
                                                Please select a reservation time.
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>

                                <!-- Service Information -->
                                <div class="mb-5">
                                    <h5 class="text-success fw-bold mb-4 border-bottom border-success pb-2">
                                        <i class="fas fa-flask me-2"></i>Service Details
                                    </h5>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="booking-service" class="form-label fw-semibold text-dark">
                                                <i class="fas fa-cogs text-success me-1"></i>Service Type *
                                            </label>
                                            <select id="booking-service" name="service_id" 
                                                    class="form-select form-select-lg border-2" required>
                                                <option value="">Choose a service...</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a service type.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="booking-sample" class="form-label fw-semibold text-dark">
                                                <i class="fas fa-vials text-success me-1"></i>Number of Samples *
                                            </label>
                                            <input type="number" id="booking-sample" name="samples" 
                                                   class="form-control form-control-lg border-2" 
                                                   min="1" max="50" placeholder="Enter number of samples" required>
                                            <div class="invalid-feedback">
                                                Please enter a valid number of samples (1-50).
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>


                               

                                <!-- Submit Button -->
                                <div class="d-grid gap-3">
                                    <button type="submit" class="btn btn-success btn-lg rounded-pill py-3 shadow-sm">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        <span class="fw-bold">CONFIRM RESERVATION</span>
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg rounded-pill py-2">
                                        <i class="fas fa-undo me-2"></i>Clear Form
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

  

  </main><!-- End #main -->



@endsection
