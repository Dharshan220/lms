@extends('layouts.guest')

@section('title', 'Register - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-auth-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 100vh;
        position: relative;
        z-index: 10;
    }

    .ns-auth-left {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .ns-auth-card {
        width: 100%;
        max-width: 420px;
    }

    .ns-auth-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        margin-bottom: 36px;
    }

    .ns-auth-logo .ns-logo-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #FF6B35, #9B59B6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 16px;
    }

    .ns-auth-logo span {
        font-size: 24px;
        font-weight: 800;
        background: linear-gradient(135deg, #FF8F65, #4ECDC4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .ns-auth-card h2 {
        font-size: 28px;
        font-weight: 800;
        color: #F9FAFB;
        margin-bottom: 8px;
    }

    .ns-auth-card .ns-subtitle {
        font-size: 15px;
        color: #6B7280;
        margin-bottom: 28px;
    }

    .ns-form-group {
        margin-bottom: 18px;
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

    .ns-form-select {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: #F3F4F6;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
    }

    .ns-form-select option {
        background: #1A1D29;
        color: #F3F4F6;
    }

    .ns-form-select:focus { border-color: #FF6B35; }

    .ns-form-check {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 24px;
    }

    .ns-form-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        accent-color: #FF6B35;
        margin-top: 2px;
    }

    .ns-form-check label {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
        line-height: 1.5;
    }

    .ns-form-check a {
        color: #FF6B35;
        text-decoration: none;
    }

    .ns-form-check a:hover { text-decoration: underline; }

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

    .ns-auth-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 14px;
        color: #6B7280;
    }

    .ns-auth-footer a {
        color: #FF6B35;
        text-decoration: none;
        font-weight: 600;
    }

    .ns-auth-footer a:hover { text-decoration: underline; }

    .ns-error-msg {
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #FCA5A5;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .ns-error-msg ul { margin: 0; padding-left: 16px; }

    .ns-auth-right {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(78, 205, 196, 0.08), rgba(255, 107, 53, 0.08));
        border-left: 1px solid rgba(255, 255, 255, 0.05);
        padding: 60px;
        position: relative;
        overflow: hidden;
    }

    .ns-auth-right-content {
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .ns-auth-right-content i {
        font-size: 80px;
        background: linear-gradient(135deg, #4ECDC4, #FF6B35);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 24px;
        display: block;
    }

    .ns-auth-right-content h3 {
        font-size: 24px;
        font-weight: 800;
        color: #F3F4F6;
        margin-bottom: 12px;
    }

    .ns-auth-right-content p {
        font-size: 15px;
        color: #6B7280;
        max-width: 280px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .ns-auth-right::before {
        content: '';
        position: absolute;
        top: -100px;
        left: -100px;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(78, 205, 196, 0.08), transparent 60%);
    }

    .ns-auth-right::after {
        content: '';
        position: absolute;
        bottom: -100px;
        right: -100px;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 107, 53, 0.06), transparent 60%);
    }

    @media (max-width: 768px) {
        .ns-auth-wrapper { grid-template-columns: 1fr; }
        .ns-auth-right { display: none; }
        .ns-auth-left { padding: 24px; }
    }
</style>

<div class="ns-auth-wrapper">
    <div class="ns-auth-left">
        <div class="ns-auth-card">
            <a href="{{ url('/') }}" class="ns-auth-logo">
                <div class="ns-logo-icon">NS</div>
                <span>Nano Spark</span>
            </a>

            <h2>Create your account</h2>
            <p class="ns-subtitle">Join thousands of students learning IoT, Robotics & AI.</p>

            @if ($errors->any())
                <div class="ns-error-msg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="ns-form-group">
                    <label for="name">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="ns-form-control" placeholder="Enter your full name">
                </div>

                <div class="ns-form-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="ns-form-control" placeholder="you@example.com">
                </div>

                <div class="ns-form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="ns-form-control" placeholder="Create a strong password">
                </div>

                <div class="ns-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="ns-form-control" placeholder="Confirm your password">
                </div>

                <div class="ns-form-group">
                    <label for="role">I am a</label>
                    <select id="role" name="role" class="ns-form-select">
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                    </select>
                </div>

                <div class="ns-form-check">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                </div>

                <button type="submit" class="ns-btn-gradient">
                    <i class="bi bi-rocket-takeoff"></i> Create Account
                </button>
            </form>

            <div class="ns-auth-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>

    <div class="ns-auth-right">
        <div class="ns-auth-right-content">
            <i class="bi bi-robot"></i>
            <h3>Join the Innovation</h3>
            <p>Start your journey into IoT, Robotics, and AI with guided courses and hands-on projects.</p>
        </div>
    </div>
</div>
@endsection
