@extends('layouts.app')

@section('title', $quiz->title . ' - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-1">{{ $quiz->title }}</h4>
            <p class="text-muted mb-0">{{ $quiz->course->title ?? '' }}</p>
        </div>
        <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit Questions
        </a>
    </div>

    {{-- Quiz Info Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card section-card text-center py-3">
                <div class="text-muted small fw-semibold">Questions</div>
                <h3 class="fw-bold text-primary mb-0">{{ $quiz->questions->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card section-card text-center py-3">
                <div class="text-muted small fw-semibold">Time Limit</div>
                <h3 class="fw-bold text-info mb-0">{{ $quiz->time_limit_minutes }}min</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card section-card text-center py-3">
                <div class="text-muted small fw-semibold">Passing Marks</div>
                <h3 class="fw-bold text-warning mb-0">{{ $quiz->passing_marks }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card section-card text-center py-3">
                <div class="text-muted small fw-semibold">Status</div>
                @if($quiz->is_published)
                    <span class="badge bg-success fs-6">Published</span>
                @else
                    <span class="badge bg-secondary fs-6">Draft</span>
                @endif
            </div>
        </div>
    </div>

    @if($quiz->description)
        <div class="card section-card mb-4">
            <div class="card-body px-4 py-3">
                <strong class="text-muted small">DESCRIPTION:</strong>
                <p class="mb-0 mt-1">{{ $quiz->description }}</p>
            </div>
        </div>
    @endif

    {{-- Questions List --}}
    <div class="card section-card mb-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-ol me-2 text-success"></i>Questions</h5>
        </div>
        <div class="card-body p-4">
            @if($quiz->questions->count())
                @foreach($quiz->questions as $qIdx => $question)
                    <div class="card mb-3 {{ $loop->last ? 'mb-0' : '' }}" style="border-left: 4px solid #0d6efd; border-radius: 10px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Q{{ $qIdx + 1 }}</span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary mb-2">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</span>
                                    <span class="badge bg-info bg-opacity-10 text-info mb-2">{{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-3">{{ $question->question }}</h6>
                            @if($question->question_type == 'mcq')
                                <div class="row g-2 mb-3">
                                    @foreach(['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $key => $opt)
                                        @if($opt)
                                            <div class="col-md-6">
                                                <div class="p-2 rounded {{ $question->correct_answer == $key ? 'bg-success bg-opacity-10 border border-success' : 'bg-light' }}" style="font-size:0.9rem;">
                                                    <strong>{{ $key }}.</strong> {{ $opt }}
                                                    @if($question->correct_answer == $key)
                                                        <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="mb-3 p-2 bg-success bg-opacity-10 border border-success rounded" style="font-size:0.9rem;">
                                    <strong>Correct Answer:</strong> {{ $question->correct_answer }}
                                </div>
                            @endif
                            @if($question->explanation)
                                <div class="bg-light rounded p-3" style="font-size:0.85rem;">
                                    <strong class="text-muted"><i class="bi bi-info-circle me-1"></i>Explanation:</strong>
                                    <span class="text-muted">{{ $question->explanation }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <i class="bi bi-question-circle text-muted" style="font-size:2.5rem;"></i>
                    <p class="text-muted mt-2 mb-3">No questions yet.</p>
                    <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Add Questions
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Attempts --}}
    <div class="card section-card">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-people me-2 text-info"></i>Recent Attempts ({{ $quiz->attempts->count() }})</h5>
        </div>
        <div class="card-body p-4">
            @if($quiz->attempts->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Student</th>
                                <th class="fw-semibold">Score</th>
                                <th class="fw-semibold">Status</th>
                                <th class="fw-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz->attempts->take(10) as $attempt)
                                <tr>
                                    <td class="fw-semibold">{{ $attempt->user->name ?? 'N/A' }}</td>
                                    <td>{{ $attempt->score ?? 'N/A' }} / {{ $quiz->passing_marks }}</td>
                                    <td>
                                        @if(isset($attempt->score) && $attempt->score >= $quiz->passing_marks)
                                            <span class="badge bg-success">Passed</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $attempt->created_at?->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No attempts yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
