@extends('layouts.app')

@section('title', 'Parent Dashboard - Nano Spark')

@section('content')
@push('styles')
<style>
    .xp-progress-bar { background: linear-gradient(90deg, #6f42c1, #e83e8c, #fd7e14); background-size: 200% 100%; animation: shimmer 2s ease-in-out infinite; }
    @keyframes shimmer { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .child-card { transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid; }
    .child-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .welcome-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .progress-gradient { background: linear-gradient(90deg, #20c997, #0dcaf0); }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Welcome Card --}}
    <div class="welcome-section rounded-4 p-4 p-md-5 mb-4 text-white shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="mb-0 opacity-75">Monitor your children's learning progress and achievements.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow" style="width:80px;height:80px;">
                    <span class="display-6 text-primary fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:55px;height:55px;">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $childrenCount ?? 0 }}</h3>
                    <small class="text-muted">My Children</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:55px;height:55px;">
                        <i class="bi bi-book text-success fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $totalCourses ?? 0 }}</h3>
                    <small class="text-muted">Total Courses</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width:55px;height:55px;">
                        <i class="bi bi-graph-up text-warning fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $averageProgress ?? 0 }}%</h3>
                    <small class="text-muted">Average Progress</small>
                </div>
            </div>
        </div>
    </div>

    {{-- My Children --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>My Children</h5>
    </div>

    @if(isset($children) && $children->count())
        <div class="row g-4">
            @foreach($children as $index => $child)
                @php
                    $colors = ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#0d6efd'];
                    $color = $colors[$index % count($colors)];
                @endphp
                <div class="col-lg-6">
                    <div class="card child-card h-100 border-0 shadow-sm" style="border-left-color: {{ $color }}!important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="flex-shrink-0">
                                    @if($child->avatar)
                                        <img src="{{ asset('storage/' . $child->avatar) }}" alt="{{ $child->name }}" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-4" style="width:60px;height:60px;background-color:{{ $color }};">
                                            {{ substr($child->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">{{ $child->name }}</h5>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-light text-dark"><i class="bi bi-mortarboard me-1"></i>{{ $child->grade ?? 'N/A' }}</span>
                                        <span class="badge bg-light text-dark"><i class="bi bi-building me-1"></i>{{ $child->school ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- XP and Level --}}
                            <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded-3" style="background:{{ $color }}10;">
                                <span class="badge fs-6" style="background-color:{{ $color }};">
                                    <i class="bi bi-star-fill me-1"></i> Level {{ $child->level ?? 1 }}
                                </span>
                                <span class="fw-semibold" style="color:{{ $color }};">
                                    <i class="bi bi-lightning-fill me-1"></i>{{ number_format($child->xp_points ?? 0) }} XP
                                </span>
                            </div>

                            {{-- Enrolled Courses --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted"><i class="bi bi-book me-1"></i>{{ $child->enrollments_count ?? $child->enrolledCourses->count() ?? 0 }} Courses Enrolled</small>
                                </div>
                            </div>

                            {{-- Progress Overview --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Overall Progress</small>
                                    <small class="fw-bold">{{ $child->average_progress ?? 0 }}%</small>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar rounded-pill" style="width:{{ $child->average_progress ?? 0 }}%; background-color:{{ $color }};"></div>
                                </div>
                            </div>

                            {{-- Recent Activity --}}
                            @if(isset($child->recentActivities) && $child->recentActivities->count())
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold d-block mb-2"><i class="bi bi-clock-history me-1"></i>Recent Activity</small>
                                    @foreach($child->recentActivities->take(3) as $activity)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="rounded-circle flex-shrink-0" style="width:6px;height:6px;background-color:{{ $color }};"></div>
                                            <small class="text-muted">{{ Str::limit($activity->description ?? $activity->action ?? '', 50) }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- View Detail Button --}}
                            <a href="{{ route('parent.child-progress', $child->id) }}" class="btn w-100 rounded-pill" style="background-color:{{ $color }};color:#fff;">
                                <i class="bi bi-eye me-1"></i> View Detailed Progress
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                    <i class="bi bi-people text-primary" style="font-size:2.5rem;"></i>
                </div>
                <h5 class="text-muted">No Children Found</h5>
                <p class="text-muted mb-0">No children are linked to your account yet. Contact the administrator to link your children.</p>
            </div>
        </div>
    @endif
</div>
@endsection
