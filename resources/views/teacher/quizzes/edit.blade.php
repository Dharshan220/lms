@extends('layouts.app')

@section('title', 'Edit Quiz - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Edit Quiz</h4>
            <p class="text-muted mb-0">{{ $quiz->title }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2" style="border-radius:12px;">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('teacher.quizzes.update', $quiz) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Quiz Info --}}
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Quiz Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Quiz Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Course</label>
                                <input type="text" class="form-control" value="{{ $quiz->course->title ?? 'N/A' }}" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="2">{{ old('description', $quiz->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Questions --}}
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-list-ol me-2 text-success"></i>Questions ({{ $quiz->questions->count() }})</h5>
                        <button type="button" class="btn btn-success btn-sm" onclick="addQuestion()">
                            <i class="bi bi-plus-lg me-1"></i>Add Question
                        </button>
                    </div>
                    <div class="card-body p-4" id="questions-container">
                        @forelse($quiz->questions as $qIdx => $question)
                            <div class="question-block" data-index="{{ $qIdx }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-primary mb-0">Question {{ $qIdx + 1 }}</h6>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Question Text <span class="text-danger">*</span></label>
                                        <textarea name="questions[{{ $qIdx }}][question]" class="form-control" rows="2" required>{{ $question->question }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Question Type</label>
                                        <select name="questions[{{ $qIdx }}][question_type]" class="form-select">
                                            <option value="mcq" {{ $question->question_type == 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                                            <option value="true_false" {{ $question->question_type == 'true_false' ? 'selected' : '' }}>True / False</option>
                                            <option value="short_answer" {{ $question->question_type == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                            <option value="coding" {{ $question->question_type == 'coding' ? 'selected' : '' }}>Coding</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Marks</label>
                                        <input type="number" name="questions[{{ $qIdx }}][marks]" class="form-control" value="{{ $question->marks }}" min="1">
                                    </div>
                                    @if($question->question_type == 'mcq')
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label fw-semibold small">Option A <span class="text-danger">*</span></label>
                                        <input type="text" name="questions[{{ $qIdx }}][option_a]" class="form-control" value="{{ $question->option_a }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label fw-semibold small">Option B <span class="text-danger">*</span></label>
                                        <input type="text" name="questions[{{ $qIdx }}][option_b]" class="form-control" value="{{ $question->option_b }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label fw-semibold small">Option C</label>
                                        <input type="text" name="questions[{{ $qIdx }}][option_c]" class="form-control" value="{{ $question->option_c }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label fw-semibold small">Option D</label>
                                        <input type="text" name="questions[{{ $qIdx }}][option_d]" class="form-control" value="{{ $question->option_d }}">
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Correct Answer <span class="text-danger">*</span></label>
                                        <input type="text" name="questions[{{ $qIdx }}][correct_answer]" class="form-control" value="{{ $question->correct_answer }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Explanation</label>
                                        <textarea name="questions[{{ $qIdx }}][explanation]" class="form-control" rows="2">{{ $question->explanation }}</textarea>
                                    </div>
                                </div>
                                <hr class="mt-4">
                            </div>
                        @empty
                            <div class="text-center py-4" id="no-questions-msg">
                                <p class="text-muted mb-0">No questions yet. Click "Add Question" to start.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-warning"></i>Quiz Settings</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Time Limit (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="time_limit_minutes" class="form-control" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Passing Marks <span class="text-danger">*</span></label>
                            <input type="number" name="passing_marks" class="form-control" value="{{ old('passing_marks', $quiz->passing_marks) }}" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Max Attempts <span class="text-danger">*</span></label>
                            <input type="number" name="max_attempts" class="form-control" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" required>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published', $quiz->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Published</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-check-lg me-2"></i>Update Quiz
                            </button>
                            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>

<script>
let questionIndex = {{ $quiz->questions->count() }};

function addQuestion() {
    const msg = document.getElementById('no-questions-msg');
    if (msg) msg.remove();

    const container = document.getElementById('questions-container');
    const html = `
        <div class="question-block" data-index="${questionIndex}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-primary mb-0">Question ${questionIndex + 1}</h6>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small">Question Text <span class="text-danger">*</span></label>
                    <textarea name="questions[${questionIndex}][question]" class="form-control" rows="2" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Question Type</label>
                    <select name="questions[${questionIndex}][question_type]" class="form-select">
                        <option value="mcq">Multiple Choice (MCQ)</option>
                        <option value="true_false">True / False</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="coding">Coding</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Marks</label>
                    <input type="number" name="questions[${questionIndex}][marks]" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold small">Option A</label>
                    <input type="text" name="questions[${questionIndex}][option_a]" class="form-control" placeholder="Option A">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold small">Option B</label>
                    <input type="text" name="questions[${questionIndex}][option_b]" class="form-control" placeholder="Option B">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold small">Option C</label>
                    <input type="text" name="questions[${questionIndex}][option_c]" class="form-control" placeholder="Option C">
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label fw-semibold small">Option D</label>
                    <input type="text" name="questions[${questionIndex}][option_d]" class="form-control" placeholder="Option D">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Correct Answer <span class="text-danger">*</span></label>
                    <input type="text" name="questions[${questionIndex}][correct_answer]" class="form-control" placeholder="e.g. A or True" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Explanation</label>
                    <textarea name="questions[${questionIndex}][explanation]" class="form-control" rows="2" placeholder="Explain the correct answer..."></textarea>
                </div>
            </div>
            <hr class="mt-4">
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    questionIndex++;
    updateQuestionNumbers();
}

function removeQuestion(btn) {
    btn.closest('.question-block').remove();
    updateQuestionNumbers();
}

function updateQuestionNumbers() {
    document.querySelectorAll('.question-block').forEach((block, i) => {
        block.querySelector('h6').textContent = 'Question ' + (i + 1);
    });
}
</script>
@endsection
