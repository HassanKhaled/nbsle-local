@extends('loggedTemp.head')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
@section('loggedContent')
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <!-- filter  start -->
                        <div class="col-xl-12 col-md-12 mb-3 align-content-center">
                            <div class="w-75 mx-auto">
                                @if(Auth()->user()->hasRole('admin'))
                                    <form action="{{route('getUniDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                        @csrf
                                        <div> Type
                                            <select class="typeschart" id="typeschart" name="selectOption" style="height:40px;" onchange="run(this.value)" >
                                                <option type="submit" value="public" {{$request->selectOption == 'public'?"selected":""}}>Governmental</option>
                                                <option type="submit" value="private" {{$request->selectOption == 'private'?"selected":""}}>Private</option>
                                                <option type="submit" value="ahli" {{$request->selectOption == 'ahli'?"selected":""}}>National</option>
                                                <option type="submit" value="Institution" {{$request->selectOption == 'Institution'?"selected":""}}>Institute</option>
                                            </select>
                                        </div>
                                        <div class="ml-3"> University
                                            <select class="unischart" id="unischart" name="uni_selected" style="height:40px;max-width: 200px" onchange="runUni(this.value)">
                                                <option value="" >All</option>
                                            </select>
                                        </div>
                                        <div class="ml-3"> Faculty
                                            <select class="facsselect" id="facsselect" name="fac_selected" style="height:40px;max-width: 330px"> {{--onchange="run(this.value)">--}}
                                                <option value="" selected>All</option>
                                            </select>
                                        </div>
                                        <div class="ml-3"> Price
                                            <select name="price" style="height: 40px;">
                                                <option value="all" {{$request->price=='all'?'selected':''}}>All</option>
                                                <option value="less100k" {{$request->price=='less100k'?'selected':''}}><100,000</option>
                                                <option value="more100k" {{$request->price=='more100k'?'selected':''}}>>100,000</option>
                                            </select>
                                        </div>
                                        <div class="ml-3"> Count
                                            <select name="count" style="height: 40px;">
                                                <option type="submit" value="devices" {{$request->count=='devices'?'selected':''}}>devices</option>
                                                <option type="submit" value="units" {{$request->count=='units'?'selected':''}}>units</option>
                                            </select>
                                        </div>
                                        <div class="ml-3">From: <input name="start_date" type="date" style="height: 40px" value="{{$request->start_date}}"> </div>
                                        <div class="ml-3">To: <input name="end_date" type="date" style="height: 40px" value="{{$request->end_date}}"> </div>
                                        <div class="ml-3 mt-3"> <button class="btn btn-success btn-round">Search</button> </div>
                                    </form>
                                @endif
                                @if(Auth()->user()->hasRole('university'))
                                    <form action="{{route('getLabDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                        @csrf
                                        <div> Faculty
                                            <select class="facultyschart" id="facultyschart" name="selectOption" style="height:40px;">{{--onchange="run(this.value)">--}}
                                                <option value="" disabled selected>Select Faculty</option>
                                                <option type="submit" {{$request->selectOption=='Central Labs'?'selected':''}}>Central Labs</option>
                                            @foreach($stables['faculties'] as $fac)
                                                    <option type="submit" value="{{$fac->fac_id}}" {{$fac->fac_id==$request->selectOption?'selected':''}}>{{$fac->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div> Price
                                            <select name="price" style="height: 40px;">
                                                <option value="all" {{$request->price=='all'?'selected':''}}>All</option>
                                                <option value="less100k" {{$request->price=='less100k'?'selected':''}}><100,000</option>
                                                <option value="more100k" {{$request->price=='more100k'?'selected':''}}>>100,000</option>
                                            </select>
                                        </div>
                                        <div>From: <input name="start_date" type="date" style="height: 40px" value="{{$request->start_date}}"> </div>
                                        <div>To: <input name="end_date" type="date" style="height: 40px" value="{{$request->end_date}}"> </div>
                                        <div> <button class="btn btn-success btn-round">Search</button> </div>
                                    </form>
                                @endif
                                @if(Auth()->user()->hasRole('faculty'))
                                    <form action="{{route('getFacDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                        @csrf
                                        <div> Department
                                            <select class="deptschart" id="deptschart" name="deptChosen" style="height:40px; max-width: 200px" onchange="runDept(this.value)">{{--onchange="run(this.value)">--}}
                                                <option value="" selected>Select Department</option>
                                                @foreach($depts as $id=>$name)
                                                    <option type="submit" value="{{$id}}" {{$id== $request->deptChosen?'selected':''}}>{{$name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div> Lab
                                            <select class="labschart" id="labschart" name="selectOption" style="height:40px; max-width: 200px">{{--onchange="run(this.value)">--}}
                                                <option value="" selected>Select Lab</option>
                                                @foreach($labs as $index=>$value)
                                                    <option type="submit" value="{{$value->id}}" {{$value->id == $request->selectOption?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div> Price
                                            <select name="price" style="height: 40px;">
                                                <option value="all" {{$request->price=='all'?'selected':''}}>All</option>
                                                <option value="less100k" {{$request->price=='less100k'?'selected':''}}><100,000</option>
                                                <option value="more100k" {{$request->price=='more100k'?'selected':''}}>>100,000</option>
                                            </select>
                                        </div>
                                        <div> From: <input name="start_date" type="date" style="height: 40px" value="{{$request->start_date}}"> </div>
                                        <div> To: <input name="end_date" type="date" style="height: 40px" value="{{$request->end_date}}"> </div>
                                        <div> <button class="btn btn-success btn-round">Search</button> </div>
                                    </form>
                                @endif
                                @if(Auth()->user()->hasRole('department'))
                                    <form action="{{route('getDeptDevices')}}" method="GET" class="d-flex flex-row justify-content-around mt-3 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                        @csrf
                                        <div> Price
                                            <select name="price" style="height: 40px;">
                                                <option value="all" {{$request->price=='all'?'selected':''}}>All</option>
                                                <option value="less100k" {{$request->price=='less100k'?'selected':''}}><100,000</option>
                                                <option value="more100k" {{$request->price=='more100k'?'selected':''}}>>100,000</option>
                                            </select>
                                        </div>
                                        <div style="width: 200px">From: <input name="start_date" type="date" style="height: 40px" value="{{$request->start_date}}"> </div>
                                        <div style="width: 200px">To: <input name="end_date" type="date" style="height: 40px" value="{{$request->end_date}}"> </div>
                                        <div style="width: 200px"> <button class="btn btn-success btn-round">Search</button> </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <!-- filter  end -->
                        <!--  statistics start -->
                        <div class="col-xl-12 col-md-12">
                            <div class="card">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        @if(Auth()->user()->hasRole('admin'))
                                            <table class="table" style="border-collapse:collapse;">
                                                <thead><tr><th>University</th></tr></thead>
                                                <tbody>
                                                @foreach($devvName as $uni=>$facs)
                                                @if($facs->count()==0)
                                                    <tr><td>No Result</td></tr>
                                                @else
                                                    <tr data-toggle="collapse" data-target="#unidemo{{$loop->index}}" class="accordion-toggle faculties"><td>{{$uni}}</td><td></td><td></td><td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td></tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow"><div class="accordian-body collapse" id="unidemo{{$loop->index}}">
                                                            <table class="table">
                                                                <thead><tr><th>Faculty Names</th></tr></thead>
                                                                <tbody>
                                                                @foreach($facs as $fac=>$depts)
                                                                    <tr class="table-success" data-toggle="collapse" data-target="#facdemo{{$loop->parent->index}}{{$loop->index}}" class="accordion-toggle"><td>{{$fac}}</td><td></td><td></td><td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td></tr>
                                                                    <tr>
                                                                        <td colspan="12" class="hiddenRow"><div class="accordian-body collapse departments" id="facdemo{{$loop->parent->index}}{{$loop->index}}">
                                                                            <table class="table">
                                                                                <thead><tr><th>Department Names</th><th>Number of Labs</th><th>&nbsp;</th></tr></thead>
                                                                                <tbody>
                                                                                @foreach ($depts as $dept=>$labs)
                                                                                    <tr class="table-secondary" data-toggle="collapse" data-target="#demo{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}" class="accordion-toggle">
                                                                                        <td>{{\App\Models\departments::where('id',$dept)->pluck('name')->first()}} </td>
                                                                                        <td>{{count($labs)}}</td>
{{--                                                                            @dd($labs->values()->flatten()->count())--}}
{{--                                                                            <td>{{$labs->values()->flatten()->count()}}</td>--}}
                                                                                        <td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="12" class="hiddenRow"><div class="accordian-body collapse labs" id="demo{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
                                                                                            <table class="table">
                                                                                                <thead><tr><th></th><th>Lab Name</th><th>Number of Devices</th><th></th></tr></thead>
                                                                                                <tbody>
                                                                                                @foreach($labs as $labname=>$devs)
                                                                                                    <tr class="table-info " data-toggle="collapse" data-target="#demo{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
                                                                                                        <td></td>
                                                                                                        <td>{{$labname}}</td>
                                                                                                        <td>{{$request->count == 'units'?$devs->sum('num_units'):count($devs)}}</td>
                                                                                                        <td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td colspan="12"><div class=" collapse devices" id="demo{{$loop->parent->parent->parent->index}}{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
                                                                                                            <table class="table">
                                                                                                                <thead><tr><th>Device Name</th><th>Model</th><th>Num of units</th><th>services</th><th>costs</th></tr></thead>
                                                                                                                <tbody>
                                                                                                                @foreach($devs as $dev)
                                                                                                                    <tr class="table-inverse"><td>{{$dev->name==null?$dev->Arabicname:$dev->name}}</td><td>{{$dev->model}}</td>
                                                                                                                        <td>{{$dev->num_units}}</td><td>{{$dev->services}}</td><td>{{$dev->cost}}</td></tr>
                                                                                                                @endforeach
                                                                                                                </tbody>
                                                                                                            </table></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                                </tbody>
                                                                                            </table></div>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table></div>
                                                                    </td></tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table></div>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @elseif(Auth()->user()->hasRole('university'))
                                            <table class="table" style="border-collapse:collapse;">
                                                    @if($selected == 'Central Labs')
                                                        <thead><tr><th></th><th>Lab Name</th><th>Number of Devices</th><th></th></tr></thead>
                                                        <tbody>
                                                        @foreach($devvName as $labname=>$devs)
                                                            @if($devs->count()==0)
                                                                <tr><td>No Result</td></tr>
                                                            @else
                                                                <tr class="table " data-toggle="collapse" data-target="#demo{{$loop->index}}">
                                                                    <td></td>
                                                                    <td>{{$labname}}</td>
                                                                    <td>{{count($devs)}}</td>
                                                                    <td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="12"><div class=" collapse" id="demo{{$loop->index}}">
                                                                            <table class="table">
                                                                                <thead><tr><th>Device Name</th><th>Model</th><th>Num of units</th><th>services</th><th>costs</th></tr></thead>
                                                                                <tbody>
                                                                                @foreach($devs as $dev)
                                                                                    <tr><td>{{$dev->name==null?$dev->Arabicname:$dev->name}}</td><td>{{$dev->model}}</td>
                                                                                        <td>{{$dev->num_units}}</td><td>{{$dev->services}}</td><td>{{$dev->cost}}</td></tr>
                                                                                @endforeach
                                                                                </tbody>
                                                                            </table></div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                    @foreach($devvName as $fac=>$depts)
                                                        <thead><tr><th>Faculty Names</th></tr></thead>
                                                        <tbody>
                                                        @if($depts->count()==0)
                                                            <tr><td>No Result</td></tr>
                                                        @else
                                                            <tr data-toggle="collapse" data-target="#facdemo{{$loop->index}}" class="accordion-toggle"><td>{{$fac}}</td><td></td><td></td><td><a class="btn btn-success text-white">Details</a></td></tr>
                                                            <tr>
                                                                <td colspan="12" class="hiddenRow"><div class="accordian-body collapse departments" id="facdemo{{$loop->index}}">
                                                                        <table class="table">
                                                                            <thead><tr><th>Department Names</th><th>Number of Labs</th><th>Number of Devices</th><th>&nbsp;</th></tr></thead>
                                                                            <tbody>
                                                                            @foreach ($depts as $dept=>$labs)
                                                                                <tr data-toggle="collapse" data-target="#demo{{$loop->parent->index}}{{$loop->index}}" class="accordion-toggle">
                                                                                    <td>{{\App\Models\departments::where('id',$dept)->pluck('name')->first()}}</td>
                                                                                    <td>{{count($labs)}}</td>
                                                                                    <td>{{$labs->values()->flatten()->count()}}</td>
                                                                                  
                                                                                    <td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="12" class="hiddenRow"><div class="accordian-body collapse labs" id="demo{{$loop->parent->index}}{{$loop->index}}">
                                                                                            <table class="table">
                                                                                                <thead><tr><th></th><th>Lab Name</th><th>Number of Devices</th><th></th></tr></thead>
                                                                                                <tbody>
                                                                                                @foreach($labs as $labname=>$devs)
                                                                                                    <tr class="table-info " data-toggle="collapse" data-target="#demo{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
                                                                                                        <td></td>
                                                                                                        <td>{{$labname}}</td>
                                                                                                        <td>{{count($devs)}}</td>
                                                                                                        <td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td colspan="12"><div class=" collapse devices" id="demo{{$loop->parent->parent->index}}{{$loop->parent->index}}{{$loop->index}}">
                                                                                                                <table class="table">
                                                                                                                    <thead><tr><th>Device Name</th><th>Model</th><th>Num of units</th><th>services</th><th>costs</th></tr></thead>                                                                                                                    <tbody>
                                                                                                                    @foreach($devs as $dev)
                                                                                                                        <tr><td>{{$dev->name}}</td><td>{{$dev->model}}</td><td>{{$dev->num_units}}</td><td>{{$dev->services}}</td>
                                                                                                                            <td>{{$dev->cost}}</td></tr>
                                                                                                                    @endforeach
                                                                                                                    </tbody>
                                                                                                                </table></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                                </tbody>
                                                                                            </table></div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table></div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    @endforeach
                                                    @endif

                                                </table>
                                        @elseif(Auth()->user()->hasRole('faculty'))
                                            <table class="table" style="border-collapse:collapse;">
                                                    <thead><tr><th>Lab Name</th><th>Number of Devices</th><th></th></tr></thead>
                                                    <tbody>
                                                    @foreach($devvName as $labname=>$devs)
                                                        @if($devs->count()==0)
                                                            <tr><td>No Result</td></tr>
                                                        @else
                                                            <tr class="table " data-toggle="collapse" data-target="#demo{{$loop->index}}">
                                                                <td>{{$labname}}</td>
                                                                <td>{{$devs->count()}}</td>
{{--                                                                <td>{{$devs->sum('num_units')}}</td>--}}
                                                                <td class="text-c-blue"><a class="btn btn-success text-white">Details</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="12"><div class=" collapse" id="demo{{$loop->index}}">
                                                                        <table class="table">
                                                                            <thead><tr>
                                                                                <th></th>
                                                                                <th>Device Name</th>
                                                                                <th>Model</th>
                                                                                <th>Num of units</th>
                                                                                <th>services</th>
                                                                                <th>costs</th>
                                                                            </tr></thead>
                                                                            <tbody>
                                                                            @foreach($devs as $dev)
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td>{{$dev->name==null?$dev->Arabicname:$dev->name}}</td>
                                                                                    <td>{{$dev->model}}</td>
                                                                                    <td>{{$dev->num_units}}</td>
                                                                                    <td>{{$dev->services}}</td>
                                                                                    <td>{{$dev->costs}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table></div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12">
                            <div class="card">
                                @if($errors->any())
                                    <div class="card-header">
                                        <div class="alert alert-danger">
                                            {{ $errors->first() }}
                                        </div>
                                    </div>
                                    <div class="card-block"></div>
                                @else
                                    <div class="card-header">
                                        <h5>chart</h5>
                                    </div>
                                    <div class="card-block">
                                        <div id="devices_chart" style="height: 400px;"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    <!--  sale analytics end -->
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        // $(document).ready(function () {console.log('dfdfd');});
        @if (Auth()->user()->hasRole('admin'))
        var stables = @json($stables);
        values = stables.universities;
        valuesFac = stables.all_faculties;
        $(document).ready(function () {
            var selected_uni = @json($request->uni_selected);
            type = $('#typeschart').val();
            var option = '';
                console.log(selected_uni);
            for (var i=0;i<values.length;i++){
                if (values[i].type === type){
                    if (values[i].id.toString() === selected_uni) {
                        option += '<option value="' + values[i].id + '" selected>' + values[i].name + '</option>';
                    } else {
                        option += '<option value="' + values[i].id + '">' + values[i].name + '</option>';
                    }
                }
            }
            $('#unischart').append(option);
        });

        function run(selected_type){
            var selected_uni = @json($request->uni_selected);
            $('#unischart').find('option:not(:first)').remove();

            for (const val of values) {
                if (val.type === selected_type){
                    if(val.id === selected_uni){
                        $('#unischart').append($(document.createElement('option')).prop({
                            value: val.id,
                            text: val.name,selected:true}
                        ))
                    }
                    else {
                    $('#unischart').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name}
                    ))}
                }
            }
        }
        function runUni(selected){
            $('#facsselect').find('option:not(:first)').remove();
            for (const val of valuesFac) {
                if (val.uni_id == selected){
                    console.log(val.fac_id,val.name);
                    $('#facsselect').append($(document.createElement('option')).prop({
                        value: val.fac_id,
                        text: val.name
                    }))
                }
            }
        }
        @endif

        @if (Auth()->user()->hasRole('faculty'))
        function runDept(selected_dept){
                        console.log('here');
            $('#labschart').find('option:not(:first)').remove();
            var labs = @json($labs);
            // console.log(selected_dept==='');
            if (selected_dept===''){
                for (const val of labs){
                    $('#labschart').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            }
            else {
                for (const val of labs) {
                    if (val.dept_id === selected_dept) {
                        $('#labschart').append($(document.createElement('option')).prop({
                            value: val.id,
                            text: val.name
                        }))
                    }
                }
            }
        }
        @endif
        // Load google charts

        @if(!$errors->any())
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawFacultiesDevChart);

        var dev_Combined = [];
        dev_Combined[0] = ['Results', 'Devices'];
        var x = @json($x);
        var y = @json($y);
        {{--var title = @json($title);--}}
        for (var i = 0; i < x.length; i++){
            if (x) {
                dev_Combined[i + 1] = [ x[i], y[i]];
            }
        }
        function drawFacultiesDevChart() {
            var device_data = google.visualization.arrayToDataTable(dev_Combined);
            var options = {
                 titleTextStyle: {fontSize: 23, bold: true,}
                , legend: { position: "none" }
                ,chartArea:{left:90,top:0,width:'100%',height:300}
                ,'dataOpacity': 0.8};
            var chart = new google.visualization.ColumnChart(document.getElementById('devices_chart'));
            chart.draw(device_data, options);
        }

        @endif
    </script>
@endsection
