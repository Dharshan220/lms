@extends('layouts.app')

@section('title', 'AI Code Debugger - Nano Spark LMS')

@section('content')
<style>
    :root {
        --ns-bg: #050505;
        --ns-card: #121212;
        --ns-elevated: #181818;
        --ns-accent: #FFD400;
        --ns-success: #00D26A;
        --ns-warning: #FF9800;
        --ns-danger: #FF4D4F;
        --ns-info: #3B82F6;
        --ns-text: #FFFFFF;
        --ns-text-secondary: #A0A0A0;
        --ns-text-muted: #666666;
        --ns-border: rgba(255,255,255,0.06);
        --font-heading: 'Space Mono', monospace;
        --font-body: 'IBM Plex Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
    }

    .ns-page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
    }
    .ns-page-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(255,77,79,0.15), rgba(255,152,0,0.15));
        border: 1px solid rgba(255,77,79,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--ns-danger);
        flex-shrink: 0;
    }
    .ns-page-header h1 {
        font-family: var(--font-heading);
        font-size: 22px;
        font-weight: 700;
        color: var(--ns-text);
        margin: 0;
    }
    .ns-page-header p {
        font-size: 14px;
        color: var(--ns-text-muted);
        margin: 2px 0 0;
    }

    .ns-layout-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .ns-panel {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 20px;
        overflow: hidden;
    }
    .ns-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--ns-border);
    }
    .ns-panel-title {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-text);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    .ns-panel-title i { font-size: 18px; }
    .ns-panel-body { padding: 24px; }

    .ns-code-textarea {
        width: 100%;
        min-height: 420px;
        padding: 20px;
        border-radius: 14px;
        border: 1px solid var(--ns-border);
        background: var(--ns-elevated);
        color: #CDD6F4;
        font-family: var(--font-mono);
        font-size: 13px;
        line-height: 1.7;
        tab-size: 4;
        resize: vertical;
        outline: none;
        transition: border-color 0.2s;
    }
    .ns-code-textarea:focus {
        border-color: rgba(255,77,79,0.4);
    }
    .ns-code-textarea::placeholder { color: var(--ns-text-muted); }

    .ns-form-group { margin-bottom: 20px; }
    .ns-form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-form-select {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--ns-border);
        background: var(--ns-elevated);
        color: var(--ns-text);
        font-family: var(--font-body);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }
    .ns-form-select:focus { border-color: rgba(255,77,79,0.4); }

    .ns-btn-debug {
        width: 100%;
        padding: 14px 24px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #FF4D4F, #FF9800);
        color: #FFFFFF;
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 16px rgba(255,77,79,0.3);
    }
    .ns-btn-debug:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(255,77,79,0.45);
    }
    .ns-btn-debug:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .ns-btn-clear {
        padding: 14px 18px;
        border-radius: 14px;
        border: 1px solid var(--ns-border);
        background: transparent;
        color: var(--ns-text-muted);
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .ns-btn-clear:hover {
        border-color: rgba(255,77,79,0.3);
        color: var(--ns-danger);
        background: rgba(255,77,79,0.06);
    }
    .ns-btn-row {
        display: flex;
        gap: 10px;
        margin-top: 8px;
    }

    .ns-error-item {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 10px;
        border-left: 4px solid;
        background: var(--ns-elevated);
        border-right: 1px solid var(--ns-border);
        border-top: 1px solid var(--ns-border);
        border-bottom: 1px solid var(--ns-border);
    }
    .ns-error-item.error { border-left-color: var(--ns-danger); }
    .ns-error-item.warning { border-left-color: var(--ns-warning); }
    .ns-error-item.suggestion { border-left-color: var(--ns-info); }
    .ns-error-line {
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 6px;
    }
    .ns-error-line.error { background: rgba(255,77,79,0.12); color: var(--ns-danger); }
    .ns-error-line.warning { background: rgba(255,152,0,0.12); color: var(--ns-warning); }
    .ns-error-msg {
        font-size: 14px;
        color: var(--ns-text);
        line-height: 1.5;
        margin-bottom: 4px;
    }
    .ns-error-suggestion {
        font-size: 12px;
        color: var(--ns-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }
    .ns-error-suggestion i { color: var(--ns-accent); }

    .ns-fixed-code {
        position: relative;
        margin-top: 16px;
    }
    .ns-fixed-code pre {
        background: var(--ns-elevated);
        border: 1px solid var(--ns-border);
        border-radius: 14px;
        padding: 20px;
        color: var(--ns-success);
        font-family: var(--font-mono);
        font-size: 13px;
        line-height: 1.7;
        overflow-x: auto;
        white-space: pre;
        margin: 0;
    }
    .ns-copy-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--ns-border);
        background: var(--ns-card);
        color: var(--ns-text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .ns-copy-btn:hover {
        border-color: rgba(0,210,106,0.3);
        color: var(--ns-success);
        background: rgba(0,210,106,0.06);
    }

    .ns-section-title {
        font-family: var(--font-heading);
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .ns-section-title.errors { color: var(--ns-danger); }
    .ns-section-title.warnings { color: var(--ns-warning); }
    .ns-section-title.suggestions { color: var(--ns-info); }
    .ns-section-title.fixed { color: var(--ns-success); }
    .ns-section-count {
        font-family: var(--font-mono);
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 100px;
        background: rgba(255,255,255,0.06);
        color: var(--ns-text-secondary);
    }

    .ns-empty-state { text-align: center; padding: 60px 24px; }
    .ns-empty-icon {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: rgba(255,77,79,0.06);
        border: 1px solid rgba(255,77,79,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: var(--ns-danger);
        margin: 0 auto 20px;
    }
    .ns-empty-state h3 {
        font-family: var(--font-heading);
        font-size: 18px;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-empty-state p {
        font-size: 14px;
        color: var(--ns-text-muted);
        max-width: 340px;
        margin: 0 auto;
    }

    .ns-spinner {
        width: 48px;
        height: 48px;
        border: 3px solid var(--ns-border);
        border-top-color: var(--ns-danger);
        border-radius: 50%;
        margin: 0 auto 20px;
        animation: ns-spin 0.8s linear infinite;
    }
    @keyframes ns-spin { to { transform: rotate(360deg); } }

    .ns-summary-bar {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .ns-summary-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 10px;
        background: var(--ns-elevated);
        border: 1px solid var(--ns-border);
        font-size: 13px;
        color: var(--ns-text-secondary);
    }
    .ns-summary-item i { font-size: 16px; }
    .ns-summary-item.danger i { color: var(--ns-danger); }
    .ns-summary-item.warning i { color: var(--ns-warning); }
    .ns-summary-item.info i { color: var(--ns-info); }
    .ns-summary-item.success i { color: var(--ns-success); }
    .ns-summary-item strong {
        font-family: var(--font-mono);
        font-size: 15px;
        color: var(--ns-text);
    }

    @media (max-width: 1024px) {
        .ns-layout-split { grid-template-columns: 1fr; }
    }
</style>

<div style="padding: 24px; max-width: 1400px; margin: 0 auto;" x-data="codeDebugger()">
    <div class="ns-page-header">
        <div class="ns-page-header-icon">
            <i class="bi bi-bug"></i>
        </div>
        <div>
            <h1>AI Code Debugger</h1>
            <p>Paste your code and let AI find bugs, warnings, and improvements</p>
        </div>
    </div>

    <div class="ns-layout-split">
        {{-- Left Panel: Code Input --}}
        <div class="ns-panel" style="position: sticky; top: 24px;">
            <div class="ns-panel-header">
                <h2 class="ns-panel-title"><i class="bi bi-code-slash" style="color:var(--ns-danger)"></i> Code Editor</h2>
                <select class="ns-form-select" style="width:auto;padding:8px 36px 8px 14px;font-size:13px;" x-model="form.language" @change="updatePlaceholder()">
                    <option value="python">Python</option>
                    <option value="javascript">JavaScript</option>
                    <option value="cpp">C++ / Arduino</option>
                    <option value="c">C</option>
                    <option value="java">Java</option>
                </select>
            </div>
            <div class="ns-panel-body">
                <form action="{{ route('ai.code-debugger.debug') }}" method="POST" @submit.prevent="submitForm()">
                    @csrf
                    <input type="hidden" name="language" :value="form.language">
                    <div class="ns-form-group">
                        <textarea name="code" class="ns-code-textarea" rows="20"
                            :placeholder="placeholder"
                            x-model="form.code" required></textarea>
                    </div>
                    <div class="ns-btn-row">
                        <button type="submit" class="ns-btn-debug" :disabled="loading" style="flex:1;">
                            <template x-if="!loading">
                                <span><i class="bi bi-bug"></i> Debug Code</span>
                            </template>
                            <template x-if="loading">
                                <span><span class="ns-spinner" style="width:20px;height:20px;border-width:2px;margin:0;border-top-color:#fff;"></span> Analyzing...</span>
                            </template>
                        </button>
                        <button type="button" class="ns-btn-clear" @click="form.code = ''" title="Clear code">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Panel: Debug Output --}}
        <div class="ns-panel">
            <div class="ns-panel-header">
                <h2 class="ns-panel-title"><i class="bi bi-clipboard2-check" style="color:var(--ns-success)"></i> Debug Output</h2>
                @if(isset($results))
                    <button class="ns-copy-btn" onclick="copyAllResults()" title="Copy results">
                        <i class="bi bi-clipboard"></i>
                    </button>
                @endif
            </div>
            <div class="ns-panel-body">
                @if(isset($results))
                    {{-- Summary Bar --}}
                    <div class="ns-summary-bar">
                        @if(isset($results['errors']) && count($results['errors']))
                            <div class="ns-summary-item danger">
                                <i class="bi bi-x-circle-fill"></i>
                                <strong>{{ count($results['errors']) }}</strong> Errors
                            </div>
                        @endif
                        @if(isset($results['warnings']) && count($results['warnings']))
                            <div class="ns-summary-item warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <strong>{{ count($results['warnings']) }}</strong> Warnings
                            </div>
                        @endif
                        @if(isset($results['suggestions']) && count($results['suggestions']))
                            <div class="ns-summary-item info">
                                <i class="bi bi-info-circle-fill"></i>
                                <strong>{{ count($results['suggestions']) }}</strong> Suggestions
                            </div>
                        @endif
                        @if(!isset($results['errors']) && !isset($results['warnings']))
                            <div class="ns-summary-item success">
                                <i class="bi bi-check-circle-fill"></i>
                                No issues found
                            </div>
                        @endif
                    </div>

                    {{-- Errors --}}
                    @if(isset($results['errors']) && count($results['errors']))
                        <div class="ns-section-title errors">
                            <i class="bi bi-x-circle"></i> Errors <span class="ns-section-count">{{ count($results['errors']) }}</span>
                        </div>
                        @foreach($results['errors'] as $error)
                            <div class="ns-error-item error">
                                <div class="ns-error-line error">Line {{ $error['line'] ?? '?' }}</div>
                                <div class="ns-error-msg">{{ $error['message'] ?? '' }}</div>
                                @if(isset($error['suggestion']))
                                    <div class="ns-error-suggestion">
                                        <i class="bi bi-lightbulb"></i> {{ $error['suggestion'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    {{-- Warnings --}}
                    @if(isset($results['warnings']) && count($results['warnings']))
                        <div class="ns-section-title warnings" style="margin-top:24px;">
                            <i class="bi bi-exclamation-triangle"></i> Warnings <span class="ns-section-count">{{ count($results['warnings']) }}</span>
                        </div>
                        @foreach($results['warnings'] as $warning)
                            <div class="ns-error-item warning">
                                <div class="ns-error-line warning">Line {{ $warning['line'] ?? '?' }}</div>
                                <div class="ns-error-msg">{{ $warning['message'] ?? '' }}</div>
                                @if(isset($warning['suggestion']))
                                    <div class="ns-error-suggestion">
                                        <i class="bi bi-lightbulb"></i> {{ $warning['suggestion'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    {{-- Suggestions --}}
                    @if(isset($results['suggestions']) && count($results['suggestions']))
                        <div class="ns-section-title suggestions" style="margin-top:24px;">
                            <i class="bi bi-info-circle"></i> Suggestions <span class="ns-section-count">{{ count($results['suggestions']) }}</span>
                        </div>
                        @foreach($results['suggestions'] as $suggestion)
                            <div class="ns-error-item suggestion">
                                <div class="ns-error-msg">{{ is_array($suggestion) ? ($suggestion['message'] ?? json_encode($suggestion)) : $suggestion }}</div>
                            </div>
                        @endforeach
                    @endif

                    {{-- Fixed Code --}}
                    @if(isset($results['fixed_code']))
                        <div class="ns-section-title fixed" style="margin-top:28px;">
                            <i class="bi bi-check-circle"></i> Fixed Code
                        </div>
                        <div class="ns-fixed-code">
                            <pre id="fixedCode">{{ $results['fixed_code'] }}</pre>
                            <button class="ns-copy-btn" onclick="copyFixedCode()" title="Copy fixed code">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    @endif

                @else
                    {{-- Empty / Loading State --}}
                    <div x-show="!loading">
                        <div class="ns-empty-state">
                            <div class="ns-empty-icon">
                                <i class="bi bi-bug"></i>
                            </div>
                            <h3>Paste your code and hit Debug</h3>
                            <p>AI will scan for errors, warnings, performance issues, and suggest improvements.</p>
                        </div>
                    </div>

                    <div x-show="loading" x-cloak style="text-align:center; padding:60px 24px;">
                        <div class="ns-spinner"></div>
                        <h3 style="font-family:var(--font-heading);font-size:16px;color:var(--ns-text-secondary);margin-bottom:8px;">Analyzing code...</h3>
                        <p style="font-size:13px;color:var(--ns-text-muted);">AI is scanning your code for issues. This may take a moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function codeDebugger() {
    const placeholders = {
        python: '# Your Python code here\ndef main():\n    pass\n\nif __name__ == "__main__":\n    main()',
        javascript: '// Your JavaScript code here\nfunction main() {\n    // ...\n}\n\nmain();',
        cpp: '#include <iostream>\nusing namespace std;\n\nint main() {\n    // Your C++ or Arduino code here\n    return 0;\n}',
        c: '#include <stdio.h>\n\nint main() {\n    // Your C code here\n    return 0;\n}',
        java: 'public class Main {\n    public static void main(String[] args) {\n        // Your Java code here\n    }\n}'
    };

    return {
        loading: false,
        form: {
            language: '{{ old("language", "python") }}',
            code: '{{ addslashes(old("code")) }}',
        },
        placeholder: placeholders['{{ old("language", "python") }}'] || placeholders.python,
        updatePlaceholder() {
            this.placeholder = placeholders[this.form.language] || placeholders.python;
        },
        submitForm() {
            this.loading = true;
            this.$nextTick(() => {
                this.$el.querySelector('form').submit();
            });
        }
    };
}

function copyFixedCode() {
    const code = document.getElementById('fixedCode');
    if (code) {
        navigator.clipboard.writeText(code.innerText).then(() => {
            nsToast('Fixed code copied to clipboard!', 'success');
        });
    }
}

function copyAllResults() {
    const results = document.querySelector('.ns-panel-body');
    if (results) {
        navigator.clipboard.writeText(results.innerText).then(() => {
            nsToast('Results copied to clipboard!', 'success');
        });
    }
}

function nsToast(message, type) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;padding:14px 20px;border-radius:12px;font-family:var(--font-body);font-size:14px;font-weight:600;color:#fff;box-shadow:0 8px 32px rgba(0,0,0,0.4);animation:nsToastIn 0.3s ease;';
    toast.style.background = type === 'success' ? '#00D26A' : '#FF4D4F';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
<style>@keyframes nsToastIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }</style>
@endpush
@endsection