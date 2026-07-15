@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-question-circle" style="color: var(--ns-primary);"></i> My Quizzes
            </h1>
            <p style="color: var(--text-secondary); margin:0;">Quizzes from your enrolled courses</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($quizzes as $quiz)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                            <span class="badge bg-success">Quiz</span>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-book me-1"></i>{{ $quiz->course->title ?? 'N/A' }}
                        </p>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-clock me-1"></i>{{ $quiz->time_limit_minutes ?? 30 }} min</span>
                            <span><i class="bi bi-check-circle me-1"></i>{{ $quiz->questions->count() ?? 0 }} questions</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mt-1">
                            <span>Pass: {{ $quiz->passing_marks ?? 0 }} marks</span>
                            <span>Max: {{ $quiz->max_attempts ?? 1 }} attempts</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-play-circle me-1"></i> Start Quiz
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5" style="color: var(--text-muted);">
                <i class="bi bi-question-circle" style="font-size: 64px; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
                <h5>No quizzes available</h5>
                <p>Enroll in a course to access quizzes</p>
                <a href="{{ route('student.courses.index') }}" class="btn btn-primary">Browse Courses</a>
            </div>
        @endforelse
    </div>

    @if($quizzes->hasPages())
        <div class="mt-4">{{ $quizzes->links() }}</div>
    @endif
</div>
@endsection
