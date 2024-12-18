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
                                    <h5>Add new device</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('UniDevice.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Name<span class="text-c-red">*</span></label>
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
                                            <input type="text" name="num_units" class="form-control" required="" value="{{old('num_units')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Num of units<span class="text-c-red">*</span></label>
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
                                                <div class="col-md-3"><input type="text" name="services[]" class="services form-control" placeholder="Service provided" value="{{old('services.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="servicesArabic[]" class="services form-control" placeholder="الخدمة بالعربي" value="{{old('servicesArabic.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="cost[]" class="cost form-control" placeholder="Cost/Service" value="{{old('cost.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="costArabic[]" class="cost form-control" placeholder="السعر/ لكل" value="{{old('costArabic.0')}}"></div>
                                            </div>
                                        </div>
                                        <div class="form-group form-primary">
                                            <a id="addMore" class="btn btn-primary btn-sm text-white">Add More </a>
                                        </div>
                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label class="float">University<span class="text-c-red">*</span></label>
                                            <select id="uni" name="uni_id" onchange="showLabs(this.value)">
                                                <option selected>Select University</option>
                                                @foreach($universities as $uni)
                                                    <option value="{{$uni->id}}" {{old('uni_id')==$uni->id?'selected':''}}> {{$uni->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endrole
                                        <div class="form-group form-primary">
                                            <label class="float">Lab<span class="text-c-red">*</span></label>
                                            <select id="lab" name="lab_id">
                                                <option selected>Select Lab</option>
                                                @foreach($labs as $lab)
                                                    <option value="{{$lab->id}}" {{old('lab_id')==$lab->id?'selected':''}}> {{$lab->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group form-primary">
                                            <label class="float">Device's State<span class="text-c-red">*</span></label>
                                            <select id="state" name="state" class="stateselect">
                                                <option value="available" {{old('state')=='available'?'selected':''}}>Available</option>
                                                <option value="maintenance" {{old('state')=='maintenance'?'selected':''}}>Under Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-default" id="image">
                                            <label>Image</label>
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

            // total_ammount_price();
        });

        $(document).on('click','.removeaddmore',function(event){
            $(this).closest('.delete_add_more_item').remove();
            // total_ammount_price();
        });

        function total_ammount_price() {
            $('input[name="services"]').val = implode('-', $_POST['service']);
            $('input[name="cost"]').va = implode('-', $_POST['costt']);
        }

        //$('#lab').find('option:not(:first)').remove();
        function showLabs (selected_uni){
            labs = @json($labs);
            $('#lab').find('option:not(:first)').remove();
            for (const val of labs) {
                if (val.uni_id == selected_uni){
                    $('#lab').append($(document.createElement('option')).prop({
                        value: val.id,
                        text: val.name
                    }))
                }
            }
        }
       
    </script>
@endsection
