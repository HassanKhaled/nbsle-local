@extends('loggedTemp.head')
@section('loggedContent')
<div class="container-fluid py-5 mt-5">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-users text-primary me-2"></i> Workshop Reservations
        </h2>
        <span class="badge rounded-pill bg-gradient bg-primary px-3 py-2">
            <i class="fas fa-list me-1"></i> Total: {{ $reservations->total() }}
        </span>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th><i class="fas fa-chalkboard-teacher me-1"></i> Workshop</th>
                        <th><i class="fas fa-university me-1"></i> University</th>
                        <th><i class="fas fa-building me-1"></i> Faculty</th>
                        <th><i class="fas fa-user me-1"></i> Participant Name</th>
                        <th><i class="fas fa-venus-mars me-1"></i> Gender</th>
                        <th><i class="fas fa-envelope me-1"></i> Email</th>
                        <th><i class="fas fa-id-card me-1"></i> Type</th>
                        <th><i class="fas fa-layer-group me-1"></i> Sub Type</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($reservations->currentPage() - 1) * $reservations->perPage() + 1; @endphp

                    @forelse($reservations as $res)
                        <tr>
                            <td class="fw-bold text-muted">{{ $i++ }}</td>
                            <td class="fw-semibold text-dark">
                                @if($res->workshop->workshop_ar_title)
                                    {{$res->workshop->workshop_ar_title}}
                                @else
                                    {{$res->workshop->workshop_en_title}}
                                @endif
                            </td>
                            <td>{{ $res->workshop->university->name ?? '—' }}</td>
                            <td>{{ $res->workshop->faculty->name ?? '—' }}</td>
                            <td>{{ $res->full_name }}</td>
                            <td>
                                @if(strtolower($res->gender) === 'male')
                                    <span class="badge bg-info rounded"><i class="fas fa-mars me-1"></i> Male</span>
                                @elseif(strtolower($res->gender) === 'female')
                                    <span class="badge bg-pink rounded"><i class="fas fa-venus me-1"></i> Female</span>
                                @else
                                    <span class="badge bg-secondary rounded">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($res->email)
                                    <a href="mailto:{{ $res->email }}" class="text-decoration-none text-dark">
                                        <i class="fas fa-envelope text-primary me-1"></i>{{ $res->email }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success rounded">{{ $res->par_type }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded">{{ $res->par_sub_type ?? '—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No reservations found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-light d-flex justify-content-center">
            {{ $reservations->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
