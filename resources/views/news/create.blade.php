@extends('loggedTemp.head')

@section('loggedContent')
<div class="container">
    <h1>Create News</h1>
    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">News Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="desc" class="form-label">Description</label>
            <textarea name="desc" class="form-control" rows="5">{{ old('desc') }}</textarea>
            @error('desc') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="university_id" class="form-label">University</label>
            <select name="university_id" class="form-control">
                <option value="">Select University</option>
                @foreach($universities as $uni)
                    <option value="{{ $uni->id }}" {{ old('university_id') == $uni->id ? 'selected' : '' }}>
                        {{ $uni->name }}
                    </option>
                @endforeach
            </select>
            @error('university_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Upload Image</label>
            <input type="file" name="img" class="form-control">
            @error('img') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Save News</button>
    </form>
</div>
@endsection
