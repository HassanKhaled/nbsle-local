<!DOCTYPE html>
<html lang="en">

<head>
    <title>National Bank of scientific laboratories and equipment</title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Mega Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
    <meta name="author" content="codedthemes" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('icons/2.ico?')}}" type="image/x-icon">

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
    <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap/css/bootstrap.min.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{asset('icons/font-awesome/css/font-awesome.min.css')}}">
    <!-- waves.css -->
{{--    <link rel="stylesheet" href="{{asset('pages/waves/css/waves.min.css')}}" type="text/css" media="all">--}}
    <!-- Required Fremwork -->
    <!-- waves.css -->
{{--    <link rel="stylesheet" href="{{asset('pages/waves/css/waves.min.css')}}" type="text/css" media="all">--}}
    <!-- scrollbar.css -->
{{--    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.mCustomScrollbar.css')}}">--}}

    <!-- am chart export.css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
</head>

<body>

<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">
        <nav class="navbar header-navbar pcoded-header">
            <div class="navbar-wrapper">
                <div class="navbar-logo">
                    <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-3" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(253, 251, 251, 1);"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path></svg>
                    </a>
                    <a href="/indexHomepage">
                        <i class='bx bxs-home bx-md' style='color:#eef2f3'  ></i>
                    </a>
                </div>

                <div class="navbar-container container-fluid">
                    <ul class="nav-left">
                        <li>
                            <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                        </li>
                    </ul>
                    <ul class="nav-right">
                        <li class="user-profile header-notification">
                            <a href="#!" class="waves-effect waves-light">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);"><path d="M12 2C6.579 2 2 6.579 2 12s4.579 10 10 10 10-4.579 10-10S17.421 2 12 2zm0 5c1.727 0 3 1.272 3 3s-1.273 3-3 3c-1.726 0-3-1.272-3-3s1.274-3 3-3zm-5.106 9.772c.897-1.32 2.393-2.2 4.106-2.2h2c1.714 0 3.209.88 4.106 2.2C15.828 18.14 14.015 19 12 19s-3.828-.86-5.106-2.228z"></path></svg>
                                    {{Auth::user()->username}}
                                </span>
                            </a>
                            <ul class="show-notification profile-notification">
                                <li class="waves-effect waves-light">
                                    <a href="{{route('Users.show',Auth::user()->id)}}">
                                        <i class='bx bxs-user-detail bx-sm' style='color:#448aff'> </i> Profile
                                    </a>
                                </li>
                                <li class="waves-effect waves-light">
                                    <a href="{{route('logout')}}">
                                        <i class='bx bx-log-out-circle bx-sm' style='color:#448aff'> </i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                <nav class="pcoded-navbar">
                    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                    <div class="pcoded-inner-navbar main-menu" >
                        {{--                        Side NavBar--}}
                        <div class="">
                            <div class="main-menu-header">
                                <img src="{{asset(Auth()->user()->ImagePath)}}">
                                <div class="user-details">
                                    <span id="more-details">{{Auth()->user()->name}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="pcoded-navigation-label" data-i18n="nav.category.navigation">Menu</div>
                        <ul class="pcoded-item pcoded-left-item">
                            <li class="{{ Route::currentRouteNamed( 'uniHome' ) ?  'active' : '' }}">
                                <a href="/uniHome" class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"></path></svg></span>
                                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                            @can('user')
{{--                                @dd(\Request::route()->getName()=='Users' )--}}
                                <li class="{{ Request::is('Users*') ? 'active' : ''  }}">
                                    <a href="{{ route('Users.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M9.5 12c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm1.5 1H8c-3.309 0-6 2.691-6 6v1h15v-1c0-3.309-2.691-6-6-6z"></path><path d="M16.604 11.048a5.67 5.67 0 0 0 .751-3.44c-.179-1.784-1.175-3.361-2.803-4.44l-1.105 1.666c1.119.742 1.8 1.799 1.918 2.974a3.693 3.693 0 0 1-1.072 2.986l-1.192 1.192 1.618.475C18.951 13.701 19 17.957 19 18h2c0-1.789-.956-5.285-4.396-6.952z"></path></svg></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Users</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endcan
                            @can('university')
                                <li class="{{ Request::is('Universitys*') ? 'active' : ''  }}">
                                    <a href="{{ route('Universitys.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M21 10h-2V4h1V2H4v2h1v6H3a1 1 0 0 0-1 1v9h20v-9a1 1 0 0 0-1-1zm-7 8v-4h-4v4H7V4h10v14h-3z"></path><path d="M9 6h2v2H9zm4 0h2v2h-2zm-4 4h2v2H9zm4 0h2v2h-2z"></path></svg></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Universities</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endcan
                            @can('faculty')
{{--                                @if(\App\Models\universitys::where('id',Auth()->user()->uni_id)->pluck('type')->first()=='Institution')--}}
{{--                                    <li class="{{ Request::is('FacUni*') ? 'active' : ''  }}">--}}
{{--                                        <a href="{{route('FacUni.index')}}" class="waves-effect waves-dark">--}}
{{--                                            <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M21 10h-2V4h1V2H4v2h1v6H3a1 1 0 0 0-1 1v9h20v-9a1 1 0 0 0-1-1zm-7 8v-4h-4v4H7V4h10v14h-3z"></path><path d="M9 6h2v2H9zm4 0h2v2h-2zm-4 4h2v2H9zm4 0h2v2h-2z"></path></svg></span>--}}
{{--                                            <span class="pcoded-mtext" data-i18n="nav.form-components.main">Departments</span>--}}
{{--                                            <span class="pcoded-mcaret"></span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                @else--}}
                                    <li class="{{ Request::is('FacUni*') ? 'active' : ''  }}">
                                        <a href="{{route('FacUni.index')}}" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M21 10h-2V4h1V2H4v2h1v6H3a1 1 0 0 0-1 1v9h20v-9a1 1 0 0 0-1-1zm-7 8v-4h-4v4H7V4h10v14h-3z"></path><path d="M9 6h2v2H9zm4 0h2v2h-2zm-4 4h2v2H9zm4 0h2v2h-2z"></path></svg></span>
                                            <span class="pcoded-mtext" data-i18n="nav.form-components.main">Faculties</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('UniLab*') ? 'active' : ''  }}">
                                        <a href="{{route('UniLab.index')}}" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M15 9.783V4h1V2H8v2h1v5.783l-4.268 9.389a1.992 1.992 0 0 0 .14 1.911A1.99 1.99 0 0 0 6.553 22h10.895a1.99 1.99 0 0 0 1.681-.917c.37-.574.423-1.289.14-1.911L15 9.783zm-4.09.631c.06-.13.09-.271.09-.414V4h2v6c0 .143.03.284.09.414L15.177 15H8.825l2.085-4.586z"></path></svg></span>
                                            <span class="pcoded-mtext" data-i18n="nav.form-components.main">Central Labs</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::is('UniDevice*') ? 'active' : ''  }}">
                                        <a href="{{route('UniDevice.index')}}" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M3 8h2v5c0 2.206 1.794 4 4 4h2v5h2v-5h2c2.206 0 4-1.794 4-4V8h2V6H3v2zm4-6h2v3H7zm8 0h2v3h-2z"></path></svg></span>
                                            <span class="pcoded-mtext" data-i18n="nav.form-components.main">Central Devices</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
{{--                                @endif--}}
                            @endcan
                            @can('department')
                                <li class="{{ Request::is('DeptFac*') ? 'active' : ''  }}">
                                    <a href="{{route('DeptFac.index')}}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M20 13.01h-7V10h1c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v4c0 1.103.897 2 2 2h1v3.01H4V18H3v4h4v-4H6v-2.99h5V18h-1v4h4v-4h-1v-2.99h5V18h-1v4h4v-4h-1v-4.99zM10 8V4h4l.002 4H10z"></path></svg></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Departments</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endcan
                            @can('lab')
                                <li class="{{ Request::is('Lab*') ? 'active' : ''  }}">
                                    <a href="{{route('Lab.index')}}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M15 9.783V4h1V2H8v2h1v5.783l-4.268 9.389a1.992 1.992 0 0 0 .14 1.911A1.99 1.99 0 0 0 6.553 22h10.895a1.99 1.99 0 0 0 1.681-.917c.37-.574.423-1.289.14-1.911L15 9.783zm-4.09.631c.06-.13.09-.271.09-.414V4h2v6c0 .143.03.284.09.414L15.177 15H8.825l2.085-4.586z"></path></svg></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Labs</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endcan
                            @can('device')
                                <li class="{{ Request::is('DeviceLab*') ? 'active' : ''  }}">
                                    <a href="{{route('DeviceLab.index')}}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1); "><path d="M3 8h2v5c0 2.206 1.794 4 4 4h2v5h2v-5h2c2.206 0 4-1.794 4-4V8h2V6H3v2zm4-6h2v3H7zm8 0h2v3h-2z"></path></svg></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Devices</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endcan
{{--                            EXPORT anyone can export--}}
                            <li class="{{ Route::currentRouteNamed( 'export' ) ?  'active' : '' }}">
                                <a href="{{ route('export') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M18 22a2 2 0 0 0 2-2v-5l-5 4v-3H8v-2h7v-3l5 4V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4z"></path></svg></span>
                                    <span class="pcoded-mtext" data-i18n="nav.form-components.main">Export Data</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                            @can('import')
                                <li class="{{ Route::currentRouteNamed( 'import' ) ?  'active' : '' }}">
                                    <a href="{{ route('import') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);"><path d="M20 14V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4h-7v3l-5-4 5-4v3h7zM13 4l5 5h-5V4z"></path></svg></span>
                                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">Import Data</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
{{--------------------------          Select University to view USERS Usernames and Passwords -------------------}}
                        @can('university')
                            <div class="pcoded-navigation-label" data-i18n="nav.category.navigation">Admins' Credentials</div>
                            <ul class="pcoded-item pcoded-left-item">
                                <li class="ml-3">
                                    <select class="selectedUni" name="uni_id" style="width: 200px" >
                                        <option selected disabled>Select university</option>
                                        <label hidden>{{$unis = \App\Models\universitys::all()}}</label>
                                        @foreach($unis as $uni)
                                            <option value="{{$uni->id}}">{{$uni->name}}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-round btn-primary showAdmin mt-2" data-toggle="modal" data-target="#exampleModal">
                                        View Admin
                                    </button>
                                </li>
                            </ul>
                        @endcan
                    </div>
                </nav>
                <div class="pcoded-content">
                    @yield('loggedContent')
                </div>
{{--------------------------  Modal Window to view selected university coordinators  -----------------------}}
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">University Admin</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <strong>Username: </strong>
                                <br><br>
                                <strong>Password: </strong>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
{{-------------------------  Modal ends here  --------------------------}}
            </div>
        </div>
    </div>
</div>

{{-- AJAX Code to Fetch the username and password from database using the selected university id --}}
@can('university')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('.showAdmin').click(function(){
                var uniid = Number($('.selectedUni').val().trim());
                fetchRecords(uniid);
            });
        });

        function fetchRecords(id){
            $.ajax({
                url: 'getUser/'+id,
                type: 'get',
                dataType: 'json',
                success: function(response){
                    var len = 0;
                    $('.modal-body').empty();
                    if(response['data'] != null){
                        len = response['data'].length;
                    }
                    for(var i=0; i<len; i++){
                        var username = response['data'][i].username;
                        var password = response['data'][i].password_hashed;

                        var body = "<strong>Username: </strong>"+"<p>"+username+"</p>"+
                            "<strong> Password: </strong>"+"<p>"+password+"</p>";
                        $(".modal-body").append(body);
                    }
                }
            });
        }
    </script>
@endcan


<!-- Required Jquery -->
<script type="text/javascript" src="{{asset('js/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery-ui/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/popper.js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('pages/widget/excanvas.js')}}"></script>
<!-- waves js -->
<script src="{{asset('pages/waves/js/waves.min.js')}}"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="{{asset('js/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
<!-- modernizr js -->
<script type="text/javascript" src="{{asset('js/modernizr/modernizr.js')}}"></script>
<!-- Chart js -->
<script type="text/javascript" src="{{asset('js/chart.js/Chart.js')}}"></script>
<!-- menu js -->
<script src="{{asset('js/pcoded.min.js')}}"></script>
<script src="{{asset('js/vertical-layout.min.js')}}"></script>
<!-- custom js -->
<script type="text/javascript" src="{{asset('pages/dashboard/custom-dashboard.js')}}"></script>
<script type="text/javascript" src="{{asset('js/script.js')}}"></script>
</body>
</html>
