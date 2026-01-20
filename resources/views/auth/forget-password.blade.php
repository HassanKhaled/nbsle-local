@extends('templ.head')
@section('tmplt-contnt')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 60px 15px 40px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                <div class="card shadow border-0" style="border-radius: 12px;">
                    <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0 fw-semibold">Forgot Password</h5>
                    </div>

                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('sendResetPassword') }}" id="forgotPasswordForm">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Email Address : </label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    placeholder="Enter your email"
                                    style="padding: 0.7rem 0.85rem;"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn" style="padding: 0.7rem;">
                                    Send Password
                                </button>
                            </div>

                            <div id="countdown" class="text-center text-danger" style="display:none;">
                                <strong>Wait <span id="timer">3:00</span> to retry</strong>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                ← Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const countdownDiv = document.getElementById('countdown');
    const timerSpan = document.getElementById('timer');

    const endTime = localStorage.getItem('passwordResetEndTime');
    if (endTime && new Date().getTime() < endTime) {
        startCountdown(endTime);
    }

    form.addEventListener('submit', function(e) {
        const now = new Date().getTime();
        const storedEndTime = localStorage.getItem('passwordResetEndTime');
        
        if (storedEndTime && now < storedEndTime) {
            e.preventDefault();
            alert('Please wait before requesting again.');
            return false;
        }
    });

    @if(session('success'))
        const newEndTime = new Date().getTime() + (3 * 60 * 1000);
        localStorage.setItem('passwordResetEndTime', newEndTime);
        startCountdown(newEndTime);
    @endif

    function startCountdown(endTime) {
        submitBtn.disabled = true;
        countdownDiv.style.display = 'block';

        const interval = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(interval);
                submitBtn.disabled = false;
                countdownDiv.style.display = 'none';
                localStorage.removeItem('passwordResetEndTime');
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            timerSpan.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        }, 1000);
    }
});
</script>
@endsection