@extends('layouts.app')

@section('title', 'Discussions - Nano Spark')

@section('content')
@push('styles')
<style>
    .discussion-card { transition: transform 0.2s, box-shadow 0.2s; }
    .discussion-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    .filter-btn { font-size: 0.8rem; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="bi bi-chat-dots text-primary" style="font-size:1.4rem;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">Discussions</h4>
                <p class="text-muted mb-0">Join conversations and ask questions</p>
            </div>
        </div>
        <button class="btn btn-primary rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#newDiscussionModal">
            <i class="bi bi-plus-lg me-1"></i> New Discussion
        </button>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="fw-semibold text-muted small">Filter by Course:</span>
                <div class="d-flex gap-2 flex-wrap" id="courseFilters">
                    <button class="btn btn-sm btn-primary rounded-pill filter-btn active" onclick="filterDiscussions('all', this)">All</button>
                    @if(isset($courses) && count($courses))
                        @foreach($courses as $course)
                            <button class="btn btn-sm btn-outline-secondary rounded-pill filter-btn" onclick="filterDiscussions('course-{{ $course->id }}', this)">{{ $course->title }}</button>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Discussions List --}}
    @if(isset($discussions) && $discussions->count())
        <div class="row g-3" id="discussionsList">
            @foreach($discussions as $discussion)
                @php
                    $colors = ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#0d6efd'];
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <div class="col-md-6 col-xl-4 discussion-item" data-course="course-{{ $discussion->course_id ?? '0' }}">
                    <div class="card discussion-card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:36px;height:36px;background-color:{{ $color }};">
                                        {{ substr($discussion->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $discussion->user->name ?? 'User' }}</div>
                                        <small class="text-muted">{{ $discussion->created_at?->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if($discussion->is_resolved)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Resolved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Open</span>
                                @endif
                            </div>

                            <h6 class="fw-bold mt-3 mb-2" style="font-size:0.95rem;">
                                <a href="{{ route('discussions.show', $discussion) }}" class="text-decoration-none text-dark">{{ $discussion->title }}</a>
                            </h6>
                            <p class="text-muted mb-3" style="font-size:0.82rem;">{{ Str::limit($discussion->content ?? $discussion->body ?? '', 100) }}</p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2 align-items-center">
                                    @if($discussion->course)
                                        <span class="badge bg-light text-dark" style="font-size:0.7rem;"><i class="bi bi-book me-1"></i>{{ Str::limit($discussion->course->title, 25) }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-chat-dots me-1"></i>{{ $discussion->replies_count ?? $discussion->replies->count() ?? 0 }} replies
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $discussions->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="rounded bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                    <i class="bi bi-chat-dots text-primary" style="font-size:2.5rem;"></i>
                </div>
                <h5 class="text-muted">No discussions yet</h5>
                <p class="text-muted mb-3">Start a new discussion to get help from peers and instructors.</p>
                <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#newDiscussionModal">
                    <i class="bi bi-plus-lg me-1"></i> Start a Discussion
                </button>
            </div>
        </div>
    @endif
</div>

{{-- New Discussion Modal --}}
<div class="modal fade" id="newDiscussionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0">
                <h5 class="fw-bold modal-title"><i class="bi bi-chat-dots me-2 text-primary"></i>New Discussion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('discussions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="What's your question about?" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">General Discussion</option>
                            @if(isset($courses) && count($courses))
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="5" placeholder="Describe your question or topic in detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill fw-semibold">
                        <i class="bi bi-send me-1"></i> Post Discussion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterDiscussions(course, btn) {
    document.querySelectorAll('#courseFilters .filter-btn').forEach(b => {
        b.classList.remove('active');
        b.classList.add('btn-outline-secondary');
        b.classList.remove('btn-primary');
    });
    btn.classList.add('active');
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('btn-primary');

    document.querySelectorAll('.discussion-item').forEach(item => {
        if (course === 'all' || item.dataset.course === course) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection
