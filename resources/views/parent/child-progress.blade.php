@extends('layouts.app')

@section('title', 'Child Progress - Nano Spark')

@section('content')
@push('styles')
<style>
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .profile-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .badge-card { transition: transform 0.2s; }
    .badge-card:hover { transform: scale(1.05); }
    .timeline-item { position: relative; padding-left: 30px; }
    .timeline-item::before { content: ''; position: absolute; left: 8px; top: 0; bottom: -20px; width: 2px; background: #dee2e6; }
    .timeline-item:last-child::before { display: none; }
    .timeline-dot { position: absolute; left: 0; top: 4px; width: 18px; height: 18px; border-radius: 50%; border: 3px solid #667eea; background: #fff; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Back Button --}}
    <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary mb-3 rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
    </a>

    {{-- Child Profile Header --}}
    <div class="profile-header rounded-4 p-4 p-md-5 mb-4 text-white shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                @if($child->avatar)
                    <img src="{{ asset('storage/' . $child->avatar) }}" alt="{{ $child->name }}" class="rounded-circle border border-3 border-white shadow" style="width:100px;height:100px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center shadow" style="width:100px;height:100px;">
                        <span class="display-5 fw-bold" style="color:#667eea;">{{ substr($child->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="col-md-10">
                <h2 class="fw-bold mb-1">{{ $child->name }}</h2>
                <div class="d-flex gap-2 flex-wrap mb-2">
                    <span class="badge bg-white text-primary fs-6"><i class="bi bi-mortarboard me-1"></i>{{ $child->grade ?? 'N/A' }}</span>
                    <span class="badge bg-white text-dark fs-6"><i class="bi bi-building me-1"></i>{{ $child->school ?? 'N/A' }}</span>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <span class="badge bg-light text-dark fs-6"><i class="bi bi-star-fill text-warning me-1"></i> Level {{ $child->level ?? 1 }}</span>
                    <span class="badge bg-light text-dark fs-6"><i class="bi bi-lightning-fill text-success me-1"></i> {{ number_format($child->xp_points ?? 0) }} XP</span>
                    <span class="badge bg-light text-dark fs-6"><i class="bi bi-fire text-danger me-1"></i> {{ $child->daily_streak ?? 0 }} Day Streak</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-book text-primary fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['coursesEnrolled'] ?? 0 }}</h4>
                    <small class="text-muted">Enrolled</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-check-circle text-success fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['coursesCompleted'] ?? 0 }}</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-lightning text-warning fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ number_format($stats['totalXp'] ?? 0) }}</h4>
                    <small class="text-muted">XP Points</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-trophy text-info fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['level'] ?? 1 }}</h4>
                    <small class="text-muted">Level</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-fire text-danger fs-4 mb-1"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['streak'] ?? 0 }}</h4>
                    <small class="text-muted">Streak</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="bi bi-patch-check text-purple fs-4 mb-1" style="color:#6f42c1;"></i>
                    <h4 class="fw-bold mb-0">{{ $stats['badgesCount'] ?? 0 }}</h4>
                    <small class="text-muted">Badges</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Enrolled Courses --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-collection me-2 text-primary"></i>Enrolled Courses</h5>
                </div>
                <div class="card-body">
                    @if(isset($enrollments) && $enrollments->count())
                        @foreach($enrollments as $enrollment)
                            @php $course = $enrollment->course; @endphp
                            <div class="d-flex align-items-center gap-3 py-3 @if(!$loop->last) border-bottom @endif">
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
                                    <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $course->teacher->name ?? 'N/A' }}</small>
                                    <div class="progress mt-2" style="height:6px;">
                                        <div class="progress-bar rounded-pill" style="width:{{ $enrollment->progress_percentage }}%; background-color:{{ ['#6f42c1','#e83e8c','#fd7e14','#20c997','#0d6efd'][$loop->index % 5] }};"></div>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="badge bg-success rounded-pill">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-book text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">No courses enrolled yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Grade Report --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-table me-2 text-success"></i>Grade Report</h5>
                </div>
                <div class="card-body">
                    @if(isset($gradeReport) && count($gradeReport))
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Assignments</th>
                                        <th>Quizzes</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gradeReport as $report)
                                        <tr>
                                            <td class="fw-semibold">{{ $report['course_name'] ?? '' }}</td>
                                            <td>{{ $report['assignments_avg'] ?? 'N/A' }}%</td>
                                            <td>{{ $report['quizzes_avg'] ?? 'N/A' }}%</td>
                                            <td>
                                                <span class="badge rounded-pill" style="background-color:{{ $report['grade_color'] ?? '#6c757d' }};">
                                                    {{ $report['grade'] ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ ($report['status'] ?? '') === 'passed' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($report['status'] ?? 'pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-table text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">No grade data available yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Activity Timeline --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Activity Timeline</h5>
                </div>
                <div class="card-body">
                    @if(isset($activities) && $activities->count())
                        @foreach($activities as $activity)
                            <div class="timeline-item mb-4">
                                <div class="timeline-dot" style="border-color:{{ ['#6f42c1','#e83e8c','#fd7e14','#20c997','#0d6efd'][$loop->index % 5] }};"></div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="fw-semibold mb-1">{{ $activity->description ?? $activity->action ?? '' }}</p>
                                        <small class="text-muted">
                                            @if($activity->course)
                                                <i class="bi bi-book me-1"></i>{{ $activity->course->title }}
                                            @endif
                                        </small>
                                    </div>
                                    <small class="text-muted flex-shrink-0">{{ $activity->created_at?->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clock-history text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">No recent activity yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Badges Earned --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-patch-check me-2 text-warning"></i>Badges Earned</h5>
                </div>
                <div class="card-body">
                    @if(isset($badges) && $badges->count())
                        <div class="row g-2">
                            @foreach($badges as $badge)
                                <div class="col-6">
                                    <div class="badge-card text-center p-3 rounded-3 shadow-sm h-100" style="background:{{ $badge->color ?? '#6f42c1' }}15; border:2px solid {{ $badge->color ?? '#6f42c1' }}30;">
                                        <div class="mb-1">
                                            <i class="bi {{ $badge->icon ?? 'bi-trophy' }} fs-4" style="color:{{ $badge->color ?? '#6f42c1' }};"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0 small" style="font-size:0.75rem;">{{ $badge->name }}</h6>
                                        <small class="text-muted" style="font-size:0.65rem;">{{ $badge->xp_reward ?? 0 }} XP</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-patch-check text-muted" style="font-size:2rem;"></i>
                            <p class="text-muted mt-2 mb-0 small">No badges earned yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Attendance Overview --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2 text-success"></i>Attendance</h5>
                </div>
                <div class="card-body">
                    @if(isset($attendance))
                        <div class="text-center mb-3">
                            <div class="position-relative d-inline-block">
                                <svg width="120" height="120" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e9ecef" stroke-width="10"/>
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#20c997" stroke-width="10" stroke-linecap="round"
                                        stroke-dasharray="{{ ($attendance['percentage'] ?? 0) * 3.14 }}"
                                        stroke-dashoffset="0" transform="rotate(-90 60 60)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h4 class="fw-bold mb-0">{{ $attendance['percentage'] ?? 0 }}%</h4>
                                    <small class="text-muted">Attendance</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <h5 class="fw-bold text-success mb-0">{{ $attendance['present'] ?? 0 }}</h5>
                                <small class="text-muted">Present</small>
                            </div>
                            <div>
                                <h5 class="fw-bold text-danger mb-0">{{ $attendance['absent'] ?? 0 }}</h5>
                                <small class="text-muted">Absent</small>
                            </div>
                            <div>
                                <h5 class="fw-bold text-warning mb-0">{{ $attendance['late'] ?? 0 }}</h5>
                                <small class="text-muted">Late</small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-check text-muted" style="font-size:2rem;"></i>
                            <p class="text-muted mt-2 mb-0 small">No attendance data available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
