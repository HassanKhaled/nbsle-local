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
                                    <h5>Edit lab</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('Lab.update',$lab->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name',$lab->name) }}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Lab Name <span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname',$lab->Arabicname)}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم المعمل باللغة العربية</label>
                                        </div>
                                        <div class=" ">
                                            <label >Accredited</label>
                                            <input type="radio" name="accredited" required="" value="1" {{old('accredited',$lab->accredited)==1? 'checked' :""}}>
                                            <label >Yes</label>
                                            <input type="radio" name="accredited" required="" value="0" {{old('accredited',$lab->accredited)==0? 'checked' :""}}>
                                            <label >No</label>
                                        </div>
                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label >University: </label>
                                            <strong>{{$university}}</strong>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label >Faculty: </label>
                                            <strong>{{$faculty}}</strong>
                                        </div>
                                        @endrole
                                        @can('department')
                                            <div class="form-group form-primary">
                                                <label >Department</label>
                                                <select name="dept_id" class="departmentselect" id="departmentselect">
                                                    <option value="" disabled selected>Choose Department</option>
                                                    @foreach($departments as $i=>$dept)
                                                        @if($lab->fac_id == $deptfac[$i]->fac_id) {{--show departments in the same faculty as the lab--}}
                                                        <option value="{{$dept->id}}" {{old('dept_id',$lab->dept_id)==$dept->id?'selected':''}}> {{$dept->name}} </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                           
                                        @endcan
                                        <div class="container col-xs-12 col-sm-12 col-md-12 mb-2 morerows addRow" id="addRow">
                                        @foreach($coors as $coor)
                                            @if($loop->first)
                                            <div class="row form-group">
                                                <div class="col-md-4"><strong>Coordinator <span class="text-c-red">*</span></strong></div>
                                                <div class="col-md-6">Staff ?
                                                    <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="1" {{old('coor_staff.0',$coor->staff)==1? 'checked' :""}}>Yes
                                                    <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="0" {{old('coor_staff.0',$coor->staff)==0? 'checked' :""}}>No
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4"><input type="text" name="coor_name[]" class="coor_name form-control" placeholder="Name" value="{{old('coor_name.0',$coor->name)}}"></div>
                                                <div class="col-md-4"><input type="text" name="coor_telephone[]" class="coor_telephone form-control" placeholder="Telephone" value="{{old('coor_telephone.0',$coor->telephone)}}"></div>
                                                <div class="col-md-4"><input type="text" name="coor_email[]" class="coor_email form-control" placeholder="Email" value="{{old('coor_email.0',$coor->email)}}"></div>
                                            </div>
                                                @else
                                                    <div class="row form-group">
                                                        <div class="col-md-4"><strong>Coordinator </strong></div>
                                                        <div class="col-md-4">Staff ?
                                                            <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="1" {{$coor->staff==1? 'checked' :""}}>Yes
                                                            <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="0" {{$coor->staff==0? 'checked' :""}}>No
                                                        </div>
                                                        <div class="col-md-4"><i class="removeaddmore btn btn-danger btn-sm col-md-6"> Remove </i></div>
{{--                                                        <div class="col-md-2"></div>--}}
                                                        <div class="col-md-4"><input type="text" name="coor_name[]" class="coor_name form-control" placeholder="Name" value="{{$coor->name}}"></div>
                                                        <div class="col-md-4"><input type="text" name="coor_telephone[]" class="coor_telephone form-control" placeholder="Telephone" value="{{$coor->telephone}}"></div>
                                                        <div class="col-md-4"><input type="text" name="coor_email[]" class="coor_email form-control" placeholder="Email" value="{{$coor->email}}"></div>
                                                    </div>
                                            @endif
                                        @endforeach
                                        </div>
                                        <a id="addMore" class="btn btn-success ">Add Coordinator </a>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Save Edits</button>
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
            <div class="col-md-4">
                <i class="removeaddmore btn btn-danger btn-sm col-md-6"> Remove </i>
            </div>
            <div class="col-md-4"><strong>Coordinator </strong></div>
            <div class="col-md-4">Staff ?
                <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="1">Yes
                <input type="checkbox" name="coor_staff[]" class="coor_staff col-md-2" value="0">No
            </div>
            <div class="col-md-4"><input type="text" name="coor_name[]" class="coor_name form-control" placeholder="Name"></div>
            <div class="col-md-4"><input type="text" name="coor_telephone[]" class="coor_telephone form-control" placeholder="Telephone"></div>
            <div class="col-md-4"><input type="text" name="coor_email[]" class="coor_email form-control" placeholder="Email"></div>
        </div>
    </script>
    <script type="text/javascript"></script>

    <script>

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
