@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .question-nav-btn { width:36px; height:36px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:0.85rem; }
    .option-label { cursor:pointer; border:2px solid #e9ecef; border-radius:12px; padding:12px 16px; display:block; transition:all 0.2s; }
    .option-label:hover { border-color:#0d6efd; background:#f8f9ff; }
    .option-label.selected { border-color:#0d6efd; background:#e7f1ff; font-weight:600; }
    .timer-warning { animation: pulse 1s infinite; color:#dc3545!important; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
</style>
@endpush

<div class="container py-4">
    <form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" id="quizForm">
        @csrf
        <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="fw-bold mb-0">{{ $quiz->title }}</h5>
                    <small class="text-muted">{{ $quiz->questions->count() }} questions &middot; {{ $quiz->time_limit_minutes }} min time limit</small>
                </div>
                <div class="text-end">
                    <div id="timer" class="fw-bold fs-4 text-primary">
                        <i class="bi bi-clock me-1"></i>
                        <span id="timerDisplay">{{ $quiz->time_limit_minutes }}:00</span>
                    </div>
                    <small class="text-muted">Time Remaining</small>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap gap-2 justify-content-center" id="questionNav">
                    @foreach($quiz->questions as $q)
                        <button type="button" class="btn btn-outline-secondary question-nav-btn" data-question="{{ $loop->index }}" onclick="goToQuestion({{ $loop->index }})">
                            {{ $loop->index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        @foreach($quiz->questions as $question)
            <div class="card border-0 shadow-sm rounded-4 mb-4" id="question{{ $loop->index }}" style="display:{{ $loop->first ? 'block' : 'none' }};">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="badge bg-primary mb-2">Question {{ $loop->index + 1 }} of {{ $quiz->questions->count() }}</span>
                            <h5 class="fw-bold">{{ $question->question }}</h5>
                        </div>
                        <span class="badge bg-light text-dark">{{ $question->marks }} {{ Str::plural('mark', $question->marks) }}</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach(['A' => $question->option_a, 'B' => $question->option_b, 'C' => $question->option_c, 'D' => $question->option_d] as $key => $option)
                            @if($option)
                                <label class="option-label d-flex align-items-center gap-3" onclick="selectOption(this, {{ $loop->parent->loop->index }}, '{{ $key }}')">
                                    <input type="radio" name="answers[{{ $loop->parent->loop->index }}][selected_answer]" value="{{ $key }}" class="d-none" required>
                                    <input type="hidden" name="answers[{{ $loop->parent->loop->index }}][question_id]" value="{{ $question->id }}">
                                    <div class="rounded-circle border d-flex align-items-center justify-content-center flex-shrink-0" style="width:35px;height:35px;">
                                        <span class="fw-bold">{{ $key }}</span>
                                    </div>
                                    <span class="option-text">{{ $option }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        @if(!$loop->first)
                            <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="goToQuestion({{ $loop->index - 1 }})">
                                <i class="bi bi-arrow-left me-1"></i> Previous
                            </button>
                        @else
                            <div></div>
                        @endif
                        @if(!$loop->last)
                            <button type="button" class="btn btn-primary rounded-pill" onclick="goToQuestion({{ $loop->index + 1 }})">
                                Next <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        @else
                            <button type="submit" class="btn btn-success rounded-pill px-4" onclick="return confirm('Are you sure you want to submit the quiz?')">
                                <i class="bi bi-send me-1"></i> Submit Quiz
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div class="text-center my-4">
            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5" onclick="return confirm('Are you sure you want to submit the quiz?')">
                <i class="bi bi-send me-2"></i> Submit Quiz
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const totalQuestions = {{ $quiz->questions->count() }};
    const timeLimitMinutes = {{ $quiz->time_limit_minutes }};
    let timeRemaining = timeLimitMinutes * 60;

    function goToQuestion(index) {
        document.querySelectorAll('[id^="question"]').forEach(function(el) { if (el.id.match(/^question\d+$/)) el.style.display = 'none'; });
        document.getElementById('question' + index).style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function selectOption(label, questionIndex, answer) {
        const card = document.getElementById('question' + questionIndex);
        card.querySelectorAll('.option-label').forEach(function(el) { el.classList.remove('selected'); });
        label.classList.add('selected');
        label.querySelector('input[type="radio"]').checked = true;
        const navBtns = document.querySelectorAll('#questionNav button');
        navBtns[questionIndex].classList.remove('btn-outline-secondary');
        navBtns[questionIndex].classList.add('btn-success');
    }

    function updateTimer() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const display = document.getElementById('timerDisplay');
        if (display) display.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        if (timeRemaining <= 60) document.getElementById('timer').classList.add('timer-warning');
        if (timeRemaining <= 0) { document.getElementById('quizForm').submit(); return; }
        timeRemaining--;
    }
    setInterval(updateTimer, 1000);
</script>
@endpush
@endsection
