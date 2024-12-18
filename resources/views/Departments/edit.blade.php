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
                        <a class="h-25" href="{{ route('DeptFac.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Edit department</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('DeptFac.update',$department->dept_id) }}" method="POST" >
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" value="{{ old('name',$dept->name) }}" required="">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{ old('Arabicname',$dept->Arabicname) }}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم القسم باللغة العربية</label>
                                        </div>
{{--                                        <div class="form-group form-default" id="coor">--}}
{{--                                            <label class="float">Coordinator:</label>--}}
{{--                                            <select id="coor_id" name="coor_id" >--}}
{{--                                                @foreach($users as $item)--}}
{{--                                                    <option {{$item->id == $department->coor_id ?'selected':''}} value="{{ $item->id }}"> {{ $item->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                        <input hidden type="number" id="fac_id" name="fac_id" required value="{{$department->fac_id}}">--}}
                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label class="float">University</label>
                                            <strong>{{$university}}</strong>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label class="float">Faculty</label>
                                            <strong>{{$faculty}}</strong>
                                        </div>
                                        @endrole
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Save edits</button>
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
