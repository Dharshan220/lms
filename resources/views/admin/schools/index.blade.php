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
                <h1 class="text-2xl font-bold text-gray-900">Manage Schools</h1>
                <p class="text-muted mt-1 mb-0">View and manage all registered schools</p>
            </div>
            <a href="{{ route('admin.schools.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add School
            </a>
        </div>

        {{-- Search --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.schools.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Search Schools</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, city, or email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search me-1"></i> Search</button>
                        <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">Clear</a>
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
                                <th>City</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools ?? [] as $school)
                                <tr>
                                    <td class="ps-3">{{ $school->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $school->name }}</div>
                                        <small class="text-muted">{{ Str::limit($school->address, 40) }}</small>
                                    </td>
                                    <td>{{ $school->city }}</td>
                                    <td>{{ $school->phone }}</td>
                                    <td>{{ $school->email }}</td>
                                    <td>
                                        @if($school->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $school->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Delete Modal --}}
                                <div class="modal fade" id="deleteModal{{ $school->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $school->name }}</strong>? This action cannot be undone.
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" class="d-inline">
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
                                        <i class="bi bi-building" style="font-size: 2.5rem;"></i>
                                        <p class="mt-3">No schools found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($schools) && $schools->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $schools->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
