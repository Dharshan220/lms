@extends('layouts.app')

@section('title', 'AI Assignment Generator - Nano Spark')

@section('content')
@push('styles')
<style>
    .generate-btn { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
    .generate-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
    .assignment-display { white-space: pre-line; line-height: 1.8; }
    .assignment-display h1,.assignment-display h2,.assignment-display h3 { color: #333; font-weight: 700; }
    .assignment-section { border-left: 3px solid #667eea; padding-left: 16px; margin-bottom: 16px; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
            <i class="bi bi-file-earmark-text text-success" style="font-size:1.4rem;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1">AI Assignment Generator</h4>
            <p class="text-muted mb-0">Create comprehensive assignments powered by AI</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Input Form --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Assignment Details</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ai.assignment-generator.generate') }}" method="POST" id="assignmentForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Topic / Subject <span class="text-danger">*</span></label>
                            <input type="text" name="topic" class="form-control" value="{{ old('topic') }}"
                                placeholder="e.g. Data Structures, Environmental Science" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                            <select name="difficulty" class="form-select">
                                <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty', 'intermediate') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Assignment Type</label>
                            <select name="assignment_type" class="form-select">
                                <option value="theory" {{ old('assignment_type') == 'theory' ? 'selected' : '' }}>Theory</option>
                                <option value="coding" {{ old('assignment_type') == 'coding' ? 'selected' : '' }}>Coding</option>
                                <option value="project" {{ old('assignment_type') == 'project' ? 'selected' : '' }}>Project</option>
                                <option value="mixed" {{ old('assignment_type', 'mixed') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Max Marks</label>
                            <input type="number" name="max_marks" class="form-control" value="{{ old('max_marks', 100) }}" min="1" max="500">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold generate-btn" id="generateBtn">
                                <i class="bi bi-magic me-2"></i>Generate Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Generated Assignment --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-file-text me-2 text-success"></i>Generated Assignment</h5>
                    @if(isset($assignment))
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="copyAssignment()">
                                <i class="bi bi-clipboard me-1"></i> Copy
                            </button>
                            <button class="btn btn-success btn-sm rounded-pill" onclick="downloadAssignment()">
                                <i class="bi bi-download me-1"></i> Download
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if(isset($assignment))
                        <div class="assignment-display p-3 bg-light rounded-3" id="assignmentContent">
                            {!! nl2br(e($assignment)) !!}
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <form action="{{ route('ai.assignment-generator.save') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="assignment_content" value="{{ $assignment }}">
                                <input type="hidden" name="topic" value="{{ old('topic') }}">
                                <button type="submit" class="btn btn-success rounded-pill">
                                    <i class="bi bi-save me-1"></i> Save to Course
                                </button>
                            </form>
                            <button class="btn btn-outline-primary rounded-pill" onclick="editAssignment()">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </button>
                        </div>
                    @else
                        <div class="text-center py-5" id="emptyState">
                            <div class="rounded bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-file-earmark-text text-success" style="font-size:2.5rem;"></i>
                            </div>
                            <h5 class="text-muted">Your assignment will appear here</h5>
                            <p class="text-muted mb-0">Fill in the form and click "Generate" to create an assignment.</p>
                        </div>

                        <div id="loadingState" class="text-center py-5" style="display:none;">
                            <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;" role="status"></div>
                            <h5 class="text-muted">Generating assignment...</h5>
                            <p class="text-muted mb-0">AI is crafting your assignment. This may take a moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('assignmentForm')?.addEventListener('submit', function() {
    document.getElementById('generateBtn').disabled = true;
    document.getElementById('generateBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('loadingState').style.display = 'block';
});

function copyAssignment() {
    const content = document.getElementById('assignmentContent').innerText;
    navigator.clipboard.writeText(content).then(() => {
        showToast('Assignment copied to clipboard!');
    });
}

function downloadAssignment() {
    const content = document.getElementById('assignmentContent').innerText;
    const blob = new Blob([content], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'assignment-' + Date.now() + '.txt';
    a.click();
    URL.revokeObjectURL(url);
}

function editAssignment() {
    const content = document.getElementById('assignmentContent');
    const current = content.innerText;
    const textarea = document.createElement('textarea');
    textarea.className = 'form-control';
    textarea.rows = 20;
    textarea.value = current;
    textarea.style.fontFamily = 'monospace';
    content.replaceWith(textarea);
    textarea.id = 'assignmentContent';

    const editBtn = document.querySelector('[onclick="editAssignment()"]');
    if (editBtn) {
        editBtn.outerHTML = '<button class="btn btn-success rounded-pill" onclick="saveEdit()"><i class="bi bi-save me-1"></i> Save Changes</button>';
    }
}

function saveEdit() {
    showToast('Changes saved!');
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-success position-fixed bottom-0 end-0 m-3 shadow';
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush
@endsection
