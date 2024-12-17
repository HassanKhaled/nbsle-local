@extends('templ.head')
@section('tmplt-contnt')

    <main id="main">

        <!-- ======= About Us Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Strategies</h2>
                </div>

            </div>
        </section><!-- End About Us Section -->

        <!-- ======= About Section ======= -->
        <section class="about" data-aos="fade-up">
            <div class="container">

                <div class="row">
                    <div class="col-lg-6">
                        <img src="{{asset('img/features-1.svg')}}" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0">
                        {{--            <h3>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>--}}
                        {{--            <p class="fst-italic">--}}
                        {{--              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore--}}
                        {{--              magna aliqua.--}}
                        {{--            </p>--}}
                        <ul>
                            <li class="flex-row d-flex"><i class="bi bi-check2-circle"></i><p>Establishing a data base platform serving the academic and external societies for the development of the scientific research state.</p></li>
                            <li class="flex-row d-flex"><i class="bi bi-check2-circle"></i><p>Aiding the scientific laboratories in the qualification process</p></li>
                            <li class="flex-row d-flex"><i class="bi bi-check2-circle"></i><p>Assisting and guiding researchers about the location of scientific devices in Egyptian universities and official staff and technicians in laboratories.</p></li>
                            <li class="flex-row d-flex"><i class="bi bi-check2-circle"></i><p>Work on training technical personnel on the use of scientific equipment.</p></li>
                            <li class="flex-row d-flex"><i class="bi bi-check2-circle"></i><p>Establishing central maintenance centers and preparing the technical cadres necessary to operate them.</p></li>
                            <li class="flex-row d-flex"><i class="bi bi-check2-circle"></i><p>Work on scientific marketing for scientific devices</p></li>
                        </ul>
                    </div>
                </div>

            </div>
        </section><!-- End About Section -->

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
