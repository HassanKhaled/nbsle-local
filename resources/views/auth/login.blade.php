@extends('templ.head')
@section('tmplt-contnt')
<style>
/* Custom CSS for Lab Login Design */
.login-section {
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
    padding: 60px 0;
}

.lab-login-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(76, 175, 80, 0.2);
    overflow: hidden;
    background: white;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.lab-login-card:hover {
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

.lab-form-label {
    font-weight: 600;
    color: #2e7d32;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.lab-btn-login {
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
    width: 100%;
}

.lab-btn-login:hover {
    background: linear-gradient(135deg, #388e3c 0%, #4caf50 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
}

.lab-btn-login::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.lab-btn-login:hover::before {
    left: 100%;
}

.password-toggle {
    color: #4caf50;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
    padding: 8px;
    background: rgba(76, 175, 80, 0.1);
    margin-left: 10px;
}

.password-toggle:hover {
    color: #2e7d32;
    background: rgba(76, 175, 80, 0.2);
    transform: scale(1.1);
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

/* Footer Links Styling */
.lab-footer-links {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #e0e0e0;
    flex-wrap: wrap;
    gap: 15px;
}

.lab-footer-link {
    font-size: 13px;
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.lab-footer-link:hover {
    color: #4caf50;
    text-decoration: none;
    transform: translateX(2px);
}

.lab-footer-link.primary {
    color: #4caf50;
    font-weight: 600;
}

.lab-footer-link.primary:hover {
    color: #2e7d32;
}

/* Loading Overlay */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #e0e0e0;
    border-top: 5px solid #4caf50;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .login-section {
        padding: 30px 15px;
    }
    
    .lab-card-body {
        padding: 30px 20px;
    }
    
    .lab-title {
        font-size: 24px;
    }
    
    .lab-footer-links {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}

@media (max-width: 576px) {
    .lab-footer-links {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<main id="main">
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    
    <section class="login login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    
                    <div class="card lab-login-card">
                        <div class="card-header lab-card-header">
                            {{ __('Login') }}
                        </div>
                        <div class="card-body lab-card-body">
                            <form method="POST" action="{{ route('login') }}" id="loginForm">
                                @csrf
                                
                                <div class="form-group-enhanced">
                                    <label for="username" class="lab-form-label">
                                        {{ __('Username') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <i class='bx bx-user input-icon'></i>
                                        <input id="username" 
                                               type="text" 
                                               class="form-control lab-form-control with-icon @error('username') is-invalid @enderror" 
                                               name="username" 
                                               value="{{ old('username') }}" 
                                               required 
                                               autocomplete="username" 
                                               autofocus
                                               placeholder="Enter your username">
                                        @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <small class="text-danger" id="username-error" style="display: none; font-weight: 600;"></small>
                                    </div>
                                </div>

                                <div class="form-group-enhanced">
                                    <label for="password" class="lab-form-label">
                                        {{ __('Password') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative flex-grow-1">
                                            <i class='bx bx-lock-alt input-icon'></i>
                                            <input id="password" 
                                                   type="password" 
                                                   class="form-control lab-form-control with-icon @error('password') is-invalid @enderror" 
                                                   name="password" 
                                                   required 
                                                   autocomplete="current-password"
                                                   placeholder="Enter your password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <small class="text-danger" id="password-error" style="display: none; font-weight: 600;"></small>
                                        </div>
                                        <i class='bx bxs-hide bx-sm password-toggle' id="togglePassword" onclick="myFunction()"></i>
                                    </div>
                                </div>

                                <div class="form-group-enhanced text-center">
                                    <button type="submit" class="btn lab-btn-login">
                                        <i class='bx bx-log-in me-2'></i>
                                        {{ __('Login') }}
                                    </button>
                                </div>

                                <!-- Footer Links -->
                                <div class="lab-footer-links">
                                        <div>
                                            <span style="
                                                font-size: clamp(0.85rem, 1.2vw, 0.95rem);
                                                color: black;
                                                font-weight: bold;
                                            ">
                                                Don't have an account?
                                            </span>

                                            <a href="{{ route('register') }}"
                                            class="lab-footer-link primary"
                                            style="font-size: clamp(0.9rem, 1.3vw, 1rem);">
                                                Create Account
                                            </a>
                                        </div>

                                        <div>
                                            <a href="{{ route('password.request') }}"
                                            class="lab-footer-link"
                                            style="
                                                font-size: clamp(0.9rem, 1.3vw, 1rem);
                                                font-weight: bold;
                                                color: black;
                                            ">
                                                Forgot Password?
                                            </a>
                                        </div>
                                    </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="//code.jquery.com/jquery.js"></script>
<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        if($('#togglePassword').hasClass('bx bxs-show')) {
            $('#togglePassword').removeClass('bx bxs-show')
            $('#togglePassword').addClass('bx bxs-hide')
        }
        else {
            $('#togglePassword').removeClass('bx bxs-hide')
            $('#togglePassword').addClass('bx bxs-show')
        }
    }

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
                field.after(`<small class="text-danger d-block mt-1" id="${fieldId}-error" style="font-weight: 600;">${message}</small>`);
            }
            field.addClass('is-invalid');
        }
        
        // Validate form
        function validateForm() {
            clearErrors();
            let isValid = true;
            
            // Validate Username
            const username = $('#username').val().trim();
            if (username === '') {
                showError('username', 'Username is required');
                isValid = false;
            }
            
            // Validate Password
            const password = $('#password').val();
            if (password === '') {
                showError('password', 'Password is required');
                isValid = false;
            }
            
            return isValid;
        }
        
        // Clear error on input
        $('#username, #password').on('input', function() {
            const fieldId = $(this).attr('id');
            $(`#${fieldId}-error`).hide();
            $(this).removeClass('is-invalid');
        });
        
        // Form submission with validation
        $('#loginForm').on('submit', function(e) {
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
    });
</script>

@endsection