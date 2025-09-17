@extends('loggedTemp.head')

@section('loggedContent')
<div class="container">
    <h1>{{ $news->title }}</h1>

    @if($news->img_path)
        <img src="{{ asset('storage/' . $news->img_path) }}" class="img-fluid mb-3" alt="">
    @endif

    <p>{{ $news->desc }}</p>
    <p><strong>University:</strong> {{ $news->university->name ?? '-' }}</p>
    <p><strong>Date:</strong> {{ $news->publish_date }}</p>
    <p><strong>Location:</strong> {{ $news->location }}</p>
    <p><strong>Status:</strong> {{ $news->is_active ? 'Active' : 'Inactive' }}</p>
    @if($news->newsImages->isNotEmpty())
        <h4>Additional Images</h4>
        <div class="row">
            @foreach($news->newsImages as $image)
                <div class="col-md-3 mb-3">
                    <img src="{{ asset('storage/' . $image->image_url) }}" class="img-fluid rounded" alt="">
                </div>
            @endforeach
        </div>
    @endif
    <a href="{{ route('news.edit', $news) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('news.destroy', $news) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this news?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger">Delete</button>
    </form>
    <a href="{{ route('news.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
