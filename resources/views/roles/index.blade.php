@extends('loggedTemp.head')
@section('loggedContent')

    <div class="row col-8 " >
        <div class="col-lg-12 margin-tb py-4 ">
            <div class=" float-left">
                <h2>Roles Management</h2>
            </div>
{{--                        @if(Auth()->user()->hasRole('admin'))--}}
            <div class=" float-right">
                <a class="btn btn-success" href="{{ route('roles.create') }}"> Add New Roles</a>
            </div>
{{--                        @endif--}}
        </div>
    </div>

    @if(Session::has('message'))
        <div class=" alert alert-{{ empty(Session::get('alert-class')) ? 'success' : Session::get('alert-class')}}" role="alert" >
            <p style="text-align: right">{{ Session::get('message') }}</p> </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-auto">
            <table class="table table-bordered table-hover table-responsive">
                <thead class="thead-light">
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>permissions</th>
                    <th width="280px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($roles as $item)
                    <tr>
                        <td>{{$item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            @foreach($item->permissions as $item2)
                                <span class="badge badge-success" style="margin: 2px;color: black">{{$item2->name}} </span>
                            @endforeach
                        </td>
                        <td>
                            {{--                            <form action="{{ route('roles.destroy',$item->id) }}" method="POST">--}}
                            {{--                        @can('product-edit')--}}
                            <a class="btn btn-primary" href="{{ route('roles.edit',$item->id) }}">Edit</a>
                            {{--                        @endcan--}}

                            {{--                                @csrf--}}
                            {{--                                @method('DELETE')--}}
                            {{--                        @can('product-delete')--}}
                            {{--                                <button type="submit" class="btn btn-danger" href="{{route('roles.destroy',$item)}}">Delete</button>--}}
                            {{--                        @endcan--}}
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $roles->links() !!}




@endsection
