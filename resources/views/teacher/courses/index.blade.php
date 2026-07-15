@extends('layouts.app')

@section('title', 'My Courses - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Courses</h4>
            <p class="text-muted mb-0">Manage and organize your courses</p>
        </div>
        <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary fw-semibold">
            <i class="bi bi-plus-lg me-2"></i>Add Course
        </a>
    </div>

    {{-- Filters --}}
    <div class="card section-card mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label small fw-semibold text-muted">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4 col-lg-3">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    @if($courses->count())
        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card course-card h-100">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="{{ $course->title }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="height:160px;background:linear-gradient(135deg,#667eea,#764ba2);">
                                <i class="bi bi-book text-white" style="font-size:2.5rem;"></i>
                            </div>
                        @endif
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title fw-bold mb-0" style="font-size:0.92rem;">{{ Str::limit($course->title, 35) }}</h6>
                                @if($course->is_published)
                                    <span class="badge bg-success bg-opacity-10 text-success flex-shrink-0 ms-2">Published</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary flex-shrink-0 ms-2">Draft</span>
                                @endif
                            </div>
                            @if($course->category)
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $course->category->name }}</span>
                            @endif
                            <div class="d-flex justify-content-between text-muted" style="font-size:0.8rem;">
                                <span><i class="bi bi-people me-1"></i>{{ $course->enrollments_count ?? $course->enrollments()->count() }} students</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $course->duration_hours }}h</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 pb-3 pt-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>View & Manage
                                </a>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="{{ route('teacher.courses.show', $course) }}#lessons" class="btn btn-outline-secondary">
                                        <i class="bi bi-list-ul"></i> Lessons
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $courses->withQueryString()->links() }}
        </div>
    @else
        <div class="card section-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-book text-muted" style="font-size:3rem;"></i>
                <h5 class="mt-3 text-muted">No courses found</h5>
                <p class="text-muted">{{ request()->hasAny(['search', 'status']) ? 'Try adjusting your filters.' : 'Create your first course to get started.' }}</p>
                @if(!request()->hasAny(['search', 'status']))
                    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Create Course
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .section-card, .course-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .course-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: all 0.2s; }
</style>
@endsection
