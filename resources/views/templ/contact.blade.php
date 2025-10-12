@extends('templ.head')
@section('tmplt-contnt')
  <main id="main">

    <!-- ======= Contact Section ======= -->
    <section class="breadcrumbs bg-color shadow-lg">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Contact Us</h2>
        </div>

      </div>
    </section><!-- End Contact Section -->

    <!-- ======= Contact Section ======= -->
    <section class="contact" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
      <div class="container">

        <div class="row">

          <div class="col-lg-6">

            <div class="row">
              <div class="col-md-12">
                <div class="info-box">
                  <i class="bx bx-map"></i>
                  <h3>Our Address</h3>
                  <p>The Supreme Council of Universities
                      <br>,Cairo University Campus ,3<sup>rd</sup> Floor - Giza - Egypt</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box">
                  <i class="bx bx-envelope"></i>
                  <h3>Email Us</h3>
                  <p>nbsle@scu.eg<br><br></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box">
                  <i class="bx bx-phone-call"></i>
                  <h3>Call Us</h3>
                  <p>
                      Tel: +2 02 37742346
                      <br>
                      Fax: +2 02 35706471
                  </p>
                </div>
              </div>
            </div>

          </div>
            <!-- ======= Map Section ======= -->
          <div class="col-lg-6">
              <section class="map mt-2">
                  <div class="container-fluid p-0">
                      <iframe src="https://maps.google.com/maps?q=Supreme%20Council%20of%20Universities&t=&z=15&ie=UTF8&iwloc=&output=embed" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                  </div>
              </section>
          </div>
            <!-- End Map Section -->
        </div>
      </div>
    </section><!-- End Contact Section -->
  </main><!-- End #main -->


  <!-- Vendor JS Files -->
  {{--<script src="{{asset('assets/vendor/aos/aos.js')}}"></script>--}}
  {{--<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
  {{--<script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>--}}
  {{--<script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}'"></script>--}}
  {{--<script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>--}}
  {{--<script src="{{asset('assets/vendor/purecounter/purecounter.js')}}'"></script>--}}
  {{--<script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>--}}
  {{--<script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}'"></script>--}}

  {{--<!-- Template Main JS File -->--}}
  {{--<script src="{{asset('assets/js/main.js')}}"></script>--}}


  <script src="assets/vendor/aos/aos.js"></script>
  <!-- <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  @endsection
