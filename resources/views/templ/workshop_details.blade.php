@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <div class="container py-5">
        <div class="card shadow-lg border-0">
            {{-- Header --}}
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">{{ $workshop->workshop_ar_title ?? $workshop->workshop_en_title }}</h3>
            </div>

            {{-- Body --}}
            <div class="card-body">
                {{-- Workshop image --}}
                <div class="text-center mb-4">
                    <img src="{{ $workshop->workshop_logoPath 
                                ? asset($workshop->workshop_logoPath) 
                                : asset('images/default-workshop.png') }}" 
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 280px; object-fit: contain;"
                         alt="{{ $workshop->workshop_ar_title ?? $workshop->workshop_en_title }}">
                </div>

                {{-- Info rows --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                        <strong>Place:</strong> {{ $workshop->place }}
                    </div>
                    <div class="col-md-3 mb-2">
                        <i class="far fa-calendar text-primary me-2"></i>
                        <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($workshop->st_date)->format('d M Y') }}
                    </div>
                    <div class="col-md-3 mb-2">
                        <i class="far fa-calendar text-danger me-2"></i>
                        <strong>End Date:</strong> {{ \Carbon\Carbon::parse($workshop->end_date)->format('d M Y') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <i class="fas fa-user text-warning me-2"></i>
                        <strong>Representative:</strong> {{ $workshop->rep_name }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <i class="fas fa-envelope text-info me-2"></i>
                        <strong>Email:</strong> {{ $workshop->rep_email }}
                    </div>
                </div>

                {{-- Notes --}}
                @if($workshop->notes)
                    <div class="mb-3">
                        <i class="fas fa-sticky-note text-secondary me-2"></i>
                        <strong>Notes:</strong>
                        <p class="mt-2">{{ $workshop->notes }}</p>
                    </div>
                @endif

                {{-- Footer (views + likes + reserve) --}}
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                    <span class="text-muted">
                        <i class="fas fa-eye text-primary me-1"></i>
                        {{ $workshop->views }} views
                    </span>

                    <div class="d-flex gap-2">
                        <button class="btn-like btn btn-outline-primary d-flex align-items-center" data-id="{{ $workshop->id }}">
                            <i class="fas fa-thumbs-up me-1"></i>
                            <span id="likes-{{ $workshop->id }}">{{ $workshop->likes }}</span>
                        </button>

                        {{-- ✅ Reserve button --}}
                        <a href="{{ route('userworkshop', ['workshop_id' => $workshop->id]) }}" 
                        class="btn btn-success d-flex align-items-center">
                            <i class="fas fa-calendar-check me-1"></i> Reserve
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener("DOMContentLoaded", () => {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    document.querySelectorAll(".btn-like").forEach(btn => {
        btn.addEventListener("click", async (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            btn.disabled = true;

            try {
                const res = await fetch(`/workshops/${id}/like`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Accept": "application/json"
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    document.getElementById(`likes-${id}`).textContent = data.likes;
                } else {
                    console.error(await res.text());
                    alert("Error liking this workshop.");
                }
            } catch (err) {
                console.error(err);
                alert("Something went wrong.");
            } finally {
                btn.disabled = false;
            }
        });
    });
});
</script>
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
