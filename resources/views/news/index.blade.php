@extends('loggedTemp.head')

@section('loggedContent')
<div class="container">
    <h1>All News</h1>

        {{-- Filter --}}
    <form method="GET" action="{{ route('news.index') }}" class="mb-3 d-flex align-items-center">
        <label for="university_id" class="me-2">Filter by University:</label>
        <select name="university_id" id="university_id" class="form-select me-2" style="width:auto;">
            <option value="">All Universities</option>
            @foreach($universities as $uni)
                <option value="{{ $uni->id }}" 
                    {{ request('university_id') == $uni->id ? 'selected' : '' }}>
                    {{ $uni->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('news.index') }}" class="btn btn-secondary ms-2">Reset</a>
    </form>

    <a href="{{ route('news.create') }}" class="btn btn-primary mb-3">Create News</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>University</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ Str::limit($item->desc, 100) }}</td>
                    <td>{{ $item->university->name ?? 'N/A' }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$item->img_path) }}" width="100">
                    </td>
                    <td>
                        <a href="{{ route('news.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('news.destroy', $item->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this news?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
