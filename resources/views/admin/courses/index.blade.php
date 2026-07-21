@extends('layouts.app')

@section('title', 'Course Management - Nano Spark LMS')

@section('content')
<div style="max-width:1400px">
    <div class="ns-page-header animate-fadeIn">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="ns-page-title">Course Management</h1>
                <p class="ns-page-subtitle">View and manage all courses across the platform</p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="ns-btn ns-btn-primary">
                <i class="bi bi-plus-circle"></i> Add Course
            </a>
        </div>
    </div>

    <div class="ns-card mb-4">
        <div class="ns-card-body">
            <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label class="ns-form-label">Search</label>
                    <div class="ns-input-icon">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="ns-input" placeholder="Search courses..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="ns-form-label">Teacher</label>
                    <select name="teacher_id" class="ns-select">
                        <option value="">All Teachers</option>
                        @foreach($teachers ?? [] as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="ns-form-label">Status</label>
                    <select name="status" class="ns-select">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="ns-btn ns-btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('admin.courses.index') }}" class="ns-btn ns-btn-ghost">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="ns-card">
        <div class="ns-table-wrapper">
            <table class="ns-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Students</th>
                        <th>Lessons</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses ?? [] as $course)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="" style="width:48px;height:36px;border-radius:6px;object-fit:cover;border:1px solid var(--border-subtle)">
                                    @else
                                        <div style="width:48px;height:36px;border-radius:6px;background:linear-gradient(135deg,rgba(255,212,0,0.12),rgba(59,130,246,0.12));display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                            <i class="bi bi-book" style="font-size:14px;color:var(--accent-primary)"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary);font-size:14px">{{ Str::limit($course->title, 40) }}</div>
                                        @if($course->category)
                                            <small style="color:var(--text-muted)">{{ $course->category->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="ns-user-avatar" style="width:28px;height:28px;font-size:10px">{{ strtoupper(substr($course->teacher->name ?? 'T', 0, 1)) }}</div>
                                    <span style="color:var(--text-secondary);font-size:13px">{{ $course->teacher->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-family:var(--font-heading);font-weight:600;color:var(--text-primary)">{{ $course->enrollments_count ?? $course->enrollment_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span style="color:var(--text-muted);font-size:13px">{{ $course->lessons_count ?? 0 }}</span>
                            </td>
                            <td>
                                @if($course->is_published)
                                    <span class="ns-badge success"><i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Published</span>
                                @else
                                    <span class="ns-badge" style="background:rgba(255,255,255,0.06);color:var(--text-muted)"><i class="bi bi-circle-fill me-1" style="font-size:6px"></i>Draft</span>
                                @endif
                            </td>
                            <td>
                                <span style="color:var(--text-muted);font-size:13px">{{ $course->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                            </td>
                            <td style="text-align:right">
                                <div class="d-flex gap-1" style="justify-content:flex-end">
                                    <a href="{{ route('admin.courses.show', $course) }}" class="ns-btn ns-btn-ghost ns-btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="ns-btn ns-btn-ghost ns-btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="ns-btn ns-btn-ghost ns-btn-sm" title="Delete"
                                            style="color:var(--danger)"
                                            onclick="event.preventDefault();document.getElementById('delete-course-{{ $course->id }}').submit();">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-course-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display:none"
                                          onsubmit="return confirm('Delete course &quot;{{ addslashes($course->title) }}&quot;? All related data will be removed.');">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding:60px 20px">
                                <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,212,0,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                    <i class="bi bi-book" style="font-size:2rem;color:var(--accent-primary)"></i>
                                </div>
                                <h6 style="font-family:var(--font-heading);color:var(--text-primary);margin-bottom:6px">No courses found</h6>
                                <p style="color:var(--text-muted);font-size:13px">{{ request()->hasAny(['search', 'status', 'teacher_id']) ? 'Try adjusting your filters.' : 'No courses have been created yet.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($courses) && $courses->hasPages())
            <div class="ns-card-footer">
                {{ $courses->withQueryString()->links() }}
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
