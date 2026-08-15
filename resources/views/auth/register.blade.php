@extends('layouts.guest')

@section('title', 'Register - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-auth-wrapper { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; position: relative; z-index: 10; }
    .ns-auth-left { display: flex; align-items: center; justify-content: center; padding: 40px; }
    .ns-auth-card { width: 100%; max-width: 420px; }
    .ns-auth-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; margin-bottom: 40px; }
    .ns-auth-logo .ns-logo-icon {
        width: 44px; height: 44px; border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 0 16px rgba(255, 193, 7, 0.25);
    }
    .ns-auth-logo span { font-family: 'Baloo 2', 'Inter', sans-serif; font-size: 24px; font-weight: 700; color: #111111; letter-spacing: -0.3px; }
    .ns-auth-card h2 { font-family: 'Baloo 2', 'Inter', sans-serif; font-size: 30px; font-weight: 700; color: #111111; margin-bottom: 8px; letter-spacing: -0.5px; }
    .ns-auth-card .ns-subtitle { font-size: 15px; color: #9CA3AF; margin-bottom: 32px; }
    .ns-form-group { margin-bottom: 20px; }
    .ns-form-group label { display: block; font-size: 13px; font-weight: 600; color: #4B5563; margin-bottom: 6px; }
    .ns-form-control {
        width: 100%; padding: 12px 16px; border-radius: 12px;
        border: 1px solid #D0D5DD;
        background: #FFFFFF; color: #111111;
        font-size: 15px; outline: none; transition: all 0.2s;
        font-family: 'Inter', 'Baloo 2', sans-serif;
    }
    .ns-form-control::placeholder { color: #98A2B3; }
    .ns-form-control:focus { border-color: #FFC107; box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15); }
    .ns-form-select {
        width: 100%; padding: 12px 16px; border-radius: 12px;
        border: 1px solid #D0D5DD;
        background: #FFFFFF; color: #111111;
        font-size: 15px; outline: none; transition: all 0.2s;
        font-family: 'Inter', 'Baloo 2', sans-serif; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667085' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .ns-form-select option { background: #FFFFFF; color: #111111; }
    .ns-btn-gradient {
        width: 100%; padding: 14px 24px; font-size: 15px; font-weight: 700;
        font-family: 'Inter', 'Baloo 2', sans-serif;
        color: #111111; background: linear-gradient(135deg, #F7B500, #FFD54F);
        border: none; border-radius: 12px; cursor: pointer;
        box-shadow: 0 4px 16px rgba(255, 193, 7, 0.3); transition: all 0.3s;
    }
    .ns-btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(255, 193, 7, 0.4); }
    .ns-auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: #9CA3AF; }
    .ns-auth-footer a { color: #F7B500; text-decoration: none; font-weight: 600; }
    .ns-auth-footer a:hover { text-decoration: underline; }
    .ns-error-msg {
        padding: 12px 16px; border-radius: 12px;
        background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.25);
        color: #DC2626; font-size: 13px; margin-bottom: 20px;
    }
    .ns-error-msg ul { margin: 0; padding-left: 16px; }
    .ns-auth-right {
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.06), rgba(255, 193, 7, 0.04));
        border-left: 1px solid #EDEDEA;
        padding: 60px; position: relative; overflow: hidden;
    }
    .ns-auth-right::before {
        content: ''; position: absolute; top: -150px; right: -150px;
        width: 500px; height: 500px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 193, 7, 0.1), transparent 60%);
        animation: float 6s ease-in-out infinite;
    }
    .ns-auth-right::after {
        content: ''; position: absolute; bottom: -150px; left: -150px;
        width: 450px; height: 450px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 193, 7, 0.08), transparent 60%);
        animation: float 8s ease-in-out infinite reverse;
    }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
    .ns-auth-right-content { text-align: center; position: relative; z-index: 2; }
    .ns-auth-right-content .auth-illustration {
        width: 280px; height: 280px; margin: 0 auto 24px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
        border: 1px solid rgba(255, 193, 7, 0.2);
        display: flex; align-items: center; justify-content: center;
    }
    .ns-auth-right-content .auth-illustration i {
        font-size: 100px;
        background: linear-gradient(135deg, #F7B500, #FFD54F);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ns-auth-right-content h3 { font-family: 'Baloo 2', 'Inter', sans-serif; font-size: 26px; font-weight: 700; color: #111111; margin-bottom: 12px; letter-spacing: -0.3px; }
    .ns-auth-right-content p { font-size: 15px; color: #9CA3AF; max-width: 300px; margin: 0 auto; line-height: 1.7; }
    .ns-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 768px) {
        .ns-auth-wrapper { grid-template-columns: 1fr; }
        .ns-auth-right { display: none; }
        .ns-auth-left { padding: 24px; }
        .ns-form-row { grid-template-columns: 1fr; }
    }
</style>

<div class="ns-auth-wrapper">
    <div class="ns-auth-left">
        <div class="ns-auth-card" style="background: #FFFFFF; border: 1px solid #EDEDEA; border-radius: 20px; padding: 40px; box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);">
            <a href="{{ url('/') }}" class="ns-auth-logo">
                <img src="{{ asset('images/nano-spark-logo.jpg') }}" alt="Nano Spark" class="ns-logo-icon">
                <span>Nano Spark</span>
            </a>
            <h2>Create your account</h2>
            <p class="ns-subtitle">Start your journey in IoT, Robotics & AI today.</p>

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
                    <label for="role">I am a</label>
                    <select id="role" name="role" class="ns-form-select" required>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>Parent</option>
                    </select>
                </div>
                <div class="ns-form-row">
                    <div class="ns-form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" class="ns-form-control" placeholder="Min 8 characters">
                    </div>
                    <div class="ns-form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="ns-form-control" placeholder="Confirm password">
                    </div>
                </div>
                <button type="submit" class="ns-btn-gradient" style="margin-top: 8px;">
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
            <div class="auth-illustration">
                <i class="bi bi-robot"></i>
            </div>
            <h3>Join the Future of Learning</h3>
            <p>Access 50+ courses in IoT, Robotics, AI, and Embedded Systems. Build real projects with STEM kits.</p>
        </div>
    </div>
</div>
@endsection
