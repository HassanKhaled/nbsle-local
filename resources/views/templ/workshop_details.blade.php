@extends('templ.head')

@section('tmplt-contnt')

<main id="main">
    <section class="breadcrumbs bg-color shadow-lg">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2> Workshops</h2>
        </div>

      </div>
    </section>

    <div class="container py-3">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Header Cover Image --}}
            <div class="position-relative" style="height: 300px; overflow: hidden;">
                <img src="{{ $workshop->workshop_logoPath ? asset($workshop->workshop_logoPath) : asset('images/default-workshop.png') }}" 
                     class="w-100 h-100 object-fit-cover" 
                     alt="{{ $workshop->workshop_en_title ?? $workshop->workshop_ar_title }}">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
                    <h2 class="fw-bold mb-2">{{ $workshop->workshop_en_title ?? $workshop->workshop_ar_title }}</h2>
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt text-warning me-2"></i>{{ $workshop->place }}
                    </p>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body bg-white">

                {{-- Info Section --}}
                <div class="row gy-4 mt-2 text-center">
                    <div class="col-md-4 col-12">
                        <div class="border p-3 rounded-3 h-100">
                            <i class="far fa-calendar text-primary fs-3 mb-2"></i>
                            <div class="fw-bold">Start Date</div>
                            <div class="text-muted">
                                {{ \Carbon\Carbon::parse($workshop->st_date)->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="border p-3 rounded-3 h-100">
                            <i class="far fa-calendar-times text-danger fs-3 mb-2"></i>
                            <div class="fw-bold">End Date</div>
                            <div class="text-muted">
                                {{ \Carbon\Carbon::parse($workshop->end_date)->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="border p-3 rounded-3 h-100">
                            <i class="fas fa-user text-success fs-3 "></i>
                            <div class="fw-bold">Representative</div>
                            <div class="text-muted">{{ $workshop->rep_name }} - {{ $workshop->rep_email }}</div>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
              

                <div class="card my-3">
                    <div class="card-header bg-secondary text-white">
                        <h5>Arabic Lecturers</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($workshop->Lec_ar_names) && is_array($workshop->Lec_ar_names))
                            @foreach($workshop->Lec_ar_names as $index => $lecName)
                                <div class="mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold">Lecturer {{ $index + 1 }}</h6>
                                    <p><strong>Name:</strong> {{ $lecName }}</p>
                                    <p><strong>Details:</strong> {{ $workshop->Lec_ar_details[$index] ?? '—' }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No Arabic lecturers added.</p>
                        @endif
                    </div>
                </div>
                <div class="card my-3">
                    <div class="card-header bg-secondary text-white">
                        <h5>English Lecturers</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($workshop->Lec_en_names) && is_array($workshop->Lec_en_names))
                            @foreach($workshop->Lec_en_names as $index => $lecName)
                                <div class="mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold">Lecturer {{ $index + 1 }}</h6>
                                    <p><strong>Name:</strong> {{ $lecName }}</p>
                                    <p><strong>Details:</strong> {{ $workshop->Lec_en_details[$index] ?? '—' }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No English lecturers added.</p>
                        @endif
                    </div>
                </div>

                @if($workshop->notes)
                <div class="mt-4 border rounded-3 bg-light p-3">
                    <h6 class="fw-bold mb-2"><i class="fas fa-sticky-note text-primary me-2"></i>Notes</h6>
                    <p class="mb-0 text-secondary">{{ $workshop->notes }}</p>
                </div>
                @endif

                {{-- Footer --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 border-top pt-3">
                    <div class="text-muted mb-3 mb-md-0">
                        <i class="fas fa-eye text-primary me-1"></i> {{ $workshop->views }} views
                    </div>

                    <div class="d-flex gap-2">
                        {{-- Like Button --}}
                        <button class="btn-like btn btn-outline-primary rounded-pill px-4 d-flex align-items-center gap-2" data-id="{{ $workshop->id }}">
                            <i class="fas fa-thumbs-up"></i>
                            <span id="likes-{{ $workshop->id }}">{{ $workshop->likes }}</span>
                        </button>

                        {{-- Reserve Button --}}
                        @php
                            $today = \Carbon\Carbon::today();
                            $endDate = \Carbon\Carbon::parse($workshop->end_date);
                        @endphp

                        @if ($endDate->gte($today))
                            <a href="{{ route('userworkshop', ['workshop_id' => $workshop->id]) }}" 
                            class="btn btn-success rounded-pill px-4 d-flex align-items-center gap-2">
                                <i class="fas fa-calendar-check"></i>
                                <span>Reserve Now</span>
                            </a>
                        @endif
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
                    headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" }
                });
                if (res.ok) {
                    const data = await res.json();
                    document.getElementById(`likes-${id}`).textContent = data.likes;
                    btn.classList.replace("btn-outline-primary", "btn-primary");
                } else {
                    alert("Error while liking this workshop.");
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
.card {
    transition: 0.3s ease-in-out;
}
.card:hover {
    transform: translateY(-3px);
}
.object-fit-cover {
    object-fit: cover;
}
.border {
    border-color: #dee2e6 !important;
}
.btn-like:hover, .btn-success:hover {
    transform: scale(1.05);
}
</style>
@endsection
