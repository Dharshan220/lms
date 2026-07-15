@extends('layouts.app')

@section('title', 'AI Quiz Generator - Nano Spark')

@section('content')
@push('styles')
<style>
    .question-card { border-left: 4px solid; transition: transform 0.2s, box-shadow 0.2s; }
    .question-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    .option-item { cursor: pointer; transition: all 0.2s; border: 2px solid #e9ecef; border-radius: 10px; padding: 10px 14px; }
    .option-item:hover { border-color: #667eea; background: #667eea08; }
    .option-item.correct { border-color: #198754; background: #19875410; }
    .option-item.wrong { border-color: #dc3545; background: #dc354510; }
    .option-item.selected { border-color: #667eea; background: #667eea15; }
    .generate-btn { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
    .generate-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
            <i class="bi bi-question-circle text-primary" style="font-size:1.4rem;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1">AI Quiz Generator</h4>
            <p class="text-muted mb-0">Generate quiz questions on any topic using AI</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Input Form --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Quiz Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ai.quiz-generator.generate') }}" method="POST" id="quizForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select" required>
                                <option value="">Select a course</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" {{ (old('course_id', $validated['course_id'] ?? '') == $c->id) ? 'selected' : '' }}>
                                        {{ $c->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select the course for this quiz</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Topic / Subject <span class="text-danger">*</span></label>
                            <input type="text" name="topic" class="form-control" value="{{ old('topic', $validated['topic'] ?? '') }}"
                                placeholder="e.g. Photosynthesis, Python Basics, Solar System" required>
                            <small class="text-muted">Enter the topic you want to be quizzed on</small>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Number of Questions</label>
                                <select name="num_questions" class="form-select">
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ old('num_questions', $validated['num_questions'] ?? 5) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Difficulty</label>
                                <select name="difficulty" class="form-select">
                                    <option value="easy" {{ old('difficulty', $validated['difficulty'] ?? '') == 'easy' ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ old('difficulty', $validated['difficulty'] ?? 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ old('difficulty', $validated['difficulty'] ?? '') == 'hard' ? 'selected' : '' }}>Hard</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Question Type</label>
                            <select name="question_type" class="form-select">
                                <option value="mcq" {{ old('question_type', $validated['question_type'] ?? '') == 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                                <option value="true_false" {{ old('question_type', $validated['question_type'] ?? '') == 'true_false' ? 'selected' : '' }}>True / False</option>
                                <option value="mixed" {{ old('question_type', $validated['question_type'] ?? 'mixed') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold generate-btn" id="generateBtn">
                                <i class="bi bi-magic me-2"></i>Generate Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Generated Questions --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-success"></i>Generated Questions</h5>
                    @if(isset($questions) && count($questions))
                        <button class="btn btn-success btn-sm rounded-pill" onclick="saveAllQuestions()">
                            <i class="bi bi-save me-1"></i> Save All
                        </button>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if(isset($questions) && count($questions))
                        <form action="{{ route('ai.quiz-generator.generate') }}" method="POST" id="saveForm">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @foreach($questions as $index => $question)
                                @php
                                    $colors = ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#0d6efd'];
                                    $color = $colors[$index % count($colors)];
                                    $options = $question['options'] ?? [];
                                    if (empty($options)) {
                                        $options = array_filter([
                                            $question['option_a'] ?? null,
                                            $question['option_b'] ?? null,
                                            $question['option_c'] ?? null,
                                            $question['option_d'] ?? null,
                                        ]);
                                    }
                                    $qType = $question['question_type'] ?? ($question['type'] ?? 'mcq');
                                    $correctIdx = $question['correct_answer'] ?? 'A';
                                    if (is_string($correctIdx)) {
                                        $correctMap = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
                                        $correctIdx = $correctMap[strtoupper($correctIdx)] ?? 0;
                                    }
                                @endphp
                                <div class="card question-card mb-3" style="border-left-color:{{ $color }}!important;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge rounded-pill" style="background-color:{{ $color }};">Q{{ $index + 1 }}</span>
                                                <span class="badge bg-light text-dark">{{ ucfirst($qType === 'true_false' ? 'True/False' : 'MCQ') }}</span>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light" onclick="this.closest('.question-card').remove()">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </div>
                                        <h6 class="fw-bold mb-3">{{ $question['question'] ?? '' }}</h6>

                                        <div class="row g-2 mb-3">
                                            @foreach($options as $optIndex => $option)
                                                @php
                                                    $labels = ['A', 'B', 'C', 'D'];
                                                    $isCorrect = ($correctIdx == $optIndex);
                                                @endphp
                                                <div class="col-md-6">
                                                    <div class="option-item {{ $isCorrect ? 'correct' : '' }}">
                                                        <span class="fw-bold me-2" style="color:{{ $isCorrect ? '#198754' : '#667eea' }};">{{ $labels[$optIndex] ?? '' }}.</span>
                                                        {{ $option }}
                                                        @if($isCorrect)
                                                            <i class="bi bi-check-circle-fill text-success ms-1 float-end"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if(isset($question['explanation']))
                                            <div class="alert alert-info py-2 px-3 mb-0" style="border-radius:8px;font-size:0.85rem;">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <strong>Explanation:</strong> {{ $question['explanation'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    @else
                        <div class="text-center py-5" id="emptyState">
                            <div class="rounded bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-question-circle text-primary" style="font-size:2.5rem;"></i>
                            </div>
                            <h5 class="text-muted">Your quiz questions will appear here</h5>
                            <p class="text-muted mb-0">Select a topic and click "Generate Quiz" to create questions.</p>
                        </div>

                        <div id="loadingState" class="text-center py-5" style="display:none;">
                            <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;" role="status"></div>
                            <h5 class="text-muted">Generating questions...</h5>
                            <p class="text-muted mb-0">AI is crafting your quiz. This may take a moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('quizForm')?.addEventListener('submit', function() {
    document.getElementById('generateBtn').disabled = true;
    document.getElementById('generateBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('loadingState').style.display = 'block';
});

function saveAllQuestions() {
    document.getElementById('saveForm').submit();
}

function editQuestion(index) {
    const card = document.querySelectorAll('.question-card')[index];
    if (!card) return;
    const qText = card.querySelector('h6.fw-bold');
    const current = qText.textContent;
    const newText = prompt('Edit question:', current);
    if (newText !== null && newText.trim()) {
        qText.textContent = newText.trim();
    }
}

function removeQuestion(index) {
    if (!confirm('Remove this question?')) return;
    const card = document.querySelectorAll('.question-card')[index];
    if (card) card.remove();
}
</script>
@endpush
@endsection
