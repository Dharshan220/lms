@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Course</h1>
                <p class="text-muted mt-1 mb-0">Create a new course in the system</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Courses
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

        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">Course Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="title" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="short_description" class="form-label fw-semibold">Short Description</label>
                                    <input type="text" name="short_description" id="short_description"
                                           class="form-control @error('short_description') is-invalid @enderror"
                                           value="{{ old('short_description') }}" maxlength="255">
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">Description</label>
                                    <textarea name="description" id="description" rows="6"
                                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-semibold">Category</label>
                                <select name="category_id" id="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories ?? [] as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="teacher_id" class="form-label fw-semibold">Teacher <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher_id"
                                        class="form-select @error('teacher_id') is-invalid @enderror" required>
                                    <option value="">-- Select Teacher --</option>
                                    @foreach($teachers ?? [] as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="school_id" class="form-label fw-semibold">School <small class="text-muted">(optional)</small></label>
                                <select name="school_id" id="school_id"
                                        class="form-select @error('school_id') is-invalid @enderror">
                                    <option value="">-- Select School --</option>
                                    @foreach($schools ?? [] as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                                <select name="level" id="level"
                                        class="form-select @error('level') is-invalid @enderror" required>
                                    <option value="">-- Select Level --</option>
                                    @foreach(['beginner', 'intermediate', 'advanced'] as $lvl)
                                        <option value="{{ $lvl }}" {{ old('level') == $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="duration_hours" class="form-label fw-semibold">Duration (Hours)</label>
                                <input type="number" name="duration_hours" id="duration_hours"
                                       class="form-control @error('duration_hours') is-invalid @enderror"
                                       value="{{ old('duration_hours') }}" step="0.5" min="0">
                                @error('duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label fw-semibold">Price ($)</label>
                                <input type="number" name="price" id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', '0.00') }}" step="0.01" min="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_featured">Featured Course</label>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                                       {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_published">Published</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-circle me-1"></i> Create Course
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
