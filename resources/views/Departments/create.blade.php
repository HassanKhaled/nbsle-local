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
                                    <h5>Add new department</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('DeptFac.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم القسم باللغة العربية</label>
                                        </div>
                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label class="float">University<span class="text-c-red">*</span></label>
                                            <select id="uni" name="uni_id" onchange="showFacs(this.value)">
                                                <option disabled selected>Choose University</option>
                                                @foreach($universities as $uni)
                                                    <option value="{{$uni->id}}" {{old('uni_id')==$uni->id?'selected':''}}> {{$uni->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label class="float">Faculty<span class="text-c-red">*</span></label>
                                            <select id="facs" name="fac_id" >
                                                <option value="" disabled selected>Choose Faculty</option>
                                                @foreach($facs as $fac)
                                                    <option value="{{$fac->fac_id}}" {{old('fac_id')==$fac->fac_id?'selected':''}}> {{$fac->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endrole
                                        @role('university')
                                        <div class="form-group form-primary">
                                            <label class="float">Faculty</label>
                                            <select id="facs" name="fac_id" >
{{--                                                {{$facs = \App\Models\fac_uni::where('uni_id',$user->uni_id)->get()}}--}}
                                                <option value="" disabled selected>Choose Faculty</option>
                                                @foreach($facs as $fac)
                                                    <option value="{{$fac->fac_id}}" {{old('fac_id')==$fac->fac_id?'selected':''}}> {{$fac->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endrole
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Add Department</button>
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
<script src="//code.jquery.com/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>
<script type="text/javascript"></script>

<script>

    // $('#facs').find('option:not(:first)').remove();
    function showFacs (selected_uni){
        facs = @json($facs);
        // console.log(facs);
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
