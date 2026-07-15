@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Announcement</h1>
                <p class="text-muted mt-1 mb-0">Update announcement details</p>
            </div>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Announcements
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $announcement->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="content" class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" rows="6"
                                      class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="priority" class="form-label fw-semibold">Priority</label>
                            <select name="priority" id="priority" class="form-select">
                                @foreach(['low', 'medium', 'high'] as $p)
                                    <option value="{{ $p }}" {{ old('priority', $announcement->priority) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="school_id" class="form-label fw-semibold">School <small class="text-muted">(optional)</small></label>
                            <select name="school_id" id="school_id" class="form-select">
                                <option value="">All Schools</option>
                                @foreach($schools ?? [] as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id', $announcement->school_id) == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="course_id" class="form-label fw-semibold">Course <small class="text-muted">(optional)</small></label>
                            <select name="course_id" id="course_id" class="form-select">
                                <option value="">All Courses</option>
                                @foreach($courses ?? [] as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $announcement->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_global" id="is_global" value="1"
                                       {{ old('is_global', $announcement->is_global) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_global">Global announcement</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Announcement
                                </button>
                                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
