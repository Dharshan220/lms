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
                <h1 class="text-2xl font-bold text-gray-900">Manage Students</h1>
                <p class="text-muted mt-1 mb-0">View and manage all student accounts</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.students.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">School</label>
                        <select name="school_id" class="form-select">
                            <option value="">All Schools</option>
                            @foreach($schools ?? [] as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Grade</label>
                        <select name="grade" class="form-select">
                            <option value="">All Grades</option>
                            @foreach($grades ?? [] as $grade)
                                <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
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
                                <th>Grade</th>
                                <th>XP</th>
                                <th>Level</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students ?? [] as $student)
                                <tr>
                                    <td class="ps-3">{{ $student->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                                <i class="bi bi-person text-primary small"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $student->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->school->name ?? 'N/A' }}</td>
                                    <td>{{ $student->grade ?? 'N/A' }}</td>
                                    <td>
                                        <span class="fw-semibold text-warning">{{ $student->xp_points ?? 0 }}</span>
                                        <small class="text-muted">XP</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">Level {{ $student->level ?? 1 }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete student <strong>{{ $student->name }}</strong>?
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline">
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
                                        <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                                        <p class="mt-3">No students found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($students) && $students->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $students->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
