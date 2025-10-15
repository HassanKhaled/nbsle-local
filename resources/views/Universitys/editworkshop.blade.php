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

        {{-- Alerts --}}
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
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

        @php
            $lang = null;
            if (!empty($workshop->workshop_ar_title) && !empty($workshop->workshop_en_title)) {
                $lang = 'bothLan';
            } elseif (!empty($workshop->workshop_ar_title)) {
                $lang = 'arabic';
            } elseif (!empty($workshop->workshop_en_title)) {
                $lang = 'english';
            }
        @endphp

        <section class="login">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Edit Workshop Information</h5>
                        </div>

                        <div class="card-block p-4">
                            <form action="{{ url('/Uniworkshop/'.$id.'/update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- University Info --}}
                                <legend>Institute Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">University Name</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="{{ $UniName->name }}" readonly>
                                        <input type="hidden" name="univ_id" value="{{ $UniName->id }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Faculty Name</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="Facultyid" required>
                                            @foreach($facultyName as $faculty)
                                                <option value="{{ $faculty->id }}" {{ $workshop->Faculty_id == $faculty->id ? 'selected' : '' }}>
                                                    {{ $faculty->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Workshop Language --}}
                                <hr>
                                <legend>Workshop Basic Information</legend>
                                <div class="form-group row text-center">
                                    <div class="col-md-12">
                                        <label><input type="radio" class="radioBtn" name="optradio" value="arabic" 
                                            {{ $lang == 'arabic' ? 'checked' : '' }}> Arabic</label>
                                        <label class="ml-3"><input type="radio" class="radioBtn" name="optradio" value="english" 
                                            {{ $lang == 'english' ? 'checked' : '' }}> English</label>
                                        <label class="ml-3"><input type="radio" class="radioBtn" name="optradio" value="bothLan" 
                                            {{ $lang == 'bothLan' ? 'checked' : '' }}> Both</label>
                                        @error('optradio')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Titles --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop Arabic Title</label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopArabicName" id="WorkshopArabicName" class="form-control" value="{{ $workshop->workshop_ar_title }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop English Title</label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopEnglishName" id="WorkshopEnglishName" class="form-control" value="{{ $workshop->workshop_en_title }}">
                                    </div>
                                </div>

                                {{-- Logo --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop Logo</label>
                                    <div class="col-md-6">
                                        <input type="file" name="Wlogo" accept=".bmp,.svg,.jpg,.png,.gif">
                                        @if($workshop->workshop_logoPath)
                                            <div class="mt-2">
                                                <img src="{{ asset($workshop->workshop_logoPath) }}" width="120" alt="Workshop Logo">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Lecturers --}}
                                <hr>
                                <legend>Lecturers</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">No. of Lecturers</label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <input type="number" name="nolec" id="nolec" class="form-control" 
                                                value="{{ is_array($workshop->Lec_ar_names) ? count($workshop->Lec_ar_names) : ($workshop->no_lecturers ?? 0) }}">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-info" onclick="generateLec()">Set</button>
                                            </div>
                                        </div>
                                        @error('nolec')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                        <div id="lecContainer" class="mt-3"></div>
                                    </div>
                                </div>

                                {{-- Workshop Dates --}}
                                <hr>
                                <legend>Workshop Dates</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop Period</label>
                                    <div class="col-md-6">
                                        <input type="number" name="WorkshopPer" class="form-control" value="{{ $workshop->workshop_period }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Starting Date</label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopSDate" id="WorkshopSDate" class="form-control datepicker" value="{{ \Carbon\Carbon::parse($workshop->st_date)->format('m/d/Y') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Ending Date</label>
                                    <div class="col-md-6">
                                        <input type="text" name="WorkshopEDate" id="WorkshopEDate" class="form-control datepicker" value="{{ \Carbon\Carbon::parse($workshop->end_date)->format('m/d/Y') }}">
                                    </div>
                                </div>

                                {{-- Fees --}}
                                <hr>
                                <legend>Fees Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Attendees</label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <select name="nofees" id="nofees" class="form-control">
                                                <option value="" disabled>Select type</option>
                                                <option value="1" {{ count($workshop->fees_types ?? []) == 1 ? 'selected' : '' }}>Same fees</option>
                                                <option value="2" {{ count($workshop->fees_types ?? []) == 2 ? 'selected' : '' }}>Different fees</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-info" onclick="generateFees()">Set</button>
                                            </div>
                                        </div>
                                        <div id="categoryName" class="mt-2"></div>
                                        <div id="categoryAmount" class="mt-2"></div>
                                    </div>
                                </div>

                                {{-- Location --}}
                                <hr>
                                <legend>Location</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Workshop Place</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="WorkshopPl" value="{{ $workshop->place }}">
                                    </div>
                                </div>

                                {{-- Representative --}}
                                <hr>
                                <legend>Representative</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Name</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="WorkshopCname" value="{{ $workshop->rep_name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Phone</label>
                                    <div class="col-md-5">
                                        <input type="tel" class="form-control" name="WorkshopCphone" value="{{ $workshop->rep_phone }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-5">
                                        <input type="email" class="form-control" name="WorkshopCemail" value="{{ $workshop->rep_email }}">
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Notes</label>
                                    <div class="col-md-5">
                                        <input type="text" name="Wnotes" class="form-control" value="{{ $workshop->notes }}">
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="form-group row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">Update Workshop</button>
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

<script>
    // Pass saved lecturer data to JS
    const savedArNames = @json($workshop->Lec_ar_names ?? []);
    const savedArDetails = @json($workshop->Lec_ar_details ?? []);
    const savedEnNames = @json($workshop->Lec_en_names ?? []);
    const savedEnDetails = @json($workshop->Lec_en_details ?? []);
 console.log(savedArNames);
    // Datepicker
    $('.datepicker').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        todayHighlight: true
    });

    // Lecturers
    function generateLec() {
        let count = parseInt($('#nolec').val());
        const container = $('#lecContainer');
        container.empty();
        for (let i = 0; i < count; i++) {
            const arName = savedArNames[i] ?? '';
            const arDetail = savedArDetails[i] ?? '';
            const enName = savedEnNames[i] ?? '';
            const enDetail = savedEnDetails[i] ?? '';

            container.append(`
                <div class="p-2 mb-2 border rounded">
                    <input type="text" class="form-control mb-2" name="Lec_ar_names[]" value="${arName}" placeholder="Arabic Name">
                    <input type="text" class="form-control mb-2" name="Lec_ar_details[]" value="${arDetail}" placeholder="Arabic Details">
                    <input type="text" class="form-control mb-2" name="Lec_en_names[]" value="${enName}" placeholder="English Name">
                    <input type="text" class="form-control" name="Lec_en_details[]" value="${enDetail}" placeholder="English Details">
                </div>
            `);
        }
    }

    // Fees
    function generateFees() {
        let type = parseInt($('#nofees').val());
        $('#categoryName').empty();
        $('#categoryAmount').empty();
        const feeValues = @json($workshop->fees_values ?? []);

        if (type === 1) {
            $('#categoryName').append('<input class="form-control" type="text" value="Unified Fees" readonly>');
            $('#categoryAmount').append('<input class="form-control" type="number" name="samefees" value="'+(feeValues[0] ?? '')+'" placeholder="EGP">');
        }
        if (type === 2) {
            $('#categoryName').append('<input class="form-control mb-2" type="text" value="Internal Members" readonly>');
            $('#categoryAmount').append('<input class="form-control mb-2" type="number" name="internalfees" value="'+(feeValues[0] ?? '')+'" placeholder="EGP">');
            $('#categoryName').append('<input class="form-control" type="text" value="External Members" readonly>');
            $('#categoryAmount').append('<input class="form-control" type="number" name="externalfees" value="'+(feeValues[1] ?? '')+'" placeholder="EGP">');
        }
    }

    $(document).ready(function () {
        // Init language toggle
        $('.radioBtn:checked').trigger('change');
        // Init lecturers
        if ($('#nolec').val()) generateLec();
        // Init fees
        if ($('#nofees').val()) generateFees();
    });

    // Language toggle behavior
    $('.radioBtn').on('change', function() {
        let val = $(this).val();
        $('#WorkshopArabicName, #WorkshopEnglishName').prop('disabled', true).prop('required', false);
        if (val === 'arabic') $('#WorkshopArabicName').prop('disabled', false).prop('required', true);
        if (val === 'english') $('#WorkshopEnglishName').prop('disabled', false).prop('required', true);
        if (val === 'bothLan') $('#WorkshopArabicName, #WorkshopEnglishName').prop('disabled', false).prop('required', true);
    });
</script>
@endsection
