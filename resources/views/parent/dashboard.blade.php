@extends('layouts.app')

@section('title', 'Parent Dashboard - Nano Spark LMS')

@section('content')
@push('styles')
<style>
    :root {
        --pd-bg: #050505;
        --pd-card: #121212;
        --pd-card-hover: #1a1a1a;
        --pd-border: #1e1e1e;
        --pd-border-light: #2a2a2a;
        --pd-accent: #FFD400;
        --pd-accent-dim: rgba(255, 212, 0, 0.12);
        --pd-accent-glow: rgba(255, 212, 0, 0.25);
        --pd-text: #f0f0f0;
        --pd-text-secondary: #a0a0a0;
        --pd-text-muted: #666666;
        --pd-success: #00e676;
        --pd-success-dim: rgba(0, 230, 118, 0.12);
        --pd-info: #40c4ff;
        --pd-info-dim: rgba(64, 196, 255, 0.12);
        --pd-warning: #ff9100;
        --pd-warning-dim: rgba(255, 145, 0, 0.12);
        --pd-purple: #b388ff;
        --pd-purple-dim: rgba(179, 136, 255, 0.12);
        --pd-radius: 14px;
        --pd-radius-sm: 10px;
        --pd-font-heading: 'Space Mono', monospace;
        --pd-font-body: 'IBM Plex Sans', sans-serif;
        --pd-font-mono: 'JetBrains Mono', monospace;
    }

    .pd-wrap {
        background: var(--pd-bg);
        min-height: 100vh;
        padding: 28px 32px 48px;
        font-family: var(--pd-font-body);
        color: var(--pd-text);
    }

    .pd-welcome {
        background: linear-gradient(135deg, #1a1a0a 0%, #121212 50%, #0a0a12 100%);
        border: 1px solid var(--pd-border-light);
        border-radius: var(--pd-radius);
        padding: 36px 40px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .pd-welcome::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, var(--pd-accent-dim) 0%, transparent 70%);
        border-radius: 50%;
    }

    .pd-welcome::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: 40%;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, var(--pd-accent-dim) 0%, transparent 70%);
        border-radius: 50%;
    }

    .pd-welcome-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .pd-welcome h1 {
        font-family: var(--pd-font-heading);
        font-size: 26px;
        font-weight: 700;
        color: var(--pd-text);
        margin-bottom: 6px;
    }

    .pd-welcome h1 span { color: var(--pd-accent); }

    .pd-welcome p {
        color: var(--pd-text-secondary);
        font-size: 14px;
        margin: 0;
    }

    .pd-welcome-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--pd-accent-dim);
        border: 2px solid var(--pd-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--pd-font-heading);
        font-size: 28px;
        font-weight: 700;
        color: var(--pd-accent);
        flex-shrink: 0;
    }

    .pd-welcome-avatar img {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
    }

    .pd-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .pd-stat-card {
        background: var(--pd-card);
        border: 1px solid var(--pd-border);
        border-radius: var(--pd-radius);
        padding: 22px 20px;
        transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
        position: relative;
        overflow: hidden;
    }

    .pd-stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--pd-border-light);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .pd-stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
    }

    .pd-stat-card.accent::after { background: var(--pd-accent); }
    .pd-stat-card.success::after { background: var(--pd-success); }
    .pd-stat-card.info::after { background: var(--pd-info); }
    .pd-stat-card.purple::after { background: var(--pd-purple); }

    .pd-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--pd-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
    }

    .pd-stat-icon.accent { background: var(--pd-accent-dim); color: var(--pd-accent); }
    .pd-stat-icon.success { background: var(--pd-success-dim); color: var(--pd-success); }
    .pd-stat-icon.info { background: var(--pd-info-dim); color: var(--pd-info); }
    .pd-stat-icon.purple { background: var(--pd-purple-dim); color: var(--pd-purple); }

    .pd-stat-value {
        font-family: var(--pd-font-mono);
        font-size: 28px;
        font-weight: 700;
        color: var(--pd-text);
        line-height: 1;
        margin-bottom: 4px;
    }

    .pd-stat-label {
        font-size: 13px;
        color: var(--pd-text-muted);
    }

    .pd-stat-change {
        font-family: var(--pd-font-mono);
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 20px;
    }

    .pd-stat-change.up { background: var(--pd-success-dim); color: var(--pd-success); }

    .pd-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .pd-section-title {
        font-family: var(--pd-font-heading);
        font-size: 18px;
        font-weight: 700;
        color: var(--pd-text);
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .pd-section-title i { color: var(--pd-accent); font-size: 20px; }

    .pd-children-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(460px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .pd-child-card {
        background: var(--pd-card);
        border: 1px solid var(--pd-border);
        border-radius: var(--pd-radius);
        padding: 24px;
        transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
    }

    .pd-child-card:hover {
        transform: translateY(-3px);
        border-color: var(--pd-border-light);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .pd-child-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .pd-child-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--pd-font-heading);
        font-size: 20px;
        font-weight: 700;
        flex-shrink: 0;
        color: #050505;
    }

    .pd-child-avatar img {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
    }

    .pd-child-name {
        font-family: var(--pd-font-heading);
        font-size: 16px;
        font-weight: 700;
        color: var(--pd-text);
        margin-bottom: 4px;
    }

    .pd-child-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pd-child-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--pd-border);
        color: var(--pd-text-secondary);
    }

    .pd-child-xp-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        background: var(--pd-border);
        border-radius: var(--pd-radius-sm);
        margin-bottom: 16px;
    }

    .pd-level-badge {
        font-family: var(--pd-font-mono);
        font-size: 13px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .pd-xp-text {
        font-family: var(--pd-font-mono);
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .pd-course-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
    }

    .pd-course-item + .pd-course-item { border-top: 1px solid var(--pd-border); }

    .pd-course-info { flex: 1; min-width: 0; }

    .pd-course-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--pd-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 6px;
    }

    .pd-progress-track {
        width: 100%;
        height: 5px;
        background: var(--pd-border);
        border-radius: 10px;
        overflow: hidden;
    }

    .pd-progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .pd-progress-fill.accent { background: var(--pd-accent); }
    .pd-progress-fill.success { background: var(--pd-success); }
    .pd-progress-fill.info { background: var(--pd-info); }
    .pd-progress-fill.purple { background: var(--pd-purple); }

    .pd-course-pct {
        font-family: var(--pd-font-mono);
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
        min-width: 36px;
        text-align: right;
    }

    .pd-activity-list {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--pd-border);
    }

    .pd-activity-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--pd-text-muted);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pd-activity-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 4px 0;
    }

    .pd-activity-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }

    .pd-activity-text {
        font-size: 12px;
        color: var(--pd-text-secondary);
        line-height: 1.4;
    }

    .pd-activity-time {
        font-family: var(--pd-font-mono);
        font-size: 10px;
        color: var(--pd-text-muted);
        margin-top: 2px;
    }

    .pd-child-actions {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .pd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: var(--pd-radius-sm);
        font-family: var(--pd-font-body);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }

    .pd-btn-accent {
        background: var(--pd-accent);
        color: #050505;
    }

    .pd-btn-accent:hover {
        background: #e6bf00;
        box-shadow: 0 4px 16px var(--pd-accent-glow);
        color: #050505;
    }

    .pd-btn-ghost {
        background: var(--pd-border);
        color: var(--pd-text-secondary);
        border: 1px solid var(--pd-border-light);
    }

    .pd-btn-ghost:hover {
        background: var(--pd-card-hover);
        color: var(--pd-text);
        border-color: var(--pd-border-light);
    }

    .pd-actions-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .pd-action-card {
        background: var(--pd-card);
        border: 1px solid var(--pd-border);
        border-radius: var(--pd-radius);
        padding: 22px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
        transition: all 0.25s;
    }

    .pd-action-card:hover {
        transform: translateY(-2px);
        border-color: var(--pd-accent);
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    .pd-action-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--pd-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .pd-action-title {
        font-family: var(--pd-font-heading);
        font-size: 14px;
        font-weight: 700;
        color: var(--pd-text);
        margin-bottom: 2px;
    }

    .pd-action-desc {
        font-size: 12px;
        color: var(--pd-text-muted);
    }

    .pd-empty-state {
        text-align: center;
        padding: 56px 24px;
        background: var(--pd-card);
        border: 1px dashed var(--pd-border-light);
        border-radius: var(--pd-radius);
    }

    .pd-empty-state i {
        font-size: 48px;
        color: var(--pd-text-muted);
        margin-bottom: 16px;
        opacity: 0.4;
    }

    .pd-empty-state h5 {
        font-family: var(--pd-font-heading);
        color: var(--pd-text-secondary);
        font-size: 16px;
        margin-bottom: 8px;
    }

    .pd-empty-state p {
        color: var(--pd-text-muted);
        font-size: 13px;
        max-width: 340px;
        margin: 0 auto;
    }

    @media (max-width: 992px) {
        .pd-stats-row { grid-template-columns: repeat(2, 1fr); }
        .pd-actions-grid { grid-template-columns: 1fr; }
        .pd-children-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
        .pd-wrap { padding: 20px 16px 40px; }
        .pd-welcome { padding: 24px 20px; }
        .pd-welcome h1 { font-size: 20px; }
        .pd-stats-row { grid-template-columns: 1fr 1fr; gap: 10px; }
        .pd-stat-card { padding: 16px 14px; }
        .pd-stat-value { font-size: 22px; }
        .pd-child-card { padding: 18px; }
        .pd-child-actions { flex-direction: column; }
    }
</style>
@endpush

<div class="pd-wrap">

    {{-- Welcome Section --}}
    <div class="pd-welcome animate-fadeIn">
        <div class="pd-welcome-content">
            <div>
                <h1>Welcome, <span>{{ Auth::user()->name }}</span></h1>
                <p>Monitor your children's learning progress and achievements.</p>
            </div>
            <div class="pd-welcome-avatar">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="pd-stats-row">
        <div class="pd-stat-card accent">
            <div class="pd-stat-icon accent"><i class="bi bi-people-fill"></i></div>
            <div class="pd-stat-value">{{ $stats['children_count'] ?? $children->count() ?? 0 }}</div>
            <div class="pd-stat-label">My Children</div>
            <div class="pd-stat-change up"><i class="bi bi-arrow-up-short"></i> Active</div>
        </div>
        <div class="pd-stat-card success">
            <div class="pd-stat-icon success"><i class="bi bi-book-half"></i></div>
            <div class="pd-stat-value">{{ $stats['active_courses'] ?? 0 }}</div>
            <div class="pd-stat-label">Active Courses</div>
            <div class="pd-stat-change up"><i class="bi bi-arrow-up-short"></i> Enrolled</div>
        </div>
        <div class="pd-stat-card info">
            <div class="pd-stat-icon info"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="pd-stat-value">{{ $stats['average_progress'] ?? 0 }}%</div>
            <div class="pd-stat-label">Average Progress</div>
            <div class="pd-stat-change up"><i class="bi bi-arrow-up-short"></i> Growing</div>
        </div>
        <div class="pd-stat-card purple">
            <div class="pd-stat-icon purple"><i class="bi bi-award-fill"></i></div>
            <div class="pd-stat-value">{{ $stats['certificates_earned'] ?? 0 }}</div>
            <div class="pd-stat-label">Certificates Earned</div>
            <div class="pd-stat-change up"><i class="bi bi-arrow-up-short"></i> Total</div>
        </div>
    </div>

    {{-- Children Progress --}}
    <div class="pd-section-header">
        <h2 class="pd-section-title"><i class="bi bi-people-fill"></i> Children Progress</h2>
        @if(isset($children) && $children->count())
            <span style="font-family:var(--pd-font-mono);font-size:12px;color:var(--pd-text-muted);">{{ $children->count() }} {{ Str::plural('child', $children->count()) }}</span>
        @endif
    </div>

    @if(isset($children) && $children->count())
        <div class="pd-children-grid">
            @php
                $palette = [
                    ['bg' => '#FFD400', 'fill' => 'accent'],
                    ['bg' => '#00e676', 'fill' => 'success'],
                    ['bg' => '#40c4ff', 'fill' => 'info'],
                    ['bg' => '#b388ff', 'fill' => 'purple'],
                    ['bg' => '#ff9100', 'fill' => 'accent'],
                ];
            @endphp
            @foreach($children as $child)
                @php
                    $colorSet = $palette[$loop->index % count($palette)];
                    $enrollments = $child->enrollments ?? $child->enrolledCourses ?? collect();
                    $avgProgress = $child->average_progress ?? ($enrollments->count() > 0 ? round($enrollments->avg('progress_percentage'), 0) : 0);
                    $activities = $child->recentActivities ?? collect();
                @endphp
                <div class="pd-child-card">
                    {{-- Header --}}
                    <div class="pd-child-header">
                        <div class="pd-child-avatar" style="background:{{ $colorSet['bg'] }}; color:#050505;">
                            @if($child->avatar)
                                <img src="{{ asset('storage/' . $child->avatar) }}" alt="{{ $child->name }}">
                            @else
                                {{ strtoupper(substr($child->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="pd-child-name">{{ $child->name }}</div>
                            <div class="pd-child-meta">
                                @if($child->grade)
                                    <span class="pd-child-badge"><i class="bi bi-mortarboard"></i> {{ $child->grade }}</span>
                                @endif
                                @if($child->school)
                                    <span class="pd-child-badge"><i class="bi bi-building"></i> {{ is_object($child->school) ? $child->school->name : $child->school }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- XP / Level --}}
                    <div class="pd-child-xp-row">
                        <span class="pd-level-badge" style="background:{{ $colorSet['bg'] }}20; color:{{ $colorSet['bg'] }};">
                            <i class="bi bi-star-fill"></i> Level {{ $child->level ?? 1 }}
                        </span>
                        <span class="pd-xp-text" style="color:{{ $colorSet['bg'] }};">
                            <i class="bi bi-lightning-fill"></i> {{ number_format($child->xp_points ?? 0) }} XP
                        </span>
                    </div>

                    {{-- Enrolled Courses --}}
                    @if($enrollments->count())
                        <div style="margin-bottom:4px;">
                            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--pd-text-muted);margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                                <i class="bi bi-collection-play"></i> {{ $enrollments->count() }} {{ Str::plural('Course', $enrollments->count()) }}
                            </div>
                            @foreach($enrollments->take(4) as $enrollment)
                                @php $course = $enrollment->course ?? null; @endphp
                                <div class="pd-course-item">
                                    <div class="pd-course-info">
                                        <div class="pd-course-name">{{ $course->title ?? 'Course' }}</div>
                                        <div class="pd-progress-track">
                                            <div class="pd-progress-fill {{ $colorSet['fill'] }}" style="width:{{ $enrollment->progress_percentage ?? 0 }}%;"></div>
                                        </div>
                                    </div>
                                    <div class="pd-course-pct" style="color:{{ $colorSet['bg'] }};">{{ $enrollment->progress_percentage ?? 0 }}%</div>
                                </div>
                            @endforeach
                            @if($enrollments->count() > 4)
                                <div style="font-size:11px;color:var(--pd-text-muted);text-align:center;padding-top:8px;">+{{ $enrollments->count() - 4 }} more courses</div>
                            @endif
                        </div>
                    @else
                        <div style="font-size:12px;color:var(--pd-text-muted);padding:12px 0;text-align:center;">
                            <i class="bi bi-book me-1"></i> No courses enrolled yet
                        </div>
                    @endif

                    {{-- Overall Progress --}}
                    <div style="margin-top:12px;padding:12px 14px;background:var(--pd-border);border-radius:var(--pd-radius-sm);display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-size:12px;color:var(--pd-text-secondary);">Overall Progress</span>
                        <span style="font-family:var(--pd-font-mono);font-size:14px;font-weight:700;color:{{ $colorSet['bg'] }};">{{ $avgProgress }}%</span>
                    </div>

                    {{-- Recent Activity --}}
                    @if($activities->count())
                        <div class="pd-activity-list">
                            <div class="pd-activity-label"><i class="bi bi-clock-history"></i> Recent Activity</div>
                            @foreach($activities->take(3) as $activity)
                                <div class="pd-activity-item">
                                    <div class="pd-activity-dot" style="background:{{ $colorSet['bg'] }};"></div>
                                    <div>
                                        <div class="pd-activity-text">{{ Str::limit($activity->description ?? $activity->action ?? '', 60) }}</div>
                                        @if($activity->created_at)
                                            <div class="pd-activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="pd-child-actions">
                        <a href="{{ route('parent.child.progress', $child->id) }}" class="pd-btn pd-btn-accent">
                            <i class="bi bi-eye"></i> View Progress
                        </a>
                        <a href="{{ route('parent.reports') }}" class="pd-btn pd-btn-ghost">
                            <i class="bi bi-bar-chart-line"></i> Reports
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="pd-empty-state">
            <i class="bi bi-people"></i>
            <h5>No Children Linked</h5>
            <p>No children are linked to your account yet. Contact the administrator to link your children.</p>
        </div>
    @endif

    {{-- Quick Actions --}}
    <div class="pd-section-header">
        <h2 class="pd-section-title"><i class="bi bi-lightning-fill"></i> Quick Actions</h2>
    </div>

    <div class="pd-actions-grid">
        <a href="{{ route('parent.reports') }}" class="pd-action-card">
            <div class="pd-action-icon" style="background:var(--pd-accent-dim);color:var(--pd-accent);">
                <i class="bi bi-bar-chart-line"></i>
            </div>
            <div>
                <div class="pd-action-title">View Reports</div>
                <div class="pd-action-desc">Detailed academic progress</div>
            </div>
        </a>

        <a href="javascript:void(0)" class="pd-action-card" onclick="alert('Messages feature coming soon!')">
            <div class="pd-action-icon" style="background:var(--pd-info-dim);color:var(--pd-info);">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div>
                <div class="pd-action-title">Messages</div>
                <div class="pd-action-desc">Contact teachers & staff</div>
            </div>
        </a>

        <a href="{{ route('profile.edit') }}" class="pd-action-card">
            <div class="pd-action-icon" style="background:var(--pd-purple-dim);color:var(--pd-purple);">
                <i class="bi bi-gear"></i>
            </div>
            <div>
                <div class="pd-action-title">Settings</div>
                <div class="pd-action-desc">Manage your account</div>
            </div>
        </a>
    </div>

</div>
@endsection
