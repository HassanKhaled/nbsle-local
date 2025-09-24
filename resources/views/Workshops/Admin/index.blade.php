@extends('templ.head')

@section('tmplt-contnt')
<div class="container py-5 mt-5">
        {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-chalkboard-teacher text-primary me-2"></i> Manage Workshops
        </h2>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $i=1;
    @endphp

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.workshops.index') }}" class="card shadow-sm mb-4 p-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Workshop title...">
            </div>

            <div class="col-md-3">
                <label class="form-label">University</label>
                <select name="university" id="filter-university" class="form-select">
                    <option value="">-- All --</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ request('university') == $uni->id ? 'selected' : '' }}>
                            {{ $uni->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Faculty</label>
                <select name="faculty" id="filter-faculty" class="form-select">
                    <option value="">-- All --</option>
                    @foreach($faculties as $fac)
                        <option value="{{ $fac->id }}" {{ request('faculty') == $fac->id ? 'selected' : '' }}>
                            {{ $fac->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- All --</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                </select>
            </div>

            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i></button>
            </div>
            
        </div>
    </form>


    {{-- Workshops Table --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Workshop</th>
                        <th>University</th>
                        <th>Faculty</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Approved</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workshops as $workshop)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $workshop->workshop_ar_title ?? $workshop->workshop_en_title }}</td>
                            <td>{{ $workshop->university->name ?? 'N/A' }}</td>
                            <td>{{ $workshop->faculty->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-info">{{ \Carbon\Carbon::parse($workshop->st_date)->format('d M Y') }}</span></td>
                            <td><span class="badge bg-warning">{{ \Carbon\Carbon::parse($workshop->end_date)->format('d M Y') }}</span></td>
                            <td>
                                @if($workshop->is_approved)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Yes</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>No</span>
                                @endif
                            </td>
                            <td>
                                @if(!$workshop->is_approved)
                                    <form method="POST" action="{{ route('admin.workshops.approve',$workshop->id) }}">
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
                            <td colspan="8" class="text-center text-muted py-3">No workshops found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="card-footer">
             {{ $workshops->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const uniSelect = document.getElementById('filter-university');
        const facSelect = document.getElementById('filter-faculty');

        if (!uniSelect || !facSelect) return;

        uniSelect.addEventListener('change', async () => {
            const uniId = uniSelect.value;

            // If no uni selected, you may want to reload page to show all faculties,
            // or request all faculties. Here we'll request faculties for selected uni.
            try {
                // Endpoint returns JSON list of faculties for a university
                const url = '/faculties-by-university/' + uniId; // create this route below
                const res = await fetch(url);
                if (!res.ok) throw new Error('Failed to fetch faculties');

                const data = await res.json(); // expected: [{id:1,name:'...'}, ...]
                // clear
                facSelect.innerHTML = '<option value="">-- All --</option>';

                data.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f.id;
                    opt.textContent = f.name;
                    facSelect.appendChild(opt);
                });
            } catch (err) {
                console.error(err);
                // fallback: do nothing (user can still submit)
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Remove query string from URL without reloading the page
        if (window.location.search) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    });
</script>

@endsection
