@extends('layouts.app')

@section('title', 'My Assignments - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Assignments</h4>
            <p class="text-muted mb-0">Manage assignments and grade submissions</p>
        </div>
        <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary fw-semibold">
            <i class="bi bi-plus-lg me-2"></i>Create Assignment
        </a>
    </div>

    {{-- Search --}}
    <div class="card section-card mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search assignments..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>
        </div>
    </div>

    @if($assignments->count())
        <div class="card section-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold ps-4">Title</th>
                                <th class="fw-semibold">Course</th>
                                <th class="fw-semibold">Due Date</th>
                                <th class="fw-semibold">Submissions</th>
                                <th class="fw-semibold text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded bg-warning bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;">
                                                <i class="bi bi-file-earmark-text text-warning"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $assignment->title }}</div>
                                                <small class="text-muted">Max: {{ $assignment->max_marks }} marks</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-muted">{{ $assignment->course->title ?? 'N/A' }}</span></td>
                                    <td>
                                        <span class="{{ $assignment->due_date->isPast() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                            {{ $assignment->due_date->format('M d, Y') }}
                                            <br><small>{{ $assignment->due_date->format('h:i A') }}</small>
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $subCount = $assignment->submissions->count();
                                            $pendingCount = $assignment->submissions->where('status', 'pending')->count();
                                        @endphp
                                        <span class="badge bg-primary">{{ $subCount }} total</span>
                                        @if($pendingCount > 0)
                                            <span class="badge bg-warning text-dark">{{ $pendingCount }} pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('teacher.assignments.submissions', $assignment) }}" class="btn btn-outline-success" title="Submissions"><i class="bi bi-inbox"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $assignments->withQueryString()->links() }}
        </div>
    @else
        <div class="card section-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-text text-muted" style="font-size:3rem;"></i>
                <h5 class="mt-3 text-muted">No assignments found</h5>
                <p class="text-muted">{{ request('search') ? 'Try a different search.' : 'Create your first assignment to get started.' }}</p>
                @if(!request('search'))
                    <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Create Assignment
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
