@extends('layouts.app')

@section('title', 'Discussions - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Course Discussions</h4>
            <p class="text-muted mb-0">Monitor and participate in student discussions</p>
        </div>
    </div>

    @if(isset($discussions) && $discussions->count())
        <div class="row g-4">
            @foreach($discussions as $discussion)
                <div class="col-md-6 col-xl-4">
                    <div class="card section-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $discussion->user->name ?? 'Student' }}</div>
                                        <small class="text-muted">{{ $discussion->created_at?->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if($discussion->is_resolved)
                                    <span class="badge bg-success">Resolved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Open</span>
                                @endif
                            </div>
                            <h6 class="fw-bold mt-3 mb-2" style="font-size:0.95rem;">
                                <a href="{{ route('discussions.show', $discussion) }}" class="text-decoration-none text-dark stretched-link">{{ $discussion->title }}</a>
                            </h6>
                            <p class="text-muted mb-3" style="font-size:0.85rem;">{{ Str::limit($discussion->content, 120) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                @if($discussion->course)
                                    <span class="badge bg-light text-dark">{{ $discussion->course->title }}</span>
                                @endif
                                <small class="text-muted">
                                    <i class="bi bi-chat-dots me-1"></i>{{ $discussion->replies->count() ?? 0 }} replies
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
        <div class="card section-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-dots text-muted" style="font-size:3rem;"></i>
                <h5 class="mt-3 text-muted">No discussions yet</h5>
                <p class="text-muted mb-0">Discussions from your students will appear here.</p>
            </div>
        </div>
    @endif
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .section-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: all 0.2s; }
</style>
@endsection
