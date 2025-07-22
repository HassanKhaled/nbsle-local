<!DOCTYPE html>
<html>
    <head>
{{--        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>--}}
{{--        <link rel="shortcut icon" href="http://sstatic.net/so/favicon.ico">--}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"  crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <title> National Bank For Scientific Laboratories and Equipment </title>
{{--        <script defer src="/public/css/font-awesome.min.css"></script>--}}
    </head>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        a{
            font-size:20px ;
            font-family: Helvetica, Arial, sans-serif;
        }
        .nav-link:hover,.dropdown-item:hover{
            /*background-color: #0068AB;*/
            background-color: #97D45F;
            border-radius: 10px;
        }
        .active{border-radius: 10px;background-color: #97D45F;}
    </style>
    <div>
        <div class="row ">
            <div id="banner" class="banner" >
                <img src="{{asset('banner_728x90.gif')}}" alt="NRL Supreme Counsil of Universites" style="width: 100%;">
            </div>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-md-center" id="navbarNav">
                    <ul class="navbar-nav justify-content-around w-100 px-2">
                        <li class="nav-item active">
                            <a class="nav-link" href="/uniHome"><img src="{{asset('home-solid.svg')}}" style="width:40px;">Home <span class="sr-only"></span></a>
                        </li>
                        {{--                        {{dd(auth()->user()->role()->first()->permissions)}}--}}
                        @can('user')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Users.index') }}" id="uni"><img src="{{asset('users.svg')}}" style="width:40px;">Manage Users</a>
                            </li>
                        @endcan
                        @can('university')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Universitys.index') }}" id="uni"><img src="{{asset('university.svg')}}" style="width:40px;">Manage University</a>
                            </li>
                        @endcan
                        @can('faculty')
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('FacUni.index')}}" id="fac"><img src="{{asset('faculty.svg')}}" style="width:40px;"> Manage Faculty</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('UniLab.index')}}" id="uni_lab"><img src="{{asset('vials.svg')}}" style="width:40px;">Central Labs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('UniDevice.index')}}" id="uni_device"><img src="{{asset('microscope-solid.svg')}}" style="width:40px;">Central Devices</a>
                            </li>
                        @endcan
                        @can('department')
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('DeptFac.index')}}" id="dept"><img src="{{asset('dept.png')}}" style="width:40px;">Manage Departments</a>
                            </li>
                        @endcan
                        @can('lab')
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('Lab.index')}}" id="dept"><img src="{{asset('vials.svg')}}" style="width:40px;">Manage Labs</a>
                            </li>
                        @endcan
                        @can('device')
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('DeviceLab.index')}}" id="device"><img src="{{asset('microscope-solid.svg')}}" style="width:40px;">Manage Devices</a>
                            </li>
                        @endcan
{{--                    <li class="nav-item ">--}}
{{--                        <form class="nav-link" id="logout-form" action="{{ url('logout') }}" method="POST">--}}
{{--                            {{ csrf_field() }}--}}
{{--                            <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit()"><img src="{{asset('sign-out-alt-solid.svg')}}" style="width:40px;">Logout</a>--}}
{{--                            <button type="submit" ><img src="{{asset('sign-out-alt-solid.svg')}}" style="width:40px;">Logout</button>--}}
{{--                        </form>--}}
{{--                        <a class="nav-link" href="{{route('logout')}}"><img src="{{asset('sign-out-alt-solid.svg')}}" style="width:40px;">Logout</a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="#"><img src="{{asset('user-regular.svg')}}" style="width:30px;"> {{Auth::user()->name}}</a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="#" id="uppass">Change Password</a>--}}
{{--                    </li>--}}
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{asset('user-regular.svg')}}" style="width:30px;">{{Auth::user()->name}}</a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <form class="nav-link" id="logout-form" action="{{ url('logout') }}" method="POST">
                                    {{ csrf_field() }}
                                    <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit()"><img src="{{asset('sign-out-alt-solid.svg')}}" style="width:40px;">Logout</a>
                                </form>
                                <a class="dropdown-item" href="{{route('Users.show','1')}}">Change Password</a>
{{--                                <a class="dropdown-item" href="{{route('logout')}}"><img src="{{asset('sign-out-alt-solid.svg')}}" style="width:40px;">Logout</a>--}}
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <body>
        <div class="container mt-2 w-75"  >
            @yield('loggedContent')
        </div>
        <div id="div-spacer" style="height: 50px;"></div>
        <br><br>
    </body>
    <script>
        $(document).ready(function(){
            $(".dropdown").hover(function(){
                var dropdownMenu = $(this).children(".dropdown-menu");
                if(dropdownMenu.is(":visible")){
                    dropdownMenu.parent().toggleClass("open");
                }
            });
            $('.nav-item').click(function (){
                // alert(this.classList);
            //     $(".nav-item .active").removeClass('active');
            //     this.addClass('active');

                $('.nav-item.active').removeClass('active');
                $(this).addClass('active');
            });
            // $('.nav-list').on('click', 'li', function() {
            //     $('.nav-list li.active').removeClass('active');
            //     $(this).addClass('active');
            // });
        });
    </script>
</html>
