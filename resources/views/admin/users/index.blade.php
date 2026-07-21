@extends('layouts.app')

@section('title', 'User Management - Nano Spark LMS')

@section('content')
<div style="max-width:1400px">
    <div class="ns-page-header animate-fadeIn">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="ns-page-title">User Management</h1>
                <p class="ns-page-subtitle">Manage all users, roles and permissions</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="ns-btn ns-btn-primary">
                <i class="bi bi-person-plus"></i> Add User
            </a>
        </div>
    </div>

    <div class="ns-card mb-4">
        <div class="ns-card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label class="ns-form-label">Search</label>
                    <div class="ns-input-icon">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="ns-input" placeholder="Name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="ns-form-label">Role</label>
                    <select name="role" class="ns-select">
                        <option value="">All Roles</option>
                        @foreach(['admin', 'teacher', 'student'] as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="ns-form-label">Status</label>
                    <select name="status" class="ns-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="ns-btn ns-btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('admin.users.index') }}" class="ns-btn ns-btn-ghost">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="ns-card">
        <div class="ns-table-wrapper">
            <table class="ns-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width:40px;height:40px;border-radius:10px;object-fit:cover;border:2px solid var(--border-subtle)">
                                    @else
                                        <div class="ns-user-avatar" style="width:40px;height:40px;font-size:14px">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary)">{{ $user->name }}</div>
                                        <small style="color:var(--text-muted)">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span style="color:var(--text-secondary);font-size:13px">{{ $user->email }}</span></td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="ns-badge" style="background:rgba(255,77,79,0.12);color:#FF4D4F"><i class="bi bi-shield-check me-1"></i>Admin</span>
                                @elseif($user->role == 'teacher')
                                    <span class="ns-badge" style="background:rgba(59,130,246,0.12);color:#3B82F6"><i class="bi bi-person-video3 me-1"></i>Teacher</span>
                                @else
                                    <span class="ns-badge" style="background:rgba(255,212,0,0.12);color:#FFD400"><i class="bi bi-mortarboard me-1"></i>Student</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active ?? true)
                                    <span class="ns-badge success"><i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Active</span>
                                @else
                                    <span class="ns-badge" style="background:rgba(255,255,255,0.06);color:var(--text-muted)"><i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Inactive</span>
                                @endif
                            </td>
                            <td>
                                <span style="color:var(--text-muted);font-size:13px">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</span>
                            </td>
                            <td style="text-align:right">
                                <div class="d-flex gap-1" style="justify-content:flex-end">
                                    <a href="{{ route('admin.users.show', $user) }}" class="ns-btn ns-btn-ghost ns-btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="ns-btn ns-btn-ghost ns-btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="ns-btn ns-btn-ghost ns-btn-sm" title="Delete"
                                            style="color:var(--danger)"
                                            onclick="event.preventDefault();document.getElementById('delete-form-{{ $user->id }}').submit();">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:none"
                                          onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding:60px 20px">
                                <div style="width:72px;height:72px;border-radius:50%;background:rgba(59,130,246,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                    <i class="bi bi-people" style="font-size:2rem;color:#3B82F6"></i>
                                </div>
                                <h6 style="font-family:var(--font-heading);color:var(--text-primary);margin-bottom:6px">No users found</h6>
                                <p style="color:var(--text-muted);font-size:13px">{{ request()->hasAny(['search', 'role', 'status']) ? 'Try adjusting your filters.' : 'No users registered yet.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($users) && $users->hasPages())
            <div class="ns-card-footer">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.ns-form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
.ns-input, .ns-select {
    width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-subtle);
    background: var(--bg-elevated); color: var(--text-primary); font-family: var(--font-body);
    font-size: 14px; outline: none; transition: border-color 0.2s;
}
.ns-input:focus, .ns-select:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(255,212,0,0.1); }
.ns-input::placeholder { color: var(--text-muted); opacity: 0.6; }
.ns-select option { background: #121212; color: var(--text-primary); }
.ns-input-icon { position: relative; }
.ns-input-icon i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px; pointer-events: none; }
.ns-input-icon .ns-input { padding-left: 36px; }
.ns-table { width: 100%; border-collapse: collapse; }
.ns-table thead th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); padding: 14px 16px; border-bottom: 1px solid var(--border-subtle); }
.ns-table tbody td { padding: 14px 16px; border-bottom: 1px solid var(--border-subtle); color: var(--text-primary); vertical-align: middle; }
.ns-table tbody tr:hover { background: rgba(255,255,255,0.02); }
.ns-table tbody tr:last-child td { border-bottom: none; }
.ns-card-footer { padding: 16px 24px; border-top: 1px solid var(--border-subtle); }
</style>
@endsection
