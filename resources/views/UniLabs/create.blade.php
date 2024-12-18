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
                        <a class="h-25" href="{{ route('UniLab.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill: rgba(68, 138, 255, 1);"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm2.707 14.293-1.414 1.414L7.586 12l5.707-5.707 1.414 1.414L10.414 12l4.293 4.293z"></path></svg>                        </a>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Add new lab</h5>
                                </div>
                                <div class="card-block">
                                    <form class="form-material " action="{{ route('UniLab.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group form-primary">
                                            <input type="text" name="name" class="form-control" required="" value="{{old('name')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Lab Name<span class="text-c-red">*</span></label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="Arabicname" class="form-control" value="{{old('Arabicname')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">اسم المعمل باللغة العربية</label>
                                        </div>
                                        <div class="form-group form-primary">
                                            <input type="text" name="location" class="form-control" required="" value="{{old('location')}}">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Location<span class="text-c-red">*</span></label>
                                        </div>

                                        @role('admin')
                                        <div class="form-group form-primary">
                                            <label class="float">University<span class="text-c-red">*</span></label>
                                            <select id="uni" name="uni_id" >
                                            <option value="" disabled selected>Choose University</option>
                                                @foreach($universities as $uni)
                                                    <option value="{{old('uni_id',$uni->id)}}"> {{$uni->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endrole

                                        <div class="container col-xs-12 col-sm-12 col-md-12 mb-2 morerows addRow" id="addRow">
                                            <div class="row form-group">
                                                <div class="col-md-3"><strong>Coordinator Name</strong><span class="text-c-red">*</span> </div>
                                                <div class="col-md-3"><strong>Coordinator Email</strong></div>
                                                <div class="col-md-3"><strong>Coordinator Phone</strong><span class="text-c-red">*</span> </div>
                                                <div class="col-md-3"><strong>Staff / Technician</strong><span class="text-c-red">*</span> </div>
                                                <div class="col-md-3"><input type="text" name="names[]" class="services form-control" placeholder="Name *" value="{{old('names.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="emails[]" class="cost form-control" placeholder="Email" value="{{old('emails.0')}}"></div>
                                                <div class="col-md-3"><input type="text" name="phones[]" class="cost form-control" placeholder="Phone *" value="{{old('phones.0')}}"></div>
                                                <div class="col-md-1"><input type="checkbox" name="staffs[]" class="cost form-control" value=1 {{old('staffs.0')==1?'checked':''}}></div>
                                                <div class="col-md-1"><input type="checkbox" name="staffs[]" class="cost form-control" value="0" {{old('staffs.0')==0?'checked':''}}></div>
                                            </div>
                                        </div>
                                                <div class="col-md-2"><a id="addMore" class="btn btn-success btn-sm">Add More </a></div>
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
            <div class="col-md-3"><input type="text" name="names[]" class="services form-control" placeholder="Name *"></div>
            <div class="col-md-3"><input type="text" name="emails[]" class="cost form-control" placeholder="Email"></div>
            <div class="col-md-3"><input type="text" name="phones[]" class="cost form-control" placeholder="Phone *"></div>
            <div class="col-md-1"><input type="checkbox" name="staffs[]" class="cost form-control" value="1"></div>
            <div class="col-md-1"><input type="checkbox" name="staffs[]" class="cost form-control" value="0"></div>
            <i class="col-md-1 removeaddmore" style="cursor:pointer;color:red;"> X </i>
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
            // total_ammount_price();
        });

        function total_ammount_price() {
            $('input[name="services"]').val = implode('-', $_POST['service']);
            $('input[name="cost"]').va = implode('-', $_POST['costt']);
        }

    </script>
@endsection
