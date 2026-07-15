@extends('layouts.app')

@section('title', 'Live Classes - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Live Classes</h4>
            <p class="text-muted mb-0">Schedule and manage your live class sessions</p>
        </div>
        <a href="{{ route('teacher.live-classes.create') }}" class="btn btn-primary fw-semibold">
            <i class="bi bi-plus-lg me-2"></i>Schedule Class
        </a>
    </div>

    {{-- Filters --}}
    <div class="card section-card mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live Now</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    @if($liveClasses->count())
        <div class="row g-4">
            @foreach($liveClasses as $class)
                <div class="col-md-6 col-xl-4">
                    <div class="card section-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                                        <i class="bi bi-camera-video text-info" style="font-size:1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0" style="font-size:0.95rem;">{{ $class->title }}</h6>
                                        <small class="text-muted">{{ $class->course->title ?? '' }}</small>
                                    </div>
                                </div>
                                @if($class->status == 'scheduled')
                                    <span class="badge bg-primary">Scheduled</span>
                                @elseif($class->status == 'live')
                                    <span class="badge bg-danger pulse">Live</span>
                                @elseif($class->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </div>
                            <div class="bg-light rounded p-3 mb-3">
                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Date</small>
                                        <strong style="font-size:0.9rem;">{{ $class->scheduled_at->format('M d, Y') }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Time</small>
                                        <strong style="font-size:0.9rem;">{{ $class->scheduled_at->format('h:i A') }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Duration</small>
                                        <strong style="font-size:0.9rem;">{{ $class->duration_minutes }} min</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Max Students</small>
                                        <strong style="font-size:0.9rem;">{{ $class->max_students ?? 'Unlimited' }}</strong>
                                    </div>
                                </div>
                            </div>
                            @if($class->description)
                                <p class="text-muted mb-3" style="font-size:0.85rem;">{{ Str::limit($class->description, 100) }}</p>
                            @endif
                            <div class="d-flex gap-2">
                                @if($class->status == 'scheduled' && $class->meeting_link)
                                    <a href="{{ $class->meeting_link }}" target="_blank" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Join
                                    </a>
                                @endif
                                @if($class->status == 'scheduled')
                                    <form action="{{ route('teacher.live-classes.cancel', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this class?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $liveClasses->withQueryString()->links() }}
        </div>
    @else
        <div class="card section-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-camera-video text-muted" style="font-size:3rem;"></i>
                <h5 class="mt-3 text-muted">No live classes found</h5>
                <p class="text-muted">{{ request('status') ? 'Try a different filter.' : 'Schedule your first live class to get started.' }}</p>
                @if(!request('status'))
                    <a href="{{ route('teacher.live-classes.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Schedule Class
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .pulse { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
</style>
@endsection
