@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .assignment-row { transition: background 0.2s; }
    .assignment-row:hover { background: #f8f9fa; }
</style>
@endpush

<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-file-earmark-text me-2 text-warning"></i>My Assignments</h2>
        <p class="text-muted mb-0">Track and submit your course assignments</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:45px;height:45px;">
                        <i class="bi bi-clock text-info fs-5"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $assignments->total() }}</h4>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:45px;height:45px;">
                        <i class="bi bi-hourglass-split text-warning fs-5"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $assignments->filter(fn($a) => $a->submissions->isEmpty())->count() }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:45px;height:45px;">
                        <i class="bi bi-send text-primary fs-5"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $assignments->filter(fn($a) => $a->submissions->isNotEmpty() && !$a->submissions->first()->grade)->count() }}</h4>
                    <small class="text-muted">Submitted</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:45px;height:45px;">
                        <i class="bi bi-check-circle text-success fs-5"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $assignments->filter(fn($a) => $a->submissions->first()->grade ?? false)->count() }}</h4>
                    <small class="text-muted">Graded</small>
                </div>
            </div>
        </div>
    </div>

    @if($assignments->count())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 fw-bold">Assignment</th>
                                <th class="py-3 fw-bold">Course</th>
                                <th class="py-3 fw-bold">Due Date</th>
                                <th class="py-3 fw-bold">Status</th>
                                <th class="py-3 fw-bold">Grade</th>
                                <th class="py-3 fw-bold text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $status = $submission ? ($submission->grade ? 'graded' : 'submitted') : 'pending';
                                    $isOverdue = $assignment->due_date && $assignment->due_date->isPast() && !$submission;
                                @endphp
                                <tr class="assignment-row">
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $assignment->title }}</div>
                                        @if($assignment->max_marks)
                                            <small class="text-muted">Max: {{ $assignment->max_marks }} marks</small>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ $assignment->course->title ?? 'N/A' }}</small></td>
                                    <td>
                                        @if($assignment->due_date)
                                            <div class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">{{ $assignment->due_date->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $assignment->due_date->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">No deadline</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($status === 'graded')
                                            <span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-circle me-1"></i>Graded</span>
                                        @elseif($status === 'submitted')
                                            <span class="badge bg-info rounded-pill px-3"><i class="bi bi-send me-1"></i>Submitted</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-3"><i class="bi bi-hourglass me-1"></i>Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($status === 'graded')
                                            <span class="fw-bold text-success">{{ $submission->grade }}/{{ $assignment->max_marks }}</span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('student.assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $assignments->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-x text-muted" style="font-size:4rem;"></i>
            <h5 class="text-muted mt-3">No assignments found</h5>
            <p class="text-muted">Assignments from your enrolled courses will appear here.</p>
            <a href="{{ route('student.courses.my') }}" class="btn btn-primary rounded-pill">
                <i class="bi bi-collection-play me-1"></i> My Courses
            </a>
        </div>
    @endif
</div>
@endsection
