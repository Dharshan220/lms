@extends('layouts.app')

@section('title', $assignment->title . ' - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-1">{{ $assignment->title }}</h4>
            <p class="text-muted mb-0">{{ $assignment->course->title ?? '' }}</p>
        </div>
    </div>

    {{-- Assignment Info --}}
    <div class="card section-card mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-8">
                    <h6 class="fw-bold text-muted small mb-2">DESCRIPTION</h6>
                    <div class="bg-light rounded p-3" style="white-space:pre-line;">{{ $assignment->description }}</div>
                </div>
                <div class="col-md-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Due Date</div>
                                <div class="fw-bold {{ $assignment->due_date->isPast() ? 'text-danger' : '' }}" style="font-size:0.9rem;">
                                    {{ $assignment->due_date->format('M d, Y') }}
                                    <br>{{ $assignment->due_date->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Max Marks</div>
                                <div class="fw-bold text-primary">{{ $assignment->max_marks }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">File Types</div>
                                <div class="fw-bold" style="font-size:0.8rem;">{{ $assignment->allowed_file_types ?? 'Any' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="text-muted small">Max Size</div>
                                <div class="fw-bold">{{ $assignment->max_file_size_mb ?? 10 }}MB</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submissions --}}
    <div class="card section-card">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-inbox me-2 text-success"></i>Submissions ({{ $assignment->submissions->count() }})
            </h5>
            <a href="{{ route('teacher.assignments.submissions', $assignment) }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-4">
            @if($assignment->submissions->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Student</th>
                                <th class="fw-semibold">Submitted</th>
                                <th class="fw-semibold">Grade</th>
                                <th class="fw-semibold">Status</th>
                                <th class="fw-semibold text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignment->submissions as $submission)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $submission->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $submission->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $submission->submitted_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($submission->grade !== null)
                                            <span class="fw-bold text-primary">{{ $submission->grade }} / {{ $assignment->max_marks }}</span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->status == 'graded')
                                            <span class="badge bg-success">Graded</span>
                                        @elseif($submission->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($submission->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('teacher.assignments.grade', $submission) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-check2-square me-1"></i>Grade
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size:2.5rem;"></i>
                    <p class="text-muted mt-2 mb-0">No submissions yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
