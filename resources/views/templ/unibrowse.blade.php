@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

        <!-- ======= Top green Section ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>{{$uniname}}</h2>
                </div>
            </div>
        </section>
        <!-- End Top green Section -->

        <!-- ======= Portfolio Section ======= -->
        <section class="portfolio">
            <div class="container">
                {{------------------------------- central labs ----------------------------------}}
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    <div class="col-lg-3 col-md-6 portfolio-wrap p-5">
                        <div class="portfolio-item" style="background-color: white">
                            <a href="{{route('browsecentrallab',[$uni_selected, $uniname])}}">
                                <img src="{{asset('images/universities/No_Image.png')}}" class="img-fluid" style="width:200px;height:200px;" alt="">
                            </a>
                            <h6 class="text-center" href="{{route('browsecentrallab',[$uni_selected, $uniname])}}">Central Labs</h6>
                        </div>
                    </div>
                </div>
                {{---------------------------- List faculties -----------------------------------}}
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    @foreach($faculties as $u => $uni)
                        <div class="col-lg-3 col-md-6 portfolio-wrap p-5">
                            <div class="portfolio-item" style="background-color: white">
                                <a href="{{route('browsefaculty',[$uni_selected, $uniname, $uni->fac_id, $uni->name])}}">
                                    <img src="{{asset($uni->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="">
                                </a>
                                <h6 class="text-center" href="{{route('browsefaculty',[$uni_selected, $uniname, $uni->fac_id, $uni->name])}}">{{$uni->name}}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section><!-- End Portfolio Section -->
    </main>

    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>
@endsection

