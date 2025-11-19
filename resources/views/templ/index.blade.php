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
                                    <span class="mb-0 text-muted">{{ $item->location }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-clock me-1 text-primary"></i>
                                    <span class="mb-0 text-muted">{{ \Carbon\Carbon::parse($item->time)->format('h:i A') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="text-bold mb-0">
                                    {{ $item->title }}
                                </h5>
                                <span class="text-muted">{{ Str::limit($item->desc, 100) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted d-flex align-items-center me-3">
                                    <i class="fas fa-eye text-primary me-1"></i>
                                    {{ $item->views }}
                                </span>

                                <button class="btn-like d-flex align-items-center"
                                    data-id="{{ $item->id }}">
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

    <section class="services py-5">
        <div class="container">
            <h2 class="text-center mb-5 text-dark font-weight-light">Data Quality Rank</h2>

            <div class="row">
                @foreach($enrichedData as $university)
                <div class="col-sm-3 mb-4">
                    {{-- Generate the new route URL --}}
                    <a
                        href="{{ route('browseuniversity', [
                       'id' => $university['id'], 
                       // Slugify the name for a clean, URL-friendly string
                       'uniname' => Illuminate\Support\Str::slug($university['name'])
                   ]) }}"
                        class="university-card-link">
                        <div class="university-card d-flex align-items-center justify-content-center position-relative">
                            <div class="card-content">

                                {{-- University Logo --}}
                                <img
                                    src="https://nbsle.scu.eg/{{ $university['ImagePath'] }}"
                                    alt="{{ $university['name'] }} Logo"
                                    class="card-image img-fluid"
                                    onerror="this.onerror=null; this.src='https://placehold.co/90x90/555/ffffff?text=Logo';" {{-- Fallback image --}}>

                                {{-- University Rank --}}
                                <p class="university-rank">
                                    #{{ $university["rank"] }}
                                </p>

                                {{-- University Name Section (New) --}}
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
<style>
    /* ===== صندوق التاريخ ===== */
    .date-box {
        position: absolute;
        bottom: -25px;
        /* نصه تحت الصورة */
        left: 10px;
        background-color: #1a8d29ff;
        /* اللون الجديد */
        color: #fff;
        padding: 5px;
        border-radius: 6px;
        text-align: center;
        width: 60px;
        /* أكبر شوية */
        height: 60px;
        /* أكبر شوية */
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
        /* أكبر */
        font-weight: bold;
        margin: 0;
    }

    .date-box small {
        font-size: 12px;
        /* أكبر */
        text-transform: uppercase;

    }

    .btn-like {
        border: none;
        background: transparent;
        color: #41A451 !important;
        /* رمادي افتراضي */
        font-size: 1.1rem;
        /* حجم النص */
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .btn-like i {
        font-size: 1.2rem;
        /* تكبير الأيقونة */
    }

    .btn-like:hover {
        color: #0d6efd;
        /* لون primary عند الهوفر */
    }

    .carousel-image {
        object-fit: cover;
        /* ensures image covers the area */
    }

    @media (max-width: 768px) {
        .carousel-image {
            height: 40vh;
            /* reduce height on tablets */
        }
    }

    @media (max-width: 576px) {
        .carousel-image {
            height: 30vh;
            /* smaller height on phones */
        }
    }
</style>
<!-- Vendor JS Files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', '.btn-like', function() {
        var newsId = $(this).data('id');
        var url = "{{ url('/news') }}/" + newsId + "/like";

        $.post(url, {
            _token: "{{ csrf_token() }}"
        }, function(data) {
            $("#likes-" + newsId).text(data.likes);
        });
    });
</script>

@endsection