@extends('loggedTemp.head')
@section('loggedContent')
    @if(Session::has('message'))
        <div class=" alert alert-{{ empty(Session::get('alert-class')) ? 'success' : Session::get('alert-class')}}" role="alert" >
            <p style="text-align: right">{{ Session::get('message') }}</p>
        </div>
    @endif
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <!--  project and team member start -->
                        <div class="col-xl-12 col-md-12">
                            <div class="card table-card col-xl-12">
                                <div class="card-header">
                                    <h5>Users</h5>
                                    <div class="card-header-right">
                                        <a class="" href="{{ route('Users.create') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(8, 119, 230, 1);transform: ;msFilter:;"><path d="M4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5zM19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2z"></path></svg>                                        </a>
                                    </div>
                                </div>
                                <div class="card-block">
                                    @can('university') {{--Only for system admin--}}
                                    <div class="form-group ml-3">
                                        <strong >Find User:</strong> <input type="text" id="filter">
                                    </div>
                                    @endcan
                                    <div class="table-responsive">
                                        <table class="table table-hover display " id="myTable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th >Role</th>
                                                @if($user_logged->hasRole('faculty'))    
                                                    <th >Department</th>
                                                @endif   
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{++$i}}</td>
                                                    <td>
                                                        <div class="d-inline-block align-middle">
                                                            <div class="d-inline-block">
                                                                <h6 style="word-wrap: break-word;white-space: pre-wrap;word-break: break-word;">{{ $user->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="">{{$user->phone}}</td>
                                                    @if($user_logged->hasRole('admin'))
                                                    <td>
                                                        <div class="d-inline-block align-middle">
                                                            <div class="d-inline-block">
                                                                <h6 style="word-wrap: break-word;white-space: pre-wrap;word-break: break-word;">{{\App\Models\universitys::find($user->uni_id) == null?"":\App\Models\universitys::find($user->uni_id)['name']}} Admin</h6>
                                                                {{-- <h6 style="word-wrap: break-word;white-space: pre-wrap;word-break: break-word;">{{ $user->uni_id == null ? "":\App\Models\universitys::find($user->uni_id)['name']}} Admin</h6>
 --}}
                                                                {{-- <td>{{\App\Models\departments::find($user->dept_id) == null?"___ ":\App\Models\departments::find($user->dept_id)['name']}}</td> --}}
                                                            </div>
                                                        </div>

                                                    </td>
                                                    @endif
                                                    @if($user_logged->hasRole('university'))
                                                    <td>{{\App\Models\facultys::find($user->fac_id) == null?"University ":\App\Models\facultys::find($user->fac_id)['name']}} Admin</td>
                                                   {{--  <td>{{\App\Models\departments::find($user->dept_id) == null?"___ ":\App\Models\departments::find($user->dept_id)['name']}}</td> --}}
                                                    @endif
                                                    @if($user_logged->hasRole('faculty'))
                                                    {{-- <td>{{\App\Models\departments::find($user->dept_id) == null?"Faculty ":\App\Models\departments::find($user->dept_id)['name']}} Admin</td> --}}
                                                    <td>{{\App\Models\departments::find($user->dept_id) == null?"Faculty ":"Department "}} Admin</td>
                                                    <td>{{\App\Models\departments::find($user->dept_id) == null?"___ ":\App\Models\departments::find($user->dept_id)['name']}}</td>
                                                    @endif
                                                    <td>
                                                        <a class="btn" href="{{ route('Users.edit',$user->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(13, 98, 241, 1);"><path d="M15 11h7v2h-7zm1 4h6v2h-6zm-2-8h8v2h-8zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2zm4-7c1.995 0 3.5-1.505 3.5-3.5S9.995 5 8 5 4.5 6.505 4.5 8.5 6.005 12 8 12z"></path></svg>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('Users.destroy', $user->id) }}" method="post" onSubmit="return confirm('Are You Sure To Delete This User?')">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button class="btn btn-sm bg-transparent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: rgba(245, 8, 8, 1);"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
{{--                                        <div class="text-right">--}}
{{--                                            {!! $users->links("pagination::bootstrap-4") !!}--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  project and team member end -->
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script>
    $(function() {
        $(':input').change(myFunction).keyup(myFunction);
    });
    function myFunction() {
        var keyword = this.value;
        console.log(keyword);
        keyword = keyword.toUpperCase();
        var table_3 = document.getElementById("myTable");
        var all_tr = table_3.getElementsByTagName("tr");
        for(var i=0; i<all_tr.length; i++){
            var all_columns = all_tr[i].getElementsByTagName("td");
            for(j=0;j<all_columns.length; j++){
                if(all_columns[j]){
                    var column_value = all_columns[j].textContent || all_columns[j].innerText;
                    column_value = column_value.toUpperCase();
                    if(column_value.indexOf(keyword) > -1){
                        all_tr[i].style.display = ""; // show
                        break;
                    }else{
                        all_tr[i].style.display = "none"; // hide
                    }
                }
            }
        }
    }
</script>
