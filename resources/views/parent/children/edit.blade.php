@extends('layouts.app')

@section('title', 'Edit Child - Nano Spark LMS')

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

    .pc-btn-danger {
        background: rgba(255, 77, 79, 0.1);
        color: #ff6b6b;
        border: 1px solid rgba(255, 77, 79, 0.3);
    }

    .pc-btn-danger:hover {
        background: rgba(255, 77, 79, 0.18);
        color: #ff8080;
        border-color: rgba(255, 77, 79, 0.5);
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

    .pc-remove {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-subtle);
    }

    .pc-remove p {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
</style>
@endpush

<div class="pc-wrap">
    <div class="pc-header">
        <div>
            <h1><i class="bi bi-person-gear"></i> Edit Child</h1>
            <p>Update the details for <strong>{{ $child->name }}</strong>.</p>
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
        <form action="{{ route('parent.children.update', $child->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label for="name" class="form-label">Full Name <span style="color:#ff4d4f;">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $child->name) }}" required>
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Email Address <span style="color:#ff4d4f;">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $child->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                </div>

                <div class="col-md-6">
                    <label for="grade" class="form-label">Grade / Class</label>
                    <input type="text" id="grade" name="grade" class="form-control" value="{{ old('grade', $child->grade) }}" placeholder="e.g. Grade 7">
                </div>

                <div class="col-md-6">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $child->date_of_birth?->format('Y-m-d')) }}">
                </div>

                <div class="col-12">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $child->phone) }}" placeholder="Optional contact number">
                </div>
            </div>

            <div class="pc-note">
                <i class="bi bi-shield-check"></i>
                <span>This child is linked to your account. Changes apply immediately to {{ $child->name }}'s account.</span>
            </div>

            <div class="pc-actions">
                <a href="{{ route('parent.dashboard') }}" class="btn pc-btn-ghost">Cancel</a>
                <button type="submit" class="btn pc-btn-accent">
                    <i class="bi bi-check-lg me-1"></i> Save Changes
                </button>
            </div>
        </form>

        <div class="pc-remove">
            <p><i class="bi bi-exclamation-triangle me-1"></i> Removing a child unlinks them from your account. Their learning data is not deleted.</p>
            <form action="{{ route('parent.children.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($child->name) }} from your account?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn pc-btn-danger w-100">
                    <i class="bi bi-person-dash me-1"></i> Remove {{ $child->name }} from My Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection