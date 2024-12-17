@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">
        <!-- ======= Our Portfolio Section ======= -->
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Search</h2>
                </div>
            </div>
        </section>
        <!-- End Our Portfolio Section -->
        <!-- ======= Portfolio Section ======= -->
        <section class="portfolio">
            <div class="container">
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    <form class="form-material" action="{{route('university.search','All')}}" method="get">
                        @csrf
                        <strong>Device Name</strong>
                        <input name="searchDevices" type="text">
                        <button class="btn btn-basic">Search</button>
                    </form>
                    <br><br>
                    <section class="search-form">
                        <div class="card">
                            <button class="btn card-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Advanced Search
                            </button>
                            <div class="collapse" id="collapseExample">
                                <div class="card-body">
                                    <form class="form-material row" action="{{ route('university.search','All') }}" method="get">
                                        @csrf
                                        <label hidden>{{$universitys = \App\Models\universitys::all()->sortBy('name')}}</label>
                                        <div class="row col-10">
                                            <strong class="col-3">University</strong>
                                            <select class="col-6" name="uni_id" onchange="run(this.value)">
                                                <option name="uni_id" selected value="">Select University</option>
                                                @foreach($universitys as $uni)
                                                    <option name="uni_id" value="{{$uni->id}}">{{$uni->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row col-6 mt-3">
                                            <strong class="col-5">Equipment Name</strong>
                                            <input class="col-6" name="device_name">
                                        </div>
                                        <div class="row col-6 mt-3">
                                            <strong class="col-5">Lab Name</strong>
                                            <input class="col-6" name="lab">
                                        </div>
                                        <div class="row col-6 mt-3">
                                            <strong class="col-5">Model</strong>
                                            <input class="col-6" name="model">
                                        </div>
                                        <label hidden>{{$facs = \App\Models\facultys::all()->sortBy('name')}}</label>
                                        <div class="row col-6 mt-3">
                                            <strong class="col-5">Faculty</strong>
                                            <select class="col-6" name="fac_id" id="facs">
                                                <option name="fac_id" selected value="">Select Faculty</option>
                                                @foreach($facs as $fac)
                                                    <option name="fac_id" value="{{$fac->id}}">{{$fac->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row col-6 mt-3">
                                            <i class="col-9"></i>
                                            <button class="btn btn-basic col-3">Search</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
        <!-- End Portfolio Section -->
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        function run(selected_uni){
            var values = @json($faculties);
            $('#facs').find('option:not(:first)').remove();
            for (const val of values) {
                if (val.uni_id == selected_uni){
                    console.log(val);
                    $('#facs').append($(document.createElement('option')).prop({
                        value: val.fac_id,
                        text: val.name
                    }))
                }
            }
        }
    </script>

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

