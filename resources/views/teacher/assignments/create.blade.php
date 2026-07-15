@extends('layouts.app')

@section('title', 'Create Assignment - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Create Assignment</h4>
            <p class="text-muted mb-0">Set up a new assignment for your students</p>
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

    <form action="{{ route('teacher.assignments.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Assignment Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Build a Calculator App" required>
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
                                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Detailed assignment instructions..." required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-warning"></i>Settings</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Max Marks <span class="text-danger">*</span></label>
                            <input type="number" name="max_marks" class="form-control" value="{{ old('max_marks', '100') }}" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Allowed File Types</label>
                            <input type="text" name="allowed_file_types" class="form-control" value="{{ old('allowed_file_types', 'pdf,doc,docx,zip') }}" placeholder="e.g. pdf,doc,zip">
                            <div class="form-text">Comma separated extensions.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Max File Size (MB)</label>
                            <input type="number" name="max_file_size_mb" class="form-control" value="{{ old('max_file_size_mb', '10') }}" min="1" max="100">
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Publish Immediately</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-check-lg me-2"></i>Create Assignment
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
