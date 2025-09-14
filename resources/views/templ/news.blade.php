@extends('templ.head')
@section('tmplt-contnt')
    <main id="main">

<section id="news-details" dir="rtl">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">{{ $newsItem->title }}</h2>
            <!-- <p class="lead text-muted">تفاصيل الخبر</p> -->
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    @if($newsItem->img_path)
                        <img src="{{ asset('storage/' . $newsItem->img_path) }}" class="card-img-top" alt="News Image">
                    @else
                        <img src="{{ asset('images/default-news.png') }}" class="card-img-top" alt="Default News Image">
                    @endif
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            @if($newsItem->university)
                                 <strong>university:</strong> {{ $newsItem->university->name }}
                            @endif
                        </p>

                        <p class="card-text">{!! $newsItem->desc !!}</p>

                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="btn btn-primary px-4">
                                <i class="fas fa-arrow-right me-2"></i> Back to News
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
    </main>
@section('styles')
<style>
    .carousel-item img {
        object-fit: cover;
    }
    
    .card-text {
        line-height: 1.8;
        color: #34495e;
    }
</style>
