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
                        <a class="h-25" href="{{ route('UniDevice.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Edit Device</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('UniDevice.update',$device->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" value="{{ old('name',$device->name) }}" required="">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name</label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname',$device->Arabicname)}}" >
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم الجهاز باللغة العربية</label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="model" class="form-control" value="{{ old('model',$device->model) }}" required="">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Model</label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="num_units" class="form-control" value="{{ old('num_units',$device->num_units) }}" required="">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Num of units</label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer',$device->manufacturer) }}" >
                                            <span class="form-bar"></span>
                                            <label class="float-label">Manufacturer</label>
                                        </div>
                                        <div class="form-group form-primary" >
                                            <input type="text" name="description" class="form-control" value="{{old('description',$device->description)}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Description</label>
                                        </div>
                                        <div class="form-group form-primary" >
                                            <input type="text" name="ArabicDescription" class="form-control" value="{{old('ArabicDescription',$device->ArabicDescription)}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">وصف الجهاز باللغة العربية</label>
                                        </div>
                                        <div class="form-group form-primary" >
                                            <input type="text" name="price" class="form-control" value="{{old('price',$device->price)}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Device's price</label>
                                        </div>
                                        @for($i = 0; $i <count($cost) ;$i++)
                                        <div class="container col-xs-12 col-sm-12 col-md-12 mb-2 morerows addRow delete_add_more_item" id="addRow">
                                            <div class="row form-group">
                                                <div class="col-md-3"><input type="text" name="services[]" class="form-control" value="{{old('services.i',$services[$i])}}" placeholder="service"></div>
                                                <div class="col-md-3"><input type="text" name="servicesArabic[]" class="form-control" value="{{old('servicesArabic.i',$servicesArabic[$i])}}" placeholder="الخدمة"></div>
                                                <div class="col-md-3"><input type="text" name="cost[]" class="form-control" value="{{old('cost.i',$cost[$i])}}" placeholder="cost"> </div>
                                                <div class="col-md-3"><input type="text" name="costArabic[]" class="form-control" value="{{old('costArabic.i',$costArabic[$i])}}" placeholder="السعر"> </div>
                                                <i class="col-md-2 removeaddmore" style="cursor:pointer;color:red;"> Remove </i>
                                            </div>
                                        </div>
                                        @endfor
                                        <div class="col-md-2">
                                            <i id="addMore" class="btn btn-primary btn-sm text-white">Add another</i>
                                        </div><br>
                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label class="float">University</label>
                                            <strong>{{$university}}</strong>
                                        </div>
                                        @endrole
                                        <div class="form-group form-default" id="choose_type">
                                            <label class="float">Lab</label>
                                            <select id="lab_id" name="lab_id" >
                                                @foreach($labs as $lab)
                                                    <option {{old('lab_id',$lab->id) == $device->lab_id ?'selected':''}} value="{{$lab->id}}"> {{$lab->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label class="float">Device's State<span class="text-c-red">*</span></label>
                                            <select id="lab" name="state" class="stateselect">
                                                <option value="available" {{old('state',$device->state)=='available'?'selected':''}}>Available</option>
                                                <option value="maintenance" {{old('state',$device->state)!='available'?'selected':''}}>Under Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <img src="{{asset($device->ImagePath)}}" style="max-width: 200px">
                                            <span class="form-bar"></span>
                                        </div>
                                        <div class="form-group form-default" id="image">
                                            <input type="file" id="pic" name="ImagePath" accept="image/*" class="form-control" style="height:50px">
                                            <span class="form-bar"></span>
                                        </div>
                                        <div class="form-group form-primary" >
                                            <input type="text" name="AdditionalInfo" class="form-control" value="{{old('AdditionalInfo',$device->AdditionalInfo)}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Additional Info</label>
                                        </div>
                                        <div class="form-group form-primary" >
                                            <input type="text" name="ArabicAddInfo" class="form-control" value="{{old('ArabicAddInfo',$device->ArabicAddInfo)}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">معلومات إضافية باللغة العربية</label>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Save edits</button>
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
        </div>
    </script>
    <script type="text/javascript"></script>
    <script>
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
