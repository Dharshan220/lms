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
                <h1 class="text-2xl font-bold text-gray-900">Manage Teachers</h1>
                <p class="text-muted mt-1 mb-0">View and manage all teachers</p>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add Teacher
            </a>
        </div>

        {{-- Search --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.teachers.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Search Teachers</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or school..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i> Search</button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Clear</a>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>School</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers ?? [] as $teacher)
                                <tr>
                                    <td class="ps-3">{{ $teacher->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                                <i class="bi bi-person-workspace text-success small"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $teacher->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>{{ $teacher->school->name ?? 'N/A' }}</td>
                                    <td>{{ $teacher->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($teacher->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $teacher->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $teacher->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete teacher <strong>{{ $teacher->name }}</strong>?
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="d-inline">
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
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-workspace" style="font-size: 2.5rem;"></i>
                                        <p class="mt-3">No teachers found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($teachers) && $teachers->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $teachers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
