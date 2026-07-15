@extends('layouts.app')

@section('title', 'Generated Lesson Plan - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.ai-lesson-planner.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Generated Lesson Plan</h4>
            <p class="text-muted mb-0">{{ $course->title ?? '' }} &middot; Topic: {{ $validated['topic'] ?? '' }}</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card section-card">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-file-text me-2 text-success"></i>Lesson Plan</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyPlan()">
                            <i class="bi bi-clipboard me-1"></i>Copy
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="printPlan()">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="bg-light rounded p-4 lesson-plan-content" style="white-space:pre-line; line-height:1.8; font-size:0.95rem;">{{ $lessonPlan }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card section-card mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Plan Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">COURSE</small>
                        <div class="fw-semibold">{{ $course->title ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">TOPIC</small>
                        <div class="fw-semibold">{{ $validated['topic'] ?? 'N/A' }}</div>
                    </div>
                    @if(!empty($validated['grade_level']))
                        <div class="mb-3">
                            <small class="text-muted fw-semibold">GRADE LEVEL</small>
                            <div class="fw-semibold">{{ $validated['grade_level'] }}</div>
                        </div>
                    @endif
                    @if(!empty($validated['duration_minutes']))
                        <div class="mb-3">
                            <small class="text-muted fw-semibold">DURATION</small>
                            <div class="fw-semibold">{{ $validated['duration_minutes'] }} minutes</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card section-card">
                <div class="card-body p-4">
                    <a href="{{ route('teacher.ai-lesson-planner.index') }}" class="btn btn-primary w-100 fw-semibold mb-2">
                        <i class="bi bi-plus-lg me-2"></i>Generate Another
                    </a>
                    <button class="btn btn-success w-100 fw-semibold" onclick="downloadPlan()">
                        <i class="bi bi-download me-2"></i>Download as Text
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>

<script>
function copyPlan() {
    const text = document.querySelector('.lesson-plan-content').innerText;
    navigator.clipboard.writeText(text).then(() => alert('Copied to clipboard!'));
}

function printPlan() {
    window.print();
}

function downloadPlan() {
    const text = document.querySelector('.lesson-plan-content').innerText;
    const blob = new Blob([text], { type: 'text/plain' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'lesson-plan-{{ Str::slug($validated["topic"] ?? "plan") }}.txt';
    a.click();
}
</script>
@endsection
