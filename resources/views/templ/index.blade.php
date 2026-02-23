@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <!-- ======= Hero Section ======= -->
    <section id="her" class="d-flex justify-content-center align-items-center p-0">
        <div id="heroCarousel" class="carousel slide w-100" data-bs-ride="carousel">
            <!-- Indicators -->
            <!-- <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div> -->

            <!-- Carousel Inner -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('NewBanner.png') }}" class="d-block w-100 img-fluid carousel-image" alt="Slide 1">
                </div>
                <!-- <div class="carousel-item">
                    <img src="{{ asset('NewBanner.png') }}" class="d-block w-100 img-fluid" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('NewBanner.png') }}" class="d-block w-100 img-fluid" alt="Slide 3">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('NewBanner.png') }}" class="d-block w-100 img-fluid" alt="Slide 4">
                </div> -->
            </div>

            <!-- Controls -->
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button> -->
        </div>
    </section>
    <!-- End Hero Section -->

    <!-- ======= Statistics Section ======= -->

    <section id="news" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Latest News & Events</h2>
                <p class="lead text-muted">Follow the latest unit activities</p>
            </div>

            @foreach($items->chunk(3) as $row)
            <div class="row mb-4">

                @foreach($row as $item)
                <div class="col-md-4 mb-4">

                    {{-- NEWS CARD --}}
                    @if($item->type === 'news')
                    {{-- ⬇️ EXACT SAME NEWS CARD CODE YOU ALREADY HAVE --}}
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
                                    <span class="mb-0 text-muted">{{ $item->location }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-clock me-1 text-primary"></i>
                                    <span class="mb-0 text-muted">{{ \Carbon\Carbon::parse($item->time)->format('h:i A') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="text-bold mb-0">{{ $item->title }}</h5>
                                <span class="text-muted">{{ Str::limit($item->desc, 100) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted d-flex align-items-center me-3">
                                    <i class="fas fa-eye text-primary me-1"></i>
                                    {{ $item->views }}
                                </span>

                                <button class="btn-like btn btn-outline-success btn-sm d-flex align-items-center" data-id="{{ $item->id }}">
                                    <i class="fas fa-thumbs-up me-1"></i>
                                    <span id="likes-{{ $item->id }}">{{ $item->likes }}</span>
                                </button>

                                <a href="{{ route('news.public.details', $item) }}"
                                    class="btn btn-primary d-flex align-items-center">
                                    <span class="me-1">Details</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- WORKSHOP CARD --}}
                    @if($item->type === 'workshop')
                    {{-- ⬇️ EXACT SAME WORKSHOP CARD CODE YOU ALREADY HAVE --}}
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <a href="{{ route('workshops.show', $item->id) }}">
                                <img src="{{ $item->workshop_logoPath ? asset($item->workshop_logoPath) : asset('images/default-workshop.png') }}"
                                    alt="{{ $item->workshop_ar_title ?? $item->workshop_en_title }}"
                                    class="card-img-top"
                                    loading="lazy">

                                <div class="date-box">
                                    <h5 class="mb-0 text-white">{{ \Carbon\Carbon::parse($item->st_date)->format('d') }}</h5>
                                    <small>{{ \Carbon\Carbon::parse($item->st_date)->format('M') }}</small>
                                </div>
                            </a>
                        </div>

                        <div class="card-body d-flex flex-column mt-4">
                            <h5 class="fw-bold mb-2">
                                {{ $item->workshop_ar_title ?? $item->workshop_en_title }}
                            </h5>
                            <p class="text-muted mb-3">
                                {{ Str::limit($item->notes, 100) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1 text-success"></i>
                                    {{ $item->place }}
                                </span>
                                <span class="text-muted">
                                    <i class="far fa-calendar me-1 text-success"></i>
                                    {{ \Carbon\Carbon::parse($item->st_date)->format('d M Y') }}
                                </span>
                            </div>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-eye text-success me-1"></i>
                                    {{ $item->views }}
                                </span>

                                <button class="btn-like1 btn btn-outline-success btn-sm d-flex align-items-center"
                                    data-id="{{ $item->id }}">
                                    <i class="fas fa-thumbs-up me-1"></i>
                                    <span id="likes-{{ $item->id }}">{{ $item->likes }}</span>
                                </button>

                                <a href="{{ route('workshops.show', $item->id) }}"
                                    class="btn btn-primary btn-sm d-flex align-items-center">
                                    <span class="me-1">Details</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @endforeach

            </div>
            @endforeach

        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Interactive Calender</h2>
                <p class="lead text-muted">For latest News & Workshops</p>
            </div>
            @include('templ.calendar')
        </div>
    </section>


    @if(isset($enrichedData))
    <style>
        /* --- STYLISH HEADER START --- */
        .services h2.section-title {
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #2d3436;
            position: relative;
            padding-bottom: 20px;
            margin-bottom: 50px !important;
        }

        .services h2.section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 4px;
            background-color: #007bff;
            /* You can change this color to match your brand */
            border-radius: 2px;
        }

        /* --- STYLISH HEADER END --- */

        /* Exact control over the spacing between cards and rows */
        .services .university-card-link {
            margin: 5px !important;
            display: inline-block;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .services .university-card {
            padding: 15px !important;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);

            /* SQUARE SHAPE logic */
            width: 160px;
            aspect-ratio: 1 / 1;
            flex-direction: column;
            text-align: center;
        }

        .services .row {
            margin: 0 !important;
        }

        .services .col-auto {
            padding: 0 !important;
        }

        .university-card-link:hover {
            transform: translateY(-3px);
        }
    </style>

    <section class="services py-5">
        <div class="container">
            <h2 class="text-center section-title font-weight-light">Data Quality Rank</h2>

            @php
            $data = collect($enrichedData);
            @endphp

            {{-- Row 1: Rank 1 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(0, 1) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 2: Rank 2 & 3 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(1, 2) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 3: Rank 4, 5 & 6 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(3, 3) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 4: The Rest --}}
            <div class="row justify-content-center">
                @foreach($data->slice(6) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    <!-- @if(isset($enrichedData))
    <style>
        /* Exact control over the spacing between cards and rows */
        .services .university-card-link {
            margin: 5px !important;
            /* Exact 5px gap around every card */
            display: inline-block;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .services .university-card {
            /* Restoring internal padding so content isn't touching the card borders */
            padding: 15px !important;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);

            /* --- SQUARE SHAPE LOGIC --- */
            width: 160px;
            /* Reduced from 220px to make them smaller */
            aspect-ratio: 1 / 1;
            /* Keeps it square */
            flex-direction: column;
            text-align: center;
            /* ------------------------- */
        }

        /* Cleaning up Bootstrap defaults to let our 5px margin rule the layout */
        .services .row {
            margin: 0 !important;
        }

        .services .col-auto {
            padding: 0 !important;
        }

        .university-card-link:hover {
            transform: translateY(-3px);
        }
    </style>

    <section class="services py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-dark font-weight-light">Data Quality Rank</h2>

            @php
            $data = collect($enrichedData);
            @endphp

            {{-- Row 1: Rank 1 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(0, 1) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 2: Rank 2 & 3 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(1, 2) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 3: Rank 4, 5 & 6 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(3, 3) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 4: The Rest --}}
            <div class="row justify-content-center">
                @foreach($data->slice(6) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif -->


    <!-- @if(isset($enrichedData))
    <style>
        /* Exact control over the spacing between cards and rows */
        .services .university-card-link {
            margin: 5px !important;
            /* Exact 5px gap around every card */
            display: inline-block;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .services .university-card {
            /* Restoring internal padding so content isn't touching the card borders */
            padding: 15px !important;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);

            /* --- SQUARE SHAPE LOGIC START --- */
            width: 220px;
            /* Set a fixed width */
            aspect-ratio: 1 / 1;
            /* Forces height to match width */
            flex-direction: column;
            /* Ensures content stacks vertically inside the square */
            text-align: center;
            /* --- SQUARE SHAPE LOGIC END --- */
        }

        /* Cleaning up Bootstrap defaults to let our 5px margin rule the layout */
        .services .row {
            margin: 0 !important;
        }

        .services .col-auto {
            padding: 0 !important;
        }

        .university-card-link:hover {
            transform: translateY(-3px);
        }
    </style>

    <section class="services py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-dark font-weight-light">Data Quality Rank</h2>

            @php
            $data = collect($enrichedData);
            @endphp

            {{-- Row 1: Rank 1 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(0, 1) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 2: Rank 2 & 3 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(1, 2) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 3: Rank 4, 5 & 6 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(3, 3) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 4: The Rest --}}
            <div class="row justify-content-center">
                @foreach($data->slice(6) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif -->

    <!-- @if(isset($enrichedData))
    <style>
        /* Exact control over the spacing between cards and rows */
        .services .university-card-link {
            margin: 5px !important;
            /* Exact 5px gap around every card */
            display: inline-block;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .services .university-card {
            /* Restoring internal padding so content isn't touching the card borders */
            padding: 15px !important;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* Cleaning up Bootstrap defaults to let our 5px margin rule the layout */
        .services .row {
            margin: 0 !important;
        }

        .services .col-auto {
            padding: 0 !important;
        }

        .university-card-link:hover {
            transform: translateY(-3px);
        }
    </style>

    <section class="services py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-dark font-weight-light">Data Quality Rank</h2>

            @php
            $data = collect($enrichedData);
            @endphp

            {{-- Row 1: Rank 1 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(0, 1) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 2: Rank 2 & 3 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(1, 2) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 3: Rank 4, 5 & 6 --}}
            <div class="row justify-content-center">
                @foreach($data->slice(3, 3) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Row 4: The Rest --}}
            <div class="row justify-content-center">
                @foreach($data->slice(6) as $university)
                <div class="col-auto">
                    <a href="{{ route('browseuniversity', ['id' => $university['id'], 'uniname' => Illuminate\Support\Str::slug($university['name'])]) }}" class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">
                                <img src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}" alt="{{ $university['name'] }} Logo" class="card-image img-fluid" onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';">
                                <p class="university-rank">#{{ $university["rank"] }}</p>
                                <div class="university-name-container">
                                    <p class="english-name">{{ $university['name'] }}</p>
                                    <p class="arabic-name">{{ $university['Arabicname'] }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif -->


    {{-- Bootstrap JS dependencies (needed for certain components, though not strictly for this layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" xintegrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5j4J2n" crossorigin="anonymous"></script>

    <script>
        // JAVASCRIPT FOR REVEAL ANIMATION (Intersection Observer)

        // Select all the cards to observe
        const cards = document.querySelectorAll('.university-card');

        // Options for the observer (when to trigger the callback)
        const observerOptions = {
            root: null, // relative to the viewport
            rootMargin: '0px',
            threshold: 0.2 // Trigger when 20% of the item is visible
        };

        // The callback function executed when the observed element enters/exits the viewport
        const cardObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                // If the card is intersecting (visible), add the animation class
                if (entry.isIntersecting) {
                    // Use a short delay based on the card's position (for a staggered effect)
                    const index = Array.from(cards).indexOf(entry.target);
                    entry.target.style.transitionDelay = `${index * 0.08}s`;

                    entry.target.classList.add('card-visible');

                    // Stop observing once it's visible to save performance
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Start observing each card
        cards.forEach(card => {
            cardObserver.observe(card);
        });
    </script>

    <section class="services">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3" data-aos="fade-up">
                    <div class="icon-box icon-box-pink">
                        <div class="icon"><i class="bx bxs-bank"></i></div>
                        <a class="nav-link fw-bold" href="{{ route('browse') }}">
                            <h4 class="title">{{$universitys}} </h4>
                            <h4 class="title">Universities</h4>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon-box icon-box-blue">

                        <div class="icon"><i class="bx bxs-school"></i></div>
                        <a class="nav-link fw-bold" href="{{ route('institutions') }}">
                            <h4 class="title">{{$institutes}}</h4>
                            <h4 class="title">Institutes</h4>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon-box icon-box-cyan">
                        <div class="icon"><i class="bx bxs-vial"></i></div>
                        <a class="nav-link fw-bold" href="{{ route('institutions') }}">
                            <h4 class="title">{{$labs}} </h4>
                            <h4 class="title">Registered labs</h4>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="250">
                    <div class="icon-box icon-box-green">
                        <div class="icon"><i class="bx bx-plug"></i></div>
                        <a class="nav-link fw-bold" href="{{ route('allDevices') }}">
                            <h4 class="title">{{$devices}} </h4>
                            <h4 class="title">Equipment</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        // جميع أزرار اللايك
        document.querySelectorAll(".btn-like, .btn-like1").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                let type = btn.classList.contains('btn-like1') ? 'workshops' : 'news';
                btn.disabled = true;

                try {
                    const res = await fetch(`/${type}/${id}/like`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrf,
                            "Accept": "application/json"
                        }
                    });

                    if (res.ok) {
                        const data = await res.json();
                        if (data.likes !== undefined) {
                            document.getElementById(`likes-${id}`).textContent = data.likes;
                        }
                    } else {
                        console.error("Error:", res.statusText);
                        alert("Something went wrong. Please try again.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("Something went wrong. Please try again.");
                } finally {
                    btn.disabled = false;
                }
            });
        });
    });
</script>
<style>
    /* ===== Date Box ===== */
    .date-box {
        position: absolute;
        bottom: -25px;
        left: 10px;
        background-color: #1a8d29ff;
        /* للـ news */
        color: #fff;
        padding: 5px;
        border-radius: 6px;
        text-align: center;
        width: 60px;
        height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        line-height: 1;
        z-index: 2;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .date-box h5 {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .date-box small {
        font-size: 12px;
        text-transform: uppercase;
    }

    /* ===== Like Button ===== */
    .btn-like,
    .btn-like1 {
        border: none;
        background: transparent;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .btn-like i,
    .btn-like1 i {
        font-size: 1.2rem;
    }

    .btn-like:hover,
    .btn-like1:hover {
        color: #0d6efd;
    }
</style>

@endsection