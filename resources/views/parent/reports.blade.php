@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-bar-chart-line" style="color: var(--ns-accent);"></i> Academic Reports
            </h1>
            <p style="color: var(--text-secondary); margin:0;">Detailed progress reports for your children</p>
        </div>
        <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Dashboard</a>
    </div>

    @forelse($children as $child)
        @php
            $enrollments = \App\Models\Enrollment::where('user_id', $child->id)->with('course')->get();
            $completed = $enrollments->where('is_completed', true)->count();
            $total = $enrollments->count();
            $avgProgress = $total > 0 ? round($enrollments->avg('progress_percentage'), 1) : 0;
            $quizAttempts = \App\Models\QuizAttempt::where('user_id', $child->id)->get();
            $avgScore = $quizAttempts->isNotEmpty()
                ? round($quizAttempts->avg('score') / max($quizAttempts->avg('total_marks'), 1) * 100, 1) : 0;
            $lessonsCompleted = \App\Models\LessonProgress::where('user_id', $child->id)->where('is_completed', true)->count();
        @endphp

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-person-fill me-2"></i>{{ $child->name }}
                    @if($child->school) <small class="text-muted fw-normal">- {{ $child->school->name }}</small> @endif
                </h5>
                <a href="{{ route('parent.child.progress', $child->id) }}" class="btn btn-sm btn-outline-primary">Detailed View</a>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-2 text-center">
                        <div class="rounded bg-primary bg-opacity-10 p-3 mb-2">
                            <i class="bi bi-book text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="fw-bold fs-5">{{ $total }}</div>
                        <small class="text-muted">Enrolled</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="rounded bg-success bg-opacity-10 p-3 mb-2">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="fw-bold fs-5">{{ $completed }}</div>
                        <small class="text-muted">Completed</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="rounded bg-info bg-opacity-10 p-3 mb-2">
                            <i class="bi bi-graph-up text-info" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="fw-bold fs-5">{{ $avgProgress }}%</div>
                        <small class="text-muted">Avg Progress</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="rounded bg-warning bg-opacity-10 p-3 mb-2">
                            <i class="bi bi-journal-check text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="fw-bold fs-5">{{ $lessonsCompleted }}</div>
                        <small class="text-muted">Lessons Done</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="rounded bg-danger bg-opacity-10 p-3 mb-2">
                            <i class="bi bi-award text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="fw-bold fs-5">{{ $avgScore }}%</div>
                        <small class="text-muted">Quiz Avg</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="rounded" style="background: rgba(155,89,182,0.1); padding: 12px; margin-bottom: 8px;">
                            <i class="bi bi-star" style="font-size: 1.5rem; color: var(--ns-accent);"></i>
                        </div>
                        <div class="fw-bold fs-5">{{ number_format($child->xp_points ?? 0) }}</div>
                        <small class="text-muted">XP Points</small>
                    </div>
                </div>

                @if($enrollments->count() > 0)
                    <hr>
                    <h6 class="fw-semibold mb-3">Course Progress</h6>
                    @foreach($enrollments as $enrollment)
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-semibold">{{ $enrollment->course->title ?? 'N/A' }}</small>
                                    <small class="text-muted">{{ $enrollment->progress_percentage ?? 0 }}%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: {{ $enrollment->progress_percentage ?? 0 }}%; background: linear-gradient(135deg, var(--ns-primary), var(--ns-teal));"></div>
                                </div>
                            </div>
                            <span class="ms-3">
                                @if($enrollment->is_completed) <span class="badge bg-success">Done</span>
                                @else <span class="badge bg-warning text-dark">In Progress</span> @endif
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5" style="color: var(--text-muted);">
            <i class="bi bi-people" style="font-size: 64px; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
            <h5>No children linked</h5>
            <p>Contact admin to link your child's account</p>
        </div>
    @endforelse
</div>
@endsection
