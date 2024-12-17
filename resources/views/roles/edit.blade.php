@extends('loggedTemp.head')
@section('loggedContent')
    <style>

    </style>

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


    <div class="row h-25">
        <div class="card w-50 mx-auto ">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="float-left">
                            <h2>Edit User</h2>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('Users.index') }}"> Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="col-3 ">
                    <form action="{{ route('roles.update',$role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{$role->name}}</strong>
                                    <input type="hidden" name="name" value="{{$role->name}}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Username:</strong>
                                    <select id="permissions"  class="form-control" multiple  required name="permissions[]">
                                        @foreach($permissions as $item)
                                            <option {{ $role->permissions->contains('id' , $item->id) ? 'selected' : ''}} value="{{$item->id}}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



@endsection
