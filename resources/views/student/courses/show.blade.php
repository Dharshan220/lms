@extends('layouts.app')

@section('title', 'Course Details - Nano Spark LMS')

@section('content')
@php
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
@endphp
@push('styles')
<style>
    .ns-detail-hero {
        padding: 32px 32px 0;
    }

    .ns-detail-hero-inner {
        background: linear-gradient(135deg, rgba(255,212,0,0.06) 0%, rgba(255,212,0,0.01) 100%);
        border: 1px solid var(--border-subtle);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }

    .ns-detail-hero-bg {
        position: relative;
        padding: 40px;
        display: flex;
        gap: 32px;
        align-items: flex-start;
    }

    .ns-detail-hero-bg::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,212,0,0.06) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ns-detail-hero-bg::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: -40px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255,212,0,0.04) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ns-detail-thumb {
        width: 320px;
        min-width: 320px;
        height: 200px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        z-index: 2;
    }

    .ns-detail-thumb i {
        font-size: 56px;
        color: rgba(255,255,255,0.9);
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
    }

    .ns-detail-info {
        flex: 1;
        min-width: 0;
        z-index: 2;
    }

    .ns-detail-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .ns-detail-breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.2s;
    }

    .ns-detail-breadcrumb a:hover {
        color: var(--accent-primary);
    }

    .ns-detail-breadcrumb i {
        font-size: 8px;
        opacity: 0.5;
    }

    .ns-detail-category {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        font-family: var(--font-heading);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: rgba(255,212,0,0.1);
        color: var(--accent-primary);
        border: 1px solid rgba(255,212,0,0.15);
        margin-bottom: 12px;
    }

    .ns-detail-title {
        font-family: var(--font-heading);
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 10px;
        line-height: 1.2;
    }

    .ns-detail-desc {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .ns-detail-teacher {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .ns-detail-teacher-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-primary), #FF9800);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: #050505;
        font-family: var(--font-heading);
    }

    .ns-detail-teacher-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .ns-detail-teacher-label {
        font-size: 12px;
        color: var(--text-muted);
    }

    .ns-detail-stats {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .ns-detail-stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--text-muted);
        padding: 6px 14px;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
    }

    .ns-detail-stat i {
        font-size: 14px;
    }

    .ns-detail-stat strong {
        color: var(--text-primary);
        font-family: var(--font-heading);
    }

    .ns-detail-body {
        padding: 32px;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
        align-items: start;
    }

    .ns-detail-sidebar {
        position: sticky;
        top: 80px;
    }

    .ns-detail-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
    }

    .ns-detail-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ns-detail-card-header h3 {
        font-family: var(--font-heading);
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .ns-detail-card-header i {
        font-size: 16px;
        color: var(--accent-primary);
    }

    .ns-detail-card-body {
        padding: 20px;
    }

    .ns-sidebar-price {
        font-family: var(--font-heading);
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .ns-sidebar-price.free {
        color: var(--success);
    }

    .ns-sidebar-enroll-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        font-family: var(--font-heading);
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        margin-bottom: 12px;
    }

    .ns-sidebar-enroll-btn.primary {
        background: var(--accent-primary);
        color: #050505;
    }

    .ns-sidebar-enroll-btn.primary:hover {
        background: #FFC000;
        box-shadow: 0 6px 20px rgba(255,212,0,0.3);
    }

    .ns-sidebar-enroll-btn.success {
        background: var(--success);
        color: #050505;
    }

    .ns-sidebar-enroll-btn.success:hover {
        box-shadow: 0 6px 20px rgba(0,210,106,0.3);
    }

    .ns-sidebar-progress-wrap {
        margin-bottom: 12px;
    }

    .ns-sidebar-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .ns-sidebar-progress-label {
        font-size: 12px;
        color: var(--text-muted);
    }

    .ns-sidebar-progress-value {
        font-size: 13px;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--success);
    }

    .ns-sidebar-feature {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-subtle);
        font-size: 13px;
        color: var(--text-muted);
    }

    .ns-sidebar-feature:last-child {
        border-bottom: none;
    }

    .ns-sidebar-feature i {
        font-size: 16px;
        color: var(--accent-primary);
        width: 20px;
        text-align: center;
    }

    .ns-content-section {
        margin-bottom: 24px;
    }

    .ns-content-section h2 {
        font-family: var(--font-heading);
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ns-content-section h2 i {
        color: var(--accent-primary);
    }

    .ns-content-text {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.7;
    }

    .ns-learn-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .ns-learn-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 14px;
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
    }

    .ns-learn-item i {
        color: var(--success);
        font-size: 14px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .ns-learn-item span {
        font-size: 13px;
        color: var(--text-muted);
    }

    .ns-lesson-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ns-lesson-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-subtle);
        transition: background 0.15s;
    }

    .ns-lesson-item:last-child {
        border-bottom: none;
    }

    .ns-lesson-item:hover {
        background: rgba(255,255,255,0.02);
    }

    .ns-lesson-index {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--bg-elevated);
        border: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        font-family: var(--font-heading);
        flex-shrink: 0;
    }

    .ns-lesson-item.completed .ns-lesson-index {
        background: rgba(0,210,106,0.1);
        border-color: rgba(0,210,106,0.2);
        color: var(--success);
    }

    .ns-lesson-info {
        flex: 1;
        min-width: 0;
    }

    .ns-lesson-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .ns-lesson-item.completed .ns-lesson-title {
        color: var(--text-muted);
    }

    .ns-lesson-meta {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ns-lesson-meta i {
        font-size: 12px;
    }

    .ns-lesson-badge {
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        font-family: var(--font-heading);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        flex-shrink: 0;
    }

    .ns-lesson-badge.video {
        background: rgba(129,140,248,0.1);
        color: #818CF8;
    }

    .ns-lesson-badge.document {
        background: rgba(255,152,0,0.1);
        color: var(--warning);
    }

    .ns-lesson-badge.quiz {
        background: rgba(0,210,106,0.1);
        color: var(--success);
    }

    .ns-lesson-check {
        color: var(--success);
        font-size: 16px;
        flex-shrink: 0;
    }

    .ns-requirements-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ns-requirements-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        font-size: 13px;
        color: var(--text-muted);
    }

    .ns-requirements-list i {
        color: var(--accent-primary);
        font-size: 12px;
    }

    @media (max-width: 1100px) {
        .ns-detail-body {
            grid-template-columns: 1fr;
        }

        .ns-detail-sidebar {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .ns-detail-hero-bg {
            flex-direction: column;
            padding: 24px;
        }

        .ns-detail-thumb {
            width: 100%;
            min-width: auto;
            height: 180px;
        }

        .ns-detail-title {
            font-size: 22px;
        }

        .ns-detail-hero { padding: 16px 16px 0; }
        .ns-detail-body { padding: 16px; }
        .ns-learn-grid { grid-template-columns: 1fr; }
        .ns-detail-stats { gap: 8px; }
        .ns-detail-stat { font-size: 12px; padding: 5px 10px; }
    }
</style>
@endpush

<div class="ns-page-content" style="padding: 0 0 32px">
    <div style="max-width: 1400px; margin: 0 auto">
        {{-- Hero Section --}}
        <div class="ns-detail-hero">
            <div class="ns-detail-hero-inner">
                <div class="ns-detail-hero-bg">
                    <div class="ns-detail-thumb" style="background: {{ $thumbGradient }}">
                        <i class="bi {{ $thumbIcon }}"></i>
                    </div>

                    <div class="ns-detail-info">
                        <div class="ns-detail-breadcrumb">
                            <a href="{{ route('student.courses.index') }}">Courses</a>
                            <i class="bi bi-chevron-right"></i>
                            <span style="color: var(--text-primary)">{{ $course->title }}</span>
                        </div>

                        @if($course->category)
                            <span class="ns-detail-category">{{ $course->category->name }}</span>
                        @endif

                        <h1 class="ns-detail-title">{{ $course->title }}</h1>

                        @if($course->short_description)
                            <p class="ns-detail-desc">{{ $course->short_description }}</p>
                        @endif

                        <div class="ns-detail-teacher">
                            <div class="ns-detail-teacher-avatar">
                                {{ strtoupper(substr($course->teacher->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="ns-detail-teacher-name">{{ $course->teacher->name ?? 'Unknown' }}</div>
                                <div class="ns-detail-teacher-label">Course Instructor</div>
                            </div>
                        </div>

                        <div class="ns-detail-stats">
                            <div class="ns-detail-stat">
                                <i class="bi bi-collection-play" style="color: var(--accent-primary)"></i>
                                <strong>{{ $totalLessons }}</strong> Lessons
                            </div>
                            <div class="ns-detail-stat">
                                <i class="bi bi-people" style="color: var(--success)"></i>
                                <strong>{{ number_format($enrollmentCount) }}</strong> Students
                            </div>
                            <div class="ns-detail-stat">
                                <i class="bi bi-clock" style="color: var(--warning)"></i>
                                <strong>{{ $course->duration_hours ?? 0 }}</strong> Hours
                            </div>
                            <div class="ns-detail-stat">
                                <i class="bi bi-bar-chart" style="color: #818CF8"></i>
                                <strong>{{ ucfirst($course->level) }}</strong>
                            </div>
                            @if(($course->rating ?? 0) > 0)
                                <div class="ns-detail-stat">
                                    <i class="bi bi-star-fill" style="color: var(--accent-primary)"></i>
                                    <strong>{{ number_format($course->rating, 1) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="ns-detail-body">
            {{-- Left Column --}}
            <div class="ns-detail-main">
                {{-- About --}}
                <div class="ns-content-section">
                    <h2><i class="bi bi-info-circle"></i>About This Course</h2>
                    <div class="ns-content-text">
                        {!! nl2br(e($course->description)) !!}
                    </div>
                </div>

                {{-- What You'll Learn --}}
                @if($course->description)
                    <div class="ns-content-section">
                        <h2><i class="bi bi-lightbulb"></i>What You'll Learn</h2>
                        <div class="ns-learn-grid">
                            @foreach(explode("\n", $course->description) as $point)
                                @if(trim($point))
                                    <div class="ns-learn-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>{{ trim($point) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Curriculum --}}
                <div class="ns-content-section">
                    <h2><i class="bi bi-journal-bookmark"></i>Course Curriculum</h2>
                    @if($course->lessons->count())
                        @php
                            $grouped = $course->lessons->groupBy(fn($l) => $l->module_number ?? 1);
                        @endphp
                        @foreach($grouped as $moduleNum => $lessons)
                            <div class="ns-detail-card" style="margin-bottom: 12px;" x-data="{ open: true }">
                                <div class="ns-detail-card-header" style="cursor: pointer;" @click="open = !open">
                                    <i class="bi bi-folder2-open"></i>
                                    <h3 style="flex: 1">Module {{ $moduleNum }}</h3>
                                    <span style="font-size: 12px; color: var(--text-muted); font-family: var(--font-heading)">{{ $lessons->count() }} lessons</span>
                                    <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'" style="color: var(--text-muted); font-size: 14px"></i>
                                </div>
                                <div x-show="open" x-transition style="padding: 0;">
                                    <ul class="ns-lesson-list">
                                        @foreach($lessons as $lesson)
                                            @php
                                                $isCompleted = $enrolled && isset($completedLessonIds) && in_array($lesson->id, $completedLessonIds);
                                            @endphp
                                            <li class="ns-lesson-item {{ $isCompleted ? 'completed' : '' }}">
                                                <div class="ns-lesson-index">
                                                    @if($isCompleted)
                                                        <i class="bi bi-check-lg"></i>
                                                    @else
                                                        {{ $loop->index + 1 }}
                                                    @endif
                                                </div>
                                                <div class="ns-lesson-info">
                                                    <div class="ns-lesson-title">{{ $lesson->title }}</div>
                                                    <div class="ns-lesson-meta">
                                                        @if($lesson->duration_minutes)
                                                            <span><i class="bi bi-clock"></i> {{ $lesson->duration_minutes }} min</span>
                                                        @endif
                                                        @if($lesson->is_free)
                                                            <span style="color: var(--success)"><i class="bi bi-unlock"></i> Free</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($lesson->content_type)
                                                    <span class="ns-lesson-badge {{ $lesson->content_type }}">
                                                        {{ ucfirst($lesson->content_type) }}
                                                    </span>
                                                @endif
                                                @if($isCompleted)
                                                    <i class="bi bi-check-circle-fill ns-lesson-check"></i>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="ns-detail-card">
                            <div class="ns-detail-card-body" style="text-align: center; padding: 40px;">
                                <i class="bi bi-journal-text" style="font-size: 36px; color: var(--text-muted); opacity: 0.3; margin-bottom: 12px; display: block;"></i>
                                <p style="color: var(--text-muted); margin: 0;">No lessons available yet.</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Requirements --}}
                @if(isset($course->requirements) && $course->requirements)
                    <div class="ns-content-section">
                        <h2><i class="bi bi-list-check"></i>Requirements</h2>
                        <div class="ns-detail-card">
                            <div class="ns-detail-card-body">
                                <ul class="ns-requirements-list">
                                    @foreach(explode("\n", $course->requirements) as $req)
                                        @if(trim($req))
                                            <li>
                                                <i class="bi bi-dot"></i>
                                                {{ trim($req) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- STEM Kits --}}
                @if(isset($course->stemKits) && $course->stemKits->count())
                    <div class="ns-content-section">
                        <h2><i class="bi bi-cpu"></i>STEM Kit Requirements</h2>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            @foreach($course->stemKits as $kit)
                                <div class="ns-detail-card">
                                    <div class="ns-detail-card-body" style="display: flex; align-items: center; gap: 14px;">
                                        <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(255,212,0,0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="bi bi-box-seam" style="color: var(--accent-primary); font-size: 18px;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">{{ $kit->name }}</div>
                                            <div style="font-size: 12px; color: var(--text-muted);">{{ $kit->description ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Reviews --}}
                <div class="ns-content-section">
                    <h2><i class="bi bi-chat-left-text"></i>Student Reviews</h2>
                    <div class="ns-detail-card">
                        <div class="ns-detail-card-body" style="text-align: center; padding: 40px;">
                            <div style="font-family: var(--font-heading); font-size: 42px; font-weight: 700; color: var(--accent-primary); margin-bottom: 8px;">
                                {{ number_format($course->rating ?? 0, 1) }}
                            </div>
                            <div style="display: flex; justify-content: center; gap: 4px; margin-bottom: 8px;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($course->rating ?? 0))
                                        <i class="bi bi-star-fill" style="color: var(--accent-primary); font-size: 18px;"></i>
                                    @elseif($i - ($course->rating ?? 0) < 1 && $i - ($course->rating ?? 0) > 0)
                                        <i class="bi bi-star-half" style="color: var(--accent-primary); font-size: 18px;"></i>
                                    @else
                                        <i class="bi bi-star" style="color: var(--text-muted); font-size: 18px;"></i>
                                    @endif
                                @endfor
                            </div>
                            <p style="color: var(--text-muted); font-size: 13px; margin: 0;">Based on {{ number_format($enrollmentCount) }} enrollments</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="ns-detail-sidebar">
                <div class="ns-detail-card">
                    <div class="ns-detail-card-body">
                        @if($course->price > 0)
                            <div class="ns-sidebar-price">${{ number_format($course->price, 2) }}</div>
                        @else
                            <div class="ns-sidebar-price free">Free</div>
                        @endif

                        @if($enrolled)
                            <a href="{{ route('student.courses.learn', $enrollment) }}" class="ns-sidebar-enroll-btn success">
                                <i class="bi bi-play-fill"></i>Continue Learning
                            </a>
                            @if($enrollment)
                                <div class="ns-sidebar-progress-wrap">
                                    <div class="ns-sidebar-progress-header">
                                        <span class="ns-sidebar-progress-label">Your Progress</span>
                                        <span class="ns-sidebar-progress-value">{{ $enrollment->progress_percentage }}%</span>
                                    </div>
                                    <div class="ns-enroll-progress-bar" style="margin-bottom: 0;">
                                        <div class="ns-enroll-progress-fill complete" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="ns-sidebar-enroll-btn primary">
                                    <i class="bi bi-cart-plus"></i>Enroll Now
                                </button>
                            </form>
                        @endif

                        <div class="ns-sidebar-feature">
                            <i class="bi bi-collection-play"></i>
                            <span><strong>{{ $totalLessons }}</strong> Lessons</span>
                        </div>
                        <div class="ns-sidebar-feature">
                            <i class="bi bi-clock"></i>
                            <span><strong>{{ $course->duration_hours ?? 0 }}</strong> hours of content</span>
                        </div>
                        <div class="ns-sidebar-feature">
                            <i class="bi bi-bar-chart"></i>
                            <span><strong>{{ ucfirst($course->level) }}</strong> level</span>
                        </div>
                        <div class="ns-sidebar-feature">
                            <i class="bi bi-people"></i>
                            <span><strong>{{ number_format($enrollmentCount) }}</strong> students enrolled</span>
                        </div>
                        <div class="ns-sidebar-feature">
                            <i class="bi bi-infinity"></i>
                            <span>Full lifetime access</span>
                        </div>
                        <div class="ns-sidebar-feature">
                            <i class="bi bi-phone"></i>
                            <span>Access on mobile & desktop</span>
                        </div>
                        <div class="ns-sidebar-feature">
                            <i class="bi bi-patch-check"></i>
                            <span>Certificate of completion</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
