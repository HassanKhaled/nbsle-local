<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"  crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <title> National Bank For Scientific Laboratories and Equipment </title>
        <script defer src="/public/css/font-awesome.min.css"></script>
    </head>
    <style>
        .dropdown:hover .dropdown-menu{
           display: block;
       }
       .dropdown-menu{
            margin-top: 0;
        }
       .nav-link:hover,.dropdown-item:hover{
           /*background-color: #0068AB;*/
           background-color: #97D45F;
           border-radius: 10px;
       }
       a{
           font-size:20px ;
           font-family: Helvetica, Arial, sans-serif;
       }
    </style>
    <script>
        $(document).ready(function(){
            $(".dropdown").hover(function(){
                var dropdownMenu = $(this).children(".dropdown-menu");
                if(dropdownMenu.is(":visible")){
                    dropdownMenu.parent().toggleClass("open");
                }
            });
        });
    </script>
    <div id="header" class="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top w-100 rounded">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-center" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto justify-content-around w-100">
                <li class="nav-item dropdown">
                    <a class="nav-link text-white"  href="/" id="homeDropdown" role="button"  aria-haspopup="true" aria-expanded="false">Home</a>
                    <div class="dropdown-menu" id="home-nav" aria-labelledby="homeDropdown">
                        <a class="dropdown-item" href="{{route('about','history')}}">About Us</a>
                        <a class="dropdown-item" href="{{route('about','VisionAndMission')}}">Vision and Mission</a>
                        <a class="dropdown-item" href="{{route('about','strategy')}}">Strategy</a>
                        <a class="dropdown-item" href="{{route('about','services')}}">Services</a>
                        <a class="dropdown-item" href="{{route('about','objective')}}">Objectives</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Universities</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
{{--                        <a class="dropdown-item" href="{{route('university.search','All' )}}">All</a>--}}
                        <a class="dropdown-item" href="{{route('university.search','public' )}}">Public</a>
                        <a class="dropdown-item" href="{{route('university.search','private' )}}">Private</a>
                        <a class="dropdown-item" href="{{route('university.search','ahli' )}}">Ahli</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/browse">Institutions</a>
                </li>
                @auth()
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('userworkshop') }}">Workshop</a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link text-white" href='#'> Contact Us</a>
                </li>
                @guest()
                <li class="nav-item" >
                    <a class="nav-link text-white" href="/login">Login</a>
                </li>
                @endguest
                @auth()
                    <li class="nav-item" >
                        <form class="nav-link" id="logout-form" action="{{ url('logout') }}" method="POST">
                            {{ csrf_field() }}
                            <a class=" text-white" href="#" onclick="document.getElementById('logout-form').submit()">Logout</a>
                        </form>
{{--                        <a class="nav-link text-white" href="{{route('logout')}}">Logout</a>--}}
                    </li>
                @endauth
                <form class="form-inline my-2 my-lg-0" action="{{route('university.search','All')}}" method="get" >
                    @csrf
                    <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search" value="{{request('search')}}" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </ul>
        </div>
    </nav>
        <div id="banner" class="banner" >
            <img src="{{asset('banner_728x90.gif')}}" alt="NRL Supreme Counsil of Universites" style="width: 100%;">
        </div>
    </div>
    <body>
        @yield('content')
        <div id="div-spacer" style="height: 50px;"></div>
        <br><br>
    </body>
    <footer class="container-fluid fixed-bottom bg-dark text-light sm:items-center">
        <div >
{{--            <div class="w-50 m-auto text-center "><h4>Visitors: 5311</h4></div>--}}
            <div class="w-50 m-auto text-center "><h5>© CopyRight @ SCU-MIS for 2021</h5></div>
        </div>
    </footer>
</html>

