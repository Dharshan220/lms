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
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                @if($category->icon) <i class="bi bi-{{ $category->icon }} me-2"></i> @endif
                {{ $category->name }}
            </h1>
            <p style="color: var(--text-secondary); margin:0;">
                {{ $category->courses_count ?? 0 }} courses &middot; {{ $category->children_count ?? 0 }} subcategories
                @if($category->is_active) <span class="badge bg-success ms-2">Active</span> @else <span class="badge bg-danger ms-2">Inactive</span> @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    @if($category->description)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <p style="color: var(--text-secondary);">{{ $category->description }}</p>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-book me-2"></i>Courses in this Category</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Title</th><th>Teacher</th><th>Students</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td class="fw-semibold">{{ $course->title }}</td>
                                <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $course->enrollment_count ?? 0 }}</td>
                                <td>
                                    @if($course->is_published) <span class="badge bg-success">Published</span>
                                    @else <span class="badge bg-secondary">Draft</span> @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No courses in this category yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $courses->links() }}</div>
        </div>
    </div>

    @if($category->children && $category->children->count() > 0)
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom"><h5 class="mb-0 fw-semibold">Subcategories</h5></div>
            <div class="list-group list-group-flush">
                @foreach($category->children as $child)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ $child->name }}</span>
                        <span class="badge bg-info">{{ $child->courses_count ?? 0 }} courses</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
