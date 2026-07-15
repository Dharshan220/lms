@extends('layouts.app')

@section('title', 'Grade Submission - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.assignments.show', $submission->assignment) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Grade Submission</h4>
            <p class="text-muted mb-0">{{ $submission->assignment->title ?? '' }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2" style="border-radius:12px;">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Student Submission --}}
            <div class="card section-card mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person me-2 text-primary"></i>Student Submission</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                            <i class="bi bi-person text-primary" style="font-size:1.3rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $submission->user->name ?? 'N/A' }}</h5>
                            <small class="text-muted">{{ $submission->user->email ?? '' }}</small>
                        </div>
                        <div class="ms-auto text-end">
                            <small class="text-muted d-block">Submitted</small>
                            <small class="fw-semibold">{{ $submission->submitted_at?->format('M d, Y h:i A') ?? 'N/A' }}</small>
                        </div>
                    </div>

                    @if($submission->file_path)
                        <div class="mb-3">
                            <h6 class="fw-semibold text-muted small">ATTACHED FILE</h6>
                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-download me-2"></i>Download Submission File
                            </a>
                        </div>
                    @endif

                    @if($submission->submission_text)
                        <div>
                            <h6 class="fw-semibold text-muted small mb-2">SUBMISSION TEXT</h6>
                            <div class="bg-light rounded p-4" style="white-space:pre-line; font-size:0.95rem;">{{ $submission->submission_text }}</div>
                        </div>
                    @endif

                    @if(!$submission->file_path && !$submission->submission_text)
                        <div class="text-center py-4">
                            <i class="bi bi-file-x text-muted" style="font-size:2rem;"></i>
                            <p class="text-muted mt-2 mb-0">No submission content available.</p>
                        </div>
                    @endif

                    @if($submission->status == 'graded')
                        <hr>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <h6 class="fw-semibold text-success small">PREVIOUS GRADE</h6>
                            <div class="d-flex gap-4">
                                <div><strong>Grade:</strong> {{ $submission->grade }} / {{ $submission->assignment->max_marks }}</div>
                                @if($submission->feedback)
                                    <div><strong>Feedback:</strong> {{ $submission->feedback }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Grading Form --}}
            <div class="card section-card">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-check2-square me-2 text-success"></i>Grade Submission</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('teacher.assignments.grade', [$submission->assignment, $submission]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Grade (out of {{ $submission->assignment->max_marks }}) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="grade" class="form-control form-control-lg" value="{{ old('grade', $submission->grade ?? '') }}" min="0" max="{{ $submission->assignment->max_marks }}" required placeholder="0">
                                <span class="input-group-text bg-light fw-semibold">/ {{ $submission->assignment->max_marks }}</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Feedback</label>
                            <textarea name="feedback" class="form-control" rows="6" placeholder="Provide detailed feedback to the student...">{{ old('feedback', $submission->feedback ?? '') }}</textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg fw-semibold">
                                <i class="bi bi-check-circle me-2"></i>Submit Grade
                            </button>
                            <a href="{{ route('teacher.assignments.show', $submission->assignment) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
