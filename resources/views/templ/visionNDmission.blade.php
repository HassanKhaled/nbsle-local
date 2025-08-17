@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

        <!-- ======= Our Services Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Vision and Mission</h2>
                </div>

            </div>
        </section><!-- End Our Services Section -->



        <!-- ======= Service Details Section ======= -->
        <section class="service-details">
            <div class="container">

                <div class="row">
                    <div class="col-md-6 d-flex align-items-stretch" data-aos="fade-up">
                        <div class="card">
                            <div class="card-img">
                                <img src="{{asset('img/features-4.svg')}}" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><a href="#">Vision</a></h5>
{{--                                <p class="card-text">Determining the scientific equipment in the Egyptian Universities and making them available to researchers for scientific research development.</p>--}}
                                <p class="card-text">Looking forward to supporting scientific research and encouraging cooperation between researchers, by introducing the scientific equipment in Egyptian universities to advance scientific research and serve civil society.The unit looks forward to supporting scientific research and encouraging cooperation between researchers, by introducing the scientific equipment in Egyptian universities to advance scientific research and serve civil society.</p>
                                {{--                <div class="read-more"><a href="#"><i class="bi bi-arrow-right"></i> Read More</a></div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-stretch" data-aos="fade-up">
                        <div class="card">
                            <div class="card-img">
                                <img src="{{asset('img/features-2.svg')}}" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><a href="#">Mission</a></h5>
                                <p class="card-text">The availability and sharing of the scientific laboratories and equipment inside the Egyptian Universities in a comprehensive National Bank to support the researchers at local and regional levels.</p>
                                {{--                <div class="read-more"><a href="#"><i class="bi bi-arrow-right"></i> Read More</a></div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Service Details Section -->

    </main><!-- End #main -->

    <!-- Vendor JS Files -->
    <!-- <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/purecounter/purecounter.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

    <script src="assets/js/main.js"></script> -->
@endsection
