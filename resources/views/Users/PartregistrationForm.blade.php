@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    <div class="container mt-4">
        <section class="login">
            <div class="row justify-content-center">
                <div class="col-md-8">

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
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow">
                        <div class="card-header text-center">
                            <h5>Workshop Registration Form</h5>
                        </div>

                        <div class="card-body">
                            <form action="{{ url('/WorkRegistrationForm/store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Participant Info --}}
                                <legend>Participant Basic Information</legend>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Full Name <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="PartName" id="PartName" class="form-control" value="{{ old('PartName') }}" required>
                                        @error('PartName') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Gender <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select name="partGender" id="partGender" class="form-control" required>
                                            <option value="" disabled selected>Select an option</option>
                                            <option value="female" {{ old('partGender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="male" {{ old('partGender') == 'male' ? 'selected' : '' }}>Male</option>
                                        </select>
                                        @error('partGender') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="email" name="partEmail" id="partEmail" class="form-control" value="{{ old('partEmail') }}">
                                        @error('partEmail') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                {{-- Participant Type --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Participant Type <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select id="partType" name="partType" class="form-control" onchange="updateSubType()" required>
                                            <option value="" disabled selected>Select an option</option>
                                            <option value="Student" {{ old('partType') == 'Student' ? 'selected' : '' }}>Student</option>
                                            <option value="Staff" {{ old('partType') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                            <option value="Employee" {{ old('partType') == 'Employee' ? 'selected' : '' }}>Employee</option>
                                        </select>
                                        <div id="PartTypeCategory" class="mt-2"></div>
                                        @error('partType') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                {{-- University --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">University <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="PartUni" id="PartUni" class="form-control" value="{{ old('PartUni') }}" required>
                                        @error('PartUni') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                {{-- Faculty --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Faculty <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="PartFaculty" id="PartFaculty" class="form-control" value="{{ old('PartFaculty') }}" required>
                                        @error('PartFaculty') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                {{-- Department --}}
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">Department <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="PartDept" id="PartDept" class="form-control" value="{{ old('PartDept') }}" required>
                                        @error('PartDept') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <hr>

                                {{-- Payment Info --}}
                                <legend>Payment Information</legend>
                                <p class="text-muted ml-3">Payment details will be collected later.</p>

                                <hr>

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

{{-- ✅ JavaScript --}}
<script>
function updateSubType() {
    let type = document.getElementById("partType").value;
    let container = document.getElementById("PartTypeCategory");
    container.innerHTML = "";

    let select = document.createElement("select");
    select.name = "parSubType";
    select.className = "form-control";

    if (type === "Student") {
        select.innerHTML = `
            <option value="" disabled selected>Select type</option>
            <option value="BSc Student">BSc Student</option>
            <option value="Diploma Student">Diploma Student</option>
            <option value="MSc Student">MSc Student</option>
            <option value="PhD Student">PhD Student</option>
        `;
    }
    if (type === "Staff") {
        select.innerHTML = `
            <option value="" disabled selected>Select type</option>
            <option value="Teaching Assistant">Teaching Assistant</option>
            <option value="Assistant Professor">Assistant Professor</option>
            <option value="Associate Professor">Associate Professor</option>
            <option value="Professor">Professor</option>
        `;
    }
    if (type === "Employee") {
        // Employee has no subtypes, just set a hidden input
        container.innerHTML = `<input type="hidden" name="parSubType" value="Employee">`;
        return;
    }

    container.appendChild(select);
}
</script>
@endsection
