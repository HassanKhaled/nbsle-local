@extends('loggedTemp.head')
@section('loggedContent')
    {{-- jQuery & Bootstrap --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <main id="main">
        <div class="container py-5">
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <section class="login">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header text-center">
                                <h5>Workshop Announcement Form</h5>
                            </div>

                            <div class="card-block p-4">
                                <form action="{{ url('/UniworkshopSub/'.$uniID.'/store') }}" 
                                      method="POST" 
                                      enctype="multipart/form-data">
                                    @csrf

                                    {{-- University Info --}}
                                    <legend>Institute Information</legend>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">University Name<span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="text" name="Uniname" class="form-control"  
                                                   value="{{ $UniName->name }}" readonly>
                                        </div>

                                    </div>
                                   @if ($UniName->id == 0)
                                        <div class="col-md-6 d-none">
                                            <input type="text" name="FacultyName" class="form-control"
                                                value="{{ $facultyName->first()->name }}" readonly>
                                        </div>
                                    @else
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label text-md-right">Faculty Name</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="FacultyName" required>
                                                    <option value="" disabled selected hidden>Select a Faculty</option>
                                                    @foreach($facultyName as $faculty)
                                                        <option value="{{ $faculty->name }}">{{ $faculty->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <hr>
                                    {{-- Workshop Basic Info --}}
                                    <legend>Workshop Basic Information</legend>

                                    <div class="form-group row">
                                        <div class="col-md-12 text-center">
                                            <label><input type="radio" class="radioBtn" name="optradio" value="arabic" checked> In Arabic</label>
                                            <label class="ml-3"><input type="radio" class="radioBtn" name="optradio" value="english"> In English</label>
                                            <label class="ml-3"><input type="radio" class="radioBtn" name="optradio" value="bothLan"> In Both Languages</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop Arabic Title</label>
                                        <div class="col-md-6">
                                            <input type="text" name="WorkshopArabicName" id="WorkshopArabicName" class="form-control" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop English Name</label>
                                        <div class="col-md-6">
                                            <input type="text" name="WorkshopEnglishName" id="WorkshopEnglishName" class="form-control" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop Logo (Optional)</label>
                                        <div class="col-md-6">
                                            <input type="file" name="Wlogo" id="Wlogo" accept=".bmp,.svg,.jpg,.png,.gif" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop Banner (Optional)</label>
                                        <div class="col-md-6">
                                            <input type="file" name="Wbanner" id="Wbanner" accept=".bmp,.svg,.jpg,.png,.gif" />
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- Workshop Dates --}}
                                    <legend>Workshop Dates Information</legend>

                                    <!-- <div class="form-group row hidden">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop Period (days)<span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" name="WorkshopPer" id="WorkshopPer"  value=class="form-control">
                                        </div>
                                    </div> -->
                                    {{-- Workshop Dates Details --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop Period (days)<span class="text-danger">*</span></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="number" name="nolec" id="nolec" class="form-control" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info" onclick="generateLec()">Set</button>
                                                </div>
                                            </div>
                                            <!-- <div id="lecName" class="mt-2"></div>
                                            <div id="lecAcDet" class="mt-2"></div>
                                            <div id="SessName" class="mt-2"></div>
                                            <div id="SessDet" class="mt-2"></div>
                                            <div id="lecName2" class="mt-2"></div>
                                            <div id="lecAcDet2" class="mt-2"></div>
                                            <div id="SessName2" class="mt-2"></div>
                                            <div id="SessDet2" class="mt-2"></div> -->
                                            <div id="lecWrapper"></div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">
                                            Starting Date & Time<span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-6 pr-1">
                                                    <input type="text" name="WorkshopSDate" id="WorkshopSDate" class="form-control datepicker" placeholder="MM/DD/YYYY" required>
                                                </div>
                                                <div class="col-6 pl-1">
                                                    <input type="time" name="WorkshopSTime" id="WorkshopSTime" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">
                                            Ending Date & Time<span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-6 pr-1">
                                                    <input type="text" name="WorkshopEDate" id="WorkshopEDate" class="form-control datepicker" placeholder="MM/DD/YYYY" disabled required>
                                                </div>
                                                <div class="col-6 pl-1">
                                                    <input type="time" name="WorkshopETime" id="WorkshopETime" class="form-control" disabled required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- Fees --}}
                                    <legend>Fees Information</legend>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Attendees<span class="text-danger">*</span></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select name="nofees" id="nofees" class="form-control" required>
                                                    <option value="" disabled selected hidden>Select type</option>
                                                    <option value="1">Same fees</option>
                                                    <option value="2">Different fees</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info" onclick="generate()">Set</button>
                                                </div>
                                            </div>
                                            <div id="categoryName" class="mt-2"></div>
                                            <div id="categoryAmount" class="mt-2"></div>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- Location --}}
                                    <legend>Location Information</legend>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Workshop Place<span class="text-danger">*</span></label>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="WorkshopPl" id="WorkshopPl" required>
                                        </div>
                                    </div>

                                    <hr>
                                    {{-- Representative --}}
                                    <legend>Representative Information</legend>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Name<span class="text-danger">*</span></label>
                                        <div class="col-md-5"><input type="text" class="form-control" name="WorkshopCname" required></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Phone<span class="text-danger">*</span></label>
                                        <div class="col-md-5"><input type="tel" class="form-control" name="WorkshopCphone" required></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Email<span class="text-danger">*</span></label>
                                        <div class="col-md-5"><input type="email" class="form-control" name="WorkshopCemail" required></div>
                                    </div>

                                    <hr>
                                    {{-- Notes --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Notes</label>
                                        <div class="col-md-5"><input type="text" name="Wnotes" class="form-control"></div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- Scripts --}}
    <script>
        // Datepickers
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            todayHighlight: true,
            startDate: new Date()
        });

        $('#WorkshopSDate, #WorkshopSTime').on('change', function() {
            const startDate = $('#WorkshopSDate').val();
            const startTime = $('#WorkshopSTime').val();

            if (startDate && startTime) {
                // Enable ending date & time
                $('#WorkshopEDate, #WorkshopETime').prop('disabled', false);

                // Initialize ending datepicker
                $('#WorkshopEDate').datepicker('remove').datepicker({
                    format: 'mm/dd/yyyy',
                    startDate: startDate,
                    autoclose: true
                });
            } else {
                // Keep disabled if one is missing
                $('#WorkshopEDate, #WorkshopETime').prop('disabled', true);
            }
        });

        // Fees
        function generate() {
            let type = parseInt($('#nofees').val());
            $('#categoryName').empty();
            $('#categoryAmount').empty();

            if (type === 1) {
                $('#categoryName').append('<input class="form-control" type="text" value="Unified Fees" readonly>');
                $('#categoryAmount').append('<input class="form-control" type="number" name="samefees" placeholder="EGP" required>');
            }
            if (type === 2) {
                $('#categoryName').append('<input class="form-control mb-2" type="text" value="Internal Members" readonly>');
                $('#categoryAmount').append('<input class="form-control mb-2" type="number" name="internalfees" placeholder="EGP" required>');
                $('#categoryName').append('<input class="form-control" type="text" value="External Members" readonly>');
                $('#categoryAmount').append('<input class="form-control" type="number" name="externalfees" placeholder="EGP" required>');
            }
        }

        // Lecturers
        function generateLec() {
            let count = parseInt($('#nolec').val());
            $('#lecWrapper').empty(); // ONE main container

            for (let i = 0; i < count; i++) {
                let mode = $('input[name=optradio]:checked').val();

                let block = `<div class="card mb-3 p-3 border">`;

                block += `<h6 class="fw-bold mb-2">Day ${i + 1}</h6>`;

                if (mode === 'arabic' || mode === 'bothLan') {
                    block += `
                        <input class="form-control mb-2"
                            type="text"
                            name="LecturerArabicName${i}"
                            placeholder="اسم المحاضر في اليوم ${i + 1}"
                            required>

                        <input class="form-control mb-2"
                            type="text"
                            name="LecturerDetailsInAr${i}"
                            placeholder="تفاصيل محاضر اليوم ${i + 1}">

                        <input class="form-control mb-2"
                            type="text"
                            name="SessionTitleAr${i}"
                            placeholder="عنوان محاضرة اليوم ${i + 1}"
                            required>

                        <input class="form-control mb-2"
                            type="text"
                            name="SessionDetailsAr${i}"
                            placeholder="تفاصيل محاضرة اليوم ${i + 1}"
                            required>
                    `;
                }

                if (mode === 'english' || mode === 'bothLan') {
                    block += `
                        <input class="form-control mb-2"
                            type="text"
                            name="LecturerEnglishName${i}"
                            placeholder="Lecturer Name of Day ${i + 1}"
                            required>

                        <input class="form-control mb-2"
                            type="text"
                            name="LecturerDetailsInEng${i}"
                            placeholder="Lecturer Details of Day ${i + 1}">

                        <input class="form-control mb-2"
                            type="text"
                            name="SessionTitleEn${i}"
                            placeholder="Session Title of Day ${i + 1}"
                            required>

                        <input class="form-control mb-2"
                            type="text"
                            name="SessionDetailsEn${i}"
                            placeholder="Session Details of Day ${i + 1}"
                            required>
                    `;
                }

                block += `</div>`;

                $('#lecWrapper').append(block);
            }
        }

        // Language toggle
        $('.radioBtn').on('change', function() {
            let val = $(this).val();

            $('#WorkshopArabicName, #WorkshopEnglishName').prop('disabled', true).prop('required', false);

            if (val === 'arabic') {
                $('#WorkshopArabicName').prop('disabled', false).prop('required', true);
            }
            if (val === 'english') {
                $('#WorkshopEnglishName').prop('disabled', false).prop('required', true);
            }
            if (val === 'bothLan') {
                $('#WorkshopArabicName, #WorkshopEnglishName').prop('disabled', false).prop('required', true);
            }
        });

        // Trigger the default selection on page load
        $(document).ready(function() {
            $('input[name=optradio]:checked').trigger('change');
        });
    </script>
@endsection
