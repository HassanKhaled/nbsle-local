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

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

  <div class="container py-3">
    <div class="row">

        @forelse($workshops as $reg)

            @php
                $hasRequest = $requests->where('workshop_id', $reg->workshop->id)->first();
                $attendanceDays = \App\Models\WorkshopAttendance::where('workshop_id', $reg->workshop->id)
                    ->where('user_id', auth()->id())
                    ->pluck('day_number')
                    ->unique()
                    ->sort();
            @endphp

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $reg->workshop->workshop_ar_title ?? $reg->workshop->workshop_en_title }}
                        </h5>

                        <p class="card-text">
                            {{ $reg->workshop->description ?? '' }}
                        </p>

                        <div class="fw-bold">Start Date</div>
                        <div class="text-muted">
                            {{ \Carbon\Carbon::parse($reg->workshop->st_date)->format('d M Y') }}
                        </div>
                        <div class="text-muted">
                            {{ \Carbon\Carbon::parse($reg->workshop->st_date)->format('h:i A') }} -
                            {{ \Carbon\Carbon::parse($reg->workshop->end_date)->format('h:i A') }}
                        </div>

                        {{-- Show View Request Button if request exists --}}
                        
                        @if($hasRequest)

                            @if($hasRequest->status === 'rejected')
                                <button class="btn btn-warning w-100 mt-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRequestModal{{ $reg->id }}">
                                    ✏️ Edit Request
                                </button>

                            @elseif($hasRequest->status === 'pending')
                                <!-- <button class="btn btn-primary w-100 mt-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#newCertificateModal{{ $reg->id }}">
                                    ➕ New Certificate Request
                                </button> -->

                            
                                <button class="btn btn-outline-success w-100 mt-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#requestDetailsModal{{ $reg->id }}">
                                    View Certificate Request
                                </button>
                            @endif

                        @else
                            <button class="btn btn-primary w-100 mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="#certificateModal{{ $reg->id }}">
                                Apply for Certificate
                            </button>
                        @endif


                    </div>
                </div>
            </div>

            {{-- ============================= --}}
            {{-- Certificate Modal (Only if NO request) --}}
            {{-- ============================= --}}
            @if(!$hasRequest)
            <div class="modal fade" id="certificateModal{{ $reg->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('certificate.request') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Certificate Request</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="workshop_id" value="{{ $reg->workshop->id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Number of Certificates</label>
                                   <input type="number"
                                        name="cert_count"
                                        id="certCount{{ $reg->id }}"
                                        class="form-control cert-count"
                                        min="1"
                                        value="1"
                                        data-target="totalCost{{ $reg->id }}"
                                        required>

                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Select Attendance Days</label>

                                    @foreach($attendanceDays as $day)
                                        <div class="form-check">
                                           <input class="form-check-input attendance-day"
                                                type="checkbox"
                                                name="days[]"
                                                value="{{ $day }}"
                                                id="day{{ $reg->id }}_{{ $day }}"
                                                data-target="totalCost{{ $reg->id }}"
                                                data-cert="certCount{{ $reg->id }}" required>
                                            <label class="form-check-label"
                                                for="day{{ $reg->id }}_{{ $day }}">
                                                Day {{ $day }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="alert alert-info mt-3">
                                    💳 <strong>Payment Method:</strong> InstaPay <br>
                                    Please pay using InstaPay and upload the payment receipt below.
                                    <br>
                                    The certificate costs 100 Egyptian pounds per day, plus an additional 7 Egyptian pounds for services.
                                    Account Number: 1010001000175584
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Total Cost</label>
                                    <input type="text" id="totalCost{{ $reg->id }}"
                                        class="form-control" readonly value="0 EGP">
                                    <small class="text-muted">100 EGP per selected day</small>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Upload Payment Receipt</label>
                                    <input type="file" name="image_receipt"
                                        class="form-control"
                                        accept="image/*"
                                        required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
           @if($hasRequest && $hasRequest->status === 'confirmed')
            <div class="modal fade" id="newCertificateModal{{ $reg->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('certificate.request') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Certificate Request</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="workshop_id" value="{{ $reg->workshop->id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Number of Certificates</label>
                                   <input type="number"
                                        name="cert_count"
                                        id="certCount{{ $reg->id }}"
                                        class="form-control cert-count"
                                        min="1"
                                        value="1"
                                        data-target="totalCost{{ $reg->id }}"
                                        required>

                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Select Attendance Days</label>

                                    @foreach($attendanceDays as $day)
                                        <div class="form-check">
                                           <input class="form-check-input attendance-day"
                                                type="checkbox"
                                                name="days[]"
                                                value="{{ $day }}"
                                                id="day{{ $reg->id }}_{{ $day }}"
                                                data-target="totalCost{{ $reg->id }}"
                                                data-cert="certCount{{ $reg->id }}" required>
                                            <label class="form-check-label"
                                                for="day{{ $reg->id }}_{{ $day }}">
                                                Day {{ $day }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="alert alert-info mt-3">
                                    💳 <strong>Payment Method:</strong> InstaPay <br>
                                    Please pay using InstaPay and upload the payment receipt below.
                                    <br>
                                    The certificate costs 100 Egyptian pounds per day, plus an additional 7 Egyptian pounds for services.
                                    Account Number: 1010001000175584
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Total Cost</label>
                                    <input type="text" id="totalCost{{ $reg->id }}"
                                        class="form-control" readonly value="0 EGP">
                                    <small class="text-muted">100 EGP per selected day</small>
                                </div>

                                <div class="mb-3">
                                    <label class="fw-bold">Upload Payment Receipt</label>
                                    <input type="file" name="image_receipt"
                                        class="form-control"
                                        accept="image/*"
                                        required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if($hasRequest && $hasRequest->status === 'rejected')
                <div class="modal fade" id="editRequestModal{{ $reg->id }}">
                    <div class="modal-dialog">
                       <form method="POST"
                            action="{{ route('certificate.request.update', $hasRequest->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Certificate Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                   
                                    <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $hasRequest->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $hasRequest->email }}" required>
                                </div>
                                    {{-- Certificates Count --}}
                                    <div class="mb-3">
                                        <label>Number of Certificates</label>
                                        <input type="number"
                                            name="cert_count"
                                            id="certCountEdit{{ $reg->id }}"
                                            class="form-control cert-count"
                                            min="1"
                                            value="{{ $hasRequest->cert_count }}"
                                            data-target="totalCostEdit{{ $reg->id }}"
                                            required>
                                    </div>

                                    {{-- Attendance Days --}}
                                    <div class="mb-3">
                                        <label class="fw-bold">Select Attendance Days</label>

                                        @foreach($attendanceDays as $day)
                                            <div class="form-check">
                                                <input class="form-check-input attendance-day"
                                                    type="checkbox"
                                                    name="days[]"
                                                    value="{{ $day }}"
                                                    id="editDay{{ $reg->id }}_{{ $day }}"
                                                    data-target="totalCostEdit{{ $reg->id }}"
                                                    data-cert="certCountEdit{{ $reg->id }}"
                                                    {{ in_array($day, $hasRequest->days ?? []) ? 'checked' : '' }} required>
                                                <label class="form-check-label"
                                                    for="editDay{{ $reg->id }}_{{ $day }}">
                                                    Day {{ $day }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Payment Info --}}
                                    <div class="alert alert-warning mt-3">
                                      The previous request has been rejected for one of the following possible reasons:
                                        - Full name   - Email address  - Receipt
                                        <br>
                                        Please review these details and resubmit the request.
                                        <br>
                                        The certificate costs 100 Egyptian pounds per day, plus an additional 7 Egyptian pounds for services.
                                    </div>

                                    {{-- Total Cost --}}
                                    <div class="mb-3">
                                        <label class="fw-bold">Total Cost</label>
                                        <input type="text"
                                            id="totalCostEdit{{ $reg->id }}"
                                            class="form-control"
                                            readonly
                                            value="{{ $hasRequest->cost }} EGP">
                                    </div>

                                    {{-- Upload New Receipt --}}
                                    <div class="mb-3">
                                        <label class="fw-bold">Upload New Payment Receipt</label>
                                        <input type="file"
                                            name="image_receipt"
                                            class="form-control"
                                            accept="image/*"
                                            required>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">
                                        Update & Resubmit
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                @endif


            {{-- ============================= --}}
            {{-- Request Details Modal (Only if request exists) --}}
            {{-- ============================= --}}
            @if($hasRequest)
            <div class="modal fade" id="requestDetailsModal{{ $reg->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Certificate Request Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Name:</strong> {{ $hasRequest->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Email:</strong> {{ $hasRequest->email }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Workshop:</strong>
                                    {{ $reg->workshop->workshop_ar_title ?? $reg->workshop->workshop_en_title }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Certificates Count:</strong> {{ $hasRequest->cert_count }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Request Date:</strong>
                                    {{ $hasRequest->created_at->format('d M Y - h:i A') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Cost:</strong>
                                    {{ $hasRequest->cost }} EGP
                                </li>
                                 <li class="list-group-item">
                                    <strong>Attendance:</strong>
                                     Day{{ implode(', Day ', $hasRequest->days ?? []) }}
                                 </li>
                                @if(isset($hasRequest->status))
                                    <li class="list-group-item">
                                        <strong>Status:</strong>

                                        @if($hasRequest->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                ⏳ Pending Review
                                            </span>
                                            <div class="mt-2 text-muted small">
                                                Your payment is under review. Please wait for confirmation.
                                            </div>

                                        @elseif($hasRequest->status === 'confirmed')
                                            <span class="badge bg-success">
                                                ✅ Payment Confirmed
                                            </span>
                                            <div class="mt-2 text-success small">
                                                Payment confirmed. Please wait for the certificate and follow your request.
                                            </div>

                                        @elseif($hasRequest->status === 'rejected')
                                            <span class="badge bg-danger">
                                                ❌ Rejected
                                            </span>
                                            <div class="mt-2 text-danger small">
                                                Your request was rejected. Please contact support.
                                            </div>

                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($hasRequest->status) }}
                                            </span>
                                        @endif
                                    </li>
                                @endif


                                <li class="list-group-item">
                                    <strong>Payment Receipt:</strong><br>

                                    @if($hasRequest->image_receipt)
                                        <a href="{{ asset('storage/'.$hasRequest->image_receipt) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary mt-2">
                                            View Receipt
                                        </a>
                                    @else
                                        <span class="text-muted">No receipt uploaded</span>
                                    @endif
                                </li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            @endif


        @empty
            <div class="col-12 text-center">
                <p class="text-muted">You are not registered in any workshops.</p>
            </div>
        @endforelse

    </div>
  </div>

</main>
<script>
function calculateCost(targetId, certInputId) {
    let daysCount = document.querySelectorAll(
        '[data-target="'+targetId+'"]:checked'
    ).length;

    let certCount = document.getElementById(certInputId).value || 1;

    let total = daysCount * 100 * certCount;
    document.getElementById(targetId).value = total + 7 + ' EGP';
}

// days change
document.querySelectorAll('.attendance-day').forEach(cb => {
    cb.addEventListener('change', function () {
        calculateCost(this.dataset.target, this.dataset.cert);
    });
});

// certificate count change
document.querySelectorAll('.cert-count').forEach(input => {
    input.addEventListener('input', function () {
        calculateCost(this.dataset.target, this.id);
    });
});
</script>


@endsection
