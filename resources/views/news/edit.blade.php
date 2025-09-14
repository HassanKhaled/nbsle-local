@extends('loggedTemp.head')

@section('loggedContent')
<div class="container">
    <h1>Edit News</h1>
    <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">News Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}">
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="desc" class="form-label">Description</label>
            <textarea name="desc" class="form-control" rows="5">{{ old('desc', $news->desc) }}</textarea>
            @error('desc') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="university_id" class="form-label">University</label>
            <select name="university_id" class="form-control">
                @foreach($universities as $uni)
                    <option value="{{ $uni->id }}" {{ $news->university_id == $uni->id ? 'selected' : '' }}>
                        {{ $uni->name }}
                    </option>
                @endforeach
            </select>
            @error('university_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Upload New Image (optional)</label>
            <input type="file" name="img" class="form-control">
            @if($news->img_path)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$news->img_path) }}" width="120">
                </div>
            @endif
            @error('img') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Update News</button>
    </form>
</div>
@endsection
