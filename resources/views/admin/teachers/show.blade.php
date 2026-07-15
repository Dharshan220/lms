@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">{{ $teacher->name }}</h1>
            <p style="color: var(--text-secondary); margin:0;">
                <i class="bi bi-envelope me-1"></i>{{ $teacher->email }}
                @if($teacher->school) &middot; <i class="bi bi-building me-1"></i>{{ $teacher->school->name }} @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-workspace text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="mb-1">{{ $teacher->name }}</h5>
                    <p class="text-muted mb-3">{{ $teacher->email }}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="text-center">
                            <div class="fw-bold fs-4" style="color: var(--ns-primary);">{{ $courses->total() }}</div>
                            <small class="text-muted">Courses</small>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold fs-4" style="color: var(--ns-accent);">{{ $totalStudents }}</div>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-book me-2"></i>Courses</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Title</th><th>Category</th><th>Students</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <td class="fw-semibold">{{ $course->title }}</td>
                                        <td><span class="badge bg-info">{{ $course->category->name ?? 'N/A' }}</span></td>
                                        <td>{{ $course->enrollment_count ?? 0 }}</td>
                                        <td>
                                            @if($course->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No courses yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $courses->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
