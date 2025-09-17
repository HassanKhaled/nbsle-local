@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <section id="news-details" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-primary">{{ $news->title }}</h2>
                <h2 class="lead text-dark"> Latest News & Updates</h2>
            </div>

            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                        
                        {{-- Images / Carousel --}}
                        @if($news->newsImages->isNotEmpty())
                            <div id="newsImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($news->newsImages as $key => $image)
                                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                 class="d-block w-100"
                                                 alt="News Image"
                                                 style="max-height: 450px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#newsImagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon  rounded-circle p-3" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#newsImagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon  rounded-circle p-3" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        @else
                            <img src="{{ asset('images/default-news.png') }}"
                                 alt="Default Image"
                                 class="img-fluid w-100"
                                 style="max-height: 450px; object-fit: cover;">
                        @endif

                        {{-- News Details --}}
                        <div class="card-body p-4">
                            <div class="row g-4 mb-4 text-center">
                                @if($news->publish_date)
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="far fa-calendar-alt fa-2x text-muted mb-2"></i>
                                            <!-- <h6 class="text-muted mb-1">Published</h6> -->
                                            <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($news->publish_date)->format('F j, Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($news->time)
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="far fa-clock fa-2x text-muted mb-2"></i>
                                            <!-- <h6 class="text-muted mb-1">Time</h6> -->
                                            <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($news->time)->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($news->location)
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="fas fa-map-marker-alt fa-2x text-muted mb-2"></i>
                                            <!-- <h6 class="text-muted mb-1">Location</h6> -->
                                            <p class="fw-bold mb-0">{{ $news->location }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($news->university)
                                    <div class="col-md-3">
                                        <div class="info-box">
                                            <i class="fas fa-university fa-2x text-muted mb-2"></i>
                                            <!-- <h6 class="text-muted mb-1">University</h6> -->
                                            <p class="fw-bold mb-0">{{ $news->university->name }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="news-description text-start">
                                <p class="card-text fs-4">{!! $news->desc !!}</p>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Back to News
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('styles')
<style>
    .info-box {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1rem;
        transition: all 0.3s ease-in-out;
    }
    .info-box:hover {
        background: #eef4ff;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .news-description {
        line-height: 1.8;
        color: #2c3e50;
    }
</style>
@endsection
