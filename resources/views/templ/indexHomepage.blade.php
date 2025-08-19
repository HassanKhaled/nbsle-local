@extends('templ.head')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

@section('tmplt-contnt')
    <main id="main">
        <!-- ======= Hero Section ======= -->
        <section id="hero" class="d-flex justify-content-center align-items-center">
            <div id="carouselExampleIndicators" class="carousel slide w-100 " data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100 img-fluid" src="{{asset('1b.png')}}"   alt="First slide" >
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{asset('2b-last-ed.png')}}"  alt="Second slide" >
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{asset('3b.png')}}"   alt="Third slide" >
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{asset('4b.png')}}"   alt="Forth slide" >
                    </div>
                </div>
            </div>
        </section>
        <!-- End Hero -->
<!-- Previous and Next button-->
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

        <!-- ======= Statistics Section ======= -->
        <section class="services">
            <div class="container">
                <div class="row">
                @if(Auth()->user()->hasRole('university'))    
                    <div class="col-md-6 col-lg-3 " data-aos="fade-up">
                        <div class="icon-box icon-box-pink">
                            <div class="icon"><i class="bx bxs-school"></i></div>
                            <h4 class="title">{{$stables['faculties']->count()}} </h4>
                            <h4 class="title">faculties</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>

                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 " data-aos="fade-up" data-aos-delay="100">
                        <div class="icon-box icon-box-blue">
                            <div class="icon"><i class="bx bxs-vial"></i></div>
                            <h4 class="title">{{$stables['labs']->count()}}</h4>
                            <h4 class="title">Labs</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3 " data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box icon-box-cyan">
                            <div class="icon"><i class="bx bxs-vial"></i></div>
                            <h4 class="title">{{$stables['central_labs']->count()}} </h4>
                            <h4 class="title">Central Labs</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box icon-box-cyan">
                            <div class="icon"><i class="bx bxs-plug"></i></div>
                            <h4 class="title">{{$stables['devices']->count()}} </h4>
                            <h4 class="title">Devices</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="250">
                        <div class="icon-box icon-box-green">
                            <div class="icon"><i class="bx bx-plug"></i></div>
                            <h4 class="title">{{$stables['central_devices']->count()}} </h4>
                            <h4 class="title">Central Devices</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>
                        </div>
                    </div>
                @endif
                @if(Auth()->user()->hasRole('faculty'))

                    <div class="col-md-6 col-lg-3" data-aos="fade-up">
                        <div class="icon-box icon-box-pink">
                            <div class="icon"><i class="bx bxs-vial"></i></div>
                            <h4 class="title">{{$stables['labs']->count()}}</h4>
                            <h4 class="title">Labs</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>

                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon-box icon-box-blue">
                            <div class="icon"><i class="bx bxs-plug"></i></div>
                            <h4 class="title">{{$stables['devices']->count()}}</h4>
                            <h4 class="title">Devices</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>
                        </div>
                    </div>

                 
                @endif

                @if(Auth()->user()->hasRole('department'))

                    <div class="col-md-6 col-lg-3" data-aos="fade-up">
                        <div class="icon-box icon-box-pink">
                            <div class="icon"><i class="bx bxs-vial"></i></div>
                            <h4 class="title">{{$stables['labs']->count()}}</h4>
                            <h4 class="title">Labs</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>

                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon-box icon-box-blue">
                            <div class="icon"><i class="bx bxs-plug"></i></div>
                            <h4 class="title">{{$stables['devices']->count()}}</h4>
                            <h4 class="title">Devices</h4>
                            <p class="description text-hide">quas molestias excepturi sint</p>
                        </div>
                    </div>

                 
                @endif

                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <!-- Vendor JS Files -->

    <!-- <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/purecounter/purecounter.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script> -->

    <!-- Template Main JS File -->
    <!-- <script src="assets/js/main.js"></script> -->
@endsection
