@extends('loggedTemp.head')
@section('loggedContent')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                            @endif</div>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Import Labs</h5>
                                </div>
                                <div class="card-block  align-items-center">
                                    <a href="{{route('downloadTemplate','labs')}}" class="d-flex flex-row">
                                        <h5>Must follow this Template </h5>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);transform: ;msFilter:;"><path d="M18 22a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4zM7 8h3v2H7V8zm0 4h10v2H7v-2zm0 4h10v2H7v-2z"></path></svg>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form action="{{route('importthat','labs')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex justify-content-evenly">
                                        @if(auth()->user()->hasRole('university'))
                                            <div >
                                                <h6>Faculty <span class="text-c-red">*</span></h6>
                                                <select id="facs" name="fac_id" required>
                                                    {{$facs = \App\Models\fac_uni::where('uni_id',auth()->user()->uni_id)->get()}}
                                                    <option value="" disabled selected>Choose Faculty</option>
                                                    <option value="central">Central Labs</option>
                                                    @foreach($facs as $fac)
                                                        <option value="{{$fac->fac_id}}"> {{$fac->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <br>
                                        @endif
                                            <div class="ml-2">
                                                <h6>Excel file <span class="text-c-red">*</span></h6>
                                                <input type="file" name="importfile" accept=".xlsx, .xls" style="width: 200px" required>
                                            </div>
                                        </div>
                                        <br><br>
                                        <button class="btn btn-primary btn-round">Import <i class="fa fa-upload"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Import Devices</h5>
                                </div>
                                <div class="card-block">
                                    <a href="{{route('downloadTemplate','devices')}}" class="d-flex flex-row">
                                         <h5>Must follow this Template </h5>
{{--                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(54, 89, 148, 1);"><path d="M3 5v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2zm7 2h8v2h-8V7zm0 4h8v2h-8v-2zm0 4h8v2h-8v-2zM6 7h2v2H6V7zm0 4h2v2H6v-2zm0 4h2v2H6v-2z"></path></svg>--}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);transform: ;msFilter:;"><path d="M18 22a2 2 0 0 0 2-2V8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12zM13 4l5 5h-5V4zM7 8h3v2H7V8zm0 4h10v2H7v-2zm0 4h10v2H7v-2z"></path></svg>
                                    </a><br>
                                    <p>Select devices' photos and refer to them in the excel file with the extension ex: photo.png</p>
                                    <strong class="text-danger">MAXIMUM 30 device and image</strong>
                                </div>
                                <div class="card-body">
                                    <form action="{{route('importthat','devices')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex justify-content-evenly">
                                            @if(auth()->user()->hasRole('university'))
                                            <div >
                                                <h6>Faculty <span class="text-c-red">*</span></h6>
                                                <select id="facs" name="fac_id" required>
                                                    {{$facs = \App\Models\fac_uni::where('uni_id',auth()->user()->uni_id)->get()}}
                                                    <option value="" disabled selected>Choose Faculty</option>
                                                    <option value="central">Central Labs</option>
                                                    @foreach($facs as $fac)
                                                        <option value="{{$fac->fac_id}}"> {{$fac->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            <div class="ml-2">
                                                <h6>Excel file <span class="text-c-red">*</span></h6>
                                                <input type="file" name="importfile" accept=".xlsx, .xls" style="width: 200px" required>
                                            </div>
                                            <div class="ml-2">
                                                <h6>Photos <span class="text-c-red">*</span></h6>
                                                <input type="file" name="photos[]" accept="image/*" style="width: 200px" multiple required>
                                            </div>
                                        </div>
                                        <br>
                                        <button class="btn btn-primary btn-round">Import <i class="fa fa-upload"></i></button>
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
