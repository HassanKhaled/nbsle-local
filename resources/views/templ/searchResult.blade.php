@extends('templ.head')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

@section('tmplt-contnt')
    <main id="main">

        <!-- ======= Our Portfolio Section ======= -->
        <section class="breadcrumbs bg-white shadow-lg">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Search results for "{{$searchFor}}"</h2>
                </div>
            </div>
        </section><!-- End Our Portfolio Section -->

        <section class="search-form">
            <div class="container">
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
                                    <option name="uni_id" value="{{$uni->id}}" {{$uni->id==$request->uni_id?'selected':''}}>{{$uni->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label hidden>{{$facs = \App\Models\facultys::all()->sortBy('name')}}</label>
                        <div class="row col-6 mt-3">
                            <strong class="col-5">Faculty</strong>
                            <select class="col-6" name="fac_id" id="facs">
                                <option name="fac_id" selected value="">Select Faculty</option>
                                @foreach($facs as $fac)
                                    <option name="fac_id" value="{{$fac->id}}" {{$fac->id == $request->fac_id?'selected':''}}>{{$fac->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row col-6 mt-3">
                            <strong class="col-5">Equipment Name</strong>
                            <input class="col-6" name="device_name" value="{{$request->device_name}}">
                        </div>
                        <div class="row col-6 mt-3">
                            <strong class="col-5">Service</strong>
                            <input class="col-6" name="services" value="{{$request->services}}">
                        </div>
                        <div class="row col-6 mt-3">
                            <strong class="col-5">Model</strong>
                            <input class="col-6" name="model" value="{{$request->model}}">
                        </div>

                        <div class="row col-6 mt-3">
                            <i class="col-9"></i>
                            <button class="btn btn-basic col-3">Search</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </section>

        <!-- ======= Search Result ======= -->
        <section class="portfolio">
            <div class="container">
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    @if(count($devices)==0 and count($unis)==0 and count($unidevices)==0)
                        <h4 class="text-center">No Results</h4>
                    @else
                    @foreach($unis as $u => $uni)
                        <div class="col-lg-2 col-md-6 portfolio-wrap ">
                            <div class="portfolio-item" style="background-color: white">
                                <a href="{{route('browseuniversity',[$uni->id,$uni->name])}}">
                                    <img src="{{asset($uni->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="">
                                </a>
                                <h6 class="text-center" href="{{route('browseuniversity',[$uni->id,$uni->name])}}">{{$uni->name}}</h6>
                            </div>
                        </div>
                    @endforeach
                        @foreach($devices as $device)
                            <div class="col-lg-2 col-md-6 portfolio-wrap">
                                <div class="portfolio-item" style="background-color: white">
                                    <a href="{{route('browsedevice',[$device->id,$device->lab_id,'0',$device->uni_id,\App\Models\universitys::find($device->uni_id)->name])}}">
                                        <img src="{{asset($device->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="">
                                    </a>
                                    <h6 class="text-center" href="{{route('browsedevice',[$device->id,$device->lab_id,'0',$device->uni_id,\App\Models\universitys::find($device->uni_id)->name])}}">{{$device->name}}</h6>
{{--                                    @dd($loop->index)--}}
                                    <p class="text-center">{{\App\Models\universitys::find($device->uni_id)->name}}</p>
                                    <p class="text-center">{{\App\Models\facultys::find($device->fac_id)->name}}</p>
                                </div>
                            </div>
                        @endforeach
                        @foreach($unidevices as $device)
                            <div class="col-lg-2 col-md-6 portfolio-wrap">
                                <div class="portfolio-item" style="background-color: white">
                                    <a href="{{route('browsedevice',[$device->id,$device->lab_id,'1',$device->uni_id,\App\Models\universitys::find($device->uni_id)->name])}}">
                                        <img src="{{asset($device->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="">
                                    </a>
                                    <h6 class="text-center" href="{{route('browsedevice',[$device->id,$device->lab_id,'1',$device->uni_id,\App\Models\universitys::find($device->uni_id)->name])}}">{{$device->name}}</h6>
                                    <p class="text-center">{{\App\Models\universitys::find($device->uni_id)->name}}</p>
                                    <p class="text-center">{{\App\Models\UniLabs::find($device->lab_id)->name}}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <!-- End Search Result -->
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        function run(selected_uni){
            var values = @json($faculties);
            $('#facs').find('option:not(:first)').remove();
            for (const val of values) {
                if (val.uni_id == selected_uni){
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

