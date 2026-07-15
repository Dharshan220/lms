@extends('layouts.app')

@section('title', 'Edit Profile - Nano Spark LMS')

@section('content')
<style>
    .ns-profile-page {
        max-width: 700px;
        margin: 0 auto;
    }

    .ns-profile-header {
        margin-bottom: 32px;
    }

    .ns-profile-header h2 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-primary, #F9FAFB);
        margin-bottom: 4px;
    }

    .ns-profile-header p {
        font-size: 14px;
        color: var(--text-muted, #6B7280);
    }

    .ns-card {
        background: var(--card-bg, rgba(255,255,255,0.03));
        border: 1px solid var(--border-color, rgba(255,255,255,0.06));
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
    }

    .ns-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary, #F3F4F6);
        margin-bottom: 4px;
    }

    .ns-card .ns-card-desc {
        font-size: 13px;
        color: var(--text-muted, #6B7280);
        margin-bottom: 24px;
    }

    .ns-form-group {
        margin-bottom: 18px;
    }

    .ns-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary, #D1D5DB);
        margin-bottom: 6px;
    }

    .ns-form-control {
        width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color, rgba(255,255,255,0.1));
        background: var(--input-bg, rgba(255,255,255,0.04));
        color: var(--text-primary, #F3F4F6);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .ns-form-control::placeholder { color: #4B5563; }
    .ns-form-control:focus { border-color: #FF6B35; }

    .ns-form-control[readonly] {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .ns-btn-gradient {
        padding: 11px 24px;
        font-size: 14px;
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

    .ns-btn-danger {
        padding: 11px 24px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #EF4444, #DC2626);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
        transition: all 0.3s;
    }

    .ns-btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(239, 68, 68, 0.45);
    }

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

    .ns-success-msg {
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(52, 211, 153, 0.1);
        border: 1px solid rgba(52, 211, 153, 0.25);
        color: #6EE7B7;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .ns-danger-zone {
        border-color: rgba(239, 68, 68, 0.25);
    }

    .ns-danger-zone h3 {
        color: #EF4444;
    }

    .ns-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 640px) {
        .ns-form-row { grid-template-columns: 1fr; }
        .ns-card { padding: 24px; }
    }
</style>

<div class="ns-profile-page">
    <div class="ns-profile-header">
        <h2>Profile Settings</h2>
        <p>Manage your account information and preferences.</p>
    </div>

    @if ($errors->any())
        <div class="ns-error-msg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="ns-success-msg">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Profile Information --}}
    <div class="ns-card">
        <h3>Profile Information</h3>
        <p class="ns-card-desc">Update your name and email address.</p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="ns-form-group">
                <label for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="ns-form-control" placeholder="Your full name">
            </div>

            <div class="ns-form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="ns-form-control" placeholder="you@example.com">
            </div>

            <button type="submit" class="ns-btn-gradient">Save Changes</button>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="ns-card">
        <h3>Update Password</h3>
        <p class="ns-card-desc">Ensure your account is using a long, random password for security.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="ns-form-group">
                <label for="current_password">Current Password</label>
                <input id="current_password" type="password" name="current_password" required autocomplete="current-password" class="ns-form-control" placeholder="Enter current password">
            </div>

            <div class="ns-form-row">
                <div class="ns-form-group">
                    <label for="password">New Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="ns-form-control" placeholder="Enter new password">
                </div>

                <div class="ns-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="ns-form-control" placeholder="Confirm new password">
                </div>
            </div>

            <button type="submit" class="ns-btn-gradient">Update Password</button>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="ns-card ns-danger-zone">
        <h3>Delete Account</h3>
        <p class="ns-card-desc">Permanently delete your account and all associated data. This action cannot be undone.</p>

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action is permanent and cannot be undone.')">
            @csrf
            @method('DELETE')

            <div class="ns-form-group">
                <label for="password_delete">Password</label>
                <input id="password_delete" type="password" name="password" required autocomplete="current-password" class="ns-form-control" placeholder="Enter your password to confirm">
            </div>

            <button type="submit" class="ns-btn-danger">
                <i class="bi bi-trash3"></i> Delete Account
            </button>
        </form>
    </div>
</div>
@endsection
