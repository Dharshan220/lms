@extends('layouts.app')

@section('title', 'Teacher Dashboard - Nano Spark')

@section('content')
<div class="ns-teacher-dashboard">
    {{-- Header --}}
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 2rem; border-radius: 0 0 1.5rem 1.5rem; margin-bottom: 1.5rem;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h2 class="mb-1 fw-bold">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="mb-0 opacity-75">Here's what's happening with your courses today.</p>
            </div>
            <a href="{{ route('teacher.courses.create') }}" class="btn btn-light btn-lg fw-semibold">
                <i class="bi bi-plus-lg me-2"></i>New Course
            </a>
        </div>
    </div>

    <div class="px-3 px-md-4">
        {{-- Stats Row --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;">Total Courses</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalCourses }}</h3>
                            <small class="text-muted">{{ $publishedCourses }} published</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;">Total Students</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalStudents }}</h3>
                            <small class="text-muted">{{ $totalEnrollments }} enrollments</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;">Pending Submissions</h6>
                            <h3 class="mb-0 fw-bold">{{ $pendingSubmissions }}</h3>
                            <small class="text-muted">Needs grading</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1" style="font-size:0.8rem;">Upcoming Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $upcomingClasses->count() }}</h3>
                            <small class="text-muted">Scheduled</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- My Courses --}}
            <div class="col-xl-8">
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-book me-2 text-primary"></i>My Courses</h5>
                            <a href="{{ route('teacher.courses.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if($coursesWithStats->count())
                            <div class="row g-3">
                                @foreach($coursesWithStats as $course)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card course-card h-100">
                                            @if($course->thumbnail)
                                                <img src="{{ Storage::url($course->thumbnail) }}" class="card-img-top" style="height:140px;object-fit:cover;" alt="{{ $course->title }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center" style="height:140px;background:linear-gradient(135deg,#667eea,#764ba2);">
                                                    <i class="bi bi-book text-white" style="font-size:2.5rem;"></i>
                                                </div>
                                            @endif
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title fw-bold mb-0" style="font-size:0.9rem;">{{ Str::limit($course->title, 40) }}</h6>
                                                    @if($course->is_published)
                                                        <span class="badge bg-success bg-opacity-10 text-success">Published</span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">Draft</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex gap-3 text-muted" style="font-size:0.8rem;">
                                                    <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count ?? 0 }}</span>
                                                    <span><i class="bi bi-list-ul me-1"></i>{{ $course->lessons_count ?? 0 }} lessons</span>
                                                </div>
                                                @if($course->enrollments_avg_progress_percentage)
                                                    <div class="progress mt-2" style="height:5px;border-radius:3px;">
                                                        <div class="progress-bar" style="width:{{ round($course->enrollments_avg_progress_percentage) }}%;border-radius:3px;"></div>
                                                    </div>
                                                    <small class="text-muted mt-1 d-block" style="font-size:0.75rem;">{{ round($course->enrollments_avg_progress_percentage) }}% avg progress</small>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white border-top-0 pb-3 pt-0">
                                                <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="bi bi-eye me-1"></i>Manage
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-book text-muted" style="font-size:3rem;"></i>
                                <h5 class="mt-3 text-muted">No courses yet</h5>
                                <p class="text-muted">Create your first course to get started.</p>
                                <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-2"></i>Create Course
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Recent Student Activity --}}
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-activity me-2 text-success"></i>Recent Student Activity</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($recentEnrollments->count())
                            <div class="list-group list-group-flush">
                                @foreach($recentEnrollments as $enrollment)
                                    <div class="list-group-item d-flex align-items-center gap-3 px-0">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0" style="font-size:0.9rem;">
                                                <strong>{{ $enrollment->user->name ?? 'Student' }}</strong> enrolled in
                                                <strong>{{ $enrollment->course->title ?? 'Course' }}</strong>
                                            </p>
                                            <small class="text-muted">{{ $enrollment->enrolled_at?->diffForHumans() }}</small>
                                        </div>
                                        <small class="text-muted fw-semibold">{{ round($enrollment->progress_percentage ?? 0) }}%</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-activity text-muted" style="font-size:2rem;"></i>
                                <p class="text-muted mt-2 mb-0">No recent activity yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-xl-4">
                {{-- Pending Submissions --}}
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-inbox me-2 text-warning"></i>Pending Submissions</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if($pendingSubmissions > 0)
                            <h2 class="fw-bold text-warning mb-1">{{ $pendingSubmissions }}</h2>
                            <p class="text-muted mb-3">submissions awaiting review</p>
                            <a href="{{ route('teacher.assignments.index') }}" class="btn btn-warning w-100 fw-medium">
                                <i class="bi bi-check2-square me-2"></i>Grade Now
                            </a>
                        @else
                            <i class="bi bi-check-circle text-success" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">All caught up!</p>
                        @endif
                    </div>
                </div>

                {{-- Upcoming Live Classes --}}
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-camera-video me-2 text-info"></i>Upcoming Classes</h5>
                            <a href="{{ route('teacher.live-classes.index') }}" class="btn btn-sm btn-outline-info">All</a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if($upcomingClasses->count())
                            @foreach($upcomingClasses as $class)
                                <div class="d-flex align-items-start gap-3 {{ !$loop->last ? 'pb-3 mb-3 border-bottom' : '' }}">
                                    <div class="rounded bg-info bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;">
                                        <div class="text-center">
                                            <small class="d-block fw-bold text-info" style="line-height:1;">{{ $class->scheduled_at->format('d') }}</small>
                                            <small class="text-info" style="font-size:0.65rem;">{{ $class->scheduled_at->format('M') }}</small>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold" style="font-size:0.9rem;">{{ Str::limit($class->title, 30) }}</h6>
                                        <small class="text-muted d-block">{{ $class->course->title ?? '' }}</small>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $class->scheduled_at->format('h:i A') }} &middot; {{ $class->duration_minutes }}min</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x text-muted" style="font-size:2rem;"></i>
                                <p class="text-muted mt-2 mb-0">No upcoming classes</p>
                                <a href="{{ route('teacher.live-classes.create') }}" class="btn btn-sm btn-outline-info mt-2">Schedule One</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-lightning me-2 text-primary"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.courses.create') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-plus-circle me-2"></i>Create New Course
                            </a>
                            <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-outline-success text-start">
                                <i class="bi bi-question-circle me-2"></i>Create Quiz
                            </a>
                            <a href="{{ route('teacher.assignments.create') }}" class="btn btn-outline-warning text-start">
                                <i class="bi bi-file-earmark-text me-2"></i>Create Assignment
                            </a>
                            <a href="{{ route('teacher.live-classes.create') }}" class="btn btn-outline-info text-start">
                                <i class="bi bi-camera-video me-2"></i>Schedule Live Class
                            </a>
                            <a href="{{ route('teacher.ai-lesson-planner.index') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-robot me-2"></i>AI Lesson Planner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card, .course-card, .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .stat-card:hover, .course-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: all 0.2s; }
    .stat-icon { width: 54px; height: 54px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
</style>
@endsection
