<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>National Bank of Scientific Laboratories and Equipment</title>
    <meta content="National Bank of Laboratories and equipment" name="description">
    <meta content="National Bank of Laboratories and equipment" name="keywords">

    <!-- Favicons -->
{{--    <link href="{{asset('icons/favicon.ico')}}" rel="icon">--}}
{{--    <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">--}}
{{--    <link rel="icon" type="jpeg" href="{{asset('icons/nblse.jpeg')}}">--}}
{{--    <link rel="icon" href="{{asset('icons/favicon.png')}}" type="image/png" />--}}
{{--    <link rel="shortcut icon" href="{{asset('icons/favicon.ico')}}" />--}}
    <link rel="icon" href="{{asset('icons/2.ico?')}}" type="image/x-icon">
    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('assets/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style2.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/fonts.css')}}">
   
   

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"  crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>--}}

<!-- =======================================================
    * Template Name: Moderna - v4.3.0
    * Template URL: https://bootstrapmade.com/free-bootstrap-template-corporate-moderna/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
{{--Visitor count--}}
    @if(Route::currentRouteName() === 'home' or Route::currentRouteName() === 'homepage')
    <script defer src="{{asset('js/counter.js')}}"></script>
    @endif
</head>

<body>
<!-- ======= Header ======= -->
<header id="header" class="fixed-top d-flex align-items-center ">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="/"><img src="{{asset('2.png')}}"  alt=""></a>
        </div>
        <nav id="navbar" class="navbar">
            <ul>
                @guest()
                <li><a href="/">Home</a></li>
                @endguest
                @if (Route::has('register'))
                @auth()
                <li><a href="/indexHomepage">Home</a></li>
                @endauth
                @endif
                <li class="dropdown"><a href="/about">About Us <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="/about">National Bank History</a></li>
                        <li><a href="/vm">Vision and Mission</a></li>
                        <li><a href="/strategy">Strategies</a></li>
                        <li><a href="/services">Services</a></li>
                        <li><a href="/obj">Objectives</a></li>
                    </ul>
                </li>
                <li><a href="{{route('browse')}}">Universities</a></li>
                <li><a href="{{route('institutions')}}">Institutes</a></li>
                <li><a href="/contact">Contact Us</a></li>
                @guest()
                    <li><a href="/login">Login</a></li>
                    <li><a href="{{route('register')}}">Register</a></li>
                @endguest
                @auth()
                @hasrole('visitor')
                    <li class="dropdown"><a href="#" style="color: #A7DA30">{{Auth()->user()->username}} <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/uniHome">Reservations</a></li>
                            <li><a href="{{route('logout')}}">Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li class="dropdown"><a href="#" style="color: #A7DA30">{{Auth()->user()->username}} <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="/uniHome">Dashboard</a></li>
                            <li><a href="{{route('logout')}}">Logout</a></li>
                        </ul>
                    </li>
                @endhasrole
                @endauth
                <li class="ml-4">
                    <form  action="{{route('university.search','All')}}" method="get" >
                        @csrf
                        <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search" value="{{request('search')}}" aria-label="Search">
                    </form>
                </li>
            </ul>
{{--            <i class="bi bi-list mobile-nav-toggle"></i>--}}
        </nav><!-- .navbar -->
        <div class="logo">
            <a><img src="{{asset('scu.png')}}" alt=""></a>
        </div>
    </div>
</header><!-- End Header -->

@yield('tmplt-contnt')

<!-- ======= Footer ======= -->
<footer id="footer" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">

    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="/">Home</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/about">National Bank History</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/services">Services</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 footer-contact">
                    <h4>Contact Us</h4>
                    <p>
                        The Supreme Council of Universities , Cairo University Campus , 3<sup>rd</sup> Floor - Giza -Egypt
                        <br><br>
                        <strong>Tel:</strong> +2 02 37742346<br>
                        <strong>Fax:</strong> +2 02 35706471<br>
                        <strong>Email:</strong> nbsle@scu.eg<br>
                    </p>
                </div>
                <div class="col-lg-4 col-md-6 footer-info">
                    <h3>About Us</h3>
                    <p> The national bank for scientific laboratories and equipment (NBLSE) has been established by the decision of SCU in 2015. NBSLE aims to:</p>
                    <ul>
                        <li class="d-flex"><i class="bx bx-chevron-right"></i><p> Create up-to-date information system for the scientific laboratories and equipment in the Egyptian universities.</p></li>
                        <li class="d-flex"><i class="bx bx-chevron-right"></i><p> Enable the researchers to inquire the system to get the needed information about the scientific laboratories and equipment to facilitate the device using, procurement, and maintenance operations.</p></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if(Route::currentRouteName() === 'home' or Route::currentRouteName() === 'homepage')

        <div id="visitorCount" class="row visitorCount">
            <div class="col-4"></div>
            <div class="col-4">
                <div>Website visit count:</div>
                <div class="website-counter"></div>
            </div>
            <div class="col-4"></div>
        </div>
        @endif
        <div class="copyright">
            &copy; Copyright <strong><span>National Bank of Laboratories - SCU</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/free-bootstrap-template-corporate-moderna/ -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>

    </div>
</footer><!-- End Footer -->

{{--<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>