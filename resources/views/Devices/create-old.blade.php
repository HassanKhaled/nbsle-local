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
                        <a class="h-25" href="{{ route('DeviceLab.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add new device</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('DeviceLab.store') }}" method="POST" enctype="multipart/form-data">
{{--                                        @method('PUT')--}}
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم الجهاز باللغة العربية</label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="model" class="form-control" required="" value="{{old('model')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Model<span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="number" name="num_units" class="form-control" required="" value="{{old('num_units')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Num of units <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="manufacturer" class="form-control" value="{{old('manufacturer')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Manufacturer</label>
                                        </div>
                                        <div class="form-group form-primary" id="website">
                                            <input type="text" name="description" class="form-control" value="{{old('description')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Description</label>
                                        </div>
                                        <div class="form-group form-primary" id="website">
                                            <input type="text" name="ArabicDescription" class="form-control" value="{{old('ArabicDescription')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">وصف الجهاز باللغة العربية</label>
                                        </div>
                                        <div class="form-group form-primary" id="price">
                                            <input type="text" name="price" class="form-control" value="{{old('price')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Device's price</label>
                                        </div>
                                        <div class="container col-xs-12 col-sm-12 col-md-12 mb-2 morerows addRow" id="addRow">
                                            <div class="row form-group">
                                                <div class="col-md-3"><input type="text" name="services[]" class="services form-control" placeholder="Services provided" value="{{old('services.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="servicesArabic[]" class="services form-control" placeholder="الخدمة بالعربي" value="{{old('servicesArabic.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="cost[]" class="cost form-control" placeholder="Cost/Service" value="{{old('cost.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="costArabic[]" class="cost form-control" placeholder="السعر/ لكل" value="{{old('costArabic.0')}}"></div>
                                            </div>
                                        </div>
                                        <div class="form-group form-primary">
                                            <a id="addMore" class="btn btn-primary text-white btn-sm">Add More </a>
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
                                            <select name="fac_id" class="facultyschart" id="facultyschart" onchange="runDept(this.value)">
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
                                                <select name="dept_id" class="departmentselect" id="departmentselect" onchange="runLab(this.value)">
                                                    <option value=""  selected>Choose Department</option>
                                                    @foreach($departments as $dept)
                                                        <option value="{{$dept->id}}" {{old('dept_id')==$dept->id?'selected':''}}> {{$dept->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endcan
                                        <div class="form-group form-primary">
                                            <label class="float">Lab <span class="text-c-red">*</span></label>
                                            <select id="lab" name="lab_id" class="labselect">
                                                <option disabled selected>Choose Lab</option>
                                                @foreach($labs as $lab)
                                                    <option value="{{$lab->id}}" {{old('lab_id')==$lab->id?'selected':''}}> {{$lab->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label class="float">Device's State<span class="text-c-red">*</span></label>
                                            <select id="lab" name="state" class="stateselect">
                                                <option value="available" {{old('state')!='maintenance'?'selected':''}} >Available</option>
                                                <option value="maintenance" {{old('state')=='maintenance'?'selected':''}}>Under Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-primary" id="image">
                                            <label>Image <span class="text-c-red">*</span></label>
                                            <input type="file" id="pic" name="ImagePath" accept="image/*" class="form-control" style="height:50px">
                                            <span class="form-bar"></span>
                                        </div>
                                        <div class="form-group form-primary" id="website">
                                            <input type="text" name="AdditionalInfo" class="form-control" value="{{old('AdditionalInfo')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Additional Info</label>
                                        </div>
                                        <div class="form-group form-primary" id="website">
                                            <input type="text" name="ArabicAddInfo" class="form-control" value="{{old('ArabicAddInfo')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">معلومات إضافية باللغة العربية</label>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Add Device</button>
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
            <div class="col-md-3"><input type="text" name="services[]" class="services form-control" placeholder="Services provided"></div>
            <div class="col-md-3"><input type="text" name="servicesArabic[]" class="services form-control" placeholder="الخدمة بالعربي"></div>
            <div class="col-md-3"><input type="text" name="cost[]" class="cost form-control" placeholder="Cost/Service"></div>
            <div class="col-md-3"><input type="text" name="costArabic[]" class="cost form-control" placeholder="السعر/ لكل"></div>
            <i class="col-md-2 removeaddmore" style="cursor:pointer;color:red;"> Remove </i>
            {{--            <i class="col-md-1 removeaddmore" style="cursor:pointer;color:red;"> X </i>--}}
        </div>
    </script>
    <script type="text/javascript"></script>

    <script>
        @if(Auth()->user()->hasRole('admin'))
        // filter faculty select based on selected university
        function runFac (selected_uni){
            facs = @json($facultys);
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
        // filter department select and lab select based on selected university and faculty
        function runDept(selected_fac){
            // filter department select
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
            // filter lab select
            $('#lab').find('option:not(:first)').remove();
            var labs = @json($labs);
            var uni = $('#uniselect').val();
            $.each(labs, function(i, val) {
                if (val.fac_id==selected_fac && val.uni_id == uni ){
                    $('#lab').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            });
        }
        // filter lab select based on selected department
        function runLab(selected_dept){
            $('#lab').find('option:not(:first)').remove();
            var labs = @json($labs);
            $.each(labs, function(i, val) {
                // console.log(selected_fac,deptfac[i].fac_id,deptfac[i].fac_id== selected_fac);
                if (val.dept_id==selected_dept){
                    $('#lab').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            });
        }
        @endif
        @if (Auth()->user()->hasRole('university'))
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

                $('#lab').find('option:not(:first)').remove();
                var labs = @json($labs);
                console.log(labs)
                $.each(labs, function(i, val) {
                    // console.log(selected_fac,deptfac[i].fac_id,deptfac[i].fac_id== selected_fac);
                    if (val.fac_id==selected_fac){
                console.log(selected_fac);
                        $('#lab').append($(document.createElement('option')).prop({
                            value: val.id,
                            text: val.name
                        }))
                    }
                });
                // runLab($('#departmentselect').selected());
            }
        }
        function runLab(selected_dept){
            // console.log(selected_dept);
            $('#lab').find('option:not(:first)').remove();
            var labs = @json($labs);
            $.each(labs, function(i, val) {
                // console.log(selected_fac,deptfac[i].fac_id,deptfac[i].fac_id== selected_fac);
                if (val.dept_id==selected_dept){
                    $('#lab').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            });
        }
        @endif
        @if (Auth()->user()->hasRole('faculty'))
        function runLab(selected_dept){
            $('#lab').find('option:not(:first)').remove();
            var labs = @json($labs);
            $.each(labs, function(i, val) {
                // console.log(selected_fac,deptfac[i].fac_id,deptfac[i].fac_id== selected_fac);
                if (val.dept_id==selected_dept){
                    $('#lab').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            });

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

        function total_ammount_price() {
            $('input[name="services"]').val = implode('-', $_POST['service']);
            $('input[name="cost"]').va = implode('-', $_POST['costt']);
        }

    </script>
@endsection
