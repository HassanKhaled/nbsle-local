@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <section id="workshops" class="py-5">
        <div class="container">
            {{-- Flash messages --}}
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Title --}}
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Latest Workshops</h2>
                <p class="lead text-muted">Stay updated with upcoming workshops</p>
            </div>

            {{-- Workshops grid --}}
            @forelse($workshops->chunk(3) as $chunk)
                <div class="row mb-4">
                    @foreach($chunk as $workshop)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <a href="{{ route('workshops.show', $workshop->id) }}">
                                        <img src="{{ $workshop->workshop_logoPath 
                                                    ? asset($workshop->workshop_logoPath) 
                                                    : asset('images/default-workshop.png') }}" 
                                             alt="{{ $workshop->workshop_ar_title ?? $workshop->workshop_en_title }}" 
                                             class="card-img-top" 
                                             loading="lazy">

                                        <div class="date-box position-absolute top-0 start-0 bg-primary text-white text-center p-2">
                                            <h5 class="mb-0">{{ \Carbon\Carbon::parse($workshop->st_date)->format('d') }}</h5>
                                            <small>{{ \Carbon\Carbon::parse($workshop->st_date)->format('M') }}</small>
                                        </div>
                                    </a>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold mb-2">
                                        {{ $workshop->workshop_ar_title ?? $workshop->workshop_en_title }}
                                    </h5>
                                    <p class="text-muted mb-3">
                                        {{ Str::limit($workshop->notes, 100) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1 text-success"></i>
                                            {{ $workshop->place }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="far fa-calendar me-1 text-primary"></i>
                                            {{ \Carbon\Carbon::parse($workshop->st_date)->format('d M Y') }}
                                        </span>
                                    </div>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="fas fa-eye text-primary me-1"></i>
                                            {{ $workshop->views }}
                                        </span>

                                        <button class="btn-like btn btn-outline-primary btn-sm d-flex align-items-center" 
                                                data-id="{{ $workshop->id }}">
                                            <i class="fas fa-thumbs-up me-1"></i>
                                            <span id="likes-{{ $workshop->id }}">{{ $workshop->likes }}</span>
                                        </button>

                                        <a href="{{ route('workshops.show', $workshop->id) }}" 
                                           class="btn btn-primary btn-sm d-flex align-items-center">
                                            <span class="me-1">Details</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="text-center">No workshops available at the moment.</p>
            @endforelse
        </div>
    </section>
</main>
{{-- Scripts --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener("DOMContentLoaded", () => {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    //Like button handler
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
                    if (data.likes !== undefined) {
                        document.getElementById(`likes-${id}`).textContent = data.likes;
                    }
                } else {
                    console.error("Error:", res.statusText);
                    alert("Something went wrong. Please try again.");
                }
            } catch (err) {
                console.error("Fetch error:", err);
                alert("Something went wrong. Please try again.");
            } finally {
                btn.disabled = false;
            }
        });
    });
});
</script>

@endsection
