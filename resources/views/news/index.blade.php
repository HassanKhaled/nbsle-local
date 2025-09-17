@extends('loggedTemp.head')

@section('loggedContent')

<div class="container">
    <h1>All News</h1>

    <a href="{{ route('news.create') }}" class="btn btn-primary mb-3">+ Add News</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>University</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->university->name ?? '-' }}</td>
                    <td>{{ $item->publish_date }}</td>
                    <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('news.show', $item) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('news.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No News Found</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $news->links() }}
</div>
@endsection
