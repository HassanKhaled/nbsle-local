@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

    <!-- ======= Top green Section ======= -->
    <section class="breadcrumbs bg-color shadow-lg">
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
                        <ul id="portfolio-flters" class="text-bold fs-1">
                            <li data-filter=".filter-public">Governmental</li>
                            <li data-filter=".filter-private">Private</li>
                            <li data-filter=".filter-ahli">National</li>
                        </ul>
                  </div>
                </div>
                {{--------------------- List All Universities --------------------------}}
                <div class="row portfolio-container g-4" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">
                    @foreach($unis as $u => $uni)
                        <div class="col-lg-3 col-md-6 portfolio-wrap filter-{{ $uni->type }}">
                            <div class="card shadow-sm border-0 h-100 text-center hover-card">
                                <a href="{{ route('browseuniversity', [$uni->id, $uni->name]) }}">
                                    <img src="{{ asset($uni->ImagePath) }}" 
                                        class="card-img-top img-fluid mx-auto d-block p-3 rounded-circle" 
                                        style="width: 160px; height: 160px; object-fit: cover;" 
                                        alt="{{ $uni->name }}">
                                </a>
                                <div class="card-body">
                                    <h6 class="card-title mb-0">
                                        <a href="{{ route('browseuniversity', [$uni->id, $uni->name]) }}" 
                                        class="text-decoration-none text-dark">
                                        {{ $uni->name }}
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section><!-- End  Section -->
    </main>
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
</style>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
@endsection
