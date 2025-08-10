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



<section class="position-relative py-5">
  <!-- Background Overlay -->
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

              <!-- Hidden Fields -->
              <input type="hidden" name="device_id" value="{{ $dev->id }}">
              <input type="hidden" name="user_id" value="{{ Auth()->user()->id }}">
              <input type="hidden" name="lab_id" value="{{ $lab->id }}">
              <input type="hidden" name="fac_id" value="{{ $facID }}">
              <input type="hidden" name="uni_id" value="{{ $uni_id }}">

              <!-- Name & Phone -->
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

              <!-- Date & Time -->
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

              <!-- Services & Samples -->
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

              <!-- Submit -->
              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg rounded-pill">Book Reservation</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

   










    


  

  </main><!-- End #main -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
  flatpickr("#booking-date", {
    dateFormat: "Y-m-d",
    minDate: "today",
    disableMobile: true
  });

  flatpickr("#booking-time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i:S",
    time_24hr: false,
    disableMobile: true
  });
</script>

  <!-- Vendor JS Files -->
  <!-- <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}'"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter.js')}}'"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}'"></script> -->

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>
  
  <script src="{{asset('assets/js/core.min.js')}}"></script>
  <script src="{{asset('assets/js/script.js')}}"></script>


@endsection
