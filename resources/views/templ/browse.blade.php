@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

    <!-- ======= Top green Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>All Universities</h2>
        </div>

      </div>
    </section><!-- End Top green Section -->

    <!-- =======  Section ======= -->
        <section class="portfolio">
            <div class="container">
                {{---------------------- Filter Universities By Type --------------------}}
                <div class="row">
                    <div class="col-lg-12">
                        <ul id="portfolio-flters">
                            <li data-filter=".filter-public">Governmental</li>
                            <li data-filter=".filter-private">Private</li>
                            <li data-filter=".filter-ahli">National</li>
                        </ul>
                  </div>
                </div>
                {{--------------------- List All Universities --------------------------}}
                <div class="row portfolio-container" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    @foreach($unis as $u => $uni)
                        <div class="col-lg-3 col-md-6 portfolio-wrap filter-{{$uni->type}} p-5" > {{-- for filtering --}}
                            <div class="portfolio-item " style="background-color: white">
                                <a href="{{route('browseuniversity',[$uni->id,$uni->name])}}">
                                    <img src="{{asset($uni->ImagePath)}}" class="img-fluid" style="width:200px;height:200px;" alt="{{$uni->ImagePath}}">
                                </a>
                                <a href="{{route('browseuniversity',[$uni->id,$uni->name])}}" style="text-decoration: none;color: black"><h6 class="text-center" >{{$uni->name}}</h6></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section><!-- End  Section -->
    </main>

    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
@endsection
