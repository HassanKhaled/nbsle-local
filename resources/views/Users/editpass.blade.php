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
                        <a class="h-25" href="/uniHome">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <!-- Multiple Open Accordion start -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-header-text">Edit Profile</h5>
                                </div>
                                <div class="card-block accordion-block">
                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                        <div class="accordion-panel">
                                            <div class="accordion-heading" role="tab" id="headingOne">
                                                <h3 class="card-title accordion-title">
                                                    <a class="accordion-msg waves-effect waves-dark" data-toggle="collapse"
                                                       data-parent="#accordion" href="#collapseOne"
                                                       aria-expanded="true" aria-controls="collapseOne">
                                                        Personal Info
                                                    </a>
                                                </h3>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="accordion-content accordion-desc">
                                                    <form class="form-material " action="{{ route('change-info') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">Name</label>
                                                        </div>
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="username" class="form-control" value="{{ old('username',$user->username) }}" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">Username</label>
                                                        </div>
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="email" class="form-control" value="{{ old('email',$user->email) }}" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">Email</label>
                                                        </div>
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">Phone</label>
                                                        </div>
                                                        <div class="">
                                                            <img src="{{asset($user->ImagePath)}}" style="max-width: 200px">
                                                            <span class="form-bar"></span>
                                                        </div>
                                                        <div class="form-group form-primary">
                                                            <label>Personal Image <span class="text-c-red">*</span></label>
                                                            <input type="file" id="ImagePath" name="ImagePath" accept="image/*" class="form-control" style="height:50px">
                                                            <span class="form-bar"></span>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center float-right">
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-panel">
                                            <div class="accordion-heading" role="tab" id="headingTwo">
                                                <h3 class="card-title accordion-title">
                                                    <a class="accordion-msg waves-effect waves-dark" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        Change Password
                                                    </a>
                                                </h3>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                <div class="accordion-content accordion-desc">
                                                    <form class="form-material " action="{{ route('change-pass') }}" method="GET">
                                                        @csrf
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="password" class="form-control" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">Old Password <span class="text-c-red">*</span></label>
                                                        </div>
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="new_password" class="form-control" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">New Password <span class="text-c-red">*</span></label>
                                                        </div>
                                                        <div class="form-group form-primary">
                                                            <input type="text" name="new_confirm_password" class="form-control" required="">
                                                            <span class="form-bar"></span>
                                                            <label class="float-label">Confirm New Password <span class="text-c-red">*</span></label>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Multiple Open Accordion ends -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{--@extends('Layouts.loggedLayout')--}}
{{--<meta charset="utf-8">--}}
{{--<!-- Required Fremwork -->--}}
{{--<link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">--}}
{{--@section('loggedContent')--}}

{{--    @if ($errors->any())--}}
{{--        <div class="alert alert-danger">--}}
{{--            <strong>Whoops!</strong> There were some problems with your input.<br><br>--}}
{{--            <ul>--}}
{{--                @foreach ($errors->all() as $error)--}}
{{--                    <li>{{ $error }}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <div class="row justify-content-center">--}}
{{--        <div class="card w-50 mx-auto ">--}}
{{--            <div class="card-header flex-row">--}}
{{--                <div class="container">--}}
{{--                    <div class="row justify-content-center ">--}}
{{--                        <div class="col-3"><a class="btn btn-primary" href="/uniHome"> Back</a></div>--}}
{{--                        <div class="col-8"><h2>Edit Password</h2></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="card-block">--}}
{{--                <form action="{{ route('oppa') }}" method="GET" class="form-material">--}}
{{--                    @csrf--}}
{{--                    <div class="form-group form-default" id="password">--}}
{{--                        <input type="text" name="password" class="form-control" required="">--}}
{{--                        <span class="form-bar"></span>--}}
{{--                        <label class="float-label">Old Password</label>--}}
{{--                    </div>--}}
{{--                    <div class="form-group form-default" id="password">--}}
{{--                        <input type="text" name="new_password" class="form-control" required="">--}}
{{--                        <span class="form-bar"></span>--}}
{{--                        <label class="float-label">New Password</label>--}}
{{--                    </div>--}}
{{--                    <div class="form-group form-default" id="password">--}}
{{--                        <input type="text" name="new_confirm_password" class="form-control" required="">--}}
{{--                        <span class="form-bar"></span>--}}
{{--                        <label class="float-label">Confirm New Password</label>--}}
{{--                    </div>--}}
{{--                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">--}}
{{--                        <button type="submit" class="btn btn-primary">Save edits</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
