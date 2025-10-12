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
    /* padding: 12px 16px; */
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
                                        <label for="name" class="lab-form-label">{{ __('Full Name') }}</label>
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
                                        <label for="username" class="lab-form-label">{{ __('Username') }}</label>
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

                                <div class="form-group-enhanced">
                                    <label for="email" class="lab-form-label">{{ __('E-Mail Address') }}</label>
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

                                <div class="form-grid form-grid-2cols">
                                    <div class="form-group-enhanced">
                                        <label for="password" class="lab-form-label">{{ __('Password') }}</label>
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
                                        <label for="password-confirm" class="lab-form-label">{{ __('Confirm Password') }}</label>
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

                                <div class="form-grid form-grid-2cols">
                                    <div class="form-group-enhanced">
                                        <label for="university" class="lab-form-label">{{ __('University') }}</label>
                                        <div class="position-relative">
                                            <i class='bx bx-building-house input-icon'></i>
                                            <label hidden>{{$unis = \App\Models\universitys::all()}}</label>
                                            <select id="university" 
                                                    class="form-control lab-form-control lab-select with-icon @error('university') is-invalid @enderror" 
                                                    name="university">
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
                                        </div>
                                    </div>

                                    <div class="form-group-enhanced">
                                        <label for="faculty" class="lab-form-label">{{ __('Faculty') }}</label>
                                        <div class="position-relative">
                                            <i class='bx bx-book-open input-icon'></i>
                                            <label hidden>{{$faculty = \App\Models\fac_uni::all()}}</label>
                                            <select id="faculty" 
                                                    class="form-control lab-form-control lab-select with-icon @error('faculty') is-invalid @enderror" 
                                                    name="faculty">
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
    // Enhanced form submission with loading animation
    $(document).ready(function() {
        $('#registerForm').on('submit', function() {
            $('#loadingOverlay').css('display', 'flex');
        });

        // Add focus animations
        $('.lab-form-control').on('focus', function() {
            $(this).parent().find('.input-icon').css('color', '#2e7d32');
        });

        $('.lab-form-control').on('blur', function() {
            $(this).parent().find('.input-icon').css('color', '#4caf50');
        });

        // Password strength indicator (optional enhancement)
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
            
            // You can add a strength indicator here if needed
        });
    });
</script>