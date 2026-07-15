@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .xp-bar { background: linear-gradient(90deg, #6f42c1, #e83e8c, #fd7e14); background-size: 200% 100%; animation: shimmer 2s ease-in-out infinite; border-radius: 20px; }
    @keyframes shimmer { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
    .profile-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .badge-card { transition: transform 0.2s; }
    .badge-card:hover { transform: scale(1.05); }
    .stat-box { transition: transform 0.2s; }
    .stat-box:hover { transform: translateY(-3px); }
    .timeline-item { position: relative; padding-left: 30px; }
    .timeline-item::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: #dee2e6; }
    .timeline-item::after { content: ''; position: absolute; left: 5px; top: 6px; width: 12px; height: 12px; border-radius: 50%; background: #0d6efd; border: 2px solid white; }
    .timeline-item:last-child::before { bottom: 50%; }
</style>
@endpush

<div class="container py-4">
    <div class="profile-card rounded-4 p-4 p-md-5 mb-4 text-white shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow" style="width:100px;height:100px;">
                    @if($student->avatar)
                        <img src="{{ asset('storage/' . $student->avatar) }}" alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                    @else
                        <span class="display-4 text-primary fw-bold">{{ substr($student->name, 0, 1) }}</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold mb-1">{{ $student->name }}</h2>
                <p class="mb-1 opacity-75"><i class="bi bi-envelope me-1"></i> {{ $student->email }}</p>
                @if($student->school)
                    <p class="mb-1 opacity-75"><i class="bi bi-building me-1"></i> {{ $student->school->name }}</p>
                @endif
                @if($student->grade)
                    <p class="mb-0 opacity-75"><i class="bi bi-bookmark me-1"></i> Grade: {{ $student->grade }}</p>
                @endif
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('student.profile.edit') }}" class="btn btn-light rounded-pill px-4">
                    <i class="bi bi-pencil me-1"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                        <span class="display-6 fw-bold text-primary">L{{ $student->level }}</span>
                    </div>
                    <div class="mt-1"><small class="text-muted">Level {{ $student->level }}</small></div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold">{{ number_format($student->xp_points) }} / {{ number_format(($student->level) * 1000) }} XP</span>
                        <span class="text-muted">Level {{ $student->level + 1 }}</span>
                    </div>
                    <div class="progress" style="height:12px;">
                        <div class="progress-bar xp-bar" style="width:{{ min(($student->xp_points / max(($student->level) * 1000, 1)) * 100, 100) }}%"></div>
                    </div>
                </div>
                <div class="col-md-2 text-center mt-3 mt-md-0">
                    <div class="fs-4 fw-bold text-danger"><i class="bi bi-fire"></i> {{ $student->daily_streak ?? 0 }}</div>
                    <small class="text-muted">Day Streak</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-box">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-book text-primary fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $enrollmentsCount }}</h3>
                    <small class="text-muted">Enrolled</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-box">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $completedCount }}</h3>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-box">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-clock text-warning fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($student->xp_points / 10) }}</h3>
                    <small class="text-muted">Total Hours</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-box">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:50px;height:50px;">
                        <i class="bi bi-award text-danger fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $student->badges->count() }}</h3>
                    <small class="text-muted">Badges</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-patch-check me-2 text-warning"></i>Badge Collection</h5>
                </div>
                <div class="card-body">
                    @if($student->badges->count())
                        <div class="row g-3">
                            @foreach($student->badges as $badge)
                                <div class="col-4">
                                    <div class="badge-card text-center p-3 rounded-3 shadow-sm h-100" style="background: {{ $badge->color ?? '#6f42c1' }}15; border: 2px solid {{ $badge->color ?? '#6f42c1' }}30;">
                                        <i class="bi {{ $badge->icon ?? 'bi-trophy' }} fs-2" style="color: {{ $badge->color ?? '#6f42c1' }};"></i>
                                        <h6 class="fw-bold mb-0 mt-2 small">{{ $badge->name }}</h6>
                                        <small class="text-muted">{{ $badge->xp_reward ?? 0 }} XP</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-patch text-muted" style="font-size:3rem;"></i>
                            <p class="text-muted mt-2">No badges earned yet. Keep learning!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if(isset($student->xpTransactions) && $student->xpTransactions->count())
                        @foreach($student->xpTransactions->take(8) as $transaction)
                            <div class="timeline-item mb-4">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold small">{{ $transaction->description ?? 'XP earned' }}</span>
                                    <span class="badge bg-success">+{{ $transaction->xp_amount ?? 0 }} XP</span>
                                </div>
                                <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clock text-muted" style="font-size:3rem;"></i>
                            <p class="text-muted mt-2">No activity yet. Start learning!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
