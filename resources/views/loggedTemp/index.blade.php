@extends('loggedTemp.head')
@section('loggedContent')
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <!-- universities, faculties ... cards etc  start -->
                    @if(Auth()->user()->hasRole('admin'))
                            <!-- Governmental Universities -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-green">{{$admin_stats['public']['unis']->count()}} Universities</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-school bx-sm' style='color:#11c15b'  ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-green">
                                                    {{$admin_stats['public']['labs']->count()}} Faculties' Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-vial bx-sm' style='color:#11c15b' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-green">{{$admin_stats['public']['devices']->count()}} Faculties' Devices</h6>
{{--                                                <h6 class="text-c-green">{{$admin_stats['public']['num_units']}} Faculties' Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-plug bx-sm' style='color:#11c15b'></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-green">
                                                    {{$admin_stats['public']['central_labs']->count()}} Central Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-vial bx-sm' style='color:#11c15b' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-green">{{$admin_stats['public']['central_devices']->count()}} Central Devices</h6>
{{--                                                <h6 class="text-c-green">{{$admin_stats['public']['num_central_units']}} Central Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-plug bx-sm' style='color:#11c15b'></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-green">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">Governmental Universities</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Private Universities -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-red">{{$admin_stats['private']['unis']->count()}} Universities</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-school bx-sm' style='color:#ff5252'  ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-red">
                                                    {{$admin_stats['private']['labs']->count()}} Faculties' Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-vial bx-sm' style='color:#ff5252' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-red">{{$admin_stats['private']['devices']->count()}} Faculties' Devices</h6>
{{--                                                <h6 class="text-c-red">{{$admin_stats['private']['num_units']}} Faculties' Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-plug bx-sm' style='color:#FF5252'></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-red">
                                                    {{$admin_stats['private']['central_labs']->count()}} Central Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-vial bx-sm' style='color:#ff5252' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-red">{{$admin_stats['private']['central_devices']->count()}} Central Devices</h6>
{{--                                                <h6 class="text-c-red">{{$admin_stats['private']['num_central_units']}} Central Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-plug bx-sm' style='color:#ff5252'></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-c-red">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">Private Universities</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- National Universities -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-blue">{{$admin_stats['ahli']['unis']->count()}} Universities</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-school bx-sm' style='color:#448aff'  ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-blue">
                                                    {{$admin_stats['ahli']['labs']->count()}} Faculties' Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-vial bx-sm' style='color:#448aff' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-blue">{{$admin_stats['ahli']['devices']->count()}} Faculties' Devices</h6>
{{--                                                <h6 class="text-c-blue">{{$admin_stats['ahli']['num_units']}} Faculties' Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-plug bx-sm' style='color:#448aff'></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-blue">
                                                    {{$admin_stats['ahli']['central_labs']->count()}} Central Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-vial bx-sm' style='color:#448aff' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-blue">{{$admin_stats['ahli']['central_devices']->count()}} Central Devices</h6>
{{--                                                <h6 class="text-c-blue">{{$admin_stats['ahli']['num_central_units']}} Central Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-plug bx-sm' style='color:#448aff'></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-blue">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">National Universities</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Institutes -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-orenge">{{$admin_stats['institute']['unis']->count()}} Institutes</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-school bx-sm' style='color:#fe8a7d'  ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-orenge">
                                                    {{$admin_stats['institute']['labs']->count()}} Departments' Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-vial bx-sm' style='color:#fe8a7d' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-orenge">{{$admin_stats['institute']['devices']->count()}} Departments' Devices</h6>
{{--                                                <h6 class="text-c-orenge">{{$admin_stats['institute']['num_units']}} Departments' Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bx-plug bx-sm' style='color:#fe8a7d'></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-orenge">
                                                    {{$admin_stats['institute']['central_labs']->count()}} Central Labs</h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-vial bx-sm' style='color:#fe8a7d' ></i>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h6 class="text-c-orenge">{{$admin_stats['institute']['central_devices']->count()}} Central Devices</h6>
{{--                                                <h6 class="text-c-orenge">{{$admin_stats['institute']['num_central_units']}} Central Devices</h6>--}}
                                            </div>
                                            <div class="col-4 text-right">
                                                <i class='bx bxs-plug bx-sm' style='color:#fe8a7d'></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-orenge">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">Institutes</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                    @if(Auth()->user()->hasRole('university'))
                        @if(\App\Models\universitys::where('id',Auth()->user()->uni_id)->pluck('type')->first()=='Institution')
                                <div class="col-xl-2 col-md-6">
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <h4 class="text-c-green">{{$stables['faculties']->count()}}</h4>
                                                    <h6 class="text-muted m-b-0"></h6>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 10h-2V4h1V2H4v2h1v6H3a1 1 0 0 0-1 1v9h20v-9a1 1 0 0 0-1-1zm-7 8v-4h-4v4H7V4h10v14h-3z"></path><path d="M9 6h2v2H9zm4 0h2v2h-2zm-4 4h2v2H9zm4 0h2v2h-2z"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-c-green">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <p class="text-white m-b-0">Departments</p>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-6">
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <h4 class="text-c-blue">{{$stables['labs']->count()}}</h4>
                                                    <h6 class="text-muted m-b-0"></h6>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M3.102 20.898c.698.699 1.696 1.068 2.887 1.068 1.742 0 3.855-.778 6.012-2.127 2.156 1.35 4.27 2.127 6.012 2.127 1.19 0 2.188-.369 2.887-1.068 1.269-1.269 1.411-3.413.401-6.039-.358-.932-.854-1.895-1.457-2.859a16.792 16.792 0 0 0 1.457-2.859c1.01-2.626.867-4.771-.401-6.039-.698-.699-1.696-1.068-2.887-1.068-1.742 0-3.855.778-6.012 2.127-2.156-1.35-4.27-2.127-6.012-2.127-1.19 0-2.188.369-2.887 1.068C1.833 4.371 1.69 6.515 2.7 9.141c.359.932.854 1.895 1.457 2.859A16.792 16.792 0 0 0 2.7 14.859c-1.01 2.626-.867 4.77.402 6.039zm16.331-5.321c.689 1.79.708 3.251.052 3.907-.32.32-.815.482-1.473.482-1.167 0-2.646-.503-4.208-1.38a26.611 26.611 0 0 0 4.783-4.784c.336.601.623 1.196.846 1.775zM12 17.417a23.568 23.568 0 0 1-2.934-2.483A23.998 23.998 0 0 1 6.566 12 23.74 23.74 0 0 1 12 6.583a23.568 23.568 0 0 1 2.934 2.483 23.998 23.998 0 0 1 2.5 2.934A23.74 23.74 0 0 1 12 17.417zm6.012-13.383c.657 0 1.152.162 1.473.482.656.656.638 2.117-.052 3.907-.223.579-.51 1.174-.846 1.775a26.448 26.448 0 0 0-4.783-4.784c1.562-.876 3.041-1.38 4.208-1.38zM4.567 8.423c-.689-1.79-.708-3.251-.052-3.907.32-.32.815-.482 1.473-.482 1.167 0 2.646.503 4.208 1.38a26.448 26.448 0 0 0-4.783 4.784 13.934 13.934 0 0 1-.846-1.775zm0 7.154c.223-.579.51-1.174.846-1.775a26.448 26.448 0 0 0 4.783 4.784c-1.563.877-3.041 1.38-4.208 1.38-.657 0-1.152-.162-1.473-.482-.656-.656-.637-2.117.052-3.907z"></path><circle cx="12" cy="12" r="2.574"></circle></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-c-blue">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <p class="text-white m-b-0">Labs</p>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-6">
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <h4 class="text-c-orenge">{{$stables['devices']->count()}}</h4>
                                                    <h6 class="text-muted m-b-0"></h6>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(254, 138, 125, 1);"><path d="M7 22a4.965 4.965 0 0 0 3.535-1.465l9.193-9.193.707.708 1.414-1.414-8.485-8.486-1.414 1.414.708.707-9.193 9.193C2.521 14.408 2 15.664 2 17s.521 2.592 1.465 3.535A4.965 4.965 0 0 0 7 22zM18.314 9.928 15.242 13H6.758l7.314-7.314 4.242 4.242z"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-c-orenge">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <p class="text-white m-b-0">Devices</p>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @else
                        <div class="col-xl-2 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-green">{{$stables['faculties']->count()}}</h4>
                                            <h6 class="text-muted m-b-0"></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 10h-2V4h1V2H4v2h1v6H3a1 1 0 0 0-1 1v9h20v-9a1 1 0 0 0-1-1zm-7 8v-4h-4v4H7V4h10v14h-3z"></path><path d="M9 6h2v2H9zm4 0h2v2h-2zm-4 4h2v2H9zm4 0h2v2h-2z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-green">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Faculties</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-purple">{{$stables['central_labs']->count()}}</h4>
                                            <h6 class="text-muted m-b-0"></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(83, 109, 254, 1);"><path d="M3.102 20.898c.698.699 1.696 1.068 2.887 1.068 1.742 0 3.855-.778 6.012-2.127 2.156 1.35 4.27 2.127 6.012 2.127 1.19 0 2.188-.369 2.887-1.068 1.269-1.269 1.411-3.413.401-6.039-.358-.932-.854-1.895-1.457-2.859a16.792 16.792 0 0 0 1.457-2.859c1.01-2.626.867-4.771-.401-6.039-.698-.699-1.696-1.068-2.887-1.068-1.742 0-3.855.778-6.012 2.127-2.156-1.35-4.27-2.127-6.012-2.127-1.19 0-2.188.369-2.887 1.068C1.833 4.371 1.69 6.515 2.7 9.141c.359.932.854 1.895 1.457 2.859A16.792 16.792 0 0 0 2.7 14.859c-1.01 2.626-.867 4.77.402 6.039zm16.331-5.321c.689 1.79.708 3.251.052 3.907-.32.32-.815.482-1.473.482-1.167 0-2.646-.503-4.208-1.38a26.611 26.611 0 0 0 4.783-4.784c.336.601.623 1.196.846 1.775zM12 17.417a23.568 23.568 0 0 1-2.934-2.483A23.998 23.998 0 0 1 6.566 12 23.74 23.74 0 0 1 12 6.583a23.568 23.568 0 0 1 2.934 2.483 23.998 23.998 0 0 1 2.5 2.934A23.74 23.74 0 0 1 12 17.417zm6.012-13.383c.657 0 1.152.162 1.473.482.656.656.638 2.117-.052 3.907-.223.579-.51 1.174-.846 1.775a26.448 26.448 0 0 0-4.783-4.784c1.562-.876 3.041-1.38 4.208-1.38zM4.567 8.423c-.689-1.79-.708-3.251-.052-3.907.32-.32.815-.482 1.473-.482 1.167 0 2.646.503 4.208 1.38a26.448 26.448 0 0 0-4.783 4.784 13.934 13.934 0 0 1-.846-1.775zm0 7.154c.223-.579.51-1.174.846-1.775a26.448 26.448 0 0 0 4.783 4.784c-1.563.877-3.041 1.38-4.208 1.38-.657 0-1.152-.162-1.473-.482-.656-.656-.637-2.117.052-3.907z"></path><circle cx="12" cy="12" r="2.574"></circle></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-purple">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Central Labs</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-red">{{$stables['central_devices']->count()}}</h4>
{{--                                            <h4 class="text-c-red">{{$stables['num_central_units']}}</h4>--}}
                                            <h6 class="text-muted m-b-0"></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(255, 82, 82, 1);"><path d="M15.794 11.09c.332-.263.648-.542.947-.84l.136-.142c.283-.293.552-.598.801-.919l.062-.075c.255-.335.486-.688.702-1.049l.128-.22c.205-.364.395-.737.559-1.123.02-.047.035-.095.055-.142.147-.361.274-.731.383-1.109.021-.07.044-.14.063-.211.107-.402.189-.813.251-1.229.013-.087.021-.175.032-.263.051-.432.087-.869.087-1.311V2h-2v.457c0 .184-.031.361-.042.543H6.022C6.012 2.819 6 2.64 6 2.457V2H4v.457c0 4.876 3.269 9.218 7.952 10.569l.028.009c2.881.823 5.056 3.146 5.769 5.965H6.251l.799-2h7.607a7.416 7.416 0 0 0-2.063-2h-4c.445-.424.956-.774 1.491-1.09a9.922 9.922 0 0 1-2.08-1.014C5.55 14.812 4 17.779 4 21.015V23h2v-1.985L6.001 21h11.998l.001.015V23h2v-1.985c0-3.83-2.159-7.303-5.443-9.07a11.1 11.1 0 0 0 1.072-.729c.055-.042.11-.082.165-.126zm-1.19-1.604a8.945 8.945 0 0 1-2.325 1.348c-.092.036-.185.068-.278.102A8.95 8.95 0 0 1 8.836 9h6.292c-.171.161-.332.333-.517.48l-.007.006zM17.619 5c-.005.016-.007.033-.012.049l-.044.151a9.089 9.089 0 0 1-.513 1.252c-.096.19-.213.365-.321.548h-9.48a9.066 9.066 0 0 1-.871-2h11.241z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-red">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Central Devices</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-blue">{{$stables['labs']->count()}}</h4>
                                            <h6 class="text-muted m-b-0"></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M7 22a4.965 4.965 0 0 0 3.535-1.465l9.193-9.193.707.708 1.414-1.414-8.485-8.486-1.414 1.414.708.707-9.193 9.193C2.521 14.408 2 15.664 2 17s.521 2.592 1.465 3.535A4.965 4.965 0 0 0 7 22zM18.314 9.928 15.242 13H6.758l7.314-7.314 4.242 4.242z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-blue">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Labs</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-orenge">{{$stables['devices']->count()}}</h4>
{{--                                            <h4 class="text-c-orenge">{{$stables['num_units']}}</h4>--}}
                                            <h6 class="text-muted m-b-0"></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(254, 138, 125, 1);"><path d="M3 8h2v5c0 2.206 1.794 4 4 4h2v5h2v-5h2c2.206 0 4-1.794 4-4V8h2V6H3v2zm4 0h10v5c0 1.103-.897 2-2 2H9c-1.103 0-2-.897-2-2V8zm0-6h2v3H7zm8 0h2v3h-2z"></path></svg>                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-orenge">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Devices</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                    @if(Auth()->user()->hasRole('faculty'))
                            <div class="col-xl-3 col-md-4">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h4 class="text-c-blue">{{$stables['labs']->count()}}</h4>
                                                <h6 class="text-muted m-b-0"></h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M7 22a4.965 4.965 0 0 0 3.535-1.465l9.193-9.193.707.708 1.414-1.414-8.485-8.486-1.414 1.414.708.707-9.193 9.193C2.521 14.408 2 15.664 2 17s.521 2.592 1.465 3.535A4.965 4.965 0 0 0 7 22zM18.314 9.928 15.242 13H6.758l7.314-7.314 4.242 4.242z"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-blue">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">Labs</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-4">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h4 class="text-c-green">{{$stables['devices']->count()}}</h4>
{{--                                                <h4 class="text-c-green">{{$stables['num_units']}}</h4>--}}
                                                <h6 class="text-muted m-b-0"></h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M3 8h2v5c0 2.206 1.794 4 4 4h2v5h2v-5h2c2.206 0 4-1.794 4-4V8h2V6H3v2zm4 0h10v5c0 1.103-.897 2-2 2H9c-1.103 0-2-.897-2-2V8zm0-6h2v3H7zm8 0h2v3h-2z"></path></svg>                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-green">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">Devices</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                    @if(Auth()->user()->hasRole('department'))
                        <div class="col-xl-3 col-md-4">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-blue">{{$stables['labs']->count()}}</h4>
                                            <h6 class="text-muted m-b-0"></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M7 22a4.965 4.965 0 0 0 3.535-1.465l9.193-9.193.707.708 1.414-1.414-8.485-8.486-1.414 1.414.708.707-9.193 9.193C2.521 14.408 2 15.664 2 17s.521 2.592 1.465 3.535A4.965 4.965 0 0 0 7 22zM18.314 9.928 15.242 13H6.758l7.314-7.314 4.242 4.242z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-blue">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Labs</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4">
                                <div class="card">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <h4 class="text-c-green">{{$stables['devices']->count()}}</h4>
{{--                                                <h4 class="text-c-green">{{$stables['num_units']}}</h4>--}}
                                                <h6 class="text-muted m-b-0"></h6>
                                            </div>
                                            <div class="col-4 text-right">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M3 8h2v5c0 2.206 1.794 4 4 4h2v5h2v-5h2c2.206 0 4-1.794 4-4V8h2V6H3v2zm4 0h10v5c0 1.103-.897 2-2 2H9c-1.103 0-2-.897-2-2V8zm0-6h2v3H7zm8 0h2v3h-2z"></path></svg>                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-c-green">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <p class="text-white m-b-0">Devices</p>
                                            </div>
                                            <div class="col-3 text-right">
                                                <i class='bx bxs-bar-chart-alt-2 bx-xs' style='color:#ffeded'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                    <!-- task, page, download counter  end -->
                        <!-- filter  start -->
                        <div class="col-xl-12 col-md-12 mb-3 ">
                            <div class="w-75 mx-auto">
                            @if(Auth()->user()->hasRole('admin'))
                                <form action="{{route('getUniDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17,193, 91, 1);"><path d="M13 20v-4.586L20.414 8c.375-.375.586-.884.586-1.415V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v2.585c0 .531.211 1.04.586 1.415L11 15.414V22l2-2z"></path></svg>
                                    @csrf
                                    <div> Type
                                        <select class="typeschart" id="typeschart" name="selectOption" style="height:40px;" onchange="run(this.value)" onloadstart="run(this.value)">
                                            <option type="submit" value="public" selected>Governmental</option>
                                            <option type="submit" value="private">Private</option>
                                            <option type="submit" value="ahli">National</option>
                                            <option type="submit" value="Institution">Institute</option>
                                        </select>
                                    </div>
                                    <div class="ml-3"> University
                                        <select class="unischart" id="unischart" name="uni_selected" style="height:40px;max-width: 200px" onchange="runUni(this.value)">
                                            <option value="" selected>All</option>
                                        </select>
                                    </div>
                                    <div class="ml-3"> Faculty
                                        <select class="facsselect" id="facsselect" name="fac_selected" style="height:40px;max-width: 330px"> {{--onchange="run(this.value)">--}}
                                            <option value="" selected>All</option>
                                        </select>
                                    </div>
                                    <div class="ml-3">Price
                                        <select name="price" style="height: 40px;">
                                            <option value="all">All</option>
                                            <option value="less100k"><100,000</option>
                                            <option value="more100k">>100,000</option>
                                        </select>
                                    </div>
                                    <div class="ml-3"> Count
                                        <select name="count" style="height: 40px;">
                                            <option type="submit" value="devices" {{$count=='devices'?'selected':''}}>devices</option>
                                            <option type="submit" value="units" {{$count=='units'?'selected':''}}>units</option>
                                        </select>
                                    </div>
                                    <div class="ml-3">From: <input name="start_date" type="date" style="height: 40px"> </div>
                                    <div class="ml-3">To: <input name="end_date" type="date" style="height: 40px"> </div>
                                    <div class="ml-3 mt-3"> <button class="btn btn-round btn-success" >Search</button> </div>
                                </form>
                            @endif
                            @if(Auth()->user()->hasRole('university'))
                            <form action="{{route('getLabDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                @csrf
                                @if(\App\Models\universitys::where('id',Auth()->user()->uni_id)->pluck('type')->first()=='Institution')
                                    <div> Department
                                        <select class="facultyschart" id="facultyschart" name="selectOption" style="height:40px;">{{--onchange="run(this.value)">--}}
                                            <option value="" disabled selected>Select Department</option>
                                            @foreach($stables['faculties'] as $fac)
                                                <option type="submit" value="{{$fac->fac_id}}">{{$fac->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                <div> Faculty
                                    <select class="facultyschart" id="facultyschart" name="selectOption" style="height:40px;">{{--onchange="run(this.value)">--}}
                                        <option value="" disabled selected>Select Faculty</option>
                                        <option type="submit">Central Labs</option>
                                    @foreach($stables['faculties'] as $fac)
                                            <option type="submit" value="{{$fac->fac_id}}">{{$fac->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div>Price
                                    <select name="price" style="height: 40px;">
                                        <option value="all">All</option>
                                        <option value="less100k"><100,000</option>
                                        <option value="more100k">>100,000</option>
                                    </select>
                                </div>
                                <div>From: <input name="start_date" type="date" style="height: 40px"> </div>
                                <div>To: <input name="end_date" type="date" style="height: 40px"> </div>
                                <div> <button class="btn btn-success btn-round">Search</button> </div>
                            </form>
                            @endif
                            @if(Auth()->user()->hasRole('faculty'))
                                 <form action="{{route('getFacDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                     @csrf
                                     <div> Department
                                         <select class="deptschart" id="deptschart" name="deptChosen" style="height:40px; max-width: 200px" onchange="runDept(this.value)">{{--onchange="run(this.value)">--}}
                                             <option value="" selected>Select Department</option>
                                             @foreach($depts as $id=>$name)
                                                 <option type="submit" value="{{$id}}">{{$name}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <div> Lab
                                         <select class="facultyschart" id="facultyschart" name="selectOption" style="height:40px; max-width: 200px">{{--onchange="run(this.value)">--}}
                                             <option value="" selected>Select Lab</option>
                                             @foreach($labs as $index=>$value)
                                                 <option type="submit" value="{{$value->id}}">{{$value->name}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <div>Price
                                         <select name="price" style="height: 40px;">
                                             <option value="all">All</option>
                                             <option value="less100k"><100,000</option>
                                             <option value="more100k">>100,000</option>
                                         </select>
                                     </div>
                                     <div>From: <input name="start_date" type="date" style="height: 40px"> </div>
                                     <div>To: <input name="end_date" type="date" style="height: 40px"> </div>
                                     <div> <button class="btn btn-success btn-round">Search</button> </div>
                                 </form>
                            @endif
                            @if(Auth()->user()->hasRole('department'))
                                <form action="{{route('getDeptDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                     @csrf
                                    <div>Price
                                        <select name="price" style="height: 40px;">
                                            <option value="all">All</option>
                                            <option value="less100k"><100,000</option>
                                            <option value="more100k">>100,000</option>
                                        </select>
                                    </div>
                                     <div style="width: 200px">From: <input name="start_date" type="date" style="height: 40px"> </div>
                                     <div style="width: 200px">To: <input name="end_date" type="date" style="height: 40px"> </div>
                                     <div style="width: 200px"> <button class="btn btn-success btn-round">Search</button> </div>
                                </form>
                            @endif
                            </div>
                        </div>
                        <!-- filter  end -->
                        <!--  sale analytics start -->
                        @if(Auth()->user()->hasRole('admin'))
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Devices in Governmental universities</h5>
                                    </div>
                                    <div class="card-block">
                                        <div id="devices_public" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Devices in Private universities</h5>
                                    </div>
                                    <div class="card-block">
                                        <div id="devices_private" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Devices in Institutes</h5>
                                    </div>
                                    <div class="card-block">
                                        <div id="devices_inst" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Devices in National universities</h5>
                                    </div>
                                    <div class="card-block">
                                        <div id="devices_ahli" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth()->user()->hasRole('university'))
                        <div class="col-xl-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    @if(\App\Models\universitys::where('id',Auth()->user()->uni_id)->pluck('type')->first()=='Institution')
                                        <h5>Total number of labs in each department</h5>
                                    @else
                                    <h5>Labs in each faculty</h5>
                                    @endif
                                </div>
                                <div class="card-block">
                                    <div id="labs_chart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    @if(\App\Models\universitys::where('id',Auth()->user()->uni_id)->pluck('type')->first()=='Institution')
                                        <h5>Devices in each department</h5>
                                    @else
                                    <h5>Devices in each faculty</h5>
                                    @endif
                                </div>
                                <div class="card-block">
                                    <div id="devices_chart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(Auth()->user()->hasRole('faculty'))
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Total number of devices in each lab</h5>
                                    </div>
                                    <div class="card-block">
                                        <div id="devices_faculty_chart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--  sale analytics end -->
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">

        // dashboard filter
        @if (Auth()->user()->hasRole('admin'))
        var stables = @json($stables);
        values = stables.universities;
        valuesFac = stables.all_faculties;
        for (const val of values) {
            if (val.type === 'public'){
                $('#unischart').append($(document.createElement('option')).prop({
                    value: val.id,
                    text: val.name
                }))
            }
        }

        // $('#facschart').append(option);

        function run(selected_type){
            $('#unischart').find('option:not(:first)').remove();
            for (const val of values) {
                if (val.type === selected_type){
                    // console.log(val);
                    $('#unischart').append($(document.createElement('option')).prop({
                        value: val.id,
                        // text: val.charAt(0).toUpperCase() + val.slice(1)
                        text: val.name
                    }))
                }
            }
        }

        function runUni(selected){
            $('#facsselect').find('option:not(:first)').remove();
            for (const val of valuesFac) {
                if (val.uni_id == selected){
                    console.log(val.fac_id,val.name);
                    $('#facsselect').append($(document.createElement('option')).prop({
                        value: val.fac_id,
                        text: val.name
                    }))
                }
            }
        }
        @endif

        @if (Auth()->user()->hasRole('faculty'))
        function runDept(selected_dept){
            $('#facultyschart').find('option:not(:first)').remove();
            var labs = @json($labs);
            // console.log(selected_dept==='');
            if (selected_dept===''){
                for (const val of labs){
                    $('#facultyschart').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            }
            else {
                for (const val of labs) {
                    if (val.dept_id == selected_dept) {
                        $('#facultyschart').append($(document.createElement('option')).prop({
                            value: val.id,
                            text: val.name
                        }))
                    }
                }
            }
        }
        @endif
        // Load google charts ///////////////////////////////////////////////////////////////////////////////////////
        google.charts.load('current', {'packages':['corechart','bar']});

        @if(Auth()->user()->hasRole('faculty'))

        {
            google.charts.setOnLoadCallback(drawLabDevbyFacChart);
            var labs = @json($fac_x);
            var devices = @json($dev_y);
            var Combined = [];
            Combined[0] = ['labs','devices'];

            for (var i = 0; i < labs.length; i++){
                if (labs) {
                    Combined[i + 1] = [ labs[i], devices[i]];
                }
            }
            var options = {
                titleTextStyle: {fontSize: 23, bold: true,}
                , legend: { position: "none" }
                ,chartArea:{left:90,top:0,width:'100%',height:300}
                // ,'width':45*dev_Combined.length, 'height':400 //private len=34  public len=27  ahli len=8 Insit len=6
                ,'dataOpacity': 0.8};
        }
        @endif
        @if(Auth()->user()->hasRole('university'))
        {
            google.charts.setOnLoadCallback(drawLabDevChart);
            google.charts.setOnLoadCallback(drawFacultiesDevChart);
            var facs = @json($fac_x);
            var labs = @json($lab_y);
            var devices = @json($dev_y);
            var Combined = [];
            var dev_Combined = [];
            Combined[0] = ['Faculty', 'labs'];
            dev_Combined[0] = ['Faculty', 'Devices'];

            for (var i = 0; i < facs.length; i++){
                if (facs) {
                    Combined[i + 1] = [ facs[i], labs[i]];
                    dev_Combined[i + 1] = [ facs[i], devices[i]];
                }
            }
            console.log(Combined,dev_Combined);
            var options = {
                // colors: ['#448AFF']
                 titleTextStyle: {fontSize: 23, bold: true,}
                // , legend: { position: "none" }
                , 'chartArea': {'width': '80%'}
                , 'width':600, 'height':400, 'padding':0, 'margin':0
                ,'dataOpacity': 0.8};

            var options2 = {
                // colors: ['#FF5252']
                titleTextStyle: {fontSize: 23, bold: true,}
                // , legend: { position: "none" }
                , 'chartArea': {'width': '70%'}
                , 'width':600, 'height':400, 'padding':0, 'margin':0
                ,'dataOpacity': 0.8};
        }
        @endif
        @if(Auth()->user()->hasRole('admin'))
        {
            google.charts.setOnLoadCallback(publicChart);
            google.charts.setOnLoadCallback(privateChart);
            google.charts.setOnLoadCallback(instChart);
            google.charts.setOnLoadCallback(ahliChart);

            var facs = @json($fac_x);
            var devices = @json($dev_y);
            var uni_typs = @json($uni_types);
            var publCombined = [];
            var privCombined = [];
            var instCombined = [];
            var ahliCombined = [];
            publCombined[0] = ['University', 'devices'];
            privCombined[0] = ['University', 'devices'];
            instCombined[0] = ['University', 'devices'];
            ahliCombined[0] = ['University', 'devices'];
            var publ ='public';
            var priv ='private';
            var inst ='Institution';
            var ahli = 'ahli';
            for (var i = 0; i < facs.length; i++){
                if (facs && uni_typs[i]===publ) { publCombined.push([ facs[i],  devices[i]]); }
                else if (facs && uni_typs[i]===priv){privCombined.push([facs[i], devices[i]]); }
                else if (facs && uni_typs[i]===inst){instCombined.push([facs[i], devices[i]]);}
                else if (facs && uni_typs[i]===ahli){ahliCombined.push([facs[i], devices[i]]);}
            }
            // console.log(publCombined);
            var admin_options = {
                titleTextStyle: {fontSize: 23, bold: true,}
                // , legend: { position: 'top' }
                // ,pieHole:0.5
                ,chartArea:{left:90,top:0,width:'100%',height:300}
                // ,'width':45*dev_Combined.length, 'height':400 //private len=34  public len=27  ahli len=8 Insit len=6
                ,'dataOpacity': 0.8};
        }
        @endif
        function publicChart(){
            var admin_data = google.visualization.arrayToDataTable(publCombined);
            var chart = new google.visualization.ColumnChart(document.getElementById('devices_public'));
            chart.draw(admin_data, admin_options);
        }
        function privateChart(){
            var admin_data = google.visualization.arrayToDataTable(privCombined);
            var chart = new google.visualization.ColumnChart(document.getElementById('devices_private'));
            chart.draw(admin_data, admin_options);
        }
        function instChart(){
            var admin_data = google.visualization.arrayToDataTable(instCombined);
            var chart = new google.visualization.ColumnChart(document.getElementById('devices_inst'));
            chart.draw(admin_data, admin_options);
        }
        function ahliChart(){
            var admin_data = google.visualization.arrayToDataTable(ahliCombined);
            var chart = new google.visualization.ColumnChart(document.getElementById('devices_ahli'));
            chart.draw(admin_data, admin_options);
        }
        function drawLabDevChart() {
            var data = google.visualization.arrayToDataTable(Combined);
            var chart = new google.visualization.PieChart(document.getElementById('labs_chart'));
            // options = {'width':600, 'chartArea': {'width': '30%'}, legend: { position: "none" }}
            chart.draw(data, options);
        }
        function drawFacultiesDevChart() {
            var device_data = google.visualization.arrayToDataTable(dev_Combined);
            var chart = new google.visualization.PieChart(document.getElementById('devices_chart'));
            // options2 = {'width':600, 'chartArea': {'width': '30%'}, legend: { position: "none" }}
            chart.draw(device_data, options2);
        }
        function drawLabDevbyFacChart(){
            var device = google.visualization.arrayToDataTable(Combined);
            var chart = new google.visualization.ColumnChart(document.getElementById('devices_faculty_chart'));
            chart.draw(device, options);
        }
    </script>
@endsection
