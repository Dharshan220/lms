@extends('layouts.app')

@section('title', 'Submissions - ' . $assignment->title . ' - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">Submissions</h4>
            <p class="text-muted mb-0">{{ $assignment->title }} &middot; {{ $assignment->course->title ?? '' }}</p>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body p-4">
            @if($assignment->submissions->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">#</th>
                                <th class="fw-semibold">Student</th>
                                <th class="fw-semibold">Submitted</th>
                                <th class="fw-semibold">File</th>
                                <th class="fw-semibold">Grade</th>
                                <th class="fw-semibold">Feedback</th>
                                <th class="fw-semibold">Status</th>
                                <th class="fw-semibold text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignment->submissions as $idx => $submission)
                                <tr>
                                    <td class="text-muted">{{ $idx + 1 }}</td>
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
                                    <td><span class="text-muted">{{ $submission->submitted_at?->format('M d, Y h:i A') ?? 'N/A' }}</span></td>
                                    <td>
                                        @if($submission->file_path)
                                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-download"></i> File
                                            </a>
                                        @elseif($submission->submission_text)
                                            <span class="text-muted fst-italic" style="font-size:0.85rem;">{{ Str::limit($submission->submission_text, 50) }}</span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->grade !== null)
                                            <span class="fw-bold text-primary">{{ $submission->grade }} / {{ $assignment->max_marks }}</span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->feedback)
                                            <span class="text-muted" style="font-size:0.85rem;" title="{{ $submission->feedback }}">{{ Str::limit($submission->feedback, 30) }}</span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->status == 'graded')
                                            <span class="badge bg-success">Graded</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('teacher.assignments.grade', $submission) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-check2-square me-1"></i>Grade
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                    <h5 class="mt-3 text-muted">No submissions yet</h5>
                    <p class="text-muted">Students haven't submitted this assignment yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
