@extends('layouts.app')

@section('title', 'Schedule Live Class - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.live-classes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Schedule Live Class</h4>
            <p class="text-muted mb-0">Set up a new live class session</p>
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

    <form action="{{ route('teacher.live-classes.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-camera-video me-2 text-primary"></i>Class Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Class Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Python Basics - Live Session" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Course <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select" required>
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Class description or agenda...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-warning"></i>Schedule Settings</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Duration (minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', '60') }}" min="15" max="240" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meeting Link <span class="text-danger">*</span></label>
                            <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meeting Password</label>
                            <input type="text" name="meeting_password" class="form-control" value="{{ old('meeting_password') }}" placeholder="Optional">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Max Students</label>
                            <input type="number" name="max_students" class="form-control" value="{{ old('max_students') }}" min="1" placeholder="Leave empty for unlimited">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-calendar-check me-2"></i>Schedule Class
                            </button>
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
@endsection
