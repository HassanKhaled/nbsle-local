@extends('templ.head')
@section('tmplt-contnt')
  <main id="main">

    <!-- ======= Our Services Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Services</h2>
        </div>

      </div>
    </section><!-- End Our Services Section -->

      <section class="about" data-aos="fade-up">
          <div class="container">
              <div class="row">
                  <div class="col-lg-6">
                      <img src="{{asset('website-design-and-development-services.png')}}" class="img-fluid" alt="">
                  </div>
                  <div class="col-lg-6 pt-4 pt-lg-0">
                      <ul>
                          <li><i class="bi bi-check2-circle"></i>Supervising and following up the registration process in the National Bank database with the data of laboratories and scientific devices.</li>
                          <li><i class="bi bi-check2-circle"></i>Providing contact information of the laboratories' technicians in the Egyptian universities for Egyptian researchers and industrial partners.</li>
                          <li><i class="bi bi-check2-circle"></i>Provide the required data for Egyptian researchers and industrial partners with contact details.</li>
                          <li><i class="bi bi-check2-circle"></i>Provide statistics data about scientific laboratories and equipment in Egyptian universities.</li>

                      </ul>
                  </div>
              </div>

          </div>
      </section><!-- End About Section -->
    <!-- ======= Service Details Section ======= -->
{{--    <section class="service-details">--}}
{{--      <div class="container">--}}
{{--        <div class="row">--}}
{{--          <div class="col-md-6 d-flex align-items-stretch" data-aos="fade-up">--}}
{{--            <div class="card">--}}
{{--              <div class="card-img">--}}
{{--                <img src="{{asset('img/service-details-1.jpg')}}" alt="...">--}}
{{--              </div>--}}
{{--              <div class="card-body">--}}
{{--                <h5 class="card-title"><a href="#">Services</a></h5>--}}
{{--                <p class="card-text">Provide the required data for Egyptian researchers and industrial partners with contact details.</p>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}

{{--        </div>--}}

{{--      </div>--}}
{{--    </section><!-- End Service Details Section -->--}}

  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
@endsection
