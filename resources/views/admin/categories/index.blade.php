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
                <h1 class="text-2xl font-bold text-gray-900">Manage Categories</h1>
                <p class="text-muted mt-1 mb-0">Organize courses by category</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Category
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th>Parent</th>
                                <th>Courses</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories ?? [] as $category)
                                <tr>
                                    <td class="ps-3">{{ $category->id }}</td>
                                    <td>
                                        <div class="rounded d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: {{ $category->color ?? '#6c757d' }}20;">
                                            <i class="bi bi-{{ $category->icon ?? 'folder' }}" style="color: {{ $category->color ?? '#6c757d' }};"></i>
                                        </div>
                                    </td>
                                    <td class="fw-semibold">{{ $category->name }}</td>
                                    <td>
                                        <span class="badge" style="background: {{ $category->color ?? '#6c757d' }};">{{ $category->color ?? '#6c757d' }}</span>
                                    </td>
                                    <td>{{ $category->parent->name ?? '--' }}</td>
                                    <td>{{ $category->courses_count ?? $category->courses->count() }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $category->name }}</strong>?
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
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
                                        <i class="bi bi-tags" style="font-size: 2.5rem;"></i>
                                        <p class="mt-3">No categories found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
