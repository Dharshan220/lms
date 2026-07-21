@extends('layouts.app')

@section('title', 'AI Quiz Generator - Nano Spark LMS')

@section('content')
<style>
    :root {
        --ns-bg: #050505;
        --ns-card: #121212;
        --ns-elevated: #181818;
        --ns-accent: #FFD400;
        --ns-success: #00D26A;
        --ns-warning: #FF9800;
        --ns-danger: #FF4D4F;
        --ns-info: #3B82F6;
        --ns-text: #FFFFFF;
        --ns-text-secondary: #A0A0A0;
        --ns-text-muted: #666666;
        --ns-border: rgba(255,255,255,0.06);
        --font-heading: 'Space Mono', monospace;
        --font-body: 'IBM Plex Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
    }

    .ns-page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }
    .ns-page-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(147,51,234,0.15));
        border: 1px solid rgba(59,130,246,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--ns-info);
        flex-shrink: 0;
    }
    .ns-page-header h1 {
        font-family: var(--font-heading);
        font-size: 22px;
        font-weight: 700;
        color: var(--ns-text);
        margin: 0;
    }
    .ns-page-header p {
        font-size: 14px;
        color: var(--ns-text-muted);
        margin: 2px 0 0;
    }

    .ns-layout-split {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 24px;
        align-items: start;
    }

    .ns-panel {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 20px;
        overflow: hidden;
    }
    .ns-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--ns-border);
    }
    .ns-panel-title {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-text);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    .ns-panel-title i {
        font-size: 18px;
    }
    .ns-panel-body {
        padding: 24px;
    }

    .ns-form-group {
        margin-bottom: 20px;
    }
    .ns-form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-form-label .required {
        color: var(--ns-danger);
    }
    .ns-form-hint {
        font-size: 12px;
        color: var(--ns-text-muted);
        margin-top: 4px;
    }
    .ns-form-input,
    .ns-form-select,
    .ns-form-textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--ns-border);
        background: var(--ns-elevated);
        color: var(--ns-text);
        font-family: var(--font-body);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }
    .ns-form-input:focus,
    .ns-form-select:focus,
    .ns-form-textarea:focus {
        border-color: rgba(59,130,246,0.4);
    }
    .ns-form-input::placeholder {
        color: var(--ns-text-muted);
    }
    .ns-form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }

    .ns-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .ns-btn-generate {
        width: 100%;
        padding: 14px 24px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #3B82F6, #8B5CF6);
        color: #FFFFFF;
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 16px rgba(59,130,246,0.3);
        margin-top: 8px;
    }
    .ns-btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(59,130,246,0.45);
    }
    .ns-btn-generate:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .ns-questions-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .ns-question-card {
        background: var(--ns-elevated);
        border: 1px solid var(--ns-border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.2s;
        border-left: 4px solid;
    }
    .ns-question-card:hover {
        border-color: rgba(255,255,255,0.1);
        border-left-color: inherit;
    }
    .ns-question-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px 0;
    }
    .ns-question-badges {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .ns-q-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        font-family: var(--font-mono);
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }
    .ns-q-type {
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        font-family: var(--font-mono);
        text-transform: uppercase;
        background: rgba(255,255,255,0.06);
        color: var(--ns-text-secondary);
    }
    .ns-question-remove {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--ns-border);
        background: transparent;
        color: var(--ns-text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .ns-question-remove:hover {
        background: rgba(255,77,79,0.1);
        border-color: rgba(255,77,79,0.3);
        color: var(--ns-danger);
    }
    .ns-question-body {
        padding: 14px 20px 20px;
    }
    .ns-question-text {
        font-size: 15px;
        font-weight: 600;
        color: var(--ns-text);
        margin-bottom: 16px;
        line-height: 1.6;
    }
    .ns-options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .ns-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--ns-border);
        background: rgba(255,255,255,0.02);
        font-size: 13px;
        color: var(--ns-text-secondary);
        transition: all 0.2s;
    }
    .ns-option.correct {
        border-color: rgba(0,210,106,0.4);
        background: rgba(0,210,106,0.08);
        color: var(--ns-success);
    }
    .ns-option-label {
        font-family: var(--font-mono);
        font-weight: 700;
        font-size: 12px;
        min-width: 20px;
    }
    .ns-option.correct .ns-option-label {
        color: var(--ns-success);
    }
    .ns-option.correct .ns-check-icon {
        margin-left: auto;
        color: var(--ns-success);
    }
    .ns-question-explanation {
        margin-top: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        background: rgba(59,130,246,0.08);
        border: 1px solid rgba(59,130,246,0.15);
        font-size: 13px;
        color: var(--ns-text-secondary);
        line-height: 1.6;
    }
    .ns-question-explanation strong {
        color: var(--ns-info);
    }

    .ns-empty-state {
        text-align: center;
        padding: 60px 24px;
    }
    .ns-empty-icon {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: rgba(59,130,246,0.06);
        border: 1px solid rgba(59,130,246,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: var(--ns-info);
        margin: 0 auto 20px;
    }
    .ns-empty-state h3 {
        font-family: var(--font-heading);
        font-size: 18px;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-empty-state p {
        font-size: 14px;
        color: var(--ns-text-muted);
    }

    .ns-loading-state {
        text-align: center;
        padding: 60px 24px;
        display: none;
    }
    .ns-spinner {
        width: 48px;
        height: 48px;
        border: 3px solid var(--ns-border);
        border-top-color: var(--ns-info);
        border-radius: 50%;
        margin: 0 auto 20px;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .ns-loading-state h3 {
        font-family: var(--font-heading);
        font-size: 16px;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-loading-state p {
        font-size: 13px;
        color: var(--ns-text-muted);
    }

    @media (max-width: 1024px) {
        .ns-layout-split {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .ns-options-grid {
            grid-template-columns: 1fr;
        }
        .ns-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="padding: 24px; max-width: 1200px; margin: 0 auto;" x-data="quizGenerator()">
    <div class="ns-page-header">
        <div class="ns-page-header-icon">
            <i class="bi bi-stars"></i>
        </div>
        <div>
            <h1>AI Quiz Generator</h1>
            <p>Generate quiz questions on any topic using AI</p>
        </div>
    </div>

    <div class="ns-layout-split">
        <div class="ns-panel" style="position: sticky; top: 24px;">
            <div class="ns-panel-header">
                <h2 class="ns-panel-title"><i class="bi bi-sliders" style="color:var(--ns-info)"></i> Quiz Settings</h2>
            </div>
            <div class="ns-panel-body">
                <form action="{{ route('ai.quiz-generator.generate') }}" method="POST" id="quizForm" @submit.prevent="submitForm()">
                    @csrf
                    <div class="ns-form-group">
                        <label class="ns-form-label">Course <span class="required">*</span></label>
                        <select name="course_id" class="ns-form-select" required x-model="form.course_id">
                            <option value="">Select a course</option>
                            @if(isset($courses))
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="ns-form-group">
                        <label class="ns-form-label">Topic / Subject <span class="required">*</span></label>
                        <input type="text" name="topic" class="ns-form-input" placeholder="e.g. Photosynthesis, Python Basics, Solar System" required x-model="form.topic">
                        <div class="ns-form-hint">Enter the topic you want to be quizzed on</div>
                    </div>

                    <div class="ns-row">
                        <div class="ns-form-group">
                            <label class="ns-form-label">Number of Questions</label>
                            <select name="num_questions" class="ns-form-select" x-model="form.num_questions">
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ $i == 5 ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="ns-form-group">
                            <label class="ns-form-label">Difficulty</label>
                            <select name="difficulty" class="ns-form-select" x-model="form.difficulty">
                                <option value="easy">Easy</option>
                                <option value="medium" selected>Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>

                    <div class="ns-form-group">
                        <label class="ns-form-label">Question Type</label>
                        <select name="question_type" class="ns-form-select" x-model="form.question_type">
                            <option value="mcq">Multiple Choice (MCQ)</option>
                            <option value="true_false">True / False</option>
                            <option value="mixed">Mixed</option>
                        </select>
                    </div>

                    <button type="submit" class="ns-btn-generate" :disabled="loading">
                        <template x-if="!loading">
                            <span><i class="bi bi-stars"></i> Generate Quiz</span>
                        </template>
                        <template x-if="loading">
                            <span><span class="ns-spinner" style="width:20px;height:20px;border-width:2px;margin:0;"></span> Generating...</span>
                        </template>
                    </button>
                </form>
            </div>
        </div>

        <div class="ns-panel">
            <div class="ns-panel-header">
                <h2 class="ns-panel-title"><i class="bi bi-list-check" style="color:var(--ns-success)"></i> Generated Questions</h2>
                @if(isset($questions) && count($questions))
                    <button class="ns-btn-generate" style="width:auto;padding:8px 18px;font-size:13px;font-family:var(--font-body);box-shadow:none;" onclick="document.getElementById('saveForm')?.submit()">
                        <i class="bi bi-save"></i> Save All
                    </button>
                @endif
            </div>
            <div class="ns-panel-body">
                @if(isset($questions) && count($questions))
                    <form action="{{ route('ai.quiz-generator.generate') }}" method="POST" id="saveForm">
                        @csrf
                        <div class="ns-questions-list">
                            @foreach($questions as $index => $question)
                                @php
                                    $colors = ['#FFD400', '#00D26A', '#3B82F6', '#FF9800', '#FF4D4F'];
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
                                <div class="ns-question-card" style="border-left-color: {{ $color }};">
                                    <div class="ns-question-header">
                                        <div class="ns-question-badges">
                                            <span class="ns-q-num" style="background: {{ $color }};">Q{{ $index + 1 }}</span>
                                            <span class="ns-q-type">{{ $qType === 'true_false' ? 'T/F' : 'MCQ' }}</span>
                                        </div>
                                        <button type="button" class="ns-question-remove" onclick="this.closest('.ns-question-card').remove()">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                    <div class="ns-question-body">
                                        <div class="ns-question-text">{{ $question['question'] ?? '' }}</div>
                                        <div class="ns-options-grid">
                                            @foreach($options as $optIndex => $option)
                                                @php
                                                    $labels = ['A', 'B', 'C', 'D'];
                                                    $isCorrect = ($correctIdx == $optIndex);
                                                @endphp
                                                <div class="ns-option {{ $isCorrect ? 'correct' : '' }}">
                                                    <span class="ns-option-label">{{ $labels[$optIndex] ?? '' }}.</span>
                                                    {{ $option }}
                                                    @if($isCorrect)
                                                        <i class="bi bi-check-circle-fill ns-check-icon"></i>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(isset($question['explanation']))
                                            <div class="ns-question-explanation">
                                                <strong><i class="bi bi-info-circle"></i> Explanation:</strong> {{ $question['explanation'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                @else
                    <div class="ns-empty-state" x-show="!loading">
                        <div class="ns-empty-icon">
                            <i class="bi bi-question-circle"></i>
                        </div>
                        <h3>Your quiz questions will appear here</h3>
                        <p>Select a topic and click "Generate Quiz" to create questions.</p>
                    </div>

                    <div class="ns-loading-state" x-show="loading" x-cloak>
                        <div class="ns-spinner"></div>
                        <h3>Generating questions...</h3>
                        <p>AI is crafting your quiz. This may take a moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function quizGenerator() {
    return {
        loading: false,
        form: {
            course_id: '{{ old("course_id", $validated["course_id"] ?? "") }}',
            topic: '{{ old("topic", $validated["topic"] ?? "") }}',
            num_questions: '{{ old("num_questions", $validated["num_questions"] ?? 5) }}',
            difficulty: '{{ old("difficulty", $validated["difficulty"] ?? "medium") }}',
            question_type: '{{ old("question_type", $validated["question_type"] ?? "mcq") }}',
        },
        submitForm() {
            this.loading = true;
            this.$nextTick(() => {
                document.getElementById('quizForm').submit();
            });
        }
    };
}
</script>
@endpush
@endsection
