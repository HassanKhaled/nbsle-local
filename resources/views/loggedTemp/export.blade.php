@extends('loggedTemp.head')
@section('loggedContent')
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row justify-content-center">
                        <div class="col-xl-12 col-md-12 mb-3 align-content-center">
                            <div class="w-100 ">
                                @if(Auth()->user()->hasRole('admin'))
                                    <form action="{{route('generateSheet')}}" method="GET" class="mt-3 ">
                                        <div class="row d-flex flex-row justify-content-evenly">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(23, 162, 184, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                        </div>
                                        @csrf
                                        <div> Type
                                            <select class="typeschart" id="typeschart" name="selectOption" style="height:40px;max-width: 120px" onchange="run(this.value)" onloadstart="run(this.value)">
                                                <option type="submit" value="public" {{$selectedtype == 'public'? 'selected':''}}>Governmental</option>
                                                <option type="submit" value="private" {{$selectedtype == 'private'? 'selected':''}}>Private</option>
                                                <option type="submit" value="ahli" {{$selectedtype == 'ahli'? 'selected':''}}>National</option>
                                                <option type="submit" value="Institution" {{$selectedtype == 'Institution'? 'selected':''}}>Institute</option>
                                            </select>
                                        </div>
                                        <div class="ml-3"> University
                                            <select class="unischart" id="unischart" name="uni_selected" style="height:40px;max-width: 330px" onchange="runUni(this.value)">
                                                <option value="" selected>All</option>
                                            </select>
                                        </div>
                                        <div class="ml-3"> Faculty
                                            <select class="facschart" id="facschart" name="fac_selected" style="height:40px;max-width: 330px"> {{--onchange="run(this.value)">--}}
                                                <option value="" {{$selected_fac==''?'selected':''}}>All</option>
{{--                                                <option value="{{$selected_fac}}" {{$selected_fac!=''?'selected':''}}>{{$selected}}</option>--}}
                                            </select>
                                        </div>
                    {{-- Price --}}     <div class="ml-3"> Price
                                            <select name="price" style="height: 40px;">
                                                <option type="submit" value="all" {{$price=='all'?'selected':''}}>All</option>
                                                <option type="submit" value="less100k" {{$price=='less100k'?'selected':''}}><100,000</option>
                                                <option type="submit" value="more100k" {{$price=='more100k'?'selected':''}}>>100,000</option>
                                            </select>
                                        </div>
                    {{-- Count --}}     <div class="ml-3"> Count
                                            <select name="count" style="height: 40px;">
                                                <option type="submit" value="devices" {{$count=='devices'?'selected':''}}>devices</option>
                                                <option type="submit" value="units" {{$count=='units'?'selected':''}}>units</option>
                                            </select>
                                        </div>
                                        <div class="ml-3">From: <input name="start_date" type="date" value="{{$start_date}}" style="height: 40px"> </div>
                                        <div class="ml-3">To: <input name="end_date" type="date" value="{{$end_date}}" style="height: 40px"> </div>
                                            <div class="ml-3"> <button title="view number of devices" class="btn btn-round btn-info" style="height:40px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);"><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path><path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path></svg>
{{--                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);"><path d="M20 12a2 2 0 0 0-.703.133l-2.398-1.963c.059-.214.101-.436.101-.67C17 8.114 15.886 7 14.5 7S12 8.114 12 9.5c0 .396.1.765.262 1.097l-2.909 3.438A2.06 2.06 0 0 0 9 14c-.179 0-.348.03-.512.074l-2.563-2.563C5.97 11.348 6 11.179 6 11c0-1.108-.892-2-2-2s-2 .892-2 2 .892 2 2 2c.179 0 .348-.03.512-.074l2.563 2.563A1.906 1.906 0 0 0 7 16c0 1.108.892 2 2 2s2-.892 2-2c0-.237-.048-.46-.123-.671l2.913-3.442c.227.066.462.113.71.113a2.48 2.48 0 0 0 1.133-.281l2.399 1.963A2.077 2.077 0 0 0 18 14c0 1.108.892 2 2 2s2-.892 2-2-.892-2-2-2z"></path></svg>--}}
                                                </button> </div>
                                            <div class="ml-3"> <button title="download number of devices" class="btn btn-round btn-info" style="height:40px;" formaction="{{route('exporttoExcel','count')}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg>
{{--                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);"><path d="M20 12a2 2 0 0 0-.703.133l-2.398-1.963c.059-.214.101-.436.101-.67C17 8.114 15.886 7 14.5 7S12 8.114 12 9.5c0 .396.1.765.262 1.097l-2.909 3.438A2.06 2.06 0 0 0 9 14c-.179 0-.348.03-.512.074l-2.563-2.563C5.97 11.348 6 11.179 6 11c0-1.108-.892-2-2-2s-2 .892-2 2 .892 2 2 2c.179 0 .348-.03.512-.074l2.563 2.563A1.906 1.906 0 0 0 7 16c0 1.108.892 2 2 2s2-.892 2-2c0-.237-.048-.46-.123-.671l2.913-3.442c.227.066.462.113.71.113a2.48 2.48 0 0 0 1.133-.281l2.399 1.963A2.077 2.077 0 0 0 18 14c0 1.108.892 2 2 2s2-.892 2-2-.892-2-2-2z"></path></svg>--}}
                                                </button></div>
                                        </div>
                                        <div class="card border-info mt-3">
                                            <div class="card-header bg-light"><h4>Highlight the columns you want to export</h4></div>
                                            <div class="card-body bg-white d-inline-flex">
                                                <div data-toggle="buttons">
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="University Name"        name="columns[]" id="university name" autocomplete="off"> University Name</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="FacultyName"        name="columns[]" id="faculty name" autocomplete="off"> Faculty Name</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="Lab Name"           name="columns[]" id="lab name" autocomplete="off"> Lab Name</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="name"               name="columns[]" id="device name" autocomplete="off"> Device Name</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="model"              name="columns[]" id="model" autocomplete="off"> Model</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="num_units"          name="columns[]" id="num of units" autocomplete="off"> Num of units</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="services"           name="columns[]" id="services" autocomplete="off"> Services</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="cost"               name="columns[]" id="cost" autocomplete="off"> Costs</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="state"              name="columns[]" id="state" autocomplete="off"> State</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="price"              name="columns[]" id="price" autocomplete="off"> Price</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="description"        name="columns[]" id="description" autocomplete="off">Description</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="AdditionalInfo"     name="columns[]" id="AddInfo" autocomplete="off"> Additional Info</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="manufacturer"       name="columns[]" id="manufacturer" autocomplete="off"> Manufacturer</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureYear"    name="columns[]" id="man year" autocomplete="off"> Manufacture Year</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureCountry" name="columns[]" id="man country" autocomplete="off">Manufacture Country</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureWebsite" name="columns[]" id="man website" autocomplete="off">Manufacturer Website</label>
                                                    <label class="btn btn-outline-info"><input type="checkbox" value="entry_date"         name="columns[]" id="entry date" autocomplete="off"> Entry Date</label>
                                                </div>
                                                <div><button title="devices info download" class="btn btn-round btn-info" style="height:40px;" formaction="{{route('exporttoExcel','everything')}}">
                                                        <h6>Download excel with details</h6></button></div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                                @if(Auth()->user()->hasRole('university'))
                                    <form action="{{route('generateSheet')}}" method="GET" class="mt-3 ">
                                        <div class="row d-flex flex-row justify-content-evenly">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(23, 162, 184, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                            </div>
                                            @csrf
                                            <div> Faculty
                                                <select class="facultyschart" id="facultyschart" name="selectOption" style="height:40px;">{{--onchange="run(this.value)">--}}
                                                    <option value="" selected>Select Faculty</option>
                                                    <option type="submit" {{'Central Labs'==$selected_fac?'selected':''}}>Central Labs</option>
                                                    @foreach($stables['faculties'] as $fac)
                                                        <option type="submit" value="{{$fac->fac_id}}" {{$fac->fac_id==$selected_fac?'selected':''}}>{{$fac->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="ml-3"> Price
                                                <select name="price" style="height: 40px;">
                                                    <option type="submit" value="all" {{$price=='all'?'selected':''}}>All</option>
                                                    <option type="submit" value="less100k" {{$price=='less100k'?'selected':''}}><100,000</option>
                                                    <option type="submit" value="more100k" {{$price=='more100k'?'selected':''}}>>100,000</option>
                                                </select>
                                            </div>
                                            <div class="ml-3">From:<input name="start_date" type="date" value="{{$start_date}}" style="height: 40px"> </div>
                                            <div class="ml-3">To:<input name="end_date" type="date" value="{{$end_date}}" style="height: 40px"> </div>
                                            <div class="ml-3"> <button title="view number of devices" class="btn btn-round btn-info" style="height:40px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);"><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path><path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path></svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);"><path d="M20 12a2 2 0 0 0-.703.133l-2.398-1.963c.059-.214.101-.436.101-.67C17 8.114 15.886 7 14.5 7S12 8.114 12 9.5c0 .396.1.765.262 1.097l-2.909 3.438A2.06 2.06 0 0 0 9 14c-.179 0-.348.03-.512.074l-2.563-2.563C5.97 11.348 6 11.179 6 11c0-1.108-.892-2-2-2s-2 .892-2 2 .892 2 2 2c.179 0 .348-.03.512-.074l2.563 2.563A1.906 1.906 0 0 0 7 16c0 1.108.892 2 2 2s2-.892 2-2c0-.237-.048-.46-.123-.671l2.913-3.442c.227.066.462.113.71.113a2.48 2.48 0 0 0 1.133-.281l2.399 1.963A2.077 2.077 0 0 0 18 14c0 1.108.892 2 2 2s2-.892 2-2-.892-2-2-2z"></path></svg>
                                                </button> </div>
                                            <div class="ml-3"> <button title="download number of devices" class="btn btn-round btn-info" style="height:40px;" formaction="{{route('exporttoExcel','count')}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);"><path d="M20 12a2 2 0 0 0-.703.133l-2.398-1.963c.059-.214.101-.436.101-.67C17 8.114 15.886 7 14.5 7S12 8.114 12 9.5c0 .396.1.765.262 1.097l-2.909 3.438A2.06 2.06 0 0 0 9 14c-.179 0-.348.03-.512.074l-2.563-2.563C5.97 11.348 6 11.179 6 11c0-1.108-.892-2-2-2s-2 .892-2 2 .892 2 2 2c.179 0 .348-.03.512-.074l2.563 2.563A1.906 1.906 0 0 0 7 16c0 1.108.892 2 2 2s2-.892 2-2c0-.237-.048-.46-.123-.671l2.913-3.442c.227.066.462.113.71.113a2.48 2.48 0 0 0 1.133-.281l2.399 1.963A2.077 2.077 0 0 0 18 14c0 1.108.892 2 2 2s2-.892 2-2-.892-2-2-2z"></path></svg>
                                                </button></div>
                                            <div class="card border-info mt-3">
                                                <div class="card-header bg-light"><h4>Highlight the columns you want to export</h4></div>
                                                <div class="card-body bg-white d-inline-flex">
                                                    <div data-toggle="buttons">
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="FacultyName"        name="columns[]" id="faculty name" autocomplete="off"> Faculty Name</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="Lab Name"           name="columns[]" id="lab name" autocomplete="off"> Lab Name</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="name"               name="columns[]" id="device name" autocomplete="off"> Device Name</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="model"              name="columns[]" id="model" autocomplete="off"> Model</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="num_units"          name="columns[]" id="num of units" autocomplete="off"> Num of units</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="services"           name="columns[]" id="services" autocomplete="off"> Services</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="cost"               name="columns[]" id="cost" autocomplete="off"> Costs</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="state"              name="columns[]" id="state" autocomplete="off"> State</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="price"              name="columns[]" id="price" autocomplete="off"> Price</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="description"        name="columns[]" id="description" autocomplete="off">Description</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="AdditionalInfo"     name="columns[]" id="AddInfo" autocomplete="off"> Additional Info</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="manufacturer"       name="columns[]" id="manufacturer" autocomplete="off"> Manufacturer</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureYear"    name="columns[]" id="man year" autocomplete="off"> Manufacture Year</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureCountry" name="columns[]" id="man country" autocomplete="off">Manufacture Country</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureWebsite" name="columns[]" id="man website" autocomplete="off">Manufacturer Website</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="entry_date"         name="columns[]" id="entry date" autocomplete="off"> Entry Date</label>
                                                    </div>
                                                    <div><button title="devices info download" class="btn btn-round btn-info" style="height:40px;" formaction="{{route('exporttoExcel','everything')}}">
                                                                <h6>Download excel with details</h6></button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                                @if(Auth()->user()->hasRole('faculty'))
                                    <form action="{{route('generateSheet')}}" method="GET" class="mt-3 ">
                                        <div class="row d-flex flex-row justify-content-evenly">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(23, 162, 184, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg>
                                            </div>
                                            @csrf
                                            <div class="ml-3"> Price
                                                <select name="price" style="height: 40px;">
                                                    <option type="submit" value="all" {{$price=='all'?'selected':''}}>All</option>
                                                    <option type="submit" value="less100k" {{$price=='less100k'?'selected':''}}><100,000</option>
                                                    <option type="submit" value="more100k" {{$price=='more100k'?'selected':''}}>>100,000</option>
                                                </select>
                                            </div>
                                            <div class="ml-3">From: <input name="start_date" type="date" value="{{$start_date}}" style="height: 40px"> </div>
                                            <div class="ml-3">To: <input name="end_date" value="{{$end_date}}" type="date"  style="height: 40px"> </div>
                                            <div class="ml-3"> <button title="view number of devices" class="btn btn-round btn-info" style="height:40px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);transform: ;msFilter:;"><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path><path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path></svg></button> </div>
                                            <div class="ml-3"> <button title="download number of devices" class="btn btn-round btn-info" style="height:40px;" formaction="{{route('exporttoExcel','count')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg></button> </div>
{{--                                            <div> <button title="devices info download" class="btn btn-round btn-success" style="height:40px;" formaction="{{route('exporttoExcel','everything')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M20 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM4 19V5h16l.002 14H4z"></path><path d="M6 7h12v2H6zm0 4h12v2H6zm0 4h6v2H6z"></path></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg></button> </div>--}}
                                            <div class="card border-info mt-3">
                                                <div class="card-header bg-light"><h4>Highlight the columns you want to export</h4></div>
                                                <div class="card-body bg-white d-inline-flex">
                                                    <div data-toggle="buttons">
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="Lab Name"           name="columns[]" id="lab name" autocomplete="off"> Lab Name</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="name"               name="columns[]" id="device name" autocomplete="off"> Device Name</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="model"              name="columns[]" id="model" autocomplete="off"> Model</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="num_units"          name="columns[]" id="num of units" autocomplete="off"> Num of units</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="services"           name="columns[]" id="services" autocomplete="off"> Services</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="cost"               name="columns[]" id="cost" autocomplete="off"> Costs</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="state"              name="columns[]" id="state" autocomplete="off"> State</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="price"              name="columns[]" id="price" autocomplete="off"> Price</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="description"        name="columns[]" id="description" autocomplete="off">Description</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="AdditionalInfo"     name="columns[]" id="AddInfo" autocomplete="off"> Additional Info</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="manufacturer"       name="columns[]" id="manufacturer" autocomplete="off"> Manufacturer</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureYear"    name="columns[]" id="man year" autocomplete="off"> Manufacture Year</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureCountry" name="columns[]" id="man country" autocomplete="off">Manufacture Country</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="ManufactureWebsite" name="columns[]" id="man website" autocomplete="off">Manufacturer Website</label>
                                                        <label class="btn btn-outline-info"><input type="checkbox" value="entry_date"         name="columns[]" id="entry date" autocomplete="off"> Entry Date</label>
                                                    </div>
                                                    <div><button title="devices info download" class="btn btn-round btn-info" style="height:40px;" formaction="{{route('exporttoExcel','everything')}}">
                                                            <h6>Download excel with details</h6></button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                                @if(Auth()->user()->hasRole('department'))
                                        <form action="{{route('generateSheet')}}" method="GET" class="mt-3 ">
                                            <div class="row d-flex flex-row justify-content-evenly">
                                                <div><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(17, 193, 91, 1);"><path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path></svg></div>
                                                @csrf
                                                <div class="ml-3"> Price
                                                    <select name="price" style="height: 40px;">
                                                        <option type="submit" value="all" {{$price=='all'?'selected':''}}>All</option>
                                                        <option type="submit" value="less100k" {{$price=='less100k'?'selected':''}}><100,000</option>
                                                        <option type="submit" value="more100k" {{$price=='more100k'?'selected':''}}>>100,000</option>
                                                    </select>
                                                </div>
                                                <div>From: <input name="start_date" type="date" value="{{$start_date}}" style="height: 40px"> </div>
                                                <div>To: <input name="end_date" value="{{$end_date}}" type="date"  style="height: 40px"> </div>
                                                <div> <button title="number of devices view" class="btn btn-round btn-success" style="height:40px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" style="fill: rgba(255, 254, 254, 1);transform: ;msFilter:;"><path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path><path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path></svg></button> </div>
                                                <div> <button title="number of devices download" class="btn btn-round btn-success" style="height:40px;" formaction="{{route('exporttoExcel','count')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg></button> </div>
                                                <div> <button title="devices info download" class="btn btn-round btn-success" style="height:40px;" formaction="{{route('exporttoExcel','everything')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M20 3H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM4 19V5h16l.002 14H4z"></path><path d="M6 7h12v2H6zm0 4h12v2H6zm0 4h6v2H6z"></path></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 254, 254, 1);"><path d="M19 9h-4V3H9v6H5l7 8zM4 19h16v2H4z"></path></svg></button> </div>
                                            </div>
                                        </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($errors->any())
                        <div class="card-header">
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        </div>
                        <div class="card-block"></div>
                    @elseif( $x == null)
                        @isset($y)
                        <div class="card-block">
                            <div class="alert alert-danger">
                               No Result
                            </div>
                        </div>
                        @endisset
                    @elseif($x != null)
                    <div class="card">
                        <div class="card-header">
                            <h5>{{$title}}</h5>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{$stables['user']->hasRole('university')?'Labs':''}}</th>
                                        <th>Number of devices</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($x as $key=>$value)
                                    <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        <td>{{$value}}</td>
                                        <td>{{$y[$key]}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">

        @if (Auth()->user()->hasRole('admin'))
        var stables = @json($stables);
        values = stables.universities;
        valuesFac = stables.all_faculties;
        var selected_uni = @json($selected_uni);
        type = $('#typeschart').val();
        var option = '';
        for (var i=0;i<values.length;i++){
            if (values[i].type === type){
                // console.log(values[i].id.toString()===selected_uni);
                if (values[i].id.toString() === selected_uni) {
                    option += '<option value="' + values[i].id + '" selected>' + values[i].name + '</option>';
                } else {
                    option += '<option value="' + values[i].id + '">' + values[i].name + '</option>';
                }
            }
        }
        $('#unischart').append(option);

            var selected_fac = @json($selected_fac);
            uni = $('#unischart').val();
            var option = '';
            for (var i = 0; i < valuesFac.length; i++) {
                if (valuesFac[i].uni_id == uni) {
                    console.log(valuesFac[i])
                    // console.log(values[i].id.toString()===selected_uni);
                    if (valuesFac[i].fac_id.toString() == selected_fac) {
                        option += '<option value="' + valuesFac[i].fac_id + '" selected>' + valuesFac[i].name + '</option>';
                    } else {
                        option += '<option value="' + valuesFac[i].fac_id + '">' + valuesFac[i].name + '</option>';
                    }
                }
            }
            $('#facschart').append(option);

        function run(selected_type){
            $('#unischart').find('option:not(:first)').remove();
            for (const val of values) {
                if (val.type === selected_type){
                    // console.log(val);
                    $('#unischart').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            }
        }

        function runUni(selected){
            $('#facschart').find('option:not(:first)').remove();
            for (const val of valuesFac) {
                if (val.uni_id == selected){
                    console.log(val.fac_id,val.name);
                    $('#facschart').append($(document.createElement('option')).prop({
                        value: val.fac_id,
                        text: val.name
                    }))
                }
            }
        }
    @endif

    </script>

@endsection
