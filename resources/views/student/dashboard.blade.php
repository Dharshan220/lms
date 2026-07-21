@extends('layouts.app')

@section('title', 'Student Dashboard - Nano Spark LMS')

@section('content')
<div class="ns-page-content" style="padding:32px">
    <div class="container-fluid" style="max-width:1400px">
        {{-- Welcome Section --}}
        <div class="ns-card animate-fadeIn" style="margin-bottom:32px;background:linear-gradient(135deg, rgba(255,212,0,0.08), rgba(255,152,0,0.04));border-color:rgba(255,212,0,0.15)">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 style="font-family:var(--font-heading); font-size:24px; font-weight:700; color:var(--text-primary); margin-bottom:4px">
                        Welcome back, {{ Auth::user()->name }}!
                    </h2>
                    <p style="color:var(--text-muted); margin-bottom:16px">Continue your learning journey. You're doing great!</p>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="ns-level-badge">
                            <i class="bi bi-star-fill"></i> Level {{ $currentLevel }}
                        </span>
                        <span class="ns-xp-badge">
                            <i class="bi bi-lightning-fill"></i> {{ number_format($totalXp) }} XP
                        </span>
                        @if($dailyStreak > 0)
                            <span class="ns-streak-badge">
                                <i class="bi bi-fire-fill"></i> {{ $dailyStreak }} Day Streak!
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div style="width:100px;height:100px;border-radius:50%;background:rgba(5,5,5,0.1);display:inline-flex;align-items:center;justify-content:center;">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
                        @else
                            <span style="font-size:36px;font-weight:700;color:#050505;font-family:var(--font-heading)">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="ns-stat-card primary">
                    <div class="ns-stat-icon primary"><i class="bi bi-book"></i></div>
                    <div class="ns-stat-value">{{ $enrolledCourses }}</div>
                    <div class="ns-stat-label">Enrolled Courses</div>
                    <div class="ns-stat-change up"><i class="bi bi-arrow-up"></i> Active</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ns-stat-card success">
                    <div class="ns-stat-icon success"><i class="bi bi-check-circle"></i></div>
                    <div class="ns-stat-value">{{ $totalLessonsCompleted }}</div>
                    <div class="ns-stat-label">Lessons Completed</div>
                    <div class="ns-stat-change up"><i class="bi bi-arrow-up"></i> Great</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ns-stat-card info">
                    <div class="ns-stat-icon info"><i class="bi bi-award"></i></div>
                    <div class="ns-stat-value">{{ number_format($totalXp) }}</div>
                    <div class="ns-stat-label">XP Points (Lvl {{ $currentLevel }})</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="ns-stat-card warning">
                    <div class="ns-stat-icon warning"><i class="bi bi-fire"></i></div>
                    <div class="ns-stat-value">{{ $dailyStreak }}</div>
                    <div class="ns-stat-label">Day Streak</div>
                </div>
            </div>
        </div>

        {{-- Continue Learning --}}
        @if($activeEnrollments->count())
        <div class="ns-card mb-4">
            <div class="ns-card-header">
                <h5 class="ns-card-title"><i class="bi bi-play-circle" style="color:var(--accent-primary); margin-right:8px"></i>Continue Learning</h5>
                <a href="{{ route('student.courses.my') }}" class="ns-btn ns-btn-outline ns-btn-sm">View All</a>
            </div>
            <div class="row g-3">
                @foreach($activeEnrollments as $enrollment)
                    @php $course = $enrollment->course; @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="ns-card" style="border-left:3px solid var(--accent-primary)">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:70px;height:50px;border-radius:8px;object-fit:cover;">
                                    @else
                                        <div style="width:70px;height:50px;border-radius:8px;background:rgba(255,212,0,0.1);display:flex;align-items:center;justify-content:center">
                                            <i class="bi bi-play-btn" style="color:var(--accent-primary)"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <h6 style="font-family:var(--font-heading);font-weight:600;margin-bottom:4px;color:var(--text-primary)">{{ $course->title }}</h6>
                                    <small style="color:var(--text-muted)">{{ $course->teacher->name ?? 'Unknown' }}</small>
                                </div>
                            </div>
                            <div style="margin-top:16px">
                                <div class="ns-progress-label">
                                    <span>Progress</span>
                                    <span>{{ $enrollment->progress_percentage }}%</span>
                                </div>
                                <div class="ns-progress">
                                    <div class="ns-progress-bar" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                </div>
                            </div>
                            <a href="{{ route('student.courses.learn', $enrollment) }}" class="ns-btn ns-btn-primary ns-btn-sm w-100" style="margin-top:16px">
                                <i class="bi bi-play-fill"></i> Continue
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="row g-4 mb-4">
            {{-- My Courses --}}
            <div class="col-lg-8">
                <div class="ns-card h-100">
                    <div class="ns-card-header">
                        <h5 class="ns-card-title"><i class="bi bi-collection" style="color:var(--info); margin-right:8px"></i>My Courses</h5>
                    </div>
                    @if($activeEnrollments->count())
                        @foreach($activeEnrollments->take(3) as $enrollment)
                            @php $course = $enrollment->course; @endphp
                            <div class="d-flex align-items-center gap-3 py-3 @if(!$loop->last) border-bottom @endif" style="border-color:var(--border-subtle)">
                                <div class="flex-shrink-0">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:80px;height:55px;border-radius:8px;object-fit:cover;">
                                    @else
                                        <div style="width:80px;height:55px;border-radius:8px;background:rgba(255,212,0,0.1);display:flex;align-items:center;justify-content:center">
                                            <i class="bi bi-play-btn" style="color:var(--accent-primary)"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <h6 style="font-family:var(--font-heading);font-weight:600;margin-bottom:4px;color:var(--text-primary)">{{ $course->title }}</h6>
                                    <small style="color:var(--text-muted)">{{ $course->teacher->name ?? 'N/A' }} &middot; {{ $course->category->name ?? '' }}</small>
                                    <div class="ns-progress" style="margin-top:8px">
                                        <div class="ns-progress-bar success" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="ns-badge success">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-book" style="font-size:3rem;color:var(--text-muted)"></i>
                            <p style="color:var(--text-muted);margin-top:12px">No courses enrolled yet. Start exploring!</p>
                            <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-primary" style="margin-top:12px">Browse Courses</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Leaderboard --}}
            <div class="col-lg-4">
                <div class="ns-card h-100">
                    <div class="ns-card-header">
                        <h5 class="ns-card-title"><i class="bi bi-trophy" style="color:var(--accent-primary); margin-right:8px"></i>Leaderboard</h5>
                    </div>
                    @if(isset($leaderboard) && count($leaderboard))
                        @foreach($leaderboard->take(5) as $index => $entry)
                            <div class="d-flex align-items-center gap-3 py-2 @if(!$loop->last) border-bottom @endif" style="border-color:var(--border-subtle)">
                                <div class="ns-leaderboard-rank {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : 'default')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ns-leaderboard-avatar" style="width:32px;height:32px;font-size:12px">
                                    {{ substr($entry->name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div style="font-size:13px;font-weight:600;color:var(--text-primary)">{{ $entry->name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted)">Lvl {{ $entry->level }}</div>
                                </div>
                                <span class="ns-badge primary">{{ number_format($entry->xp_points) }} XP</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-bar-chart" style="font-size:2.5rem;color:var(--text-muted)"></i>
                            <p style="color:var(--text-muted);margin-top:12px;font-size:13px">Leaderboard data coming soon!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('student.courses.index') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none; transition:all 0.3s">
                    <div class="ns-stat-icon primary" style="margin-bottom:0"><i class="bi bi-search"></i></div>
                    <div>
                        <h6 style="font-family:var(--font-heading);font-weight:600;margin-bottom:2px;color:var(--text-primary)">Browse Courses</h6>
                        <small style="color:var(--text-muted)">Discover new courses</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('ai.chat.index') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none; transition:all 0.3s">
                    <div class="ns-stat-icon success" style="margin-bottom:0"><i class="bi bi-robot"></i></div>
                    <div>
                        <h6 style="font-family:var(--font-heading);font-weight:600;margin-bottom:2px;color:var(--text-primary)">AI Tutor</h6>
                        <small style="color:var(--text-muted)">Get instant help</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('student.certificates.index') }}" class="ns-card d-flex align-items-center gap-3" style="text-decoration:none; transition:all 0.3s">
                    <div class="ns-stat-icon warning" style="margin-bottom:0"><i class="bi bi-award"></i></div>
                    <div>
                        <h6 style="font-family:var(--font-heading);font-weight:600;margin-bottom:2px;color:var(--text-primary)">My Certificates</h6>
                        <small style="color:var(--text-muted)">View achievements</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
