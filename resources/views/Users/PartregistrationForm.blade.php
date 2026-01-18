@extends('templ.head')

@section('tmplt-contnt')
<main id="main">
    {{-- 🔹 Hero Section with Cover Image --}}
    <section class="hero-section text-center text-white position-relative" style="
        background: url('{{ $workshop->cover ?? asset('images/workshop-default.jpg') }}') center/cover no-repeat;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);"></div>
        <div class="container position-relative" style="z-index: 2;">
            <h1 class="fw-bold mb-3" style="font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                {{ $workshop->workshop_en_title ?? $workshop->workshop_ar_title }}
            </h1>
            <p class="lead mb-0" style="font-size: 1.15rem; opacity: 0.95;">
                Register now to join this workshop and enhance your skills!
            </p>
        </div>
    </section>

    {{-- 🔹 Main Form Section --}}
    <div class="container py-5" style="margin-top: -40px; position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">

                {{-- Flash Message --}}
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" style="border-left: 4px solid #198754;">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" style="border-left: 4px solid #dc3545;">
                        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>There were some problems with your input:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body p-0">

                        {{-- Card Header --}}
                        <div class="text-center py-4 px-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6;">
                            <h3 class="fw-bold mb-2" style="color: #212529;">Workshop Registration Form</h3>
                            <p class="text-muted mb-0 small">Please fill out all required fields marked with <span class="text-danger">*</span></p>
                        </div>

                        <div class="p-4 p-md-5">
                        
                            <form id="registrationForm" action="{{ route('storeworkshop') }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf

                                {{-- Hidden fields --}}
                                <input type="hidden" name="workshop_id" value="{{ $workshop->id }}">

                                {{-- Participant Info Section --}}
                                <div class="mb-4">
                                    
                                    <h5 class="fw-semibold mb-3 pb-2" style="border-bottom: 2px solid #0d6efd; display: inline-block;">
                                        <i class="bi bi-person-fill me-2"></i>Participant Information
                                    </h5>

                                    <div class="row g-3">
                                         {{-- 🔹 Edit Data Checkbox --}}
                                        <div class="alert alert-info d-flex align-items-center mb-4" style="background-color: #e7f3ff; border-left: 4px solid #0d6efd;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="enableEditCheckbox" style="cursor: pointer; width: 20px; height: 20px;">
                                                    <label class="form-check-label ms-2" for="enableEditCheckbox" style="cursor: pointer; font-weight: 500; color: red;font-size: 1.2rem;">
                                                        <i class="bi bi-pencil-square me-2"></i>
                                                        In case you want to update any of the data you registered with, check this box
                                                    </label>
                                                </div>
                                        </div>
                                        {{-- Full Name --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   id="PartName"
                                                   name="PartName" 
                                                   class="form-control form-control-lg editable-field" 
                                                   value="{{ old('PartName', $saved_name) }}" 
                                                   placeholder="Enter your full name"
                                                   style="border-radius: 8px;"
                                                   {{ $saved_name ? 'disabled' : '' }}>
                                            <small class="text-danger error-message" id="PartName-error"></small>
                                        </div>

                                        {{-- Gender --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                Gender <span class="text-danger">*</span>
                                            </label>
                                            <select id="partGender" 
                                                    name="partGender" 
                                                    class="form-select form-select-lg editable-field" 
                                                    style="border-radius: 8px;"
                                                    {{ $saved_gender ? 'disabled' : '' }}>
                                                <option value="" disabled {{ !old('partGender', $saved_gender) ? 'selected' : '' }}>Select Gender</option>
                                                <option value="female" {{ old('partGender', $saved_gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="male" {{ old('partGender', $saved_gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            </select>
                                            <small class="text-danger error-message" id="partGender-error"></small>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" 
                                                   id="partEmail"
                                                   name="partEmail" 
                                                   class="form-control form-control-lg editable-field" 
                                                   value="{{ old('partEmail', $saved_email) }}"
                                                   placeholder="example@email.com"
                                                   style="border-radius: 8px;"
                                                   {{ $saved_email ? 'disabled' : '' }}>
                                            <small class="text-danger error-message" id="partEmail-error"></small>
                                        </div>

                                        {{-- National ID --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                National ID <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   id="national_id"
                                                   name="national_id" 
                                                   class="form-control form-control-lg editable-field" 
                                                   value="{{ old('national_id', $saved_national_id) }}"
                                                   placeholder="Enter your national ID"
                                                   maxlength="14"
                                                   style="border-radius: 8px;"
                                                   {{ $saved_national_id ? 'disabled' : '' }}>
                                            <small class="text-danger error-message" id="national_id-error"></small>
                                        </div>

                                        {{-- Phone Number --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                Phone Number <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   id="phone"
                                                   name="phone" 
                                                   class="form-control form-control-lg editable-field" 
                                                   value="{{ old('phone' , $saved_phone) }}"
                                                   placeholder="01012345678"
                                                   maxlength="11"
                                                   style="border-radius: 8px;"
                                                   {{ $saved_phone ? 'disabled' : '' }}
                                                   >

                                            <small class="text-danger error-message" id="phone-error"></small>
                                        </div>

                                        @if(!empty($saved_institution_name))
                                            {{-- Institution Name Field (shown when saved_institution_name exists) --}}
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    Institution Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       id="institution_name"
                                                       name="institution_name" 
                                                       class="form-control form-control-lg editable-field" 
                                                       value="{{ old('institution_name', $saved_institution_name) }}"
                                                       placeholder="Enter your institution name"
                                                       style="border-radius: 8px;"
                                                       disabled>
                                                <small class="text-danger error-message" id="institution_name-error"></small>
                                            </div>
                                        @else
                                            {{-- University and Faculty Dropdowns (shown when no saved_institution_name) --}}
                                            <div class="col-md-6">
                                                <label for="university" class="form-label fw-semibold">
                                                    {{ __('University') }} <span class="text-danger">*</span>
                                                </label>
                                                @php
                                                    $unis = \App\Models\universitys::all();
                                                @endphp
                                                <select id="university" 
                                                        class="form-select form-select-lg editable-field @error('uni_id') is-invalid @enderror" 
                                                        name="uni_id"
                                                        style="border-radius: 8px;"
                                                        {{ $saved_uni_id ? 'disabled' : '' }}>
                                                    <option value="">Select Your University</option>
                                                    @foreach($unis as $uni)
                                                        <option value="{{$uni->id}}" {{ old('uni_id', $saved_uni_id) == $uni->id ? 'selected' : '' }}>{{$uni->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('uni_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <small class="text-danger error-message" id="university-error"></small>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="faculty" class="form-label fw-semibold">
                                                    {{ __('Faculty') }} <span class="text-danger">*</span>
                                                </label>
                                                <select id="faculty" 
                                                        class="form-select form-select-lg editable-field @error('fac_id') is-invalid @enderror" 
                                                        name="fac_id"
                                                        style="border-radius: 8px;"
                                                        {{ $saved_fac_id ? 'disabled' : '' }}>
                                                    <option value="">Select Your Faculty</option>
                                                </select>
                                                @error('fac_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <small class="text-danger error-message" id="faculty-error"></small>
                                            </div>
                                        @endif


                                        {{-- Participant Type --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Participant Type <span class="text-danger">*</span>
                                            </label>
                                            <select id="partType" 
                                                    name="partType" 
                                                    class="form-select form-select-lg editable-field" 
                                                    onchange="updateSubType()" 
                                                    style="border-radius: 8px;"
                                                    {{ $saved_par_type ? 'disabled' : '' }}>
                                                <option value="" disabled {{ !old('partType', $saved_par_type) ? 'selected' : '' }}>Select Type</option>
                                                <option value="Student" {{ old('partType', $saved_par_type) == 'Student' ? 'selected' : '' }}>Student</option>
                                                <option value="Staff" {{ old('partType', $saved_par_type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="Employee" {{ old('partType', $saved_par_type) == 'Employee' ? 'selected' : '' }}>Employee</option>
                                            </select>
                                            <small class="text-danger error-message" id="partType-error"></small>
                                            <div id="PartTypeCategory" class="mt-3"></div>
                                        </div>
                                    </div>
                                </div>
                               

                                <input type="hidden" id="isUpdated" name="isUpdated" value="0">

                                {{-- Submit Button --}}
                                <div class="text-center mt-5 pt-3" style="border-top: 1px solid #e9ecef;">
                                    <button type="submit" 
                                            id="submitBtn"
                                            class="btn btn-primary btn-lg px-5 py-3 shadow" 
                                            style="border-radius: 50px; min-width: 250px; font-weight: 600; letter-spacing: 0.5px;">
                                        <i class="bi bi-check-circle me-2"></i>Submit Registration
                                    </button>
                                    <p class="text-muted small mt-3 mb-0">
                                        By submitting, you agree to participate in this workshop
                                    </p>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

{{-- 🔹 JavaScript --}}
<script>
// Check if we have saved institution name
const hasSavedInstitution = "{{ $saved_institution_name ?? '' }}" !== "";

// Only run faculty/university logic if we DON'T have saved institution
if (!hasSavedInstitution) {
    const facultySelect = document.getElementById('faculty');
    const uniSelect = document.getElementById('university');

    const savedFacId = "{{ $saved_fac_id ?? '' }}";
    const uniId = uniSelect.value; // Current university

    // Async function to fetch and set faculties
    async function fetchAndSetFaculty(univId, selectId, preselectId = null) {
        const facultySelect = document.getElementById(selectId);

        if (!univId) {
            facultySelect.disabled = true;
            facultySelect.innerHTML = '<option value="">Select Your Faculty</option>';
            return;
        }

        facultySelect.disabled = true;
        facultySelect.innerHTML = '<option>Loading...</option>';

        try {
            const response = await fetch('/getFacultiesByUnivId/' + univId);
            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();

            facultySelect.innerHTML = '<option value="">Select Your Faculty</option>';

            data.forEach(faculty => {
                const option = document.createElement('option');
                option.value = faculty.id;
                option.textContent = faculty.name;

                if (preselectId && faculty.id == preselectId) {
                    option.selected = true;
                    facultySelect.disabled = true; // disable if preselected
                }

                facultySelect.appendChild(option);
            });

            if (!preselectId) {
                facultySelect.disabled = false;
            }
        } catch (err) {
            facultySelect.disabled = true;
            facultySelect.innerHTML = '<option value="">Error loading faculties</option>';
            console.error('Error fetching faculties:', err);
        }
    }

    
     // Initial load: fetch faculty if uniId and savedFacId exist
     if (uniId && savedFacId) {
        fetchAndSetFaculty(uniId, 'faculty', savedFacId);
    }

    // On university change: fetch faculties normally
    uniSelect.addEventListener('change', function() {
        fetchAndSetFaculty(this.value, 'faculty');
    });
}


// Store initial saved values from backend
const savedValues = {
    name: "{{ $saved_name ?? '' }}",
    email: "{{ $saved_email ?? '' }}",
    national_id: "{{ $saved_national_id ?? '' }}",
    uni_id: "{{ $saved_uni_id ?? '' }}",
    fac_id: "{{ $saved_fac_id ?? '' }}",
    par_type: "{{ $saved_par_type ?? '' }}",
    par_sub_type: "{{ $saved_par_sub_type ?? '' }}",
    gender: "{{ $saved_gender ?? '' }}",
    phone: "{{ $saved_phone ?? '' }}",
    institution_name: "{{ $saved_institution_name ?? '' }}"
};

// Restore subtype on page load if there was a saved value - IMMEDIATE
if (savedValues.par_type) {
    updateSubType();
}


const enableEditCheckbox = document.getElementById('enableEditCheckbox');
const editableFields = document.querySelectorAll('.editable-field');
const isUpdatedInput = document.getElementById('isUpdated');

enableEditCheckbox.addEventListener('change', function() {
    editableFields.forEach(field => {
        if (this.checked) {
            // Enable all editable fields
            field.disabled = false;
            field.style.backgroundColor = '#ffffff';
        } else {
            // Disable only fields that had saved values
            if (shouldBeDisabled(field)) {
                field.disabled = true;
                field.style.backgroundColor = '#e9ecef';
            }
        }
    });
   
    isUpdatedInput.value = this.checked ? '1' : '0';

    // Also handle parSubType field
    const subTypeField = document.getElementById('parSubType');
    if (subTypeField) {
        if (this.checked) {
            subTypeField.disabled = false;
            subTypeField.style.backgroundColor = '#ffffff';
        } else if (savedValues.par_sub_type) {
            subTypeField.disabled = true;
            subTypeField.style.backgroundColor = '#e9ecef';
        }
    }
});

// Set initial disabled styling
editableFields.forEach(field => {
    if (field.disabled) {
        field.style.backgroundColor = '#e9ecef';
    }
});

function shouldBeDisabled(field) {
    const fieldId = field.id;
    
    // Check if field has a saved value
    if (fieldId === 'PartName' && savedValues.name) return true;
    if (fieldId === 'partEmail' && savedValues.email) return true;
    if (fieldId === 'national_id' && savedValues.national_id) return true;
    if (fieldId === 'university' && savedValues.uni_id) return true;
    if (fieldId === 'faculty' && savedValues.fac_id) return true;
    if (fieldId === 'partType' && savedValues.par_type) return true;
    if (fieldId === 'partGender' && savedValues.gender) return true;
    if (fieldId === 'phone' && savedValues.phone) return true;
    if (fieldId === 'parSubType' && savedValues.par_sub_type) return true;
    if (fieldId === 'institution_name' && savedValues.institution_name) return true;

    return false;
}

function updateSubType() {
    let type = document.getElementById("partType").value;
    let container = document.getElementById("PartTypeCategory");
    container.innerHTML = "";

    // Clear any existing error for subtype
    const subTypeError = document.getElementById("parSubType-error");
    if (subTypeError) {
        subTypeError.remove();
    }

    if (type === "Employee") {
        container.innerHTML = `<input type="hidden" id="parSubType" name="parSubType" value="Employee">`;
        return;
    }

    let label = document.createElement("label");
    label.className = "form-label fw-semibold";
    label.innerHTML = `${type} Category <span class="text-danger">*</span>`;
    
    let select = document.createElement("select");
    select.id = "parSubType";
    select.name = "parSubType";
    select.className = "form-select form-select-lg editable-field";
    select.style.borderRadius = "8px";

    if (type === "Student") {
        select.innerHTML = `
            <option value="" disabled ${!savedValues.par_sub_type ? 'selected' : ''}>Select Student Category</option>
            <option value="BSc Student" ${savedValues.par_sub_type === 'BSc Student' ? 'selected' : ''}>BSc Student</option>
            <option value="Diploma Student" ${savedValues.par_sub_type === 'Diploma Student' ? 'selected' : ''}>Diploma Student</option>
            <option value="MSc Student" ${savedValues.par_sub_type === 'MSc Student' ? 'selected' : ''}>MSc Student</option>
            <option value="PhD Student" ${savedValues.par_sub_type === 'PhD Student' ? 'selected' : ''}>PhD Student</option>
        `;
    } else if (type === "Staff") {
        select.innerHTML = `
            <option value="" disabled ${!savedValues.par_sub_type ? 'selected' : ''}>Select Staff Position</option>
            <option value="Teaching Assistant" ${savedValues.par_sub_type === 'Teaching Assistant' ? 'selected' : ''}>Teaching Assistant</option>
            <option value="Assistant Professor" ${savedValues.par_sub_type === 'Assistant Professor' ? 'selected' : ''}>Assistant Professor</option>
            <option value="Associate Professor" ${savedValues.par_sub_type === 'Associate Professor' ? 'selected' : ''}>Associate Professor</option>
            <option value="Professor" ${savedValues.par_sub_type === 'Professor' ? 'selected' : ''}>Professor</option>
        `;
    }

    // If there's a saved value and checkbox is not checked, disable the field
    const enableEditCheckbox = document.getElementById('enableEditCheckbox');
    if (savedValues.par_sub_type && !enableEditCheckbox.checked) {
        select.disabled = true;
        select.style.backgroundColor = '#e9ecef';
    }

    let errorSpan = document.createElement("small");
    errorSpan.className = "text-danger error-message";
    errorSpan.id = "parSubType-error";

    container.appendChild(label);
    container.appendChild(select);
    container.appendChild(errorSpan);
}

// Validation function - ONLY triggered on submit button click
function validateForm(event) {
    event.preventDefault();
    event.stopPropagation();
    
    let isValid = true;
    
    // Clear all previous errors
    document.querySelectorAll('.error-message').forEach(error => {
        error.textContent = '';
    });
    
    // Remove error styling
    document.querySelectorAll('.form-control, .form-select').forEach(field => {
        field.classList.remove('is-invalid');
    });

    // Validate Full Name
    const partName = document.getElementById('PartName');
    if (!partName.value.trim()) {
        showError('PartName', 'This field is required');
        isValid = false;
    }

    // Validate Gender
    const partGender = document.getElementById('partGender');
    if (!partGender.value) {
        showError('partGender', 'This field is required');
        isValid = false;
    }

    // Validate Email
    const partEmail = document.getElementById('partEmail');
    if (!partEmail.value.trim()) {
        showError('partEmail', 'This field is required');
        isValid = false;
    } else if (!isValidEmail(partEmail.value)) {
        showError('partEmail', 'Please enter a valid email address');
        isValid = false;
    }

    // Validate National ID
    const nationalId = document.getElementById('national_id');
    if (!nationalId.value.trim()) {
        showError('national_id', 'This field is required');
        isValid = false;
    }

    // Validate Phone Number
    const phone = document.getElementById('phone');
    if (!phone.value.trim()) {
        showError('phone', 'This field is required');
        isValid = false;
    } else if (!isValidPhone(phone.value)) {
        showError('phone', 'Phone must start with 010, 011, 012, or 015 and be 11 digits');
        isValid = false;
    }

    // Conditional validation: University/Faculty OR Institution Name
    if (hasSavedInstitution) {
        // Validate Institution Name
        const institutionName = document.getElementById('institution_name');
        if (institutionName && !institutionName.value.trim()) {
            showError('institution_name', 'This field is required');
            isValid = false;
        }
    } else {
        // Validate University
        const university = document.getElementById('university');
        if (university && !university.value) {
            showError('university', 'This field is required');
            isValid = false;
        }

        // Validate Faculty
        const faculty = document.getElementById('faculty');
        if (faculty && !faculty.value) {
            showError('faculty', 'This field is required');
            isValid = false;
        }
    }

    // Validate Participant Type
    const partType = document.getElementById('partType');
    if (!partType.value) {
        showError('partType', 'This field is required');
        isValid = false;
    }

    const parSubType = document.getElementById('parSubType');
    if (parSubType && parSubType.tagName === 'SELECT') {
        if (!parSubType.value) {
            showError('parSubType', 'This field is required');
            isValid = false;
        }
    }

    if (isValid) {
        const form = document.getElementById('registrationForm');
        form.removeEventListener('submit', validateForm);
        form.submit();
    } else {
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    return false;
}

function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + '-error');
    const field = document.getElementById(fieldId);
    
    if (errorElement) {
        errorElement.textContent = message;
    }
    if (field) {
        field.classList.add('is-invalid');
    }
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^(010|011|012|015)\d{8}$/;
    return phoneRegex.test(phone);
}

// Real-time validation for phone number (only allow numbers)
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});

const form = document.getElementById('registrationForm');
if (form) {
    form.addEventListener('submit', validateForm);
}
</script>


<style>
.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-control-lg,
.form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3) !important;
}

/* Smooth animations */
.alert {
    animation: slideDown 0.4s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Error styling */
.is-invalid {
    border-color: #dc3545 !important;
    background-color: #fff5f5;
}

.error-message {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.error-message:empty {
    display: none;
}

/* Disabled field styling */
.form-control:disabled,
.form-select:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Checkbox styling */
.form-check-input {
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endsection