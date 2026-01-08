@extends('templ.head')
@section('tmplt-contnt')
<style>
/* Custom CSS for Lab Register Design */
.register-section {
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
    padding: 60px 0;
}

.lab-register-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(76, 175, 80, 0.2);
    overflow: hidden;
    background: white;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.lab-register-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(76, 175, 80, 0.3);
}

.lab-card-header {
    background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    color: white;
    text-align: center;
    padding: 25px;
    font-size: 24px;
    font-weight: 600;
    border: none;
    position: relative;
    overflow: hidden;
}
.lab-card-body {
    padding: 40px;
    background: rgba(248, 249, 250, 0.5);
}

.lab-form-control {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: white;
}

.lab-form-control:focus {
    border-color: #4caf50;
    box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    background: white;
}

.lab-form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.lab-form-control:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
    opacity: 0.6;
}

.lab-form-label {
    font-weight: 600;
    color: #2e7d32;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.lab-btn-register {
    background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
    border: none;
    border-radius: 12px;
    padding: 14px 30px;
    font-weight: 600;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.lab-btn-register:hover {
    background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
}

.lab-btn-register::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.lab-btn-register:hover::before {
    left: 100%;
}

.lab-title {
    text-align: center;
    margin-bottom: 40px;
    color: #2e7d32;
    font-size: 32px;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.lab-subtitle {
    text-align: center;
    color: #4caf50;
    font-size: 16px;
    margin-bottom: 30px;
    font-style: italic;
}

.form-group-enhanced {
    margin-bottom: 25px;
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #4caf50;
    font-size: 18px;
    z-index: 5;
}

.lab-form-control.with-icon {
    padding-left: 50px;
}

.lab-footer {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
}

/* Custom Select Styling */
.lab-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234caf50' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    appearance: none;
}

/* Radio Button Styling */
.affiliation-section {
    margin-bottom: 30px;
    padding: 20px;
    background: rgba(76, 175, 80, 0.05);
    border-radius: 12px;
    border: 2px solid #e0e0e0;
}

.affiliation-title {
    font-weight: 600;
    color: #4caf50;
    margin-bottom: 15px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.radio-group {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.radio-option {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.radio-option input[type="radio"] {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    cursor: pointer;
    accent-color: #4caf50;
}

.radio-option label {
    font-size: 16px;
    color: #333;
    cursor: pointer;
    margin-bottom: 0;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .register-section {
        padding: 30px 15px;
    }
    
    .lab-card-body {
        padding: 30px 20px;
    }
    
    .lab-title {
        font-size: 24px;
    }
}

/* Form Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 25px;
}

@media (min-width: 768px) {
    .form-grid-2cols {
        grid-template-columns: 1fr 1fr;
    }
}

/* Hide/Show Animation */
.fade-out {
    opacity: 0;
    max-height: 0;
    overflow: hidden;
    transition: opacity 0.3s ease, max-height 0.3s ease;
}

.fade-in {
    opacity: 1;
    max-height: 500px;
    transition: opacity 0.3s ease, max-height 0.3s ease;
}
</style>

<main id="main">
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    
    <section class="login register-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">                 
                    
                    <div class="card lab-register-card">
                        <div class="card-header lab-card-header">
                            {{ __('Register') }}
                        </div>
                        <div class="card-body lab-card-body">
                            <form method="POST" action="{{ route('register') }}" id="registerForm">
                                @csrf
                                
                                <div class="form-grid form-grid-2cols">
                                    <div class="form-group-enhanced">
                                        <label for="name" class="lab-form-label">
                                            {{ __('Full Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class='bx bx-user input-icon'></i>
                                            <input id="name" 
                                                   type="text" 
                                                   class="form-control lab-form-control with-icon @error('name') is-invalid @enderror" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   required 
                                                   autocomplete="name" 
                                                   autofocus
                                                   placeholder="Enter your full name">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group-enhanced">
                                        <label for="username" class="lab-form-label">
                                            {{ __('Username') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class='bx bx-at input-icon'></i>
                                            <input id="username" 
                                                   type="text" 
                                                   class="form-control lab-form-control with-icon @error('username') is-invalid @enderror" 
                                                   name="username" 
                                                   value="{{ old('username') }}" 
                                                   required 
                                                   autocomplete="username"
                                                   placeholder="Choose a username">
                                            @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-grid form-grid-2cols">
                                    <div class="form-group-enhanced">
                                        <label for="email" class="lab-form-label">
                                            {{ __('E-Mail Address') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class='bx bx-envelope input-icon'></i>
                                            <input id="email" 
                                                   type="email" 
                                                   class="form-control lab-form-control with-icon @error('email') is-invalid @enderror" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   required 
                                                   autocomplete="email"
                                                   placeholder="Enter your email address">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group-enhanced">
                                        <label for="national_id" class="lab-form-label">
                                            {{ __('National ID') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class='bx bx-id-card input-icon'></i>
                                            <input type="text" 
                                                   id="national_id"
                                                   name="national_id" 
                                                   class="form-control lab-form-control with-icon @error('national_id') is-invalid @enderror" 
                                                   value="{{ old('national_id') }}"
                                                   required
                                                   placeholder="Enter your national ID">
                                            @error('national_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-grid form-grid-2cols">
                                    <div class="form-group-enhanced">
                                        <label for="password" class="lab-form-label">
                                            {{ __('Password') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class='bx bx-lock-alt input-icon'></i>
                                            <input id="password" 
                                                   type="password" 
                                                   class="form-control lab-form-control with-icon @error('password') is-invalid @enderror" 
                                                   name="password" 
                                                   required 
                                                   autocomplete="new-password"
                                                   placeholder="Create a password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group-enhanced">
                                        <label for="password-confirm" class="lab-form-label">
                                            {{ __('Confirm Password') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <i class='bx bx-check-shield input-icon'></i>
                                            <input id="password-confirm" 
                                                   type="password" 
                                                   class="form-control lab-form-control with-icon" 
                                                   name="password_confirmation" 
                                                   required 
                                                   autocomplete="new-password"
                                                   placeholder="Confirm your password">
                                        </div>
                                    </div>
                                </div>

                                <!-- Affiliation Section -->
                                <div class="affiliation-section">
                                    <div class="affiliation-title">AFFILIATION</div>
                                    <div class="radio-group">
                                        <div class="radio-option">
                                            <input type="radio" 
                                                   id="affiliation_university" 
                                                   name="affiliation" 
                                                   value="university">
                                            <label for="affiliation_university">University</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" 
                                                   id="affiliation_others" 
                                                   name="affiliation" 
                                                   value="others">
                                            <label for="affiliation_others">Others</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- University and Faculty Dropdowns -->
                                <div id="universitySection">
                                    <div class="form-grid form-grid-2cols">
                                        <div class="form-group-enhanced">
                                            <label for="university" class="lab-form-label">{{ __('University') }}</label>
                                            <div class="position-relative">
                                                <i class='bx bx-building-house input-icon'></i>
                                                <label hidden>{{$unis = \App\Models\universitys::all()}}</label>
                                                <select id="university" 
                                                        class="form-control lab-form-control lab-select with-icon @error('university') is-invalid @enderror" 
                                                        name="uni_id"
                                                        disabled>
                                                    <option value="">Select Your University</option>
                                                    @foreach($unis as $uni)
                                                        <option value="{{$uni->id}}" {{ old('university') == $uni->id ? 'selected' : '' }}>{{$uni->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('university')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <small class="text-danger" id="university-error" style="display: none; font-weight: 600;"></small>
                                            </div>
                                        </div>

                                        <div class="form-group-enhanced">
                                            <label for="faculty" class="lab-form-label">{{ __('Faculty') }}</label>
                                            <div class="position-relative">
                                                <i class='bx bx-book-open input-icon'></i>
                                                <label hidden>{{$faculty = \App\Models\fac_uni::all()}}</label>
                                                <select id="faculty" 
                                                        class="form-control lab-form-control lab-select with-icon @error('faculty') is-invalid @enderror" 
                                                        name="fac_id"
                                                        disabled>
                                                    <option value="">Select Your Faculty</option>
                                                    @foreach($faculty as $fac)
                                                        <option value="{{$fac->id}}" {{ old('faculty') == $fac->id ? 'selected' : '' }}>{{$fac->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('faculty')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <small class="text-danger" id="faculty-error" style="display: none; font-weight: 600;"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- institution_name Text Field (Hidden by default) -->
                                <div id="institution_nameSection" style="display: none;">
                                    <div class="form-group-enhanced">
                                        <label for="institution_name" class="lab-form-label">{{ __('institution name') }}</label>
                                        <div class="position-relative">
                                            <i class='bx bx-briefcase input-icon'></i>
                                            <input type="text" 
                                                   id="institution_name"
                                                   name="institution_name" 
                                                   class="form-control lab-form-control with-icon" 
                                                   value="{{ old('institution_name') }}"
                                                   placeholder="Enter your institution name"
                                                   disabled>
                                            <small class="text-danger" id="institution_name-error" style="display: none; font-weight: 600;"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group-enhanced text-center">
                                    <button type="submit" class="btn lab-btn-register">
                                        <i class='bx bx-user-plus me-2'></i>
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

<script src="//code.jquery.com/jquery.js"></script>
<script>
    // Enhanced form submission with loading animation and validation
    $(document).ready(function() {
        // Clear all error messages
        function clearErrors() {
            $('.text-danger').hide();
            $('.lab-form-control').removeClass('is-invalid');
            $('.invalid-feedback').hide();
        }
        
        // Show error message
        function showError(fieldId, message) {
            const field = $(`#${fieldId}`);
            const errorElement = $(`#${fieldId}-error`);
            
            if (errorElement.length) {
                errorElement.text(message).show();
            } else {
                // Create error element if it doesn't exist
                field.after(`<small class="text-danger d-block mt-1" id="${fieldId}-error" style="font-weight: 600;">${message}</small>`);
            }
            field.addClass('is-invalid');
        }
        
        // Validate email format
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Validate national ID (14 digits)
        function isValidNationalId(nationalId) {
            const nationalIdRegex = /^\d{14}$/;
            return nationalIdRegex.test(nationalId);
        }
        
        // Validate form
        function validateForm() {
            clearErrors();
            let isValid = true;
            
            // Validate Full Name
            const name = $('#name').val().trim();
            if (name === '') {
                showError('name', 'Full name is required');
                isValid = false;
            }
            
            // Validate Username
            const username = $('#username').val().trim();
            if (username === '') {
                showError('username', 'Username is required');
                isValid = false;
            }
            
            // Validate Email
            const email = $('#email').val().trim();
            if (email === '') {
                showError('email', 'Email address is required');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('email', 'Email format is not correct');
                isValid = false;
            }
            
            // Validate National ID
            const nationalId = $('#national_id').val().trim();
            if (nationalId === '') {
                showError('national_id', 'National ID is required');
                isValid = false;
            } else if (!isValidNationalId(nationalId)) {
                showError('national_id', 'National ID must be exactly 14 digits');
                isValid = false;
            }
            
            // Validate Password
            const password = $('#password').val();
            if (password === '') {
                showError('password', 'Password is required');
                isValid = false;
            } else if (password.length < 8) {
                showError('password', 'Password must be at least 8 characters');
                isValid = false;
            }
            
            // Validate Password Confirmation
            const passwordConfirm = $('#password-confirm').val();
            if (passwordConfirm === '') {
                showError('password-confirm', 'Password confirmation is required');
                isValid = false;
            } else if (password !== passwordConfirm) {
                showError('password-confirm', 'Password does not match confirmation password');
                isValid = false;
            }
            
            // Validate based on affiliation selection (optional field)
            const affiliation = $('input[name="affiliation"]:checked').val();
            
            if (affiliation === 'university') {
                const university = $('#university').val();
                const faculty = $('#faculty').val();
                
                if (!university || university === '') {
                    showError('university', 'University is required when affiliation is University');
                    isValid = false;
                }
                
                if (!faculty || faculty === '') {
                    showError('faculty', 'Faculty is required when affiliation is University');
                    isValid = false;
                }
            } else if (affiliation === 'others') {
                const institution_name = $('#institution_name').val().trim();
                if (institution_name === '') {
                    showError('institution_name', 'institution_name is required when affiliation is Others');
                    isValid = false;
                }
            }
            
            return isValid;
        }
        
        // Handle affiliation radio button changes
        function handleAffiliationChange() {
            clearErrors();
            const selectedAffiliation = $('input[name="affiliation"]:checked').val();
            
            if (selectedAffiliation === 'university') {
                // Show university section, hide institution_name
                $('#institution_nameSection').hide();
                
                // Enable university and faculty dropdowns
                $('#university').prop('disabled', false);
                $('#faculty').prop('disabled', false);
                $('#institution_name').prop('disabled', true).val('');
                
            } else if (selectedAffiliation === 'others') {
                // Show institution_name section
                $('#institution_nameSection').show();
                
                // Disable university and faculty dropdowns
                $('#university').prop('disabled', true).val('');
                $('#faculty').prop('disabled', true).val('');
                $('#institution_name').prop('disabled', false);
            }
        }
        
        // Handle radio button change
        $('input[name="affiliation"]').on('change', handleAffiliationChange);
        
        // Real-time validation for National ID
        $('#national_id').on('input', function () {
            const nationalId = $(this).val().trim();

            if (nationalId.length > 0 && !/^\d+$/.test(nationalId)) {
                showError('national_id', 'National ID must contain only digits');
            } 
            else if (nationalId.length > 14) {
                $(this).val(nationalId.substring(0, 14));
            } 
            else if (nationalId.length > 0 && nationalId.length < 14) {
                showError('national_id', `National ID must be 14 digits (${nationalId.length}/14)`);
            } 
            else if (nationalId.length === 14) {
                $('#national_id-error').remove(); // remove JS error
                $(this).removeClass('is-invalid');
            }
        });

        
        // Real-time validation for Email
        $('#email').on('blur', function() {
            const email = $(this).val().trim();
            const errorElement = $('#email-error');
            
            // Clear existing errors
            errorElement.remove();
            $(this).removeClass('is-invalid');
            
            if (email.length > 0 && !isValidEmail(email)) {
                showError('email', 'Email format is not correct');
            }
        });
        
        // Real-time validation for Password Match
        $('#password-confirm').on('input', function() {
            const password = $('#password').val();
            const passwordConfirm = $(this).val();
            const errorElement = $('#password-confirm-error');
            
            // Clear existing errors
            errorElement.remove();
            $(this).removeClass('is-invalid');
            
            if (passwordConfirm.length > 0 && password !== passwordConfirm) {
                showError('password-confirm', 'Password does not match confirmation password');
            }
        });
        
        // Form submission with validation
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                $('#loadingOverlay').css('display', 'flex');
                this.submit();
            } else {
                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        });

        // Add focus animations
        $('.lab-form-control').on('focus', function() {
            $(this).parent().find('.input-icon').css('color', '#2e7d32');
        });

        $('.lab-form-control').on('blur', function() {
            $(this).parent().find('.input-icon').css('color', '#4caf50');
        });

        // Password strength indicator
        $('#password').on('input', function() {
            var password = $(this).val();
            var strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            var strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            var strengthColors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745'];
        });
    });
</script>