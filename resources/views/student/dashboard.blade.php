@extends('layouts.app')

@section('title', 'Student Dashboard - Nano Spark LMS')

@section('content')
<div style="max-width:1400px">
    {{-- Welcome Section with Glassmorphism --}}
    <div class="ns-card-glass animate-fadeIn" style="margin-bottom:32px;border-color:rgba(255,193,7,0.15)">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                    <span class="ns-level-badge animate-bounceIn">
                        <i class="bi bi-star-fill"></i> Level {{ $currentLevel }}
                    </span>
                    <span class="ns-xp-badge animate-bounceIn" style="animation-delay:0.1s">
                        <i class="bi bi-lightning-fill"></i> {{ number_format($totalXp) }} XP
                    </span>
                    @if($dailyStreak > 0)
                        <span class="ns-streak-badge animate-bounceIn" style="animation-delay:0.2s">
                            <i class="bi bi-fire-fill"></i> {{ $dailyStreak }} Day Streak!
                        </span>
                    @endif
                </div>
                <h2 style="font-family:var(--font-display); font-size:28px; font-weight:700; color:var(--text-primary); margin-bottom:4px; letter-spacing:-0.5px">
                    Welcome back, {{ Auth::user()->name }}!
                </h2>
                <p style="color:var(--text-muted); font-size:15px; margin-bottom:0">Continue your learning journey. You're doing great!</p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div style="display:inline-flex;align-items:center;gap:16px;background:rgba(255,193,7,0.05);padding:8px 16px 8px 8px;border-radius:50px;border:1px solid rgba(255,193,7,0.1)">
                    <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#FFC107,#FF9800);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                        @else
                            <span style="font-size:18px;font-weight:700;color:#0D0D0D;font-family:var(--font-display)">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div style="text-align:left">
                        <div style="font-size:12px;color:var(--text-muted);font-weight:500">Learning Streak</div>
                        <div style="font-size:14px;font-weight:700;color:var(--accent-primary)">{{ $dailyStreak ?? 0 }} days <i class="bi bi-fire-fill" style="color:#FF9800"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="ns-stat-card primary animate-slideUp" style="animation-delay:0.1s">
                <div class="ns-stat-icon primary"><i class="bi bi-book"></i></div>
                <div class="ns-stat-value">{{ $enrolledCourses }}</div>
                <div class="ns-stat-label">Enrolled Courses</div>
                <div class="ns-stat-change up"><i class="bi bi-arrow-up"></i> Active</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="ns-stat-card success animate-slideUp" style="animation-delay:0.2s">
                <div class="ns-stat-icon success"><i class="bi bi-check-circle"></i></div>
                <div class="ns-stat-value">{{ $totalLessonsCompleted }}</div>
                <div class="ns-stat-label">Lessons Completed</div>
                <div class="ns-stat-change up"><i class="bi bi-arrow-up"></i> Great</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="ns-stat-card info animate-slideUp" style="animation-delay:0.3s">
                <div class="ns-stat-icon info"><i class="bi bi-award"></i></div>
                <div class="ns-stat-value">{{ number_format($totalXp) }}</div>
                <div class="ns-stat-label">XP Points (Lvl {{ $currentLevel }})</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="ns-stat-card warning animate-slideUp" style="animation-delay:0.4s">
                <div class="ns-stat-icon warning"><i class="bi bi-fire"></i></div>
                <div class="ns-stat-value">{{ $dailyStreak }}</div>
                <div class="ns-stat-label">Day Streak</div>
            </div>
        </div>
    </div>

    {{-- Continue Learning & Activity Timeline --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            @if($activeEnrollments->count())
            <div class="ns-card h-100 animate-slideUp" style="animation-delay:0.2s">
                <div class="ns-card-header">
                    <h5 class="ns-card-title"><i class="bi bi-play-circle" style="color:var(--accent-primary); margin-right:8px"></i>Continue Learning</h5>
                    <a href="{{ route('student.courses.my') }}" class="ns-btn ns-btn-outline ns-btn-sm">View All</a>
                </div>
                <div class="row g-3">
                    @foreach($activeEnrollments as $enrollment)
                        @php $course = $enrollment->course; @endphp
                        <div class="col-md-6 col-xl-6">
                            <div class="ns-card-glass" style="border-left:3px solid var(--accent-primary); padding:20px">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($course->thumbnail)
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:72px;height:52px;border-radius:10px;object-fit:cover;">
                                        @else
                                            <div style="width:72px;height:52px;border-radius:10px;background:rgba(255,193,7,0.1);display:flex;align-items:center;justify-content:center">
                                                <i class="bi bi-play-btn" style="color:var(--accent-primary);font-size:20px"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h6 style="font-family:var(--font-display);font-weight:600;font-size:14px;margin-bottom:2px;color:var(--text-primary)">{{ $course->title }}</h6>
                                        <small style="color:var(--text-muted);font-size:12px">{{ $course->teacher->name ?? 'Unknown' }}</small>
                                    </div>
                                </div>
                                <div style="margin-top:14px">
                                    <div class="ns-progress-label" style="margin-bottom:6px;font-size:12px">
                                        <span>Progress</span>
                                        <span>{{ $enrollment->progress_percentage }}%</span>
                                    </div>
                                    <div class="ns-progress">
                                        <div class="ns-progress-bar" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                </div>
                                <a href="{{ route('student.courses.learn', $enrollment) }}" class="ns-btn ns-btn-primary ns-btn-sm w-100" style="margin-top:14px">
                                    <i class="bi bi-play-fill"></i> Continue
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- My Courses List --}}
            <div class="ns-card mt-4 animate-slideUp" style="animation-delay:0.3s">
                <div class="ns-card-header">
                    <h5 class="ns-card-title"><i class="bi bi-collection" style="color:var(--info); margin-right:8px"></i>My Courses</h5>
                </div>
                @if($activeEnrollments->count())
                    @foreach($activeEnrollments->take(3) as $enrollment)
                        @php $course = $enrollment->course; @endphp
                        <div class="d-flex align-items-center gap-3 py-3 @if(!$loop->last) border-bottom @endif" style="border-color:var(--border-subtle)">
                            <div class="flex-shrink-0">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width:80px;height:55px;border-radius:10px;object-fit:cover;">
                                @else
                                    <div style="width:80px;height:55px;border-radius:10px;background:rgba(255,193,7,0.1);display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-play-btn" style="color:var(--accent-primary)"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 style="font-family:var(--font-display);font-weight:600;margin-bottom:2px;color:var(--text-primary)">{{ $course->title }}</h6>
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
                        <p style="color:var(--text-muted);margin-top:12px;font-size:14px">No courses enrolled yet. Start exploring!</p>
                        <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-primary" style="margin-top:12px">Browse Courses</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Activity Timeline --}}
            <div class="ns-card mb-4 animate-slideUp" style="animation-delay:0.3s">
                <div class="ns-card-header">
                    <h5 class="ns-card-title"><i class="bi bi-activity" style="color:var(--accent-primary);margin-right:8px"></i>Activity</h5>
                </div>
                <ul class="ns-timeline">
                    <li class="ns-timeline-item">
                        <div class="ns-timeline-dot"></div>
                        <div class="ns-timeline-content">
                            <h6>Completed a lesson</h6>
                            <p>Introduction to Arduino - Getting Started</p>
                            <div class="ns-timeline-time">2 hours ago</div>
                        </div>
                    </li>
                    <li class="ns-timeline-item">
                        <div class="ns-timeline-dot" style="background:var(--success)"></div>
                        <div class="ns-timeline-content">
                            <h6>Earned a badge</h6>
                            <p>Code Explorer Badge unlocked!</p>
                            <div class="ns-timeline-time">Yesterday</div>
                        </div>
                    </li>
                    <li class="ns-timeline-item">
                        <div class="ns-timeline-dot" style="background:var(--info)"></div>
                        <div class="ns-timeline-content">
                            <h6>Started new course</h6>
                            <p>AI for Young Minds - Module 1</p>
                            <div class="ns-timeline-time">2 days ago</div>
                        </div>
                    </li>
                    <li class="ns-timeline-item">
                        <div class="ns-timeline-dot" style="background:var(--warning)"></div>
                        <div class="ns-timeline-content">
                            <h6>Quiz completed</h6>
                            <p>Scored 85% on IoT Basics Quiz</p>
                            <div class="ns-timeline-time">3 days ago</div>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Leaderboard --}}
            <div class="ns-card mb-4 animate-slideUp" style="animation-delay:0.4s">
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
    <div class="row g-3 animate-slideUp" style="animation-delay:0.5s">
        <div class="col-md-3 col-6">
            <a href="{{ route('student.courses.index') }}" class="ns-quick-action">
                <div class="ns-quick-action-icon" style="background:rgba(255,193,7,0.1);color:var(--accent-primary)"><i class="bi bi-search"></i></div>
                <div><h6>Browse Courses</h6><small>Discover new courses</small></div>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('ai.chat.index') }}" class="ns-quick-action">
                <div class="ns-quick-action-icon" style="background:rgba(0,210,106,0.1);color:var(--success)"><i class="bi bi-robot"></i></div>
                <div><h6>AI Tutor</h6><small>Get instant help</small></div>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('student.assignments.index') }}" class="ns-quick-action">
                <div class="ns-quick-action-icon" style="background:rgba(59,130,246,0.1);color:var(--info)"><i class="bi bi-clipboard-check"></i></div>
                <div><h6>Assignments</h6><small>View pending tasks</small></div>
            </a>
        </div>
        <div class="col-md-3 col-6">
            <a href="{{ route('student.certificates.index') }}" class="ns-quick-action">
                <div class="ns-quick-action-icon" style="background:rgba(255,152,0,0.1);color:var(--warning)"><i class="bi bi-award"></i></div>
                <div><h6>Certificates</h6><small>View achievements</small></div>
            </a>
        </div>
    </div>
</div>
@endsection
