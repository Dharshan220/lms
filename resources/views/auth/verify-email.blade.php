@extends('layouts.guest')

@section('title', 'Verify Email - Nano Spark LMS')

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
        background: rgba(251, 191, 36, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }

    .ns-auth-icon i {
        font-size: 32px;
        color: #FBBF24;
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
        line-height: 1.7;
    }

    .ns-success-msg {
        padding: 12px 16px;
        border-radius: 8px;
        background: rgba(52, 211, 153, 0.1);
        border: 1px solid rgba(52, 211, 153, 0.25);
        color: #6EE7B7;
        font-size: 14px;
        margin-bottom: 24px;
        text-align: left;
    }

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

    .ns-auth-divider {
        display: flex;
        align-items: center;
        margin: 20px 0;
        color: #4B5563;
        font-size: 13px;
    }

    .ns-auth-divider::before,
    .ns-auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
    }

    .ns-auth-divider span {
        padding: 0 16px;
    }

    .ns-auth-back {
        text-align: center;
        margin-top: 4px;
    }

    .ns-auth-back button {
        font-size: 14px;
        color: #9CA3AF;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
        padding: 8px 16px;
    }

    .ns-auth-back button:hover { color: #FF6B35; }
</style>

<div class="ns-auth-wrapper">
    <div class="ns-auth-card">
        <a href="{{ url('/') }}" class="ns-auth-logo">
            <img src="{{ asset('images/nano-spark-logo.jpg') }}" alt="Nano Spark" class="ns-logo-icon">
            <span>Nano Spark</span>
        </a>

        <div class="ns-auth-icon">
            <i class="bi bi-envelope-check"></i>
        </div>

        <h2>Verify your email</h2>
        <p class="ns-subtitle">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>

        @if (session('status') == 'verification-link-sent')
            <div class="ns-success-msg">
                <i class="bi bi-check-circle-fill"></i>
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="ns-btn-gradient">
                <i class="bi bi-send"></i> Resend Verification Email
            </button>
        </form>

        <div class="ns-auth-divider"><span>or</span></div>

        <div class="ns-auth-back">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i> Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
