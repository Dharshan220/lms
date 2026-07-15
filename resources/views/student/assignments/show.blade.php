@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .file-drop { border: 2px dashed #dee2e6; border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.2s; cursor: pointer; }
    .file-drop:hover, .file-drop.dragover { border-color: #0d6efd; background: #f8f9ff; }
    .grade-display { font-size: 2rem; font-weight: 700; }
</style>
@endpush

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('student.assignments.index') }}" class="text-decoration-none">Assignments</a></li>
            <li class="breadcrumb-item active">{{ $assignment->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $assignment->title }}</h4>
                            <span class="text-muted"><i class="bi bi-collection me-1"></i>{{ $assignment->course->title ?? 'N/A' }}</span>
                        </div>
                        @php
                            $sub = $submission;
                            $status = $sub ? ($sub->grade ? 'graded' : 'submitted') : 'pending';
                        @endphp
                        @if($status === 'graded')
                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i>Graded</span>
                        @elseif($status === 'submitted')
                            <span class="badge bg-info rounded-pill px-3 py-2 fs-6"><i class="bi bi-send me-1"></i>Submitted</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-6"><i class="bi bi-hourglass me-1"></i>Pending</span>
                        @endif
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <i class="bi bi-calendar-event text-primary fs-5"></i>
                                <div class="fw-bold mt-1">{{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'No deadline' }}</div>
                                <small class="text-muted">Due Date</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <i class="bi bi-award text-success fs-5"></i>
                                <div class="fw-bold mt-1">{{ $assignment->max_marks ?? 'N/A' }}</div>
                                <small class="text-muted">Max Marks</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <i class="bi bi-file-earmark text-warning fs-5"></i>
                                <div class="fw-bold mt-1">{{ $assignment->max_file_size_mb ?? 10 }} MB</div>
                                <small class="text-muted">Max File Size</small>
                            </div>
                        </div>
                    </div>

                    @if($assignment->allowed_file_types)
                        <div class="mb-3">
                            <strong class="small text-muted">ALLOWED FILE TYPES:</strong>
                            <div class="mt-1">
                                @foreach(explode(',', $assignment->allowed_file_types) as $type)
                                    <span class="badge bg-light text-dark me-1">{{ trim($type) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Description</h6>
                        <div class="text-muted">{!! nl2br(e($assignment->description)) !!}</div>
                    </div>
                </div>
            </div>

            @if($status === 'graded' && $submission)
                <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-success">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-clipboard-check me-2 text-success"></i>Grade & Feedback</h5>
                        <div class="text-center mb-4">
                            <div class="grade-display text-success">{{ $submission->grade }}<span class="text-muted fs-4">/{{ $assignment->max_marks }}</span></div>
                            <p class="text-muted">Graded on {{ $submission->graded_at ? $submission->graded_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        @if($submission->feedback)
                            <div class="bg-light rounded-3 p-4">
                                <h6 class="fw-bold mb-2"><i class="bi bi-chat-left-text me-1"></i> Feedback</h6>
                                <p class="text-muted mb-0">{{ $submission->feedback }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:80px;">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-upload me-2"></i>Submission</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    @if($status === 'submitted' || $status === 'graded')
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-check-circle text-success fs-3"></i>
                            </div>
                            <h6 class="fw-bold">Submitted</h6>
                            @if($submission && $submission->submitted_at)
                                <small class="text-muted">{{ $submission->submitted_at->format('M d, Y h:i A') }}</small>
                            @endif
                        </div>

                        @if($submission && $submission->file_path)
                            <div class="mb-3">
                                <strong class="small text-muted">SUBMITTED FILE:</strong>
                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="d-block text-decoration-none mt-1">
                                    <div class="bg-light rounded-3 p-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-file-earmark text-primary fs-4"></i>
                                        <span class="small text-truncate">{{ basename($submission->file_path) }}</span>
                                        <i class="bi bi-box-arrow-up-right ms-auto text-muted"></i>
                                    </div>
                                </a>
                            </div>
                        @endif

                        @if($submission && $submission->submission_text)
                            <div class="mb-3">
                                <strong class="small text-muted">SUBMITTED TEXT:</strong>
                                <div class="bg-light rounded-3 p-3 mt-1 text-muted small">{{ Str::limit($submission->submission_text, 200) }}</div>
                            </div>
                        @endif

                        @if($status === 'submitted')
                            <div class="alert alert-info mb-0 small">
                                <i class="bi bi-info-circle me-1"></i> Waiting for teacher to grade your submission.
                            </div>
                        @endif
                    @else
                        <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">UPLOAD FILE</label>
                                <div class="file-drop" id="fileDrop" onclick="document.getElementById('fileInput').click();">
                                    <i class="bi bi-cloud-arrow-up text-primary" style="font-size:2.5rem;"></i>
                                    <p class="mb-1 mt-2 text-muted">Click or drag file here to upload</p>
                                    <small class="text-muted">Max: {{ $assignment->max_file_size_mb ?? 10 }} MB</small>
                                    <input type="file" name="file" id="fileInput" class="d-none">
                                </div>
                                <div id="fileName" class="mt-2 text-success small fw-bold" style="display:none;"></div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">TEXT SUBMISSION (Optional)</label>
                                <textarea name="submission_text" class="form-control rounded-3" rows="5" placeholder="Type your answer here...">{{ old('submission_text') }}</textarea>
                                @error('submission_text')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold" onclick="return confirm('Submit this assignment?')">
                                <i class="bi bi-send me-1"></i> Submit Assignment
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const fileDrop = document.getElementById('fileDrop');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');

    if (fileDrop && fileInput) {
        ['dragenter','dragover'].forEach(e => fileDrop.addEventListener(e, () => fileDrop.classList.add('dragover')));
        ['dragleave','drop'].forEach(e => fileDrop.addEventListener(e, () => fileDrop.classList.remove('dragover')));
        fileDrop.addEventListener('drop', e => { e.preventDefault(); fileInput.files = e.dataTransfer.files; showFileName(); });
        fileInput.addEventListener('change', showFileName);
    }

    function showFileName() {
        if (fileInput.files.length > 0) { fileName.textContent = 'Selected: ' + fileInput.files[0].name; fileName.style.display = 'block'; }
    }
</script>
@endpush
