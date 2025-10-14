@extends('templ.head')

@section('tmplt-contnt')
<main id="main">

    {{-- 🔹 Hero Section with Cover Image --}}
    <section class="hero-section text-center text-white position-relative" style="
        background: url('{{ $workshop->cover ?? asset('images/workshop-default.jpg') }}') center/cover no-repeat;
        padding: 120px 0;
    ">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5);"></div>
        <div class="container position-relative">
            <h2 class="fw-bold">{{ $workshop->workshop_en_title ?? $workshop->workshop_ar_title }}</h2>
            <p class="lead">Register now to join this workshop and enhance your skills!</p>
        </div>
    </section>

    {{-- 🔹 Main Form Section --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- ✅ Flash Message --}}
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ✅ Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">

                        <h4 class="text-center mb-4 fw-bold">Workshop Registration Form</h4>
                        <hr>

                        <form action="{{ route('storeworkshop') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Hidden fields --}}
                            <input type="hidden" name="workshop_id" value="{{ $workshop->id }}">
                            <input type="hidden" name="uni_id" value="{{ $workshop->Uni_id }}">
                            <input type="hidden" name="fac_id" value="{{ $workshop->Faculty_id }}">

                            {{-- Participant Info --}}
                            <h5 class="fw-semibold mb-3">Participant Information</h5>

                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="PartName" class="form-control" value="{{ old('PartName') }}" required>
                                @error('PartName') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="partGender" class="form-select" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="female" {{ old('partGender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="male" {{ old('partGender') == 'male' ? 'selected' : '' }}>Male</option>
                                </select>
                                @error('partGender') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="partEmail" class="form-control" value="{{ old('partEmail') }}">
                                @error('partEmail') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Participant Type --}}
                            <div class="mb-3">
                                <label class="form-label">Participant Type <span class="text-danger">*</span></label>
                                <select id="partType" name="partType" class="form-select" onchange="updateSubType()" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="Student" {{ old('partType') == 'Student' ? 'selected' : '' }}>Student</option>
                                    <option value="Staff" {{ old('partType') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Employee" {{ old('partType') == 'Employee' ? 'selected' : '' }}>Employee</option>
                                </select>
                                <div id="PartTypeCategory" class="mt-2"></div>
                                @error('partType') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <hr>

                            {{-- Payment Info --}}
                            <h5 class="fw-semibold mb-2">Payment Information</h5>
                            <p class="text-muted small">Payment details will be collected later.</p>

                            <hr>

                            {{-- Submit --}}
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm">
                                    Submit Registration
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

{{-- 🔹 JavaScript --}}
<script>
function updateSubType() {
    let type = document.getElementById("partType").value;
    let container = document.getElementById("PartTypeCategory");
    container.innerHTML = "";

    if (type === "Employee") {
        container.innerHTML = `<input type="hidden" name="parSubType" value="Employee">`;
        return;
    }

    let select = document.createElement("select");
    select.name = "parSubType";
    select.className = "form-select mt-2";

    if (type === "Student") {
        select.innerHTML = `
            <option value="" disabled selected>Select Subtype</option>
            <option value="BSc Student">BSc Student</option>
            <option value="Diploma Student">Diploma Student</option>
            <option value="MSc Student">MSc Student</option>
            <option value="PhD Student">PhD Student</option>
        `;
    } else if (type === "Staff") {
        select.innerHTML = `
            <option value="" disabled selected>Select Subtype</option>
            <option value="Teaching Assistant">Teaching Assistant</option>
            <option value="Assistant Professor">Assistant Professor</option>
            <option value="Associate Professor">Associate Professor</option>
            <option value="Professor">Professor</option>
        `;
    }

    container.appendChild(select);
}
</script>
@endsection
