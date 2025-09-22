@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <div class="container">
        <section class="login">
            <div class="row justify-content-center">
                <div class="col-md-10">

                    {{-- ✅ Flash Messages --}}
                    @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    {{-- ✅ Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow">
                        <div class="card-header text-center">
                            <h5>Faculty Workshop Announcement Form</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/FacworkshopSub/'.$uniID.'/'.$facultyID.'/store') }}" 
                                  method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Language Options --}}
                                <legend>Workshop Basic Information</legend>
                                <div class="customradio mb-3 text-center">
                                    <label class="mr-3">
                                        <input class="radioBtn" type="radio" name="optradio" value="arabic" checked>
                                        Arabic
                                    </label>
                                    <label class="mr-3">
                                        <input class="radioBtn" type="radio" name="optradio" value="english">
                                        English
                                    </label>
                                    <label>
                                        <input class="radioBtn" type="radio" name="optradio" value="bothLan">
                                        Both
                                    </label>
                                </div>

                                {{-- Workshop Arabic Title --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop Arabic Title<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopArabicName" id="WorkshopArabicName" class="form-control" disabled>
                                    </div>
                                </div>

                                {{-- Workshop English Title --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop English Title</label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopEnglishName" id="WorkshopEnglishName" class="form-control" disabled>
                                    </div>
                                </div>

                                {{-- Logo Upload --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop Logo</label>
                                    <div class="col-md-6">
                                        <input type="file" name="Wlogo" id="Wlogo" accept=".jpg,.jpeg,.png,.gif,.bmp,.svg" class="form-control-file">
                                    </div>
                                </div>

                                {{-- Number of Lecturers --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">No. of Lecturers<span class="text-danger">*</span></label>
                                    <div class="col-md-6 d-flex">
                                        <input type="number" name="nolec" id="nolec" class="form-control mr-2" required>
                                        <button type="button" class="btn btn-secondary" onclick="generateLec()">Set</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" id="lecName"></div>
                                    <div class="col-md-6" id="lecAcDet"></div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6" id="lecName2"></div>
                                    <div class="col-md-6" id="lecAcDet2"></div>
                                </div>

                                <hr>

                                {{-- Workshop Dates --}}
                                <legend>Workshop Dates Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Period (days)<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="number" name="WorkshopPer" id="WorkshopPer" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Start Date<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopSDate" id="WorkshopSDate" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">End Date<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopEDate" id="WorkshopEDate" class="form-control" disabled required>
                                    </div>
                                </div>

                                <hr>

                                {{-- Fees --}}
                                <legend>Fees Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Attendees<span class="text-danger">*</span></label>
                                    <div class="col-md-6 d-flex">
                                        <select name="nofees" id="nofees" class="form-control mr-2">
                                            <option value="" disabled selected>Select type</option>
                                            <option value="1">Same fees</option>
                                            <option value="2">Different fees</option>
                                        </select>
                                        <button type="button" class="btn btn-secondary" onclick="generateFees()">Set</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" id="categoryName"></div>
                                    <div class="col-md-6" id="categoryAmount"></div>
                                </div>

                                <hr>

                                {{-- Place --}}
                                <legend>Location Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Place<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="WorkshopPl" id="WorkshopPl" required>
                                    </div>
                                </div>

                                <hr>

                                {{-- Representative --}}
                                <legend>Representative Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Name<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="WorkshopCname" id="WorkshopCname" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Phone<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="tel" class="form-control" name="WorkshopCphone" id="WorkshopCphone" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Email<span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="WorkshopCemail" id="WorkshopCemail" required>
                                    </div>
                                </div>

                                <hr>

                                {{-- Notes --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Notes</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="Wnotes" id="Wnotes" rows="2"></textarea>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="form-group row mb-0">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary px-5">Submit</button>
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

{{-- ✅ Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>

<script>
$(function () {
    // Datepickers
    $("#WorkshopSDate").datepicker({
        format: "mm/dd/yyyy",
        todayHighlight: true,
        autoclose: true,
        startDate: "today"
    }).on('changeDate', function (e) {
        $('#WorkshopEDate').prop('disabled', false).datepicker({
            format: "mm/dd/yyyy",
            startDate: e.date,
            autoclose: true
        });
    });

    // Language toggles
    $(".radioBtn").on('change', function () {
        let lang = $("input[name=optradio]:checked").val();
        if (lang === "arabic") {
            $("#WorkshopArabicName").prop({ required: true, disabled: false });
            $("#WorkshopEnglishName").prop({ required: false, disabled: true });
        } else if (lang === "english") {
            $("#WorkshopArabicName").prop({ required: false, disabled: true });
            $("#WorkshopEnglishName").prop({ required: true, disabled: false });
        } else {
            $("#WorkshopArabicName, #WorkshopEnglishName").prop({ required: true, disabled: false });
        }
    });
});

// Generate lecturer fields
function generateLec() {
    let count = parseInt($("#nolec").val());
    let lang = $("input[name=optradio]:checked").val();

    $("#lecName, #lecAcDet, #lecName2, #lecAcDet2").empty();

    for (let i = 0; i < count; i++) {
        if (lang === "arabic" || lang === "bothLan") {
            $("#lecName").append(`<input class="form-control mb-2" type="text" name="LecturerArabicName${i}" placeholder="Arabic Name" required>`);
            $("#lecAcDet").append(`<input class="form-control mb-2" type="text" name="LecturerDetailsInAr${i}" placeholder="Academic Details (AR)" required>`);
        }
        if (lang === "english" || lang === "bothLan") {
            $("#lecName2").append(`<input class="form-control mb-2" type="text" name="LecturerEnglishName${i}" placeholder="English Name" required>`);
            $("#lecAcDet2").append(`<input class="form-control mb-2" type="text" name="LecturerDetailsInEng${i}" placeholder="Academic Details (EN)" required>`);
        }
    }
}

// Generate fees fields
function generateFees() {
    let type = $("#nofees").val();
    $("#categoryName, #categoryAmount").empty();

    if (type == 1) {
        $("#categoryName").append(`<input class="form-control" type="text" value="Unified Fees" readonly>`);
        $("#categoryAmount").append(`<input class="form-control" type="number" name="samefees" placeholder="EGP" required>`);
    } else if (type == 2) {
        $("#categoryName").append(`<input class="form-control mb-2" type="text" value="Internal Members" readonly>`);
        $("#categoryAmount").append(`<input class="form-control mb-2" type="number" name="internalfees" placeholder="EGP" required>`);

        $("#categoryName").append(`<input class="form-control" type="text" value="External Members" readonly>`);
        $("#categoryAmount").append(`<input class="form-control" type="number" name="externalfees" placeholder="EGP" required>`);
    }
}
</script>
@endsection
