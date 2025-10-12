@extends('loggedTemp.head')

@section('loggedContent')
<div class="container mt-4  p-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">✏️ Edit News</h4>
        </div>
        <div class="card-body">
            {{-- Main Update Form --}}
            <form action="{{ route('news.update', $news) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('news.partials.form', ['news' => $news])

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Update
                    </button>
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Existing Images --}}
    @if(!empty($news->newsImages) && $news->newsImages->count() > 0)
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">🖼 Additional Images</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($news->newsImages as $image)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="{{ asset('storage/' . $image->image_url) }}" 
                                     class="card-img-top rounded" 
                                     style="max-height: 160px; object-fit: cover;">

                                <div class="card-body text-center p-2">
                                    <form action="{{ route('newsImages.destroy', $image->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this image?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
