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
            <h1 class="h3 mb-1" style="color: var(--text-primary);">{{ $school->name }}</h1>
            <p style="color: var(--text-secondary); margin:0;">
                <i class="bi bi-geo-alt me-1"></i>{{ $school->city }}, {{ $school->state }}
                @if($school->is_active) <span class="badge bg-success ms-2">Active</span> @else <span class="badge bg-danger ms-2">Inactive</span> @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body text-center p-3">
                    <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                    <div class="fw-bold fs-4 mt-1">{{ $school->users_count ?? 0 }}</div>
                    <small class="text-muted">Users</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body text-center p-3">
                    <i class="bi bi-book text-success" style="font-size: 1.5rem;"></i>
                    <div class="fw-bold fs-4 mt-1">{{ $school->courses_count ?? 0 }}</div>
                    <small class="text-muted">Courses</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body text-center p-3">
                    <i class="bi bi-person-workspace text-info" style="font-size: 1.5rem;"></i>
                    <div class="fw-bold fs-4 mt-1">{{ $teachers->count() }}</div>
                    <small class="text-muted">Teachers</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body text-center p-3">
                    <i class="bi bi-mortarboard text-warning" style="font-size: 1.5rem;"></i>
                    <div class="fw-bold fs-4 mt-1">{{ $students->count() }}</div>
                    <small class="text-muted">Students</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom"><h5 class="mb-0 fw-semibold">Teachers</h5></div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        @forelse($teachers as $t)
                            <div class="list-group-item d-flex align-items-center py-3">
                                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3"><i class="bi bi-person text-success"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $t->name }}</div>
                                    <small class="text-muted">{{ $t->email }}</small>
                                </div>
                                <a href="{{ route('admin.teachers.show', $t) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No teachers</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom"><h5 class="mb-0 fw-semibold">Students</h5></div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        @forelse($students as $s)
                            <div class="list-group-item d-flex align-items-center py-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3"><i class="bi bi-person text-primary"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $s->name }}</div>
                                    <small class="text-muted">{{ $s->email }}</small>
                                </div>
                                <a href="{{ route('admin.students.show', $s) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">No students</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
        <div class="card-header bg-white border-bottom"><h5 class="mb-0 fw-semibold">School Details</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><strong>Email:</strong> {{ $school->email ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Phone:</strong> {{ $school->phone ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Address:</strong> {{ $school->address ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Pincode:</strong> {{ $school->pincode ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Contact Person:</strong> {{ $school->contact_person_name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Contact Phone:</strong> {{ $school->contact_person_phone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
