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
                        <a class="h-25" href="{{ route('Users.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add new user</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('Users.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="username" class="form-control" required="" value="{{old('username')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Username <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="password" class="form-control" required="" value="{{old('password')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Password <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="email" class="form-control" required="" value="{{old('email')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Email <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="phone" class="form-control" required="" value="{{old('phone')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Phone <span class="text-c-red">*</span></label>
                                        </div>
{{--                                            Roles--}}
                                        <div class="form-group form-primary">
                                            <label class="float">Role <span class="text-c-red">*</span></label>
                                            @role('admin')
                                                <select id="roles" name="role_id">
                                                    <option value="" disabled selected>Select Role</option>
                                                    <option  value="{{$roles['admin']}}" >Administrator</option>
                                                    <option  value="{{$roles['university']}}" >University Coordinator</option>
                                                    <option  value="{{$roles['faculty']}}"  >Faculty Coordinator</option>
                                                </select>
                                            @endrole
                                            @role('university')
                                                <select id="roles" name="role_id" >
                                                    <option value="" disabled selected>Select Role</option>
                                                    <option id="another_coor" value="{{$roles['university']}}" >University Coordinator</option>
                                                    <option  value="{{$roles['faculty']}}"  >Faculty Coordinator</option>
                                                </select>
                                                <input name="uni_id" id="coor_uni_id" value="{{$user->uni_id }}" hidden>
                                            @endrole
                                            @hasrole('faculty')
                                                <input id="role" name="role_id" value="{{$roles['department']}}" hidden> Department Coordinator
                                            @endhasrole
                                        </div>
{{--                                        Role In--}}
{{--                                        <div class="form-group form-default">--}}
                                            @can('university')
                                                <div class="form-group form-default">
                                                <label class="float">University<span class="text-c-red">*</span></label>
                                                <select id="uni" name="uni_id" onchange="runFac(this.value)">
                                                    <option value="" disabled selected>Choose University</option>
                                                    @foreach($unis as $uni)
                                                        <option value="{{$uni->id}}"> {{$uni->name}} </option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            @endcan
                                            @can('faculty')
                                                <div class="form-group form-default">
                                                    <label class="float">Faculty<span class="text-c-red">*</span></label>
                                                    <select id="facs" name="fac_id" >
                                                        <option value="" disabled selected>Choose Faculty</option>
                                                        @foreach($facs as $fac)
                                                            <option value="{{$fac->fac_id}}"> {{$fac->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endcan
                                            @hasrole('faculty')
                                                <div class="form-group form-default">
                                                <label>Department<span class="text-c-red">*</span></label>
                                                <select id="depts" name="dept_id" >
                                                    <option value="" disabled selected>Choose Department</option>
                                                    @foreach($depts as $dept)
                                                        <option value="{{$dept->id}}"> {{$dept->name}} </option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            @endhasrole
{{--                                        </div>--}}
                                        <div class="form-group form-primary">
                                            <label>Personal Image</label>
                                            <input type="file" id="pic" name="ImagePath" accept="image/*" class="form-control" style="height:50px">
                                            <span class="form-bar"></span>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Add User</button>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){

            $("#roles").change(function(){
                if ($("#roles").val()==='1'){$('select#uni').attr('disabled',true);$('select#facs').attr('disabled',true);}
                else if ($("#roles").val()==='2'){$('select#uni').attr('disabled',false);$('select#facs').attr('disabled',true);}
                else if ($("#roles").val()==='3'){$('select#facs').attr('disabled',false);}
            });
        });

        function runFac (selected_uni){
            facs = @json($facs);
            $('#facs').find('option:not(:first)').remove();
            for (const val of facs) {
                if (val.uni_id == selected_uni){
                    $('#facs').append($(document.createElement('option')).prop({
                        value: val.fac_id,
                        text: val.name
                    }))
                }
            }
        }
    </script>

@endsection
