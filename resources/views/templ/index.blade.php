@extends('templ.head')

@section('tmplt-contnt')
    <main id="main">
        <!-- ======= Hero Section ======= -->
        <section id="hero" class="d-flex justify-content-center align-items-center">
            <div id="carouselExampleIndicators" class="carousel slide w-100 " data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100 img-fluid" src="{{asset('1b.png')}}"   alt="First slide" >
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{asset('2b-last-ed.png')}}"  alt="Second slide" >
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{asset('3b.png')}}"   alt="Third slide" >
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100 img-fluid" src="{{asset('4b.png')}}"   alt="Forth slide" >
                    </div>
                </div>
            </div>
        </section>
        <!-- End Hero -->
<!-- Previous and Next button-->
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

        <!-- ======= Statistics Section ======= -->
        <section class="services">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-3" data-aos="fade-up">
                        <div class="icon-box icon-box-pink">
                            <div class="icon"><i class="bx bxs-bank"></i></div>
                            <a class="nav-link fw-bold" href="{{ route('browse') }}">
                                <h4 class="title">{{$universitys}} </h4>
                                <h4 class="title">Universities</h4>
                                <p class="description text-hide">quas molestias excepturi sint</p>
                            </a>            
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon-box icon-box-blue">
                          
                            <div class="icon"><i class="bx bxs-school"></i></div>
                                <a class="nav-link fw-bold" href="{{ route('institutions') }}">
                                    <h4 class="title">{{$institutes}}</h4>
                                    <h4 class="title">Institutes</h4>
                                    <p class="description text-hide">quas molestias excepturi sint</p>
                                </a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="icon-box icon-box-cyan">
                            <div class="icon"><i class="bx bxs-vial"></i></div>
                            <a class="nav-link fw-bold" href="{{ route('institutions') }}">
                                <h4 class="title">{{$labs}} </h4>
                                <h4 class="title">Registered labs</h4>
                                <p class="description text-hide">quas molestias excepturi sint</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="250">
                        <div class="icon-box icon-box-green">
                            <div class="icon"><i class="bx bx-plug"></i></div>
                            <a class="nav-link fw-bold" href="{{ route('allDevices') }}">
                                <h4 class="title">{{$devices}} </h4>
                                <h4 class="title">Equipment</h4>
                                <p class="description text-hide">quas molestias excepturi sint</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<section id="news" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Latest News & Events</h2>
            <p class="lead text-muted">Follow the latest unit activities</p>
        </div>

        @foreach($news->chunk(3) as $newsChunk)
            <div class="row mb-4">
                @foreach($newsChunk as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card news-card h-100 border-0 shadow-sm animate__animated">
                            <div class="position-relative">
                                <a href="">
                                    <img src="{{ $item->img_path ? asset('storage/' . $item->img_path) : asset('images/default-news.png') }}" 
                                         alt="{{ $item->title }}" 
                                         class="card-img-top" 
                                         loading="lazy" />
                                          <div class="date-box">
                                        <h5 class="mb-0 text-white">{{ $item->publish_date->format('d') }}</h5>
                                        <small class="text-white">{{ $item->publish_date->format('M') }}</small>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="card-body mt-3">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt me-1 text-success"></i>
                                        <span class="fw-bold mb-0 text-muted">{{ $item->location }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-clock me-1 text-primary"></i>
                                        <span class="fw-bold mb-0 text-muted">{{ \Carbon\Carbon::parse($item->time)->format('h:i A') }}</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="text-bold mb-0">
                                        {{ $item->title }}
                                    </h5>
                                </div>
                               <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('news.public.details', $item) }}" class="btn btn-primary  ms-auto">
                                        Details
                                      <i class="fas fa-arrow-right me-1"></i>

                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
    </main><!-- End #main -->
<style>
    /* ===== صندوق التاريخ ===== */
.date-box {
  position: absolute;
  bottom: -25px; /* نصه تحت الصورة */
  left: 10px;
  background-color: #1a8d29ff; /* اللون الجديد */
  color: #fff;
  padding: 5px;
  border-radius: 6px;
  text-align: center;
  width: 60px; /* أكبر شوية */
  height: 60px; /* أكبر شوية */
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  line-height: 1;
  z-index: 2;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.date-box h5 {
  font-size: 18px; /* أكبر */
  font-weight: bold;
  margin: 0;
}

.date-box small {
  font-size: 12px; /* أكبر */
  text-transform: uppercase;
}
</style>
    <!-- Vendor JS Files -->
@endsection
