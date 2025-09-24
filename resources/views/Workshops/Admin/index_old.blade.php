@extends('templ.head')

@section('tmplt-contnt')
<div class="container py-5">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-chalkboard-teacher text-primary me-2"></i> Manage Workshops
        </h2>
        <a href="{{ route('uniHome') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.workshops.index') }}">
                <div class="row g-3 align-items-end">
                    {{-- Search --}}
                    <div class="col-md-4">
                        <label class="form-label">Search Title</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Workshop name...">
                    </div>

                    {{-- University --}}
                    <div class="col-md-3">
                        <label class="form-label">University</label>
                        <select name="university" class="form-select">
                            <option value="">All</option>
                            @foreach($universities as $uni)
                                <option value="{{ $uni->id }}" {{ request('university') == $uni->id ? 'selected' : '' }}>
                                    {{ $uni->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Faculty --}}
                    <div class="col-md-3">
                        <label class="form-label">Faculty</label>
                        <select name="faculty" class="form-select">
                            <option value="">All</option>
                            @foreach($faculties as $fac)
                                <option value="{{ $fac->id }}" {{ request('faculty') == $fac->id ? 'selected' : '' }}>
                                    {{ $fac->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.workshops.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Workshops Table --}}
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Workshops List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Workshop</th>
                            <th>University</th>
                            <th>Faculty</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workshops as $workshop)
                            <tr>
                                <td class="fw-bold">{{ $workshop->id }}</td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $workshop->workshop_ar_title ?? $workshop->workshop_en_title }}</span>
                                        <br>
                                        <small class="text-muted">#{{ $workshop->id }}</small>
                                    </div>
                                </td>
                                <td>{{ $workshop->university->name ?? '—' }}</td>
                                <td>{{ $workshop->faculty->name ?? '—' }}</td>
                                <td><span class="badge bg-info">{{ \Carbon\Carbon::parse($workshop->st_date)->format('d M Y') }}</span></td>
                                <td><span class="badge bg-warning">{{ \Carbon\Carbon::parse($workshop->end_date)->format('d M Y') }}</span></td>
                                <td>
                                    @if($workshop->is_approved)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!$workshop->is_approved)
                                        <form method="POST" action="{{ route('admin.workshops.approve',$workshop->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i> Confirm
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock me-1"></i> Confirmed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted me-2"></i>
                                    No workshops found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
