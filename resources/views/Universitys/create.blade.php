@extends('loggedTemp.head')
@section('loggedContent')
    @if ($errors->any())
        <div class="alert alert-danger">
            There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <a class="h-25" href="{{ route('Universitys.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add new University</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('Universitys.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name<span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Arabic Name<span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="website" class="form-control" required="" value="{{old('website')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Website<span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label class="float">Type<span class="text-c-red">*</span></label>
                                            <select id="type" name="type">
                                                <option value="public"> Governmental </option>
                                                <option value="private"> Private </option>
                                                <option value="ahli"> National </option>
                                                <option value="Institution"> Institute </option>
                                            </select>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label>Logo</label>
                                            <input type="file" id="pic" name="ImagePath" accept="image/*" class="form-control" style="height:50px">
                                            <span class="form-bar"></span>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Add University</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
