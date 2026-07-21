@extends('layouts.app')

@section('title', 'My Courses - Nano Spark LMS')

@section('content')
<div style="max-width:1400px" x-data="courseManager()">
    <div class="ns-page-header animate-fadeIn">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="ns-page-title">My Courses</h1>
                <p class="ns-page-subtitle">Manage and organize your courses</p>
            </div>
            <a href="{{ route('teacher.courses.create') }}" class="ns-btn ns-btn-primary">
                <i class="bi bi-plus-lg"></i> New Course
            </a>
        </div>
    </div>

    <div class="ns-card mb-4">
        <div class="ns-card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5 col-lg-4">
                    <label class="ns-form-label">Search Courses</label>
                    <div class="ns-input-icon">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="ns-input" placeholder="Search by title..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="ns-form-label">Status</label>
                    <select name="status" class="ns-select">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-4 col-lg-2 d-flex gap-2">
                    <button type="submit" class="ns-btn ns-btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('teacher.courses.index') }}" class="ns-btn ns-btn-ghost">Clear</a>
                </div>
            </form>
        </div>
    </div>

    @if($courses->count())
        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="ns-card ns-course-card h-100 animate-fadeIn" style="animation-delay:{{ $loop->index * 50 }}ms">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" style="width:100%;height:160px;object-fit:cover;border-radius:12px 12px 0 0;" alt="{{ $course->title }}">
                        @else
                            <div style="height:160px;background:linear-gradient(135deg,rgba(255,212,0,0.15),rgba(59,130,246,0.15));display:flex;align-items:center;justify-content:center;border-radius:12px 12px 0 0;">
                                <i class="bi bi-book" style="font-size:2.5rem;color:var(--accent-primary)"></i>
                            </div>
                        @endif
                        <div style="padding:20px">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 style="font-family:var(--font-heading);font-weight:700;color:var(--text-primary);margin:0;font-size:0.95rem;line-height:1.3">{{ Str::limit($course->title, 35) }}</h6>
                                @if($course->is_published)
                                    <span class="ns-badge success" style="flex-shrink:0;margin-left:8px">Published</span>
                                @else
                                    <span class="ns-badge" style="background:rgba(255,255,255,0.08);color:var(--text-muted);flex-shrink:0;margin-left:8px">Draft</span>
                                @endif
                            </div>
                            @if($course->category)
                                <span class="ns-badge" style="background:rgba(59,130,246,0.12);color:#3B82F6;margin-bottom:12px">{{ $course->category->name }}</span>
                            @endif
                            <div class="d-flex justify-content-between" style="color:var(--text-muted);font-size:0.8rem;margin-top:8px">
                                <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count ?? $course->enrollments()->count() }} students</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $course->duration_hours ?? 0 }}h</span>
                            </div>
                            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border-subtle)">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('teacher.courses.show', $course) }}" class="ns-btn ns-btn-primary ns-btn-sm flex-grow-1" style="justify-content:center">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('teacher.courses.edit', $course) }}" class="ns-btn ns-btn-ghost ns-btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('teacher.courses.show', $course) }}#lessons" class="ns-btn ns-btn-ghost ns-btn-sm" title="Lessons">
                                        <i class="bi bi-list-ul"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $courses->withQueryString()->links() }}
        </div>
    @else
        <div class="ns-card">
            <div class="text-center" style="padding:60px 20px">
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,212,0,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
                    <i class="bi bi-book" style="font-size:2.5rem;color:var(--accent-primary)"></i>
                </div>
                <h5 style="font-family:var(--font-heading);color:var(--text-primary);margin-bottom:8px">No courses found</h5>
                <p style="color:var(--text-muted);max-width:400px;margin:0 auto 24px">{{ request()->hasAny(['search', 'status']) ? 'Try adjusting your filters to find what you\'re looking for.' : 'Create your first course and start teaching students.' }}</p>
                @if(!request()->hasAny(['search', 'status']))
                    <a href="{{ route('teacher.courses.create') }}" class="ns-btn ns-btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Create Your First Course
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
.ns-course-card { transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: default; }
.ns-course-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.4); }
.ns-input-icon { position: relative; }
.ns-input-icon i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px; pointer-events: none; }
.ns-input-icon .ns-input { padding-left: 36px; }
.ns-form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
.ns-input, .ns-select {
    width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-subtle);
    background: var(--bg-elevated); color: var(--text-primary); font-family: var(--font-body);
    font-size: 14px; outline: none; transition: border-color 0.2s;
}
.ns-input:focus, .ns-select:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(255,212,0,0.1); }
.ns-select option { background: #121212; color: var(--text-primary); }
</style>
@endsection
