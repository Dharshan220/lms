@extends('layouts.app')

@section('title', 'Pending Reviews - Nano Spark LMS')

@section('content')
<div style="max-width:1400px" x-data="reviewManager()">
    <div class="ns-page-header animate-fadeIn">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="ns-page-title">Pending Reviews</h1>
                <p class="ns-page-subtitle">Review and grade student submissions</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="ns-badge warning" style="font-size:14px;padding:6px 14px">
                    <i class="bi bi-clock-history me-1"></i>{{ ($submissions ?? collect())->count() }} pending
                </span>
            </div>
        </div>
    </div>

    <div class="ns-card mb-4">
        <div class="ns-card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5 col-lg-4">
                    <label class="ns-form-label">Search</label>
                    <div class="ns-input-icon">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="ns-input" placeholder="Search by student or assignment..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label class="ns-form-label">Course</label>
                    <select name="course_id" class="ns-select">
                        <option value="">All Courses</option>
                        @foreach($courses ?? [] as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ Str::limit($course->title, 30) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-2 d-flex gap-2">
                    <button type="submit" class="ns-btn ns-btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('teacher.submissions.index') }}" class="ns-btn ns-btn-ghost">Clear</a>
                </div>
            </form>
        </div>
    </div>

    @forelse($submissions ?? collect() as $submission)
        <div class="ns-card mb-3 animate-fadeIn" style="animation-delay:{{ $loop->index * 50 }}ms">
            <div style="padding:24px">
                <div class="d-flex align-items-start gap-4 flex-wrap">
                    <div class="ns-user-avatar" style="width:48px;height:48px;font-size:16px;flex-shrink:0">
                        {{ strtoupper(substr($submission->user->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <h6 style="font-family:var(--font-heading);font-weight:700;color:var(--text-primary);margin:0">{{ $submission->user->name ?? 'Unknown Student' }}</h6>
                            <span class="ns-badge" style="background:rgba(59,130,246,0.12);color:#3B82F6">{{ $submission->user->email ?? '' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-2" style="color:var(--text-muted);font-size:13px">
                            <span><i class="bi bi-journal-text me-1"></i>{{ $submission->assignment->title ?? 'Assignment' }}</span>
                            <span><i class="bi bi-book me-1"></i>{{ $submission->assignment->course->title ?? '' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3" style="font-size:13px">
                            <span style="color:var(--text-muted)"><i class="bi bi-calendar3 me-1"></i>Submitted {{ $submission->submitted_at?->format('M d, Y \a\t h:i A') ?? 'N/A' }}</span>
                            @if($submission->file_path)
                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="ns-btn ns-btn-ghost ns-btn-sm" style="margin:0">
                                    <i class="bi bi-download me-1"></i>File
                                </a>
                            @endif
                            @if($submission->submission_text)
                                <span class="ns-badge" style="background:rgba(255,255,255,0.06);color:var(--text-muted)">
                                    <i class="bi bi-chat-text me-1"></i>Text submission
                                </span>
                            @endif
                        </div>

                        @if($submission->submission_text)
                            <div style="background:var(--bg-elevated);border-radius:8px;padding:14px 16px;margin-bottom:16px;border:1px solid var(--border-subtle)">
                                <p style="color:var(--text-secondary);font-size:13px;margin:0;line-height:1.6">{{ Str::limit($submission->submission_text, 300) }}</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('teacher.submissions.grade', $submission) }}" class="d-flex align-items-end gap-3 flex-wrap">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="ns-form-label">Grade ({{ $submission->assignment->max_marks ?? 100 }} max)</label>
                                <input type="number" name="grade" class="ns-input" style="width:100px" min="0" max="{{ $submission->assignment->max_marks ?? 100 }}" value="{{ $submission->grade ?? '' }}" placeholder="0">
                            </div>
                            <div class="flex-grow-1">
                                <label class="ns-form-label">Feedback</label>
                                <input type="text" name="feedback" class="ns-input" placeholder="Optional feedback for the student..." value="{{ $submission->feedback ?? '' }}">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="status" value="approved" class="ns-btn ns-btn-sm" style="background:rgba(0,210,106,0.12);color:#00D26A;border:1px solid rgba(0,210,106,0.2)">
                                    <i class="bi bi-check-circle me-1"></i>Approve
                                </button>
                                <button type="submit" name="status" value="rejected" class="ns-btn ns-btn-sm" style="background:rgba(255,77,79,0.12);color:#FF4D4F;border:1px solid rgba(255,77,79,0.2)">
                                    <i class="bi bi-x-circle me-1"></i>Reject
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex flex-column align-items-end gap-2" style="flex-shrink:0">
                        @if($submission->status == 'graded' || $submission->status == 'approved')
                            <span class="ns-badge success"><i class="bi bi-check-circle me-1"></i>Reviewed</span>
                        @elseif($submission->status == 'rejected')
                            <span class="ns-badge" style="background:rgba(255,77,79,0.12);color:#FF4D4F"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                        @else
                            <span class="ns-badge warning"><i class="bi bi-clock me-1"></i>Pending</span>
                        @endif
                        @if($submission->grade !== null)
                            <span style="font-family:var(--font-heading);font-size:18px;font-weight:700;color:var(--accent-primary)">{{ $submission->grade }}<span style="font-size:12px;color:var(--text-muted)">/{{ $submission->assignment->max_marks ?? 100 }}</span></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="ns-card">
            <div class="text-center" style="padding:60px 20px">
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(0,210,106,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
                    <i class="bi bi-check-circle" style="font-size:2.5rem;color:#00D26A"></i>
                </div>
                <h5 style="font-family:var(--font-heading);color:var(--text-primary);margin-bottom:8px">All caught up!</h5>
                <p style="color:var(--text-muted);max-width:400px;margin:0 auto">{{ request()->hasAny(['search', 'course_id']) ? 'No submissions match your filters.' : 'No pending submissions to review right now.' }}</p>
            </div>
        </div>
    @endforelse

    @if(($submissions ?? collect())->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $submissions->withQueryString()->links() }}
        </div>
    @endif
</div>

<style>
.ns-form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
.ns-input, .ns-select {
    width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-subtle);
    background: var(--bg-elevated); color: var(--text-primary); font-family: var(--font-body);
    font-size: 14px; outline: none; transition: border-color 0.2s;
}
.ns-input:focus, .ns-select:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(255,212,0,0.1); }
.ns-input::placeholder { color: var(--text-muted); opacity: 0.6; }
.ns-select option { background: #121212; color: var(--text-primary); }
.ns-input-icon { position: relative; }
.ns-input-icon i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px; pointer-events: none; }
.ns-input-icon .ns-input { padding-left: 36px; }
.min-width-0 { min-width: 0; }
</style>
@endsection
