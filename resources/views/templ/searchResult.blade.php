@extends('templ.head')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

@section('tmplt-contnt')
    <main id="main">

        <!-- ======= Our Portfolio Section ======= -->
        <section class="breadcrumbs bg-color shadow-lg">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Search results for "{{$searchFor}}"</h2>
                </div>
            </div>
        </section><!-- End Our Portfolio Section -->

        <section class="search-form my-4">
            <div class="container">
                <div class="card shadow-sm">
                    <button class="btn btn-light card-header text-start fw-bold" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseExample" 
                            aria-expanded="false" aria-controls="collapseExample">
                        Advanced Search
                    </button>
                    
                    <div class="collapse" id="collapseExample">
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('university.search','All') }}" method="get">
                                @csrf
                                
                                <!-- University -->
                                <div class="col-md-6">
                                    <label for="uni_id" class="form-label fw-semibold">University</label>
                                    <select id="uni_id" name="uni_id" class="form-select" onchange="run(this.value)">
                                        <option selected value="">Select University</option>
                                        @foreach(\App\Models\universitys::all()->sortBy('name') as $uni)
                                            <option value="{{$uni->id}}" {{$uni->id==$request->uni_id?'selected':''}}>{{$uni->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Faculty -->
                                <div class="col-md-6">
                                    <label for="facs" class="form-label fw-semibold">Faculty</label>
                                    <select id="facs" name="fac_id" class="form-select">
                                        <option selected value="">Select Faculty</option>
                                        @foreach(\App\Models\facultys::all()->sortBy('name') as $fac)
                                            <option value="{{$fac->id}}" {{$fac->id==$request->fac_id?'selected':''}}>{{$fac->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Equipment Name -->
                                <div class="col-md-6">
                                    <label for="device_name" class="form-label fw-semibold">Equipment Name</label>
                                    <input type="text" id="device_name" name="device_name" class="form-control" value="{{$request->device_name}}">
                                </div>
                                
                                <!-- Service -->
                                <div class="col-md-6">
                                    <label for="services" class="form-label fw-semibold">Service</label>
                                    <input type="text" id="services" name="services" class="form-control" value="{{$request->services}}">
                                </div>
                                
                                <!-- Model -->
                                <div class="col-md-6">
                                    <label for="model" class="form-label fw-semibold">Model</label>
                                    <input type="text" id="model" name="model" class="form-control" value="{{$request->model}}">
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="col-12 text-end">
                                    <button class="btn btn-success px-4">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ======= Search Result ======= -->
        <section class="portfolio py-4">
            <div class="container">
                <div class="row portfolio-container g-4" 
                    data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    
                    @if(count($devices)==0 and count($unis)==0 and count($unidevices)==0)
                        <h4 class="text-center">No Results</h4>
                    @else
                    
                        @foreach($unis as $u => $uni)
                            <div class="col-lg-3 col-md-4 col-sm-6 portfolio-wrap">
                                <div class="card shadow-sm h-100 border-0">
                                    <a href="{{route('browseuniversity',[$uni->id,$uni->name])}}" class="d-flex justify-content-center p-3">
                                        <img src="{{asset($uni->ImagePath)}}" class="card-img-top img-fluid rounded" 
                                            style="max-height:200px; object-fit:contain;" alt="">
                                    </a>
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{$uni->name}}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach($devices as $device)
                            <div class="col-lg-3 col-md-4 col-sm-6 portfolio-wrap">
                                <div class="card shadow-sm h-100 border-0">
                                    <a href="{{route('browsedevice',[$device->id,$device->lab_id,'0',$device->uni_id,\App\Models\universitys::find($device->uni_id)->name])}}" 
                                    class="d-flex justify-content-center p-3">
                                        <img src="{{asset($device->ImagePath)}}" class="card-img-top img-fluid rounded" 
                                            style="max-height:200px; object-fit:contain;" alt="">
                                    </a>
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{$device->name}}</h6>
                                        <p class="mb-1 text-muted small">{{\App\Models\universitys::find($device->uni_id)->name}}</p>
                                        <p class="mb-0 text-muted small">{{\App\Models\facultys::find($device->fac_id)->name}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach($unidevices as $device)
                            <div class="col-lg-3 col-md-4 col-sm-6 portfolio-wrap">
                                <div class="card shadow-sm h-100 border-0">
                                    <a href="{{route('browsedevice',[$device->id,$device->lab_id,'1',$device->uni_id,\App\Models\universitys::find($device->uni_id)->name])}}" 
                                    class="d-flex justify-content-center p-3">
                                        <img src="{{asset($device->ImagePath)}}" class="card-img-top img-fluid rounded" 
                                            style="max-height:200px; object-fit:contain;" alt="">
                                    </a>
                                    <div class="card-body text-center">
                                        <h6 class="card-title">{{$device->name}}</h6>
                                        <p class="mb-1 text-muted small">{{\App\Models\universitys::find($device->uni_id)->name}}</p>
                                        <p class="mb-0 text-muted small">{{\App\Models\UniLabs::find($device->lab_id)->name}}</p>
                                    </div>
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
    <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
   
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <!-- Template Main JS File -->
    <script src="{{asset('assets/js/main.js')}}"></script>
@endsection

