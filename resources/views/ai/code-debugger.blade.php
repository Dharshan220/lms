@extends('layouts.app')

@section('title', 'AI Code Debugger - Nano Spark')

@section('content')
@push('styles')
<style>
    .code-textarea {
        font-family: 'Fira Code', 'Consolas', 'Monaco', monospace;
        font-size: 0.9rem;
        line-height: 1.6;
        tab-size: 4;
        background: #1e1e2e;
        color: #cdd6f4;
        border: 2px solid #313244;
        border-radius: 8px;
        padding: 16px;
        min-height: 400px;
        resize: vertical;
    }
    .code-textarea:focus { background: #1e1e2e; color: #cdd6f4; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.15); }
    .code-textarea::placeholder { color: #6c7086; }
    .error-item { border-left: 3px solid; padding: 8px 12px; border-radius: 0 8px 8px 0; margin-bottom: 8px; background: #fff; }
    .error-item.error { border-color: #dc3545; background: #dc354508; }
    .error-item.warning { border-color: #ffc107; background: #ffc10708; }
    .error-item.suggestion { border-color: #0dcaf0; background: #0dcaf008; }
    .fixed-code {
        font-family: 'Fira Code', 'Consolas', monospace;
        font-size: 0.85rem;
        background: #1e1e2e;
        color: #a6e3a1;
        padding: 16px;
        border-radius: 8px;
        overflow-x: auto;
        white-space: pre;
    }
    .debug-btn { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
    .debug-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
    .line-numbers { counter-reset: line; }
    .line-numbers .line::before { counter-increment: line; content: counter(line); display: inline-block; width: 3em; text-align: right; margin-right: 1em; color: #6c7086; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="bi bi-bug text-danger" style="font-size:1.4rem;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">AI Code Debugger</h4>
                <p class="text-muted mb-0">Find and fix bugs in your code using AI</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: Code Input --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-code-slash me-2 text-primary"></i>Your Code</h5>
                    <select class="form-select form-select-sm w-auto" id="languageSelect" onchange="updatePlaceholder()">
                        <option value="cpp">C++ / Arduino</option>
                        <option value="python">Python</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                    </select>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ai.code-debugger.debug') }}" method="POST" id="debugForm">
                        @csrf
                        <input type="hidden" name="language" id="languageHidden" value="cpp">
                        <div class="mb-3 position-relative">
                            <textarea name="code" class="form-control code-textarea" id="codeInput" rows="18"
                                placeholder="Paste or write your code here..." required>{{ old('code') }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-semibold debug-btn flex-grow-1" id="debugBtn">
                                <i class="bi bi-bug me-2"></i>Debug Code
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearCode()" title="Clear">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: Debug Results --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clipboard2-check me-2 text-success"></i>Debug Results</h5>
                </div>
                <div class="card-body p-4">
                    @if(isset($results))
                        {{-- Errors --}}
                        @if(isset($results['errors']) && count($results['errors']))
                            <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-circle me-1"></i>Errors Found ({{ count($results['errors']) }})</h6>
                            @foreach($results['errors'] as $error)
                                <div class="error-item error mb-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong class="text-danger">Line {{ $error['line'] ?? '?' }}:</strong>
                                            <span>{{ $error['message'] ?? '' }}</span>
                                        </div>
                                    </div>
                                    @if(isset($error['suggestion']))
                                        <small class="text-muted"><i class="bi bi-lightbulb me-1"></i>{{ $error['suggestion'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        {{-- Warnings --}}
                        @if(isset($results['warnings']) && count($results['warnings']))
                            <h6 class="fw-bold text-warning mb-2 mt-3"><i class="bi bi-exclamation-triangle me-1"></i>Warnings ({{ count($results['warnings']) }})</h6>
                            @foreach($results['warnings'] as $warning)
                                <div class="error-item warning mb-2">
                                    <strong class="text-warning">Line {{ $warning['line'] ?? '?' }}:</strong>
                                    <span>{{ $warning['message'] ?? '' }}</span>
                                </div>
                            @endforeach
                        @endif

                        {{-- Suggestions --}}
                        @if(isset($results['suggestions']) && count($results['suggestions']))
                            <h6 class="fw-bold text-info mb-2 mt-3"><i class="bi bi-info-circle me-1"></i>Suggestions</h6>
                            @foreach($results['suggestions'] as $suggestion)
                                <div class="error-item suggestion mb-2">
                                    <span>{{ is_array($suggestion) ? ($suggestion['message'] ?? json_encode($suggestion)) : $suggestion }}</span>
                                </div>
                            @endforeach
                        @endif

                        {{-- Fixed Code --}}
                        @if(isset($results['fixed_code']))
                            <h6 class="fw-bold text-success mb-2 mt-4"><i class="bi bi-check-circle me-1"></i>Fixed Code</h6>
                            <div class="position-relative">
                                <pre class="fixed-code" id="fixedCode">{{ $results['fixed_code'] }}</pre>
                                <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2" onclick="copyFixedCode()" title="Copy">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        @endif

                        {{-- No Issues --}}
                        @if(!isset($results['errors']) && !isset($results['warnings']))
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
                                <h5 class="mt-3 text-success">No issues found!</h5>
                                <p class="text-muted">Your code looks good.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5" id="emptyState">
                            <div class="rounded bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-bug text-danger" style="font-size:2.5rem;"></i>
                            </div>
                            <h5 class="text-muted">Paste your code and click Debug</h5>
                            <p class="text-muted mb-0">AI will analyze your code for errors, warnings, and suggestions.</p>
                        </div>

                        <div id="loadingState" class="text-center py-5" style="display:none;">
                            <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;" role="status"></div>
                            <h5 class="text-muted">Analyzing code...</h5>
                            <p class="text-muted mb-0">AI is scanning your code for issues.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('debugForm')?.addEventListener('submit', function() {
    document.getElementById('debugBtn').disabled = true;
    document.getElementById('debugBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Analyzing...';
    document.getElementById('languageHidden').value = document.getElementById('languageSelect').value;
    if (document.getElementById('emptyState')) document.getElementById('emptyState').style.display = 'none';
    if (document.getElementById('loadingState')) document.getElementById('loadingState').style.display = 'block';
});

function clearCode() {
    document.getElementById('codeInput').value = '';
}

function copyFixedCode() {
    const code = document.getElementById('fixedCode').innerText;
    navigator.clipboard.writeText(code).then(() => {
        showToast('Fixed code copied to clipboard!');
    });
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-success position-fixed bottom-0 end-0 m-3 shadow';
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function updatePlaceholder() {
    const lang = document.getElementById('languageSelect').value;
    const placeholders = {
        cpp: '#include <iostream>\nusing namespace std;\n\nint main() {\n    // Your C++ or Arduino code here\n    return 0;\n}',
        python: '# Your Python code here\ndef main():\n    pass\n\nif __name__ == "__main__":\n    main()',
        javascript: '// Your JavaScript code here\nfunction main() {\n    // ...\n}\n\nmain();',
        java: 'public class Main {\n    public static void main(String[] args) {\n        // Your Java code here\n    }\n}'
    };
    document.getElementById('codeInput').placeholder = placeholders[lang] || placeholders.cpp;
}
</script>
@endpush
@endsection
