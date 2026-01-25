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
            <div class="position-relative">
                <img src="{{ $workshop->workshop_logoPath ? asset($workshop->workshop_bannerPath) : asset('images/default-workshop.png') }}" 
                      class="w-100 h-100 object-fit-contain"
                     alt="{{ $workshop->workshop_en_title ?? $workshop->workshop_ar_title }}"> 
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
                            <div class="text-muted">
                                {{ \Carbon\Carbon::parse($workshop->st_date)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($workshop->end_date)->format('h:i A') }}
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
                            <div class="text-muted">
                                {{ \Carbon\Carbon::parse($workshop->st_date)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($workshop->end_date)->format('h:i A') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="border p-3 rounded-3 h-100">
                            <i class="fas fa-location-dot text-success fs-3 "></i>
                            <div class="fw-bold">Place</div>
                            <div class="text-muted">{{ $workshop->place }}</div>
                        </div>
                    </div>
                </div>

                {{-- Contact --}}
            
               @php
    $total = $workshop->no_lecturers ?? 0;

    $hasAr = is_array($workshop->Lec_ar_names) && count($workshop->Lec_ar_names);
    $hasEn = is_array($workshop->Lec_en_names) && count($workshop->Lec_en_names);
@endphp

@if($total > 0)
    @for($i = 0; $i < $total; $i++)
        <div class="card my-4 shadow">

            <div class="card-header bg-dark text-white text-center">
                <h5 class="mb-0  text-white" >Day {{ $i + 1 }}</h5>
            </div>

            <div class="card-body">

                <div class="row">
                       {{-- English Card --}}
                    @if($hasEn && !empty($workshop->Lec_en_names[$i]))
                        <div class="col-md-6 mb-3">
                            <div class="card border-success h-100" dir="ltr">
                                <div class="card-header bg-success text-white text-center">
                                    Session Details
                                </div>
                                <div class="card-body">

                                    <p><strong>Speaker Name:</strong>
                                        {{ $workshop->Lec_en_names[$i] }}
                                    </p>

                                    <p><strong>Speaker Information:</strong>
                                        {{ $workshop->Lec_en_details[$i] ?? '—' }}
                                    </p>

                                    <p><strong>Session Title:</strong>
                                        {{ $workshop->Ses_en_title[$i] ?? '—' }}
                                    </p>

                                    <p><strong>Session Description:</strong>
                                        {{ $workshop->Ses_en_details[$i] ?? '—' }}
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Arabic Card --}}
                    @if($hasAr && !empty($workshop->Lec_ar_names[$i]))
                        <div class="col-md-6 mb-3">
                            <div class="card border-success h-100" dir="rtl">
                                <div class="card-header bg-success text-white text-center">
                                    تفاصيل الجلسة 
                                </div>
                                <div class="card-body text-right">

                                    <p ><strong>اسم المحاضر:</strong>
                                        {{ $workshop->Lec_ar_names[$i] }}
                                    </p>

                                    <p><strong> معلومات عن المحاضر:</strong>
                                        {{ $workshop->Lec_ar_details[$i] ?? '—' }}
                                    </p>

                                    <p><strong>عنوان الجلسة:</strong>
                                        {{ $workshop->Ses_ar_title[$i] ?? '—' }}
                                    </p>

                                    <p><strong>وصف الجلسة:</strong>
                                        {{ $workshop->Ses_ar_details[$i] ?? '—' }}
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endif

                   

                    {{-- No Data --}}
                    @if(
                        empty($workshop->Lec_ar_names[$i]) &&
                        empty($workshop->Lec_en_names[$i])
                    )
                        <div class="col-12">
                            <p class="text-muted text-center">
                                No data available for this day
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endfor
@else
    <p class="text-muted text-center">No lecturers added.</p>
@endif


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
                            @if ($workshop->registrations_count >= 800)
                                <button class="btn btn-secondary rounded-pill px-4" disabled>
                                عذراً اكتمل العدد وسيتم تحديد موعد آخر لاحقاً
                                </button>
                            @else
                                <a href="{{ route('userworkshop', ['workshop_id' => $workshop->id]) }}"
                                class="btn btn-success rounded-pill px-4 d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Apply</span>
                                </a>
                            @endif
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
