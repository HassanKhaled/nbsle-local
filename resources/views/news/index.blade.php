@extends('loggedTemp.head')

@section('loggedContent')
<div class="container py-4">

    {{-- Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📰 All News</h2>
        <a href="{{ route('news.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Create News
        </a>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('news.index') }}" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="university_id" class="col-form-label fw-semibold">Filter by University:</label>
                </div>
                <div class="col-auto">
                    <select name="university_id" id="university_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Universities</option>
                        @foreach($universities as $uni)
                            <option value="{{ $uni->id }}" {{ request('university_id') == $uni->id ? 'selected' : '' }}>
                                {{ $uni->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-filter-circle me-1"></i> Filter
                    </button>
                    <a href="{{ route('news.index') }}" class="btn btn-outline-secondary ms-1">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- News Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">University</th>
                            <th scope="col">Image</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td class="fw-semibold">{{ $item->title }}</td>
                                <td>{{ Str::limit($item->desc, 80) }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $item->university->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <img src="{{ asset('storage/'.$item->img_path) }}" class="img-thumbnail" style="width: 100px; height: auto;">
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('news.edit', $item->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('news.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this news?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="bi bi-info-circle"></i> No news available
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
