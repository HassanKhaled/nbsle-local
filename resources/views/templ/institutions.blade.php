@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

        <!-- ======= Our Portfolio Section ======= -->
        <section class="breadcrumbs bg-color shadow-lg">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Institutes</h2>
                </div>

            </div>
        </section><!-- End Our Portfolio Section -->

        <!-- ======= Portfolio Section ======= -->
       <section class="portfolio py-5">
    <div class="container">
        <div class="row g-4" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
            @foreach($unis as $u => $uni)
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm border-0 h-100 text-center p-3 hover-card">
                        <a href="{{ route('browseuniversity', [$uni->id, $uni->name]) }}">
                            <img src="{{ asset($uni->ImagePath) }}" 
                                 class="card-img-top img-fluid mx-auto d-block rounded" 
                                 style="width: 180px; height: 180px; object-fit: cover;" 
                                 alt="{{ $uni->name }}">
                        </a>
                        <div class="card-body">
                            <h6 class="card-title mb-0">
                                <a href="{{ route('browseuniversity', [$uni->id, $uni->name]) }}" 
                                   class="text-decoration-none text-dark">
                                   {{ $uni->name }}
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    /* Optional hover effect */
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
</style><!-- End Portfolio Section -->
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

