@extends('loggedTemp.head')
@section('loggedContent')
    <style>
        td{
            max-width: 250px ;
            white-space: normal;
        }

    </style>
 <!--   
    @if(Session::has('message'))
        <div class=" alert alert-{{ empty(Session::get('alert-class')) ? 'success' : Session::get('alert-class')}}" role="alert" >
            <p style="text-align: right">{{ Session::get('message') }}</p>
        </div>    
    @endif
    -->
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <!--  project and team member start -->
                        <div class="col-xl-12 col-md-12">
                        @include('templ.flash-message')
                            <div class="card table-card">
                                <div class="card-header">
                                    <h5>Reservations</h5>
                                    <div class="card-header-right">
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
                                                <th>UserName</th>
                                                <th>DeviceName</th>
                                                <th>University</th>
                                                <th>Faculty</th>
                                                <th>Lab</th>
                                                <th>Service</th>
                                                <th>Sample</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Confirmation</th> 
                                            @role('visitor')    
                                                <th>Status</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            @endrole 
                                          
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reservations as $reservation)
                                                <tr>
                                               
                                                    <td>{{ $reservation->user->username ?? '—' }}</td>
                                                    <td>{{ $reservation->device->name ?? '—' }}</td>
                                                    <td>{{ $reservation->university->name ?? '—' }}</td>
                                                    <td>{{ $reservation->faculty->name ?? '—' }}</td>
                                                    <td>{{ $reservation->lab->name ?? '—' }}</td>
                                                    <td>{{ $reservation->service->service_name ?? '—' }}</td>
                                                    <td>{{ $reservation->samples ?? '—' }}</td>
                                                    <td>{{ $reservation->date ?? '—' }}</td>
                                                    <td>{{ $reservation->time ?? '—' }}</td>

                                                @hasanyrole('faculty|university|admin') 
                                                    <td>
                                                        
                                                        <form action="{{ route('confirm', ['id' => $reservation->id]) }}" method="POST">
                                                                @csrf
                                                            @if($reservation->confirmation === 'Pending')    
                                                                <button type="submit" class="btn btn-success">{{$reservation->confirmation}}</button>
                                                            @else
                                                                <button type="submit" class="btn btn-danger">{{$reservation->confirmation}}</button> 
                                                            @endif    
                                                        </form>
                                                        
                                                    </td>
                                                @endhasanyrole     

                                                @role('visitor')
                                                
                                                    <td> <button class="btn {{$reservation->confirmation=='Pending'?'btn-success':'btn-danger'}}" readonly>{{$reservation->confirmation}}</button> </td>
                                                    <td style=" color: {{$reservation->status=='expire'?'red':'#455a64'}}">{{$reservation->status}}</td>
                                        
                                                    <td class="text-right d-flex">
                                                    @if($reservation->status=='expire')
                                                        <a class="btn invisible" href="{{ url('/userReservation/edit', ['id' => $reservation->id]) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="m18.988 2.012 3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287-3-3L8 13z"></path><path d="M19 19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z"></path></svg>
                                                        </a>
                                                    @else
                                                        <a class="btn" href="{{ url('/userReservation/edit', ['id' => $reservation->id]) }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="m18.988 2.012 3 3L19.701 7.3l-3-3zM8 16h3l7.287-7.287-3-3L8 13z"></path><path d="M19 19H8.158c-.026 0-.053.01-.079.01-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .896-2 2v14c0 1.104.897 2 2 2h14a2 2 0 0 0 2-2v-8.668l-2 2V19z"></path></svg>
                                                        </a>
                                                    @endif    
                                                    </td>

                                                    <td>
                                                        <form action="{{ url('/userReservation', ['id' => $reservation->id]) }}" method="post" onSubmit="return confirm('Are You Sure To Delete This Reservation?')">
                                                            @csrf
                                                            @method("DELETE")
                                               
                                                    @if($reservation->status=='expire')
                                                            <button class="btn btn-sm bg-transparent invisible"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: rgba(245, 8, 8, 1);"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg></button>
                                                    @else
                                                            <button class="btn btn-sm bg-transparent"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: rgba(245, 8, 8, 1);"><path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path></svg></button>
                                                    @endif        
                                                        </form>
                                                    </td>
                                                @endrole 
                                            
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    
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

