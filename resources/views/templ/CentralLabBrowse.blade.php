@extends('templ.head')
@section('tmplt-contnt')
    <style>
        .filterDiv {
            float: left;
            /*background-color: #2196F3;*/
            /*color: #ffffff;*/
            /*width: 100px;*/
            /*line-height: 100px;*/
            text-align: center;
            /*margin: 2px;*/
            display: none;
        }
        .show {
            display: block;
        }
        /* Style the buttons */
        .btn {
            border: none;
            outline: none;
            margin: 5px 5px;
            background-color: transparent;
            cursor: pointer;
            /*text-decoration: underline;*/
            /*border-bottom: 1px solid black;*/
        }

        .btn:hover {
            background-color: transparent;
            /*color: #68A4C4;*/
            /*border-bottom: 1px solid #68A4C4;*/
            color: #A7DA30;
            border-bottom: 1px solid #A7DA30;
        }

        .btn.active {
            background-color: transparent;
            color: #A7DA30;
            border-bottom: 1px solid #A7DA30;
        }

    </style>
    <main id="main">

        <!-- ======= Our Portfolio Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>{{$facName}}</h2>
                    <ol>
                        <li><a href="{{route('browseuniversity',[$uni_id,$uniname])}}">{{$uniname}}</a></li>
                        <li>{{$facName}}</li>
                    </ol>
                </div>

            </div>
        </section><!-- End Our Portfolio Section -->

        <!-- ======= Portfolio Section ======= -->
        <section class="portfolio">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div id="myBtnContainer">
                            <button class="btn col-lg-3 mr-5 font-weight-bold" onclick="filterSelection('all')"> Show all</button>
                            <div class="col-lg-9"></div>
                            @foreach($labss as $lab)
                                <button class="btn col-lg-3 mr-5" style="word-wrap: break-word;white-space: pre-wrap;word-break: break-word;" onclick="filterSelection('l{{$lab->id}}')">{{$lab->name!=null?$lab->name:$lab->Arabicname}}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    @foreach($labss as $lab)
                        <label hidden>{{$devices = \App\Models\UniDevices::where('lab_id',$lab->id)->get()}}</label>
                        @foreach($devices as $device)
                            <div class="col-lg-2 col-md-6 portfolio-wrap filterDiv l{{$lab->id}}">
                                <div class="portfolio-item" style="background-color: white">
                                    <a href="{{route('browsedevice',[$device->id,$lab->id,'1',$uni_id, $uniname])}}">
                                        <img src="{{asset($device->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="">
                                    </a>
                                    <h6 class="text-center" href="{{route('browsedevice',[$device->id,$lab->id,'1',$uni_id, $uniname])}}">{{$device->name!=null?$device->name:$device->Arabicname}}</h6>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section><!-- End Portfolio Section -->
    </main>


    <script type="text/javascript" src="ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        // filterSelection("all")
        function filterSelection(c) {
            var x, i;
            x = document.getElementsByClassName("filterDiv");
            if (c == "all") c = "";
            for (i = 0; i < x.length; i++) {
                w3RemoveClass(x[i], "show");
                if (x[i].className.indexOf(c) > -1) {
                    w3AddClass(x[i], "show");
                    w3AddClass(x[i],'active');
                }
            }
        }

        function w3AddClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
            }
        }

        function w3RemoveClass(element, name) {
            var i, arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                while (arr1.indexOf(arr2[i]) > -1) {
                    arr1.splice(arr1.indexOf(arr2[i]), 1);
                }
            }
            element.className = arr1.join(" ");
        }

        // Add active class to the current button (highlight it)
        var btnContainer = document.getElementById("myBtnContainer");
        var btns = btnContainer.getElementsByClassName("btn");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function(){
                var current = document.getElementsByClassName("active");
                current[0].className = current[0].className.replace(" active", "");
                this.className += " active";
            });
        }
    </script>

    {{--    <script>--}}
    {{--        $(".lab").click(function(){--}}
    {{--            var class_show = '.'+ this.id;--}}
    {{--            var id_show = '#'+this.id;--}}
    {{--            $(class_show).css("display", "block");--}}
    {{--            $(".col-lg-2 .col-md-6 .portfolio-wrap").not(class_show).css("display", "none");--}}
    {{--        })--}}
    {{--    </script>--}}
    {{--    <style>--}}
    {{--        .lab:hover{--}}
    {{--            color: #0d0d0d;--}}
    {{--        }--}}
    {{--    </style>--}}
    <!-- Vendor JS Files -->
    <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
    <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
    <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>

    {{--    <!-- Template Main JS File -->--}}
    <script src="{{asset('assets/js/main.js')}}"></script>



@endsection
