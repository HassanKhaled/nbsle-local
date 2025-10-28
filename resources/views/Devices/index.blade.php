@extends('loggedTemp.head')
@section('loggedContent')
    <style>
        td{
            max-width: 250px ;
            white-space: normal;
        }

    </style>
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
                            <div class="card table-card">
                                <div class="card-header">
                                    <h5>Devices</h5>
                                    <div class="card-header-right">
                                        <a class="" href="{{ route('DeviceLab.create') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);transform: ;msFilter:;"><path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="form-group">
                                        <strong >Search For:</strong> <input type="text" id="filter">
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover display " id="myTable">
                                            <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Lab</th>
                                                @role('admin')
                                                <th>University</th>
                                                <th>Faculty</th>
                                                <th>Department</th>
                                                @endrole
                                                @role('university')
                                                <th>Faculty</th>
                                                <th>Department</th>
                                                @endrole
                                                <th>Views</th>
                                                <th>Reservations</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($devlab as $dev)
                                                <tr>
                                                    <td><img src="{{asset($dev->ImagePath)}}" style="max-width: 100px;"></td>
                                                    <td id="namecol">
                                                        <div class="d-inline-block align-middle">
                                                            <div class="d-inline-block">
                                                                <h6>{{ $dev->name==null?$dev->Arabicname:$dev->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    @hasanyrole('university|admin') {{--Lab--}}
                                                    <td>{{$labs[$dev->lab_id]->name}}</td>
                                                    @else
                                                    <td>{{$labs[$dev->lab_id]}}</td>
                                                    @endhasanyrole
                                                    @role('admin')
                                                    <td>{{$unis[$labs[$dev->lab_id]->uni_id]->name}}</td>
                                                    <td>{{$facs[$labs[$dev->lab_id]->fac_id]->name}}</td>
                                                    <td>{{$labs[$dev->lab_id]->dept_id==null?'':$departments[$labs[$dev->lab_id]->dept_id]->name}}</td>
                                                    @endrole
                                                    @role('university') {{--for university admin Faculty and Department--}}
                                                    <td>{{\App\Models\fac_uni::where('uni_id',auth()->user()->uni_id)->where('fac_id',$labs[$dev->lab_id]->fac_id)->pluck('name')->first()}}</td>
                                                    <td>{{$labs[$dev->lab_id]->dept_id==null?'':\App\Models\departments::where('id',$labs[$dev->lab_id]->dept_id)->pluck('name')->first()}}</td>
                                                    @endrole
                                                    <td>{{ $dev->views }}</td>
                                                    <td>{{ $dev->reservations_count }}</td>
                                                    <td class="text-right d-flex">
                                                        <a class="btn" href="{{ route('DeviceLab.edit',$dev->id) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="m18.988 2.012 3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287-3-3L8 13z"></path><path d="M19 19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z"></path></svg>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('DeviceLab.destroy', $dev->id) }}" method="post" onSubmit="return confirm('Are You Sure To Delete This Device?')">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button class="btn btn-sm bg-transparent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: rgba(245, 8, 8, 1);"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @role('admin')
                                        <div class="text-right">
                                            {!! $devlab->links("pagination::bootstrap-4") !!}
                                        </div>
                                        @endrole
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

