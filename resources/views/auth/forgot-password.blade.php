@extends('layouts.guest')

@section('title', 'Forgot Password - Nano Spark LMS')

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
    }

    .ns-form-control::placeholder { color: #4B5563; }
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
            <i class="bi bi-key"></i>
        </div>

        <h2>Forgot your password?</h2>
        <p class="ns-subtitle">No worries. Enter your email address and we'll send you a link to reset your password.</p>

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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="ns-form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="ns-form-control" placeholder="you@example.com">
            </div>

            <button type="submit" class="ns-btn-gradient">
                <i class="bi bi-send"></i> Send Reset Link
            </button>
        </form>

        <div class="ns-auth-back">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
