@extends('layouts.app')

@section('title', 'Edit Lesson - ' . $course->title)

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}">{{ Str::limit($course->title, 30) }}</a></li>
                <li class="breadcrumb-item active">Edit Lesson</li>
            </ol>
        </nav>
    </div>

    <div class="card section-card" style="max-width:700px;">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Edit Lesson</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('teacher.lessons.update', [$course, $lesson]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Lesson Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $lesson->title) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Content Type</label>
                        <select name="content_type" class="form-select">
                            <option value="video" {{ $lesson->content_type === 'video' ? 'selected' : '' }}>Video</option>
                            <option value="document" {{ $lesson->content_type === 'document' ? 'selected' : '' }}>Document</option>
                            <option value="text" {{ $lesson->content_type === 'text' ? 'selected' : '' }}>Text</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $lesson->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Video URL</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $lesson->video_url) }}" placeholder="https://...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Order</label>
                        <input type="number" name="order_number" class="form-control" required min="1" value="{{ old('order_number', $lesson->order_number) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Duration (min)</label>
                        <input type="number" name="duration_minutes" class="form-control" required min="1" value="{{ old('duration_minutes', $lesson->duration_minutes) }}">
                    </div>
                    <div class="col-12 d-flex gap-4">
                        <div class="form-check">
                            <input type="hidden" name="is_free" value="0">
                            <input type="checkbox" name="is_free" value="1" class="form-check-input" id="isFree" {{ $lesson->is_free ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFree">Free Lesson</label>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="isPublished" {{ $lesson->is_published ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublished">Published</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
