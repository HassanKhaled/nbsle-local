@extends('loggedTemp.head')

@section('loggedContent')
<div class="container p-3">
    <h1>Create News</h1>

    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('news.partials.form')

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('news.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

