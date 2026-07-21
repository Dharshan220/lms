@extends('layouts.app')

@section('title', 'Profile - Nano Spark LMS')

@section('content')
<div style="max-width:900px">
    <div class="ns-page-header animate-fadeIn">
        <h1 class="ns-page-title">My Profile</h1>
        <p class="ns-page-subtitle">Manage your account settings and preferences.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="ns-card text-center">
                <div style="width:100px;height:100px;border-radius:50%;margin:0 auto 16px;background:linear-gradient(135deg,var(--accent-primary),#FF9800);display:flex;align-items:center;justify-content:center;box-shadow:0 0 20px rgba(255,212,0,0.2)">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width:100px;height:100px;border-radius:50%;object-fit:cover">
                    @else
                        <span style="font-size:36px;font-weight:700;color:#050505;font-family:var(--font-heading)">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    @endif
                </div>
                <h3 style="font-family:var(--font-heading);font-size:20px;font-weight:700;color:var(--text-primary)">{{ Auth::user()->name }}</h3>
                <p style="color:var(--text-muted);font-size:14px">{{ Auth::user()->email }}</p>
                <span class="ns-badge primary" style="margin-top:8px">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'student')) }}</span>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="ns-card">
                <div class="ns-card-header">
                    <h5 class="ns-card-title">Edit Profile</h5>
                </div>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="ns-form-group">
                        <label class="ns-form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="ns-form-input" required>
                    </div>
                    <div class="ns-form-group">
                        <label class="ns-form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="ns-form-input" required>
                    </div>
                    <div class="ns-form-group">
                        <label class="ns-form-label">Bio</label>
                        <textarea name="bio" class="ns-form-input" rows="3" placeholder="Tell us about yourself...">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                    </div>
                    <button type="submit" class="ns-btn ns-btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
