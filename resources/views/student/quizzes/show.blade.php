@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .attempt-badge { font-size: 0.85rem; }
</style>
@endpush

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width:80px;height:80px;">
                        <i class="bi bi-question-circle text-primary" style="font-size:2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $quiz->title }}</h3>
                    @if($quiz->course)
                        <p class="text-muted mb-4">{{ $quiz->course->title }}</p>
                    @endif

                    @if($quiz->description)
                        <p class="text-muted mb-4">{{ $quiz->description }}</p>
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <i class="bi bi-clock text-primary fs-4"></i>
                                <div class="fw-bold mt-1">{{ $quiz->time_limit_minutes }} min</div>
                                <small class="text-muted">Time Limit</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <i class="bi bi-award text-success fs-4"></i>
                                <div class="fw-bold mt-1">{{ $quiz->passing_marks }}</div>
                                <small class="text-muted">Passing Marks</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <i class="bi bi-hash text-warning fs-4"></i>
                                <div class="fw-bold mt-1">{{ $quiz->questions->count() }}</div>
                                <small class="text-muted">Questions</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-4 mb-4">
                        <span class="attempt-badge badge bg-info">
                            <i class="bi bi-arrow-repeat me-1"></i> {{ $attemptsRemaining }} attempts remaining
                        </span>
                        @if($bestAttempt)
                            <span class="attempt-badge badge bg-success">
                                <i class="bi bi-trophy me-1"></i> Best: {{ $bestAttempt->score }}/{{ $bestAttempt->total_marks }}
                            </span>
                        @endif
                    </div>

                    @if($attempts->count())
                        <div class="text-start mb-4">
                            <h6 class="fw-bold mb-2">Previous Attempts:</h6>
                            @foreach($attempts->take(5) as $attempt)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="small text-muted">{{ $attempt->started_at->format('M d, Y h:i A') }}</span>
                                    <span class="badge {{ $attempt->is_passed ? 'bg-success' : 'bg-danger' }}">
                                        {{ $attempt->score }}/{{ $attempt->total_marks }}
                                        @if($attempt->is_passed) (Passed) @else (Failed) @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($attemptsRemaining > 0)
                        <form action="{{ route('student.quizzes.start', $quiz) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5" onclick="return confirm('Ready to start the quiz?')">
                                <i class="bi bi-play-fill me-2"></i> Start Quiz
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i> You have used all available attempts.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
