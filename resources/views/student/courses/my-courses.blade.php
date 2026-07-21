@extends('layouts.app')

@section('title', 'My Courses - Nano Spark LMS')

@section('content')
@push('styles')
<style>
    .ns-my-header {
        padding: 32px;
    }

    .ns-my-header h1 {
        font-family: var(--font-heading);
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .ns-my-header h1 i {
        color: var(--accent-primary);
        margin-right: 10px;
    }

    .ns-my-header p {
        color: var(--text-muted);
        font-size: 14px;
    }

    .ns-my-tabs {
        display: flex;
        gap: 4px;
        padding: 4px;
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        width: fit-content;
    }

    .ns-my-tab {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: var(--font-heading);
    }

    .ns-my-tab:hover {
        color: var(--text-primary);
        background: rgba(255,255,255,0.03);
    }

    .ns-my-tab.active {
        background: var(--accent-primary);
        color: #050505;
    }

    .ns-my-tab .ns-tab-count {
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 6px;
        background: rgba(255,255,255,0.1);
        font-weight: 700;
    }

    .ns-my-tab.active .ns-tab-count {
        background: rgba(5,5,5,0.15);
    }

    .ns-enroll-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .ns-enroll-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .ns-enroll-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255,212,0,0.15);
        box-shadow: 0 16px 32px rgba(0,0,0,0.25);
    }

    .ns-enroll-thumb {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .ns-enroll-thumb i {
        font-size: 38px;
        color: rgba(255,255,255,0.9);
        filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));
    }

    .ns-enroll-thumb::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: linear-gradient(to top, var(--bg-card), transparent);
    }

    .ns-enroll-status {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        font-family: var(--font-heading);
        z-index: 2;
        backdrop-filter: blur(8px);
    }

    .ns-enroll-status.completed {
        background: rgba(0,210,106,0.15);
        color: #00D26A;
        border: 1px solid rgba(0,210,106,0.2);
    }

    .ns-enroll-status.in-progress {
        background: rgba(255,212,0,0.15);
        color: #FFD400;
        border: 1px solid rgba(255,212,0,0.2);
    }

    .ns-enroll-body {
        padding: 16px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .ns-enroll-category {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--accent-primary);
        margin-bottom: 6px;
        font-family: var(--font-heading);
    }

    .ns-enroll-title {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .ns-enroll-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .ns-enroll-title a:hover {
        color: var(--accent-primary);
    }

    .ns-enroll-teacher {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }

    .ns-enroll-teacher-avatar {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(255,212,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 700;
        color: var(--accent-primary);
        font-family: var(--font-heading);
    }

    .ns-enroll-teacher span {
        font-size: 12px;
        color: var(--text-muted);
    }

    .ns-enroll-progress {
        margin-top: auto;
    }

    .ns-enroll-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .ns-enroll-progress-label {
        font-size: 12px;
        color: var(--text-muted);
    }

    .ns-enroll-progress-value {
        font-size: 13px;
        font-weight: 700;
        font-family: var(--font-heading);
    }

    .ns-enroll-progress-value.complete {
        color: var(--success);
    }

    .ns-enroll-progress-value.active {
        color: var(--accent-primary);
    }

    .ns-enroll-progress-bar {
        height: 6px;
        background: rgba(255,255,255,0.06);
        border-radius: 100px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .ns-enroll-progress-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ns-enroll-progress-fill.complete {
        background: linear-gradient(90deg, #00D26A, #059669);
    }

    .ns-enroll-progress-fill.active {
        background: linear-gradient(90deg, var(--accent-primary), #FF9800);
    }

    .ns-enroll-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        font-family: var(--font-heading);
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .ns-enroll-action.continue {
        background: var(--accent-primary);
        color: #050505;
    }

    .ns-enroll-action.continue:hover {
        background: #FFC000;
        box-shadow: 0 4px 16px rgba(255,212,0,0.3);
    }

    .ns-enroll-action.review {
        background: rgba(0,210,106,0.1);
        color: var(--success);
        border: 1px solid rgba(0,210,106,0.2);
    }

    .ns-enroll-action.review:hover {
        background: rgba(0,210,106,0.15);
    }

    .ns-my-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
    }

    .ns-my-empty i {
        font-size: 56px;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 16px;
        display: block;
    }

    .ns-my-empty h3 {
        font-family: var(--font-heading);
        font-size: 18px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .ns-my-empty p {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 20px;
    }

    .ns-enroll-timestamp {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ns-pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .ns-pagination a,
    .ns-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        font-family: var(--font-heading);
    }

    .ns-pagination a {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        color: var(--text-muted);
    }

    .ns-pagination a:hover {
        border-color: var(--accent-primary);
        color: var(--accent-primary);
        background: rgba(255,212,0,0.05);
    }

    .ns-pagination .active {
        background: var(--accent-primary);
        color: #050505;
        border: 1px solid var(--accent-primary);
    }

    .ns-pagination .disabled {
        opacity: 0.3;
        pointer-events: none;
    }

    @media (max-width: 1200px) {
        .ns-enroll-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .ns-enroll-grid { grid-template-columns: 1fr; }
        .ns-my-header { padding: 20px 16px; }
        .ns-my-tabs { overflow-x: auto; width: 100%; }
    }
</style>
@endpush

<div class="ns-page-content" style="padding: 0 0 32px">
    <div style="max-width: 1400px; margin: 0 auto">
        <div class="ns-my-header" style="padding: 32px 32px 0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h1><i class="bi bi-collection-play"></i>My Courses</h1>
                    <p>Track your enrolled courses and continue learning.</p>
                </div>
                <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-outline" style="padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center;">
                    <i class="bi bi-plus-circle" style="margin-right: 6px"></i>Enroll New Course
                </a>
            </div>

            <div style="margin-top: 20px">
                <div class="ns-my-tabs">
                    <a href="{{ route('student.courses.my') }}" class="ns-my-tab {{ !request('filter') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap"></i>All
                        <span class="ns-tab-count">{{ $enrollments->total() }}</span>
                    </a>
                    <a href="{{ route('student.courses.my', ['filter' => 'in_progress']) }}" class="ns-my-tab {{ request('filter') == 'in_progress' ? 'active' : '' }}">
                        <i class="bi bi-hourglass-split"></i>In Progress
                    </a>
                    <a href="{{ route('student.courses.my', ['filter' => 'completed']) }}" class="ns-my-tab {{ request('filter') == 'completed' ? 'active' : '' }}">
                        <i class="bi bi-check-circle"></i>Completed
                    </a>
                </div>
            </div>
        </div>

        <div style="padding: 24px 32px 0">
            @if($enrollments->count())
                <div class="ns-enroll-grid">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $categoryColors = [
                                'iot' => 'linear-gradient(135deg, #FFD400, #F59E0B)',
                                'robotics' => 'linear-gradient(135deg, #00D26A, #059669)',
                                'ai' => 'linear-gradient(135deg, #818CF8, #6366F1)',
                                'coding' => 'linear-gradient(135deg, #F472B6, #EC4899)',
                                'electronics' => 'linear-gradient(135deg, #FB923C, #EA580C)',
                            ];
                            $catSlug = strtolower($course->category->name ?? 'general');
                            $thumbGradient = $categoryColors[$catSlug] ?? 'linear-gradient(135deg, #FFD400, #FF9800)';
                            $catIcons = [
                                'iot' => 'bi-wifi',
                                'robotics' => 'bi-robot',
                                'ai' => 'bi-braces-asterisk',
                                'coding' => 'bi-code-slash',
                                'electronics' => 'bi-motherboard',
                            ];
                            $thumbIcon = $catIcons[$catSlug] ?? 'bi-book';
                            $isComplete = $enrollment->progress_percentage >= 100;
                        @endphp
                        <div class="ns-enroll-card">
                            <div class="ns-enroll-thumb" style="background: {{ $thumbGradient }}">
                                <i class="bi {{ $thumbIcon }}"></i>
                                <span class="ns-enroll-status {{ $isComplete ? 'completed' : 'in-progress' }}">
                                    {{ $isComplete ? 'Completed' : 'In Progress' }}
                                </span>
                            </div>

                            <div class="ns-enroll-body">
                                <div class="ns-enroll-category">{{ $course->category->name ?? 'General' }}</div>

                                <h3 class="ns-enroll-title">
                                    <a href="{{ route('student.courses.show', $course) }}">{{ $course->title }}</a>
                                </h3>

                                <div class="ns-enroll-teacher">
                                    <div class="ns-enroll-teacher-avatar">
                                        {{ strtoupper(substr($course->teacher->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $course->teacher->name ?? 'N/A' }}</span>
                                </div>

                                <div class="ns-enroll-progress">
                                    <div class="ns-enroll-progress-header">
                                        <span class="ns-enroll-progress-label">Progress</span>
                                        <span class="ns-enroll-progress-value {{ $isComplete ? 'complete' : 'active' }}">
                                            {{ $enrollment->progress_percentage }}%
                                        </span>
                                    </div>
                                    <div class="ns-enroll-progress-bar">
                                        <div class="ns-enroll-progress-fill {{ $isComplete ? 'complete' : 'active' }}" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>

                                    @if($isComplete)
                                        <a href="{{ route('student.courses.show', $course) }}" class="ns-enroll-action review">
                                            <i class="bi bi-arrow-repeat"></i>Review Course
                                        </a>
                                    @else
                                        <a href="{{ route('student.courses.learn', $enrollment) }}" class="ns-enroll-action continue">
                                            <i class="bi bi-play-fill"></i>Continue Learning
                                        </a>
                                    @endif

                                    @if($enrollment->enrolled_at)
                                        <div class="ns-enroll-timestamp">
                                            <i class="bi bi-clock"></i>
                                            Last accessed {{ $enrollment->enrolled_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="ns-pagination">
                    {{ $enrollments->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="ns-my-empty">
                    <i class="bi bi-book"></i>
                    <h3>No courses here yet</h3>
                    <p>You haven't enrolled in any courses yet. Start your learning journey today!</p>
                    <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-primary" style="padding: 10px 24px; border-radius: 10px; display: inline-flex; align-items: center;">
                        <i class="bi bi-search" style="margin-right: 8px"></i>Browse Courses
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
