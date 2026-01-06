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

                {{-- ✅ Flash Message --}}
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" style="border-left: 4px solid #198754;">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ✅ Validation Errors --}}
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
                                <input type="hidden" name="uni_id" value="{{ $workshop->Uni_id }}">
                                <input type="hidden" name="fac_id" value="{{ $workshop->Faculty_id }}">

                                {{-- Participant Info Section --}}
                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-3 pb-2" style="border-bottom: 2px solid #0d6efd; display: inline-block;">
                                        <i class="bi bi-person-fill me-2"></i>Participant Information
                                    </h5>

                                    <div class="row g-3">
                                        {{-- Full Name --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   id="PartName"
                                                   name="PartName" 
                                                   class="form-control form-control-lg" 
                                                   value="{{ old('PartName') }}" 
                                                   placeholder="Enter your full name"
                                                   style="border-radius: 8px;">
                                            <small class="text-danger error-message" id="PartName-error"></small>
                                        </div>

                                        {{-- Gender --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                Gender <span class="text-danger">*</span>
                                            </label>
                                            <select id="partGender" name="partGender" class="form-select form-select-lg" style="border-radius: 8px;">
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="female" {{ old('partGender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="male" {{ old('partGender') == 'male' ? 'selected' : '' }}>Male</option>
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
                                                   class="form-control form-control-lg" 
                                                   value="{{ old('partEmail') }}"
                                                   placeholder="example@email.com"
                                                   style="border-radius: 8px;">
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
                                                   class="form-control form-control-lg" 
                                                   value="{{ old('national_id') }}"
                                                   placeholder="Enter your national ID"
                                                   style="border-radius: 8px;">
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
                                                   class="form-control form-control-lg" 
                                                   value="{{ old('phone') }}"
                                                   placeholder="01012345678"
                                                   maxlength="11"
                                                   style="border-radius: 8px;">
                                            <small class="text-danger error-message" id="phone-error"></small>
                                        </div>

                                        {{-- Participant Type --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Participant Type <span class="text-danger">*</span>
                                            </label>
                                            <select id="partType" 
                                                    name="partType" 
                                                    class="form-select form-select-lg" 
                                                    onchange="updateSubType()" 
                                                    style="border-radius: 8px;">
                                                <option value="" disabled selected>Select Type</option>
                                                <option value="Student" {{ old('partType') == 'Student' ? 'selected' : '' }}>Student</option>
                                                <option value="Staff" {{ old('partType') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="Employee" {{ old('partType') == 'Employee' ? 'selected' : '' }}>Employee</option>
                                            </select>
                                            <small class="text-danger error-message" id="partType-error"></small>
                                            <div id="PartTypeCategory" class="mt-3"></div>
                                        </div>
                                    </div>
                                </div>

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
    select.className = "form-select form-select-lg";
    select.style.borderRadius = "8px";

    if (type === "Student") {
        select.innerHTML = `
            <option value="" disabled selected>Select Student Category</option>
            <option value="BSc Student">BSc Student</option>
            <option value="Diploma Student">Diploma Student</option>
            <option value="MSc Student">MSc Student</option>
            <option value="PhD Student">PhD Student</option>
        `;
    } else if (type === "Staff") {
        select.innerHTML = `
            <option value="" disabled selected>Select Staff Position</option>
            <option value="Teaching Assistant">Teaching Assistant</option>
            <option value="Assistant Professor">Assistant Professor</option>
            <option value="Associate Professor">Associate Professor</option>
            <option value="Professor">Professor</option>
        `;
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
    // Always return false to prevent default form submission
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
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    // Restore subtype on page load if there was a validation error
    const partType = document.getElementById('partType').value;
    if (partType) {
        updateSubType();
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
</style>
@endsection