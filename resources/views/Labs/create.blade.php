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
                        <a class="h-25" href="{{ route('Lab.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add new lab</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('Lab.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Lab Name <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم المعمل باللغة العربية</label>
                                        </div>

                                        <div class="form-group form-primary">
                                            <label >Accredited</label>
                                            <input type="radio" name="accredited" required="" value="1" {{old('accredited')==1?'checked':''}}>
                                            <label >Yes</label>
                                            <input type="radio" name="accredited" required="" value="0" {{old('accredited')==0?'checked':''}}>
                                            <label >No</label>
                                        </div>
                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label >University <span class="text-c-red">*</span></label>
                                            <select name="uni_id" class="uniselect" id="uniselect" onchange="runFac(this.value)">
                                                <option value="" disabled selected >Choose University</option>
                                                @foreach($universities as $uni)
                                                    <option value="{{$uni->id}}" {{old('uni_id')==$uni->id?'selected':''}}> {{$uni->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label >Faculty <span class="text-c-red">*</span></label>
                                            <select name="fac_id" class="facultyselect" id="facultyselect" onchange="runDept(this.value)">
                                                <option value="" disabled selected >Choose Faculty</option>
                                                @foreach($facultys as $fac)
                                                    <option value="{{$fac->fac_id}}" {{old('fac_id')==$fac->fac_id?'selected':''}}> {{$fac->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endrole
                                        @role('university')
                                            <div class="form-group form-primary">
                                                <label >Faculty <span class="text-c-red">*</span></label>
                                                <select name="fac_id" class="facultyselect" id="facultyselect" onchange="runDept(this.value)">
                                                    <option value="" disabled selected >Choose Faculty</option>
                                                    @foreach($facultys as $fac)
                                                        <option value="{{$fac->fac_id}}" {{old('fac_id')==$fac->fac_id?'selected':''}}> {{$fac->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endrole
                                        @can('department')
                                            <div class="form-group form-primary">
                                                <label >Department</label>
                                                <select name="dept_id" class="departmentselect" id="departmentselect">
                                                    <option value=""  selected>Choose Department</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{$dept->id}}" {{old('dept_id')==$dept->id?'selected':''}}> {{$dept->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endcan
                                        <div class="container col-xs-12 col-sm-12 col-md-12 mb-2 morerows addRow" id="addRow">
                                            <div class="row form-group">
                                                <div class="col-md-4"><strong>Coordinator </strong><span class="text-c-red">*</span></div>
                                                <div class="col-md-6">Staff ?
                                                    <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="1" {{old('coor_staff.0')==1?'checked':''}}>Yes
                                                    <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="0" {{old('coor_staff.0')==0?'checked':''}}>No
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4"><input type="text" name="coor_name[]" class="coor_name form-control" placeholder="Name" value="{{old('coor_name.0')}}"></div>
                                                <div class="col-md-4"><input type="text" name="coor_telephone[]" class="coor_telephone form-control" placeholder="Telephone" value="{{old('coor_telephone.0')}}"></div>
                                                <div class="col-md-4"><input type="text" name="coor_email[]" class="coor_email form-control" placeholder="Email" value="{{old('coor_email.0')}}"></div>
                                            </div>
                                        </div>
                                        <a id="addMore" class="btn btn-success ">Add Another </a>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Add Lab</button>
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
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js"></script>

    <script id="document-template" type="text/x-handlebars-template">
        <div class="row form-group delete_add_more_item" id="delete_add_more_item">
            <div class="col-md-4"><strong>Coordinator </strong></div>
            <div class="col-md-4">Staff ?
                <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="1">Yes
                <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="0">No
            </div>
            <div class="col-md-4">
                <i class="removeaddmore btn btn-danger btn-sm col-md-6"> Remove </i>
            </div>
            <div class="col-md-4"><input type="text" name="coor_name[]" class="coor_name form-control" placeholder="Name"></div>
            <div class="col-md-4"><input type="text" name="coor_telephone[]" class="coor_telephone form-control" placeholder="Telephone"></div>
            <div class="col-md-4"><input type="text" name="coor_email[]" class="coor_email form-control" placeholder="Email"></div>
        </div>
    </script>
    <script type="text/javascript"></script>
    <script>
        @if(Auth()->user()->hasRole('admin'))
        function runFac (selected_uni){
            facs = @json($facultys);
            console.log(facs);
            $('#facultyselect').find('option:not(:first)').remove();
            for (const val of facs) {
                if (val.uni_id == selected_uni){
                    $('#facultyselect').append($(document.createElement('option')).prop({
                        value: val.fac_id,
                        text: val.name
                    }))
                }
            }
        }
        function runDept(selected_fac){
            $('#departmentselect').find('option:not(:first)').remove();
            var depts = @json($departments);
            var deptfac = @json($deptfac);
            if (selected_fac===''){
                for (const val of depts){
                    $('#departmentselect').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            }
            else {
                $.each(depts, function(i, val) {
                    if (deptfac[i].fac_id==selected_fac && deptfac[i].uni_id == $('#uniselect').val()){
                        $('#departmentselect').append($(document.createElement('option')).prop({
                            value: val.id,
                            text: val.name
                        }))
                    }
                });
            }
        }
        @endif
        @if (Auth()->user()->hasRole('university')) //// DOESN'T WORK  /////
        function runDept(selected_fac){
            $('#departmentselect').find('option:not(:first)').remove();
            var depts = @json($departments);
            var deptfac = @json($deptfac)
            // console.log(selected_dept==='');
            if (selected_fac===''){
                for (const val of depts){
                    $('#departmentselect').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            }
            else {
                $.each(depts, function(i, val) {
                    // console.log(selected_fac,deptfac[i].fac_id,deptfac[i].fac_id== selected_fac);
                    if (deptfac[i].fac_id==selected_fac){
                        $('#departmentselect').append($(document.createElement('option')).prop({
                            value: val.id,
                            text: val.name
                        }))
                    }
                });
            }
        }
        @endif

        $(document).on('click','#addMore',function(){

            $('.morerows').show();

            var task_name = $("#task_name").val();
            var cost = $("#cost").val();
            var source = $("#document-template").html();
            var template = Handlebars.compile(source);

            var data = {
                task_name: task_name,
                cost: cost
            }
            var html = template(data);
            $("#addRow").append(html)

            // total_ammount_price();
        });

        $(document).on('click','.removeaddmore',function(event){
            $(this).closest('.delete_add_more_item').remove();
        });
    </script>
@endsection
