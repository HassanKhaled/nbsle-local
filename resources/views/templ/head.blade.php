<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>National Bank of Scientific Laboratories and Equipment</title>
    <meta content="National Bank of Laboratories and equipment" name="description">
    <meta content="National Bank of Laboratories and equipment" name="keywords">

    <link rel="icon" href="{{asset('icons/2.ico?')}}" type="image/x-icon">  
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Template Main CSS File -->

    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style2.css')}}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}"> -->
    <link rel="stylesheet" href="{{asset('assets/css/fonts.css')}}">
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

{{--Visitor count--}}
    @if(Route::currentRouteName() === 'home' or Route::currentRouteName() === 'homepage')
    <script defer src="{{asset('js/counter.js')}}"></script>
    @endif
    <style>


/* Mobile Screen */
@media (max-width: 768px) {
    .nav-logo {
         height: 70px;     /* smaller in mobile */
    }
}

/* Extra small screens */
@media (max-width: 480px) {
    .nav-logo {
        height: 70px;
    }


/* Navbar background & spacing improvements */


.navbar-nav .nav-link {
    padding: 10px 15px;
    font-weight: 500;
}

.navbar-nav .nav-link:hover {
    color: #A7DA30; /* your highlight color */
}
.navbar-toggler:hover{
    border: white;
}
}
</style>
</head>
<body>
<!-- ======= Header ======= -->

<header id="header" class="fixed-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg w-100">
            <!-- Left Logo -->
            <a class="navbar-brand" href="/">
                <img src="{{ asset('2.png') }}" alt="" class="nav-logo">
            </a>

            <!-- Toggle Button (Mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse " id="mainNavbar">
                <ul class="navbar-nav ms-auto text-white">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    @endguest
                    @if (Route::has('register'))
                        @auth
                            <li class="nav-item"><a class="nav-link" href="/indexHomepage">Home</a></li>
                        @endauth
                    @endif

                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/about" id="aboutDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            About Us
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="/about">National Bank History</a></li>
                            <li><a class="dropdown-item" href="/vm">Vision and Mission</a></li>
                            <li><a class="dropdown-item" href="/strategy">Strategies</a></li>
                            <li><a class="dropdown-item" href="/services">Services</a></li>
                            <li><a class="dropdown-item" href="/obj">Objectives</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('browse') }}">Universities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('institutions') }}">Institutes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact Us</a></li>

                    @guest
                        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endguest

                    @auth
                        @hasrole('visitor')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="visitorDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false" style="color: #A7DA30">
                                    {{ Auth()->user()->username }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="visitorDropdown">
                                    <li><a class="dropdown-item" href="/uniHome">Reservations</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false" style="color: #A7DA30">
                                    {{ Auth()->user()->username }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="/uniHome">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                                </ul>
                            </li>
                        @endhasrole
                    @endauth

                    <!-- Search -->
                    <li class="nav-item ms-3">
                        <form action="{{ route('university.search', 'All') }}" method="get" class="d-flex">
                            @csrf
                            <input class="form-control me-2" type="text" name="search" placeholder="Search"
                                value="{{ request('search') }}" aria-label="Search">
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Right Logo -->
            <a class="navbar-brand ms-3 scu">
                <img src="{{ asset('scu.png') }}" alt="" class="nav-logo">
            </a>
        </nav>
    </div>
</header>

@yield('tmplt-contnt')

<!-- ======= Footer ======= -->
<footer id="footer">

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
    </div>
</footer><!-- End Footer -->
</body>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/purecounter/purecounter.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
</html>