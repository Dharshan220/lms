@extends('layouts.app')

@section('title', 'Teacher Dashboard - Nano Spark LMS')

@section('content')
<div style="max-width:1400px">
    <div class="ns-page-header animate-fadeIn">
        <h1 class="ns-page-title">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="ns-page-subtitle">Manage your courses and track student progress.</p>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card primary">
                <div class="ns-stat-icon primary"><i class="bi bi-book"></i></div>
                <div class="ns-stat-value">{{ $totalCourses ?? 0 }}</div>
                <div class="ns-stat-label">My Courses</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card info">
                <div class="ns-stat-icon info"><i class="bi bi-people"></i></div>
                <div class="ns-stat-value">{{ $totalStudents ?? 0 }}</div>
                <div class="ns-stat-label">Total Students</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card success">
                <div class="ns-stat-icon success"><i class="bi bi-clipboard-check"></i></div>
                <div class="ns-stat-value">{{ $pendingSubmissions ?? 0 }}</div>
                <div class="ns-stat-label">Pending Reviews</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card warning">
                <div class="ns-stat-icon warning"><i class="bi bi-camera-video"></i></div>
                <div class="ns-stat-value">{{ $upcomingClasses ?? 0 }}</div>
                <div class="ns-stat-label">Upcoming Classes</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- My Courses --}}
        <div class="col-lg-8">
            <div class="ns-card">
                <div class="ns-card-header">
                    <h5 class="ns-card-title"><i class="bi bi-book" style="color:var(--accent-primary);margin-right:8px"></i>My Courses</h5>
                    <a href="{{ route('teacher.courses.create') }}" class="ns-btn ns-btn-primary ns-btn-sm"><i class="bi bi-plus-lg"></i> New Course</a>
                </div>
                @forelse($courses ?? [] as $course)
                    <div class="d-flex align-items-center gap-3 py-3 @if(!$loop->last) border-bottom @endif" style="border-color:var(--border-subtle)">
                        <div class="flex-shrink-0">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:80px;height:55px;border-radius:8px;object-fit:cover;">
                            @else
                                <div style="width:80px;height:55px;border-radius:8px;background:rgba(255,212,0,0.1);display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-play-btn" style="color:var(--accent-primary)"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <h6 style="font-family:var(--font-heading);font-weight:600;margin-bottom:4px;color:var(--text-primary)">{{ $course->title }}</h6>
                            <small style="color:var(--text-muted)">{{ $course->enrollments_count ?? 0 }} students &middot; {{ $course->lessons_count ?? 0 }} lessons</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teacher.courses.show', $course) }}" class="ns-btn ns-btn-ghost ns-btn-sm"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('teacher.courses.edit', $course) }}" class="ns-btn ns-btn-ghost ns-btn-sm"><i class="bi bi-pencil"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-book" style="font-size:3rem;color:var(--text-muted)"></i>
                        <p style="color:var(--text-muted);margin-top:12px">No courses yet. Create your first course!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions & Recent --}}
        <div class="col-lg-4">
            <div class="ns-card mb-4">
                <div class="ns-card-header">
                    <h5 class="ns-card-title">Quick Actions</h5>
                </div>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('teacher.courses.create') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none;padding:16px">
                        <div class="ns-stat-icon primary" style="margin-bottom:0;width:40px;height:40px"><i class="bi bi-plus-circle"></i></div>
                        <span style="font-weight:600;color:var(--text-primary)">New Course</span>
                    </a>
                    <a href="{{ route('teacher.quizzes.create') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none;padding:16px">
                        <div class="ns-stat-icon info" style="margin-bottom:0;width:40px;height:40px"><i class="bi bi-question-circle"></i></div>
                        <span style="font-weight:600;color:var(--text-primary)">Create Quiz</span>
                    </a>
                    <a href="{{ route('teacher.assignments.create') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none;padding:16px">
                        <div class="ns-stat-icon success" style="margin-bottom:0;width:40px;height:40px"><i class="bi bi-clipboard-check"></i></div>
                        <span style="font-weight:600;color:var(--text-primary)">New Assignment</span>
                    </a>
                    <a href="{{ route('teacher.ai-lesson-planner.index') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none;padding:16px">
                        <div class="ns-stat-icon warning" style="margin-bottom:0;width:40px;height:40px"><i class="bi bi-magic"></i></div>
                        <span style="font-weight:600;color:var(--text-primary)">AI Lesson Planner</span>
                    </a>
                </div>
            </div>

            <div class="ns-card">
                <div class="ns-card-header">
                    <h5 class="ns-card-title"><i class="bi bi-clipboard-check" style="color:var(--warning);margin-right:8px"></i>Pending Reviews</h5>
                </div>
                @forelse($pendingAssignments ?? [] as $assignment)
                    <div class="d-flex align-items-center gap-3 py-2 @if(!$loop->last) border-bottom @endif" style="border-color:var(--border-subtle)">
                        <div class="ns-stat-icon warning" style="width:32px;height:32px;font-size:14px;margin-bottom:0;flex-shrink:0"><i class="bi bi-file-earmark"></i></div>
                        <div class="flex-grow-1">
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary)">{{ $assignment->title ?? 'Assignment' }}</div>
                            <div style="font-size:11px;color:var(--text-muted)">{{ $assignment->submissions_count ?? 0 }} submissions</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <p style="color:var(--text-muted);font-size:13px">No pending reviews</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
