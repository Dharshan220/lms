@extends('layouts.guest')

@section('title', 'Verify OTP - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 40px 24px;
        position: relative;
        z-index: 10;
    }

    .ns-auth-card {
        width: 100%;
        max-width: 420px;
        text-align: center;
    }

    .ns-auth-logo {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        margin-bottom: 36px;
    }

    .ns-auth-logo .ns-logo-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
    }

    .ns-auth-logo span {
        font-size: 24px;
        font-weight: 800;
        background: linear-gradient(135deg, #FF8F65, #4ECDC4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .ns-auth-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(255, 107, 53, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }

    .ns-auth-icon i {
        font-size: 32px;
        color: #FF6B35;
    }

    .ns-auth-card h2 {
        font-size: 26px;
        font-weight: 800;
        color: #F9FAFB;
        margin-bottom: 12px;
    }

    .ns-auth-card .ns-subtitle {
        font-size: 15px;
        color: #6B7280;
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .ns-form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .ns-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #D1D5DB;
        margin-bottom: 6px;
    }

    .ns-form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: #F3F4F6;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
        letter-spacing: 8px;
        text-align: center;
        font-weight: 700;
        font-size: 24px;
    }

    .ns-form-control::placeholder { color: #4B5563; letter-spacing: normal; font-weight: 400; font-size: 15px; }
    .ns-form-control:focus { border-color: #FF6B35; }

    .ns-btn-gradient {
        width: 100%;
        padding: 13px 24px;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #FF6B35, #E55A24);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(255, 107, 53, 0.3);
        transition: all 0.3s;
    }

    .ns-btn-gradient:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(255, 107, 53, 0.45);
    }

    .ns-auth-back {
        text-align: center;
        margin-top: 24px;
    }

    .ns-auth-back a {
        font-size: 14px;
        color: #9CA3AF;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }

    .ns-auth-back a:hover { color: #FF6B35; }

    .ns-otp-inputs {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 24px;
    }

    .ns-otp-inputs input {
        width: 52px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: #F3F4F6;
        outline: none;
        transition: border-color 0.2s;
    }

    .ns-otp-inputs input:focus { border-color: #FF6B35; }

    .ns-error-msg {
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #FCA5A5;
        font-size: 13px;
        margin-bottom: 20px;
        text-align: left;
    }

    .ns-error-msg ul { margin: 0; padding-left: 16px; }

    .ns-success-msg {
        padding: 12px 16px;
        border-radius: 8px;
        background: rgba(52, 211, 153, 0.1);
        border: 1px solid rgba(52, 211, 153, 0.25);
        color: #6EE7B7;
        font-size: 14px;
        margin-bottom: 20px;
        text-align: left;
    }
</style>

<div class="ns-auth-wrapper">
    <div class="ns-auth-card">
        <a href="{{ url('/') }}" class="ns-auth-logo">
            <img src="{{ asset('images/nano-spark-logo.jpg') }}" alt="Nano Spark" class="ns-logo-icon">
            <span>Nano Spark</span>
        </a>

        <div class="ns-auth-icon">
            <i class="bi bi-shield-check"></i>
        </div>

        <h2>Verify your code</h2>
        <p class="ns-subtitle">Enter the 6-digit verification code we sent to <strong>{{ request('email') }}</strong></p>

        @if ($errors->any())
            <div class="ns-error-msg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="ns-success-msg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify-otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="ns-otp-inputs" id="otpContainer">
                <input type="text" name="otp_1" maxlength="1" inputmode="numeric" pattern="[0-9]" autofocus required>
                <input type="text" name="otp_2" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                <input type="text" name="otp_3" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                <input type="text" name="otp_4" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                <input type="text" name="otp_5" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
                <input type="text" name="otp_6" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
            </div>

            <button type="submit" class="ns-btn-gradient">
                <i class="bi bi-check-circle"></i> Verify Code
            </button>
        </form>

        <div class="ns-auth-back" style="margin-top: 16px;">
            <form method="POST" action="{{ route('password.request') }}" style="display:inline">
                @csrf
                <input type="hidden" name="email" value="{{ request('email') }}">
                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:14px;color:#9CA3AF;text-decoration:none;">
                    <i class="bi bi-arrow-counterclockwise"></i> Resend Code
                </button>
            </form>
        </div>

        <div class="ns-auth-back">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('#otpContainer input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
                if (e.key === 'Enter') {
                    let otp = '';
                    inputs.forEach(inp => otp += inp.value);
                    if (otp.length === 6) {
                        document.querySelector('#otpContainer').closest('form').submit();
                    }
                }
            });
        });
    });
</script>
@endsection
