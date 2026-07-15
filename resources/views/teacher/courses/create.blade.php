@extends('layouts.app')

@section('title', 'Create Course - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.courses.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Create New Course</h4>
            <p class="text-muted mb-0">Fill in the details to create a new course</p>
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

    <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Course Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Course Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Introduction to Python Programming" required>
                        </div>
                        <div class="mb-3">
                            <label for="short_description" class="form-label fw-semibold">Short Description</label>
                            <input type="text" name="short_description" id="short_description" class="form-control" value="{{ old('short_description') }}" placeholder="Brief course summary (max 500 chars)" maxlength="500">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Full Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="6" placeholder="Detailed course description..." required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card section-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-image me-2 text-success"></i>Course Thumbnail</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label fw-semibold">Thumbnail Image</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                            <div class="form-text">Recommended: 1280x720px. Max 2MB.</div>
                        </div>
                        <div id="thumbnail-preview" class="text-center" style="display:none;">
                            <img id="preview-img" class="img-fluid rounded" style="max-height:200px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card section-card mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-warning"></i>Settings</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                            <select name="level" id="level" class="form-select" required>
                                <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="duration_hours" class="form-label fw-semibold">Duration (Hours) <span class="text-danger">*</span></label>
                            <input type="number" name="duration_hours" id="duration_hours" class="form-control" value="{{ old('duration_hours', '10') }}" min="0" step="0.5" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label fw-semibold">Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', '0') }}" min="0" step="0.01" required>
                            <div class="form-text">Set to 0 for free courses.</div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_published">Publish Immediately</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-check-lg me-2"></i>Create Course
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

<script>
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('preview-img').src = ev.target.result;
            document.getElementById('thumbnail-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
