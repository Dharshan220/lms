@extends('layouts.app')

@section('title', 'Leaderboard - Nano Spark')

@section('content')
@push('styles')
<style>
    .leaderboard-table { border-collapse: separate; border-spacing: 0 8px; }
    .leaderboard-table th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; border: none; padding: 8px 16px; }
    .leaderboard-table td { border: none; padding: 12px 16px; vertical-align: middle; }
    .leaderboard-table tbody tr { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s; }
    .leaderboard-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    .leaderboard-table tbody tr.current-user { background: #667eea15; border: 2px solid #667eea; }
    .rank-gold { background: linear-gradient(135deg, #ffd700, #ffb347); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.3rem; font-weight: 800; }
    .rank-silver { background: linear-gradient(135deg, #c0c0c0, #a8a8a8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.1rem; font-weight: 700; }
    .rank-bronze { background: linear-gradient(135deg, #cd7f32, #b87333); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 1.1rem; font-weight: 700; }
    .podium-card { transition: transform 0.2s; }
    .podium-card:hover { transform: translateY(-5px); }
    .welcome-section { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .xp-progress-bar { background: linear-gradient(90deg, #6f42c1, #e83e8c, #fd7e14); background-size: 200% 100%; animation: shimmer 2s ease-in-out infinite; }
    @keyframes shimmer { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
    .time-filter .nav-link { font-size: 0.8rem; font-weight: 600; }
    .time-filter .nav-link.active { background: #667eea; color: #fff; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="welcome-section rounded-4 p-4 p-md-5 mb-4 text-white shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1"><i class="bi bi-trophy me-2"></i>Leaderboard</h2>
                <p class="mb-0 opacity-75">See how you rank among your peers!</p>
            </div>
        </div>
    </div>

    {{-- Time Filter --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-pills time-filter gap-2">
            <li class="nav-item">
                <a class="nav-link {{ ($timeFilter ?? 'all') == 'week' ? 'active' : '' }} rounded-pill" href="?filter=week">This Week</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($timeFilter ?? 'all') == 'month' ? 'active' : '' }} rounded-pill" href="?filter=month">This Month</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($timeFilter ?? 'all') == 'all' || !isset($timeFilter) ? 'active' : '' }} rounded-pill" href="?filter=all">All Time</a>
            </li>
        </ul>
    </div>

    {{-- Top 3 Podium --}}
    @if(isset($topThree) && count($topThree))
        <div class="row g-4 mb-5 justify-content-center">
            @foreach($topThree as $index => $student)
                @php
                    $podiumOrder = [1, 0, 2];
                    $actualIndex = $podiumOrder[$index] ?? $index;
                    $sizes = ['150px', '130px', '110px'];
                    $gradients = [
                        'linear-gradient(135deg, #ffd700, #ffb347)',
                        'linear-gradient(135deg, #c0c0c0, #e8e8e8)',
                        'linear-gradient(135deg, #cd7f32, #daa520)'
                    ];
                    $labels = ['1st', '2nd', '3rd'];
                    $medals = ['bi-trophy-fill', 'bi-award-fill', 'bi-award'];
                @endphp
                <div class="col-md-4 col-lg-3 {{ $index === 1 ? 'order-first order-md-0' : '' }}">
                    <div class="card podium-card border-0 shadow-lg text-center h-100" style="border-radius:16px;{{ $index === 0 ? 'margin-top:-20px;' : '' }}">
                        <div class="card-body p-4">
                            <div class="position-relative d-inline-block mb-3">
                                @if($student->avatar ?? false)
                                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="rounded-circle" style="width:{{ $sizes[$actualIndex] ?? '130px' }};height:{{ $sizes[$actualIndex] ?? '130px' }};object-fit:cover;border:4px solid {{ ['#ffd700','#c0c0c0','#cd7f32'][$actualIndex] ?? '#ccc' }};">
                                @else
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width:{{ $sizes[$actualIndex] ?? '130px' }};height:{{ $sizes[$actualIndex] ?? '130px' }};font-size:{{ $actualIndex === 0 ? '3rem' : '2.2rem' }};background:{{ $gradients[$actualIndex] }};">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-50 translate-middle">
                                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $gradients[$actualIndex] }};color:{{ $actualIndex === 1 ? '#333' : '#fff' }};font-size:0.8rem;">
                                        <i class="bi {{ $medals[$actualIndex] }} me-1"></i>{{ $labels[$actualIndex] ?? '' }}
                                    </span>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $student->name }}</h5>
                            <small class="text-muted d-block mb-2">Level {{ $student->level ?? 1 }}</small>
                            <div class="d-flex justify-content-center gap-3">
                                <span class="badge bg-primary rounded-pill"><i class="bi bi-lightning-fill me-1"></i>{{ number_format($student->xp_points ?? 0) }} XP</span>
                                <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-patch-check me-1"></i>{{ $student->badges_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Full Leaderboard Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-ol me-2 text-primary"></i>Full Rankings</h5>
        </div>
        <div class="card-body">
            @if(isset($leaderboard) && count($leaderboard))
                <div class="table-responsive">
                    <table class="table leaderboard-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">Rank</th>
                                <th>Student</th>
                                <th class="text-center">Level</th>
                                <th class="text-center">XP Points</th>
                                <th class="text-center">Badges</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard as $index => $student)
                                @php
                                    $isCurrentUser = Auth::user() && Auth::user()->id === $student->id;
                                    $rank = $student->rank ?? ($index + 1);
                                @endphp
                                <tr class="{{ $isCurrentUser ? 'current-user' : '' }}">
                                    <td>
                                        @if($rank == 1)
                                            <span class="rank-gold">1st</span>
                                        @elseif($rank == 2)
                                            <span class="rank-silver">2nd</span>
                                        @elseif($rank == 3)
                                            <span class="rank-bronze">3rd</span>
                                        @else
                                            <span class="fw-bold text-muted">#{{ $rank }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-shrink-0">
                                                @if($student->avatar ?? false)
                                                    <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:40px;height:40px;background:{{ ['#6f42c1','#e83e8c','#fd7e14','#20c997','#0d6efd'][$index % 5] }};">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">
                                                    {{ $student->name }}
                                                    @if($isCurrentUser)
                                                        <span class="badge bg-primary ms-1" style="font-size:0.65rem;">You</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info rounded-pill">Lvl {{ $student->level ?? 1 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold" style="color:#6f42c1;">{{ number_format($student->xp_points ?? 0) }}</span>
                                        <small class="text-muted">XP</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark rounded-pill">
                                            <i class="bi bi-patch-check me-1"></i>{{ $student->badges_count ?? 0 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="rounded bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                        <i class="bi bi-trophy text-primary" style="font-size:2.5rem;"></i>
                    </div>
                    <h5 class="text-muted">Leaderboard is being calculated</h5>
                    <p class="text-muted mb-0">Complete courses and earn XP to appear on the leaderboard!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
