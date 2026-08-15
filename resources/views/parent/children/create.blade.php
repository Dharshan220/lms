@extends('layouts.app')

@section('title', 'Add Child - Nano Spark LMS')

@section('content')
@push('styles')
<style>
    .pc-wrap {
        max-width: 640px;
        margin: 0 auto;
        padding: 28px 0 48px;
    }

    .pc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .pc-header h1 {
        font-family: 'Baloo 2', 'Inter', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pc-header h1 i { color: var(--ns-accent); }

    .pc-header p {
        color: var(--text-secondary);
        font-size: 13px;
        margin: 6px 0 0;
    }

    .pc-card {
        background: var(--card-bg, #121212);
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        padding: 28px;
    }

    .pc-card-title {
        font-family: 'Baloo 2', 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pc-card-title i { color: var(--ns-accent); }

    .pc-btn-accent {
        background: var(--ns-accent);
        color: #111111;
        border: none;
        font-weight: 600;
    }

    .pc-btn-accent:hover {
        background: #F7B500;
        color: #111111;
        box-shadow: 0 4px 16px rgba(255, 193, 7, 0.25);
    }

    .pc-btn-ghost {
        background: var(--border-subtle);
        color: var(--text-secondary);
        border: 1px solid var(--border-strong);
    }

    .pc-btn-ghost:hover {
        background: var(--elevated-bg);
        color: var(--text-primary);
    }

    .pc-note {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        font-size: 12px;
        color: var(--text-muted);
        background: rgba(255, 193, 7, 0.06);
        border: 1px dashed rgba(255, 193, 7, 0.25);
        border-radius: 10px;
        padding: 12px 14px;
        margin-top: 20px;
    }

    .pc-note i { color: var(--ns-accent); margin-top: 1px; }

    .pc-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .pc-actions .btn { flex: 1; }
</style>
@endpush

<div class="pc-wrap">
    <div class="pc-header">
        <div>
            <h1><i class="bi bi-person-plus-fill"></i> Add Child</h1>
            <p>Create a learning account for your child and link it to your profile.</p>
        </div>
        <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4" style="font-size:13px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pc-card">
        <div class="pc-card-title"><i class="bi bi-person-vcard"></i> Child Details</div>
        <form action="{{ route('parent.children.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label for="name" class="form-label">Full Name <span style="color:#ff4d4f;">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Aarav Sharma" required>
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Email Address <span style="color:#ff4d4f;">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="child@example.com" required>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Password <span style="color:#ff4d4f;">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm Password <span style="color:#ff4d4f;">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>

                <div class="col-md-6">
                    <label for="grade" class="form-label">Grade / Class</label>
                    <input type="text" id="grade" name="grade" class="form-control" value="{{ old('grade') }}" placeholder="e.g. Grade 7">
                </div>

                <div class="col-md-6">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                </div>

                <div class="col-12">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Optional contact number">
                </div>
            </div>

            <div class="pc-note">
                <i class="bi bi-shield-check"></i>
                <span>Your child will be linked to <strong>{{ $parent->name }}</strong> automatically. They will be able to log in with the email and password you set above.</span>
            </div>

            <div class="pc-actions">
                <a href="{{ route('parent.dashboard') }}" class="btn pc-btn-ghost">Cancel</a>
                <button type="submit" class="btn pc-btn-accent">
                    <i class="bi bi-check-lg me-1"></i> Add Child
                </button>
            </div>
        </form>
    </div>
</div>
@endsection