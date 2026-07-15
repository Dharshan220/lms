@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manage Courses</h1>
                <p class="text-muted mt-1 mb-0">View and manage all courses</p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Course
            </a>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">All</option>
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Level</label>
                        <select name="level" class="form-select">
                            <option value="">All</option>
                            @foreach(['beginner', 'intermediate', 'advanced'] as $lvl)
                                <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel me-1"></i> Filter</button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Students</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses ?? [] as $course)
                                <tr>
                                    <td class="ps-3">{{ $course->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($course->thumbnail)
                                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-book text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($course->title, 40) }}</div>
                                                @if($course->is_featured)
                                                    <small class="text-warning"><i class="bi bi-star-fill"></i> Featured</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                    <td>{{ $course->category->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($course->level) }}</span></td>
                                    <td>
                                        @if($course->is_published)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $course->enrollment_count ?? 0 }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $course->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $course->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $course->title }}</strong>?
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-book" style="font-size: 2.5rem;"></i>
                                        <p class="mt-3">No courses found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($courses) && $courses->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
