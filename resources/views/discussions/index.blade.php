@extends('layouts.app')

@section('title', 'Discussions - Nano Spark LMS')

@section('content')
<style>
    :root {
        --ns-bg: #F7F7F5;
        --ns-card: #FFFFFF;
        --ns-elevated: #F7F7F5;
        --ns-accent: #FFC107;
        --ns-success: #059669;
        --ns-warning: #B45309;
        --ns-danger: #D92D20;
        --ns-info: #2563EB;
        --ns-text: #111111;
        --ns-text-secondary: #4B5563;
        --ns-text-muted: #9CA3AF;
        --ns-border: #EDEDEA;
        --ns-hover: rgba(245,184,0,0.05);
        --font-heading: 'Baloo 2', 'Inter', sans-serif;
        --font-body: 'IBM Plex Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
    }

    .ns-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 32px;
    }
    .ns-page-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .ns-page-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(245,184,0,0.1);
        border: 1px solid rgba(245,184,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--ns-accent);
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
    .ns-btn-new {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        background: linear-gradient(135deg, #F7B500, #FFD54F);
        color: #111111;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 2px 12px rgba(245,184,0,0.25);
    }
    .ns-btn-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(245,184,0,0.4);
        color: #111111;
    }

    .ns-filter-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 16px 20px;
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 16px;
        margin-bottom: 24px;
    }
    .ns-filter-label {
        font-family: var(--font-heading);
        font-size: 12px;
        font-weight: 700;
        color: var(--ns-text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        white-space: nowrap;
    }
    .ns-filter-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .ns-filter-pill {
        padding: 6px 16px;
        border-radius: 100px;
        border: 1px solid var(--ns-border);
        background: transparent;
        color: var(--ns-text-secondary);
        font-family: var(--font-body);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .ns-filter-pill:hover {
        border-color: rgba(245,184,0,0.3);
        color: #B45309;
    }
    .ns-filter-pill.active {
        background: rgba(245,184,0,0.12);
        border-color: rgba(245,184,0,0.3);
        color: #B45309;
    }

    .ns-discussions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 16px;
    }
    .ns-discussion-card {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    .ns-discussion-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ns-accent), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .ns-discussion-card:hover {
        border-color: rgba(245,184,0,0.4);
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(16,24,40,0.1);
    }
    .ns-discussion-card:hover::before {
        opacity: 1;
    }

    .ns-discussion-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .ns-discussion-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ns-discussion-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: #111111;
        flex-shrink: 0;
    }
    .ns-discussion-author-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--ns-text);
    }
    .ns-discussion-author-time {
        font-size: 12px;
        color: var(--ns-text-muted);
    }

    .ns-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        font-family: var(--font-mono);
        white-space: nowrap;
    }
    .ns-status-badge.open {
        background: rgba(245,184,0,0.12);
        color: var(--ns-warning);
        border: 1px solid rgba(245,184,0,0.25);
    }
    .ns-status-badge.resolved {
        background: rgba(0,210,106,0.12);
        color: var(--ns-success);
        border: 1px solid rgba(0,210,106,0.2);
    }

    .ns-discussion-title {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-text);
        margin-bottom: 8px;
    }
    .ns-discussion-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }
    .ns-discussion-title a:hover {
        color: #B45309;
    }
    .ns-discussion-excerpt {
        font-size: 13px;
        color: var(--ns-text-muted);
        line-height: 1.6;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ns-discussion-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 14px;
        border-top: 1px solid var(--ns-border);
    }
    .ns-discussion-tags {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .ns-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #F7F7F5;
        border: 1px solid #EDEDEA;
        color: var(--ns-text-secondary);
    }
    .ns-tag.course-tag {
        background: rgba(59,130,246,0.1);
        border-color: rgba(59,130,246,0.2);
        color: var(--ns-info);
    }
    .ns-discussion-replies {
        display: flex;
        align-items: center;
        gap: 5px;
        font-family: var(--font-mono);
        font-size: 13px;
        font-weight: 600;
        color: var(--ns-text-muted);
        white-space: nowrap;
    }
    .ns-discussion-replies i {
        font-size: 14px;
    }

    .ns-empty-state {
        text-align: center;
        padding: 80px 24px;
    }
    .ns-empty-icon {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: rgba(245,184,0,0.06);
        border: 1px solid rgba(245,184,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: var(--ns-accent);
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
        margin-bottom: 24px;
    }

    .ns-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(16,24,40,0.5);
        backdrop-filter: blur(8px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
    .ns-modal {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 20px;
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
    }
    .ns-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px 0;
    }
    .ns-modal-header h3 {
        font-family: var(--font-heading);
        font-size: 18px;
        color: var(--ns-text);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    .ns-modal-close {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--ns-border);
        background: transparent;
        color: var(--ns-text-muted);
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .ns-modal-close:hover {
        background: rgba(255,77,79,0.1);
        border-color: rgba(255,77,79,0.3);
        color: var(--ns-danger);
    }
    .ns-modal-body {
        padding: 24px 28px;
    }
    .ns-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 0 28px 24px;
    }

    .ns-form-group {
        margin-bottom: 20px;
    }
    .ns-form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-form-label .required {
        color: var(--ns-danger);
    }
    .ns-form-input,
    .ns-form-select,
    .ns-form-textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #D0D5DD;
        background: #FFFFFF;
        color: var(--ns-text);
        font-family: var(--font-body);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }
    .ns-form-input:focus,
    .ns-form-select:focus,
    .ns-form-textarea:focus {
        border-color: #FFC107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15);
    }
    .ns-form-input::placeholder,
    .ns-form-textarea::placeholder {
        color: var(--ns-text-muted);
    }
    .ns-form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }
    .ns-form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .ns-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 12px;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .ns-btn-primary {
        background: linear-gradient(135deg, #F7B500, #FFD54F);
        color: #111111;
    }
    .ns-btn-primary:hover {
        box-shadow: 0 4px 16px rgba(245,184,0,0.3);
        transform: translateY(-1px);
    }
    .ns-btn-ghost {
        background: transparent;
        border: 1px solid var(--ns-border);
        color: var(--ns-text-secondary);
    }
    .ns-btn-ghost:hover {
        border-color: rgba(245,184,0,0.4);
        color: var(--ns-text);
    }

    .ns-pagination {
        display: flex;
        justify-content: center;
        margin-top: 32px;
    }
    .ns-pagination a,
    .ns-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        border-radius: 10px;
        font-family: var(--font-mono);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .ns-pagination a {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        color: var(--ns-text-secondary);
    }
    .ns-pagination a:hover {
        border-color: rgba(245,184,0,0.3);
        color: #B45309;
    }
    .ns-pagination span.active {
        background: rgba(245,184,0,0.12);
        border: 1px solid rgba(245,184,0,0.3);
        color: #B45309;
    }
    .ns-pagination .disabled {
        opacity: 0.3;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .ns-page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .ns-discussions-grid {
            grid-template-columns: 1fr;
        }
        .ns-filter-bar {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<div style="padding: 24px; max-width: 1200px; margin: 0 auto;">
    <div class="ns-page-header">
        <div class="ns-page-header-left">
            <div class="ns-page-header-icon">
                <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div>
                <h1>Discussions</h1>
                <p>Join conversations and ask questions</p>
            </div>
        </div>
        <button class="ns-btn-new" onclick="document.getElementById('newDiscussionModal').style.display='flex'">
            <i class="bi bi-plus-lg"></i> New Discussion
        </button>
    </div>

    <div class="ns-filter-bar">
        <span class="ns-filter-label">Filter:</span>
        <div class="ns-filter-pills">
            <button class="ns-filter-pill active" onclick="filterDiscussions('all', this)">All</button>
            @if(isset($courses) && count($courses))
                @foreach($courses as $course)
                    <button class="ns-filter-pill" onclick="filterDiscussions('course-{{ $course->id }}', this)">{{ $course->title }}</button>
                @endforeach
            @endif
        </div>
    </div>

    @if(isset($discussions) && $discussions->count())
        <div class="ns-discussions-grid" id="discussionsList">
            @foreach($discussions as $discussion)
                @php
                    $colors = ['#FFC107', '#059669', '#2563EB', '#F7B500', '#D92D20'];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div class="ns-discussion-card discussion-item" data-course="course-{{ $discussion->course_id ?? '0' }}">
                    <div class="ns-discussion-top">
                        <div class="ns-discussion-author">
                            <div class="ns-discussion-avatar" style="background: {{ $color }};">
                                {{ strtoupper(substr($discussion->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div class="ns-discussion-author-name">{{ $discussion->user->name ?? 'User' }}</div>
                                <div class="ns-discussion-author-time">{{ $discussion->created_at?->diffForHumans() }}</div>
                            </div>
                        </div>
                        @if($discussion->is_resolved)
                            <span class="ns-status-badge resolved"><i class="bi bi-check-circle-fill"></i> Resolved</span>
                        @else
                            <span class="ns-status-badge open"><i class="bi bi-circle-fill"></i> Open</span>
                        @endif
                    </div>

                    <h3 class="ns-discussion-title">
                        <a href="{{ route('discussions.show', $discussion) }}">{{ $discussion->title }}</a>
                    </h3>
                    <p class="ns-discussion-excerpt">{{ $discussion->content ?? $discussion->body ?? '' }}</p>

                    <div class="ns-discussion-footer">
                        <div class="ns-discussion-tags">
                            @if($discussion->course)
                                <span class="ns-tag course-tag"><i class="bi bi-book"></i> {{ Str::limit($discussion->course->title, 20) }}</span>
                            @endif
                            @if(isset($discussion->tags))
                                @foreach($discussion->tags as $tag)
                                    <span class="ns-tag">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="ns-discussion-replies">
                            <i class="bi bi-chat-dots"></i>
                            {{ $discussion->replies_count ?? $discussion->replies->count() ?? 0 }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="ns-pagination">
            {{ $discussions->links() }}
        </div>
    @else
        <div class="ns-empty-state">
            <div class="ns-empty-icon">
                <i class="bi bi-chat-dots"></i>
            </div>
            <h3>No discussions yet</h3>
            <p>Start a new discussion to get help from peers and instructors.</p>
            <button class="ns-btn-new" onclick="document.getElementById('newDiscussionModal').style.display='flex'">
                <i class="bi bi-plus-lg"></i> Start a Discussion
            </button>
        </div>
    @endif
</div>

<div class="ns-modal-overlay" id="newDiscussionModal" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="ns-modal">
        <div class="ns-modal-header">
            <h3><i class="bi bi-chat-dots-fill" style="color:var(--ns-accent)"></i> New Discussion</h3>
            <button class="ns-modal-close" onclick="document.getElementById('newDiscussionModal').style.display='none'">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form action="{{ route('discussions.store') }}" method="POST">
            @csrf
            <div class="ns-modal-body">
                <div class="ns-form-group">
                    <label class="ns-form-label">Title <span class="required">*</span></label>
                    <input type="text" name="title" class="ns-form-input" placeholder="What's your question about?" required>
                </div>
                <div class="ns-form-group">
                    <label class="ns-form-label">Course</label>
                    <select name="course_id" class="ns-form-select">
                        <option value="">General Discussion</option>
                        @if(isset($courses) && count($courses))
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="ns-form-group">
                    <label class="ns-form-label">Description <span class="required">*</span></label>
                    <textarea name="content" class="ns-form-textarea" placeholder="Describe your question or topic in detail..." required></textarea>
                </div>
            </div>
            <div class="ns-modal-footer">
                <button type="button" class="ns-btn ns-btn-ghost" onclick="document.getElementById('newDiscussionModal').style.display='none'">Cancel</button>
                <button type="submit" class="ns-btn ns-btn-primary">
                    <i class="bi bi-send-fill"></i> Post Discussion
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function filterDiscussions(course, btn) {
    document.querySelectorAll('.ns-filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.discussion-item').forEach(item => {
        item.style.display = (course === 'all' || item.dataset.course === course) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
