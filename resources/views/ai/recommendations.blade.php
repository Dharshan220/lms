@extends('layouts.app')

@section('title', 'AI Learning Recommendations - Nano Spark')

@section('content')
@push('styles')
<style>
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
    .course-rec-card { transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid; }
    .course-rec-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .path-step { position: relative; padding-left: 40px; }
    .path-step::before { content: ''; position: absolute; left: 14px; top: 32px; bottom: -16px; width: 2px; background: #dee2e6; }
    .path-step:last-child::before { display: none; }
    .path-dot { position: absolute; left: 0; top: 6px; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: #fff; }
    .weak-area-bar { height: 8px; border-radius: 4px; background: #e9ecef; }
    .weak-area-fill { height: 100%; border-radius: 4px; transition: width 0.5s; }
    .welcome-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
            <i class="bi bi-compass text-primary" style="font-size:1.4rem;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1">AI Learning Recommendations</h4>
            <p class="text-muted mb-0">Personalized suggestions to accelerate your learning</p>
        </div>
    </div>

    {{-- Stats Overview --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-bar-chart-line text-primary fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $skillLevel ?? 'Beginner' }}</h4>
                    <small class="text-muted">Current Level</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-check-circle text-success fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $completedCourses ?? 0 }}</h4>
                    <small class="text-muted">Courses Done</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-rocket text-warning fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $recommendationsCount ?? 0 }}</h4>
                    <small class="text-muted">Recommendations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-clock text-info fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $estimatedTimeToNextLevel ?? 'N/A' }}</h4>
                    <small class="text-muted">To Next Level</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Recommended Next Courses --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-book me-2 text-primary"></i>Recommended Courses</h5>
                    <small class="text-muted">Based on your skills, interests, and completed courses</small>
                </div>
                <div class="card-body">
                    @if(isset($recommendedCourses) && count($recommendedCourses))
                        <div class="row g-3">
                            @foreach($recommendedCourses as $index => $course)
                                @php
                                    $colors = ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#0d6efd'];
                                    $color = $colors[$index % count($colors)];
                                    $match = $course['match_percentage'] ?? rand(60, 95);
                                @endphp
                                <div class="col-md-6">
                                    <div class="card course-rec-card h-100 border-0 shadow-sm" style="border-left-color:{{ $color }}!important;">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge rounded-pill" style="background-color:{{ $color }};">{{ $course['category'] ?? 'General' }}</span>
                                                <span class="badge bg-success">{{ $match }}% Match</span>
                                            </div>
                                            <h6 class="fw-bold mb-1">{{ $course['title'] ?? '' }}</h6>
                                            <small class="text-muted d-block mb-2"><i class="bi bi-person me-1"></i>{{ $course['teacher'] ?? 'Instructor' }}</small>
                                            <p class="text-muted mb-2" style="font-size:0.8rem;">{{ Str::limit($course['description'] ?? '', 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $course['duration'] ?? 'N/A' }}</small>
                                                <a href="{{ route('student.courses.show', $course['id'] ?? 1) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                    <i class="bi bi-arrow-right"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-book text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">Complete more courses to get personalized recommendations.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Suggested Learning Path --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-signpost-split me-2 text-info"></i>Suggested Learning Path</h5>
                    <small class="text-muted">A structured roadmap to reach your goals</small>
                </div>
                <div class="card-body">
                    @if(isset($learningPath) && count($learningPath))
                        @foreach($learningPath as $index => $step)
                            @php
                                $colors = ['#20c997', '#0dcaf0', '#667eea', '#6f42c1', '#e83e8c'];
                                $color = $colors[$index % count($colors)];
                                $isCompleted = $step['completed'] ?? false;
                            @endphp
                            <div class="path-step mb-4">
                                <div class="path-dot" style="background-color:{{ $color }};">
                                    @if($isCompleted)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="card border-0 shadow-sm {{ $isCompleted ? 'bg-light' : '' }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $step['title'] ?? '' }}</h6>
                                                <small class="text-muted">{{ $step['description'] ?? '' }}</small>
                                            </div>
                                            @if($isCompleted)
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">Upcoming</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-signpost-split text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">Learning path will be generated based on your goals.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Weak Areas --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Areas to Improve</h5>
                </div>
                <div class="card-body">
                    @if(isset($weakAreas) && count($weakAreas))
                        @foreach($weakAreas as $area)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-semibold">{{ $area['topic'] ?? '' }}</small>
                                    <small class="text-muted">{{ $area['score'] ?? 0 }}%</small>
                                </div>
                                <div class="weak-area-bar">
                                    <div class="weak-area-fill" style="width:{{ $area['score'] ?? 0 }}%; background-color:{{ ($area['score'] ?? 0) > 60 ? '#ffc107' : '#dc3545' }};"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle text-success" style="font-size:2rem;"></i>
                            <p class="text-muted mt-2 mb-0 small">No weak areas identified. Keep up the great work!</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Time Estimate --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Level Progress</h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <svg width="140" height="140" viewBox="0 0 140 140">
                            <circle cx="70" cy="70" r="58" fill="none" stroke="#e9ecef" stroke-width="10"/>
                            <circle cx="70" cy="70" r="58" fill="none" stroke="#667eea" stroke-width="10" stroke-linecap="round"
                                stroke-dasharray="{{ ($levelProgress ?? 0) * 3.64 }}" stroke-dashoffset="0" transform="rotate(-90 70 70)"/>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h3 class="fw-bold mb-0">{{ $levelProgress ?? 0 }}%</h3>
                            <small class="text-muted">to next level</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around">
                        <div>
                            <h5 class="fw-bold mb-0 text-primary">{{ $currentLevel ?? 1 }}</h5>
                            <small class="text-muted">Current</small>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-secondary">{{ ($currentLevel ?? 1) + 1 }}</h5>
                            <small class="text-muted">Next</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Interests --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-heart me-2 text-danger"></i>Your Interests</h5>
                </div>
                <div class="card-body">
                    @if(isset($interests) && count($interests))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($interests as $interest)
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">{{ $interest }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0 small">Select your interests to get better recommendations.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
