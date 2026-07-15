@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .xp-progress-bar { background: linear-gradient(90deg, #6f42c1, #e83e8c, #fd7e14); background-size: 200% 100%; animation: shimmer 2s ease-in-out infinite; }
    @keyframes shimmer { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .badge-card { transition: transform 0.2s; }
    .badge-card:hover { transform: scale(1.05); }
    .streak-fire { animation: flicker 1.5s ease-in-out infinite alternate; }
    @keyframes flicker { 0%{transform:scale(1)} 100%{transform:scale(1.15)} }
    .welcome-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .course-progress-card { border-left: 4px solid; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Welcome Card --}}
    <div class="welcome-section rounded-4 p-4 p-md-5 mb-4 text-white shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="mb-3 opacity-75">Continue your learning journey. You're doing great!</p>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge bg-white text-primary fs-6">
                        <i class="bi bi-star-fill text-warning me-1"></i> Level {{ $currentLevel }}
                    </span>
                    <span class="badge bg-white text-success fs-6">
                        <i class="bi bi-lightning-fill me-1"></i> {{ number_format($totalXp) }} XP
                    </span>
                    @if($dailyStreak > 0)
                        <span class="badge bg-warning text-dark fs-6 streak-fire">
                            <i class="bi bi-fire-fill me-1"></i> {{ $dailyStreak }} Day Streak!
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow" style="width:100px;height:100px;">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                    @else
                        <span class="display-4 text-primary fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-book text-primary fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $enrolledCourses }}</h3>
                    <small class="text-muted">Enrolled Courses</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $totalLessonsCompleted }}</h3>
                    <small class="text-muted">Lessons Completed</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;background-color:rgba(111,66,193,0.1)">
                        <i class="bi bi-award fs-4" style="color:#6f42c1"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($totalXp) }}</h3>
                    <small class="text-muted">XP Points (Lvl {{ $currentLevel }})</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-fire text-danger fs-4 streak-fire"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $dailyStreak }}</h3>
                    <small class="text-muted">Day Streak</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Continue Learning --}}
    @if($activeEnrollments->count())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold mb-0"><i class="bi bi-play-circle me-2 text-primary"></i>Continue Learning</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($activeEnrollments as $enrollment)
                    @php $course = $enrollment->course; @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="card course-progress-card h-100 border-0 shadow-sm" style="border-left-color: {{ ['#6f42c1','#e83e8c','#fd7e14','#20c997','#0d6efd'][$loop->index % 5] }}!important;">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($course->thumbnail)
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="rounded" style="width:70px;height:50px;object-fit:cover;">
                                        @else
                                            <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:70px;height:50px;">
                                                <i class="bi bi-play-btn text-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h6 class="fw-bold mb-1 text-truncate">{{ $course->title }}</h6>
                                        <small class="text-muted">{{ $course->teacher->name ?? 'Unknown' }}</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="fw-bold">{{ $enrollment->progress_percentage }}%</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar rounded-pill" style="width: {{ $enrollment->progress_percentage }}%; background-color: {{ ['#6f42c1','#e83e8c','#fd7e14','#20c997','#0d6efd'][$loop->index % 5] }};"></div>
                                    </div>
                                </div>
                                <a href="{{ route('student.courses.learn', $enrollment) }}" class="btn btn-sm btn-primary w-100 mt-3 rounded-pill">
                                    <i class="bi bi-play-fill me-1"></i> Continue
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Badges --}}
    @if($badges && $badges->count())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold mb-0"><i class="bi bi-patch-check me-2 text-warning"></i>Recent Badges Earned</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($badges->take(6) as $badge)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="badge-card text-center p-3 rounded-3 shadow-sm h-100" style="background: {{ $badge->color ?? '#6f42c1' }}15; border: 2px solid {{ $badge->color ?? '#6f42c1' }}30;">
                            <div class="mb-2">
                                <i class="bi {{ $badge->icon ?? 'bi-trophy' }} fs-2" style="color: {{ $badge->color ?? '#6f42c1' }};"></i>
                            </div>
                            <h6 class="fw-bold mb-0 small">{{ $badge->name }}</h6>
                            <small class="text-muted">{{ $badge->xp_reward ?? 0 }} XP</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- My Enrolled Courses --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-collection me-2 text-info"></i>My Courses</h5>
                    <a href="{{ route('student.courses.my') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="card-body">
                    @if($activeEnrollments->count())
                        @foreach($activeEnrollments->take(3) as $enrollment)
                            @php $course = $enrollment->course; @endphp
                            <div class="d-flex align-items-center gap-3 py-3 @if(!$loop->last) border-bottom @endif">
                                <div class="flex-shrink-0">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="rounded" style="width:80px;height:55px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:80px;height:55px;">
                                            <i class="bi bi-play-btn text-primary"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <h6 class="fw-bold mb-1 text-truncate">{{ $course->title }}</h6>
                                    <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $course->teacher->name ?? 'N/A' }} &middot; {{ $course->category->name ?? '' }}</small>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success rounded-pill" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="badge bg-success rounded-pill">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-book text-muted" style="font-size:3rem;"></i>
                            <p class="text-muted mt-2">No courses enrolled yet. Start exploring!</p>
                            <a href="{{ route('student.courses.index') }}" class="btn btn-primary rounded-pill">Browse Courses</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Leaderboard Preview --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Leaderboard</h5>
                </div>
                <div class="card-body">
                    @if(isset($leaderboard) && count($leaderboard))
                        @foreach($leaderboard->take(5) as $index => $entry)
                            <div class="d-flex align-items-center gap-3 py-2 @if(!$loop->last) border-bottom @endif">
                                <div class="fw-bold text-center" style="width:28px;">
                                    @if($index === 0)
                                        <span class="text-warning fs-5">1st</span>
                                    @elseif($index === 1)
                                        <span class="text-secondary fs-5">2nd</span>
                                    @elseif($index === 2)
                                        <span class="text-danger fs-5">3rd</span>
                                    @else
                                        <span class="text-muted">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    @if($entry->avatar ?? false)
                                        <img src="{{ asset('storage/' . $entry->avatar) }}" class="rounded-circle" style="width:35px;height:35px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold small" style="width:35px;height:35px;">
                                            {{ substr($entry->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-bold small text-truncate">{{ $entry->name }}</div>
                                    <small class="text-muted">Lvl {{ $entry->level }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ number_format($entry->xp_points) }} XP</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-bar-chart text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 small">Leaderboard data coming soon!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3 mt-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('student.courses.index') }}" class="card border-0 shadow-sm text-decoration-none stat-card">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:55px;height:55px;">
                        <i class="bi bi-search text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Browse Courses</h6>
                        <small class="text-muted">Discover new courses</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('ai.chat.index') }}" class="card border-0 shadow-sm text-decoration-none stat-card">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:55px;height:55px;">
                        <i class="bi bi-robot text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">AI Tutor</h6>
                        <small class="text-muted">Get instant help</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('student.certificates.index') }}" class="card border-0 shadow-sm text-decoration-none stat-card">
                <div class="card-body d-flex align-items-center gap-3 py-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:55px;height:55px;">
                        <i class="bi bi-award text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">My Certificates</h6>
                        <small class="text-muted">View your achievements</small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
