@extends('layouts.app')

@section('title', $discussion->title ?? 'Discussion' . ' - Nano Spark')

@section('content')
@push('styles')
<style>
    .reply-card { transition: background 0.2s; }
    .reply-card:hover { background: #f8f9fa; }
    .reply-form textarea { border-radius: 12px; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Back Button --}}
    <a href="{{ route('discussions.index') }}" class="btn btn-outline-secondary mb-3 rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Discussions
    </a>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Original Post --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $colors = ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#0d6efd'];
                                $color = $colors[0];
                            @endphp
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:48px;height:48px;background-color:{{ $color }};">
                                {{ substr($discussion->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $discussion->user->name ?? 'User' }}</h6>
                                <small class="text-muted">{{ $discussion->created_at?->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            @if($discussion->is_resolved)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Resolved</span>
                            @else
                                <span class="badge bg-warning text-dark">Open</span>
                            @endif
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3">{{ $discussion->title }}</h4>
                    <div class="text-muted" style="line-height:1.8;">{!! nl2br(e($discussion->content ?? $discussion->body ?? '')) !!}</div>

                    @if($discussion->course)
                        <div class="mt-3">
                            <span class="badge bg-light text-dark"><i class="bi bi-book me-1"></i>{{ $discussion->course->title }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Replies --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-chat-left-text me-2 text-primary"></i>Replies <span class="badge bg-secondary">{{ $discussion->replies_count ?? $discussion->replies->count() ?? 0 }}</span></h5>
            </div>

            @if(isset($discussion->replies) && $discussion->replies->count())
                @foreach($discussion->replies as $reply)
                    @php
                        $replyColor = $colors[$loop->index % count($colors)];
                    @endphp
                    <div class="card reply-card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" style="width:40px;height:40px;background-color:{{ $replyColor }};">
                                    {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-bold">{{ $reply->user->name ?? 'User' }}</span>
                                            @if(($reply->user->role ?? '') === 'teacher')
                                                <span class="badge bg-primary ms-1" style="font-size:0.65rem;">Teacher</span>
                                            @endif
                                            <small class="text-muted ms-2">{{ $reply->created_at?->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="text-muted" style="line-height:1.7;">{!! nl2br(e($reply->content ?? $reply->body ?? '')) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-chat-dots text-muted" style="font-size:2rem;"></i>
                        <p class="text-muted mt-2 mb-0">No replies yet. Be the first to respond!</p>
                    </div>
                </div>
            @endif

            {{-- Reply Form --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-reply me-2"></i>Add a Reply</h6>
                    <form action="{{ route('discussions.reply', $discussion) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" class="form-control reply-form" rows="4"
                                placeholder="Write your reply here..." required></textarea>
                            @error('content')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill fw-semibold">
                                <i class="bi bi-send me-1"></i> Post Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Discussion Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold mb-0">Discussion Info</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted">Status</small>
                        @if($discussion->is_resolved)
                            <span class="badge bg-success">Resolved</span>
                        @else
                            <span class="badge bg-warning text-dark">Open</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted">Created</small>
                        <small class="fw-semibold">{{ $discussion->created_at?->format('M d, Y') }}</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted">Replies</small>
                        <small class="fw-semibold">{{ $discussion->replies_count ?? $discussion->replies->count() ?? 0 }}</small>
                    </div>
                    @if($discussion->course)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <small class="text-muted">Course</small>
                            <a href="{{ route('student.courses.show', $discussion->course_id) }}" class="text-decoration-none fw-semibold small">{{ Str::limit($discussion->course->title, 20) }}</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Mark as Resolved --}}
            @if(Auth::user() && ((Auth::user()->role === 'teacher') || ($discussion->user_id === Auth::user()->id) || (Auth::user()->role === 'admin')))
                @if(!$discussion->is_resolved)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-check-circle text-success" style="font-size:2rem;"></i>
                            <h6 class="mt-2 fw-bold">Mark as Resolved?</h6>
                            <p class="text-muted small mb-3">Mark this discussion as resolved if your question has been answered.</p>
                            <form action="{{ route('discussions.resolve', $discussion) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success rounded-pill w-100" onclick="return confirm('Mark this discussion as resolved?')">
                                    <i class="bi bi-check-lg me-1"></i> Mark as Resolved
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
