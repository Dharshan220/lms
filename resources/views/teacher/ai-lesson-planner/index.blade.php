@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-robot" style="color: var(--ns-accent);"></i> AI Lesson Planner
            </h1>
            <p style="color: var(--text-secondary); margin:0;">Generate comprehensive lesson plans using AI</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="{{ isset($lessonPlan) ? 'col-lg-5' : 'col-lg-6 mx-auto' }}">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="mb-3"><i class="bi bi-pencil-square me-2"></i>Lesson Details</h5>
                    <form method="POST" action="{{ route('teacher.ai-lesson-planner.generate') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Course *</label>
                            <select name="course_id" class="form-select" required>
                                <option value="">Select a course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ (old('course_id') ?? ($validated['course_id'] ?? '')) == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Topic *</label>
                            <input type="text" name="topic" class="form-control" placeholder="e.g., Introduction to Arduino LEDs" value="{{ old('topic', $validated['topic'] ?? '') }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Grade Level</label>
                                <select name="grade_level" class="form-select">
                                    @foreach([6,7,8,9,10,11,12] as $g)
                                        <option value="{{ $g }}" {{ (old('grade_level', $validated['grade_level'] ?? '9')) == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Duration (min)</label>
                                <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $validated['duration_minutes'] ?? 45) }}" min="15" max="180">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Learning Objectives</label>
                            <textarea name="learning_objectives" class="form-control" rows="3" placeholder="What should students learn?">{{ old('learning_objectives', $validated['learning_objectives'] ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="additional_notes" class="form-control" rows="2" placeholder="Any extra context...">{{ old('additional_notes', $validated['additional_notes'] ?? '') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-lg w-100 text-white fw-bold" style="background: linear-gradient(135deg, var(--ns-accent), var(--ns-primary)); border-radius: 10px;">
                            <i class="bi bi-stars me-2"></i>Generate Lesson Plan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; min-height: 500px;">
                <div class="card-body p-4">
                    @if(isset($lessonPlan))
                        <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Generated Lesson Plan</h5>
                        <div class="lesson-plan-content" style="white-space: pre-wrap; line-height: 1.8; color: var(--text-primary);">
                            {!! nl2br(e($lessonPlan)) !!}
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button onclick="navigator.clipboard.writeText(document.querySelector('.lesson-plan-content').innerText)" class="btn btn-outline-primary">
                                <i class="bi bi-clipboard me-1"></i>Copy
                            </button>
                        </div>
                    @else
                        <div class="text-center py-5" style="color: var(--text-muted);">
                            <i class="bi bi-robot" style="font-size: 64px; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
                            <h5>No lesson plan yet</h5>
                            <p>Fill in the form and click "Generate" to create a lesson plan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
