@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

        <!-- ======= Our Portfolio Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Institutes</h2>
                </div>

            </div>
        </section><!-- End Our Portfolio Section -->

        <!-- ======= Portfolio Section ======= -->
        <section class="portfolio">
            <div class="container">
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    @foreach($unis as $u => $uni)
                        <div class="col-lg-3 col-md-6 portfolio-wrap p-5">
                            <div class="portfolio-item" style="background-color: white">
                                <a href="{{route('browseuniversity',[$uni->id,$uni->name])}}">
                                    <img src="{{asset($uni->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="{{asset($uni->ImagePath)}}">
                                </a>
                                <h6 href="{{route('browseuniversity',[$uni->id, $uni->name])}}" style="text-decoration: none;color: black"><h6 class="text-center">{{$uni->name}}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section><!-- End Portfolio Section -->
    </main>

    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
    {{--    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
    <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    {{--    <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}'"></script>--}}
    {{--    <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>--}}
    {{--    <script src="{{asset('assets/vendor/purecounter/purecounter.js')}}'"></script>--}}
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    {{--    <script src="{{asset('assets/vendor/waypoints/noframework.waypoints.js')}}'"></script>--}}

    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>
    {{--    <script src="assets/vendor/aos/aos.js"></script>--}}
    {{--    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>--}}
    {{--    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>--}}
    {{--    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>--}}
    {{--    <script src="assets/vendor/php-email-form/validate.js"></script>--}}
    {{--    <script src="assets/vendor/purecounter/purecounter.js"></script>--}}
    {{--    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>--}}
    {{--    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>--}}

    {{--    <!-- Template Main JS File -->--}}
    {{--    <script src="assets/js/main.js"></script>--}}
@endsection

