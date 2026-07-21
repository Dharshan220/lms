@extends('layouts.app')

@section('title', 'Browse Courses - Nano Spark LMS')

@section('content')
@push('styles')
<style>
    .ns-browse-hero {
        padding: 32px;
        margin-bottom: 0;
    }

    .ns-browse-hero-inner {
        background: linear-gradient(135deg, rgba(255,212,0,0.08) 0%, rgba(255,212,0,0.02) 100%);
        border: 1px solid rgba(255,212,0,0.1);
        border-radius: 20px;
        padding: 40px;
        position: relative;
        overflow: hidden;
    }

    .ns-browse-hero-inner::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,212,0,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .ns-browse-hero h1 {
        font-family: var(--font-heading);
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .ns-browse-hero h1 i {
        color: var(--accent-primary);
        margin-right: 10px;
    }

    .ns-browse-hero p {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 24px;
    }

    .ns-search-row {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .ns-search-field {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .ns-search-field i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 16px;
        z-index: 2;
    }

    .ns-search-field input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        color: var(--text-primary);
        font-family: var(--font-body);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .ns-search-field input::placeholder {
        color: var(--text-muted);
    }

    .ns-search-field input:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(255,212,0,0.1);
    }

    .ns-filter-select {
        padding: 12px 16px;
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        color: var(--text-primary);
        font-family: var(--font-body);
        font-size: 14px;
        outline: none;
        min-width: 160px;
        cursor: pointer;
        transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23888' viewBox='0 0 16 16'%3E%3Cpath d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }

    .ns-filter-select:focus {
        border-color: var(--accent-primary);
    }

    .ns-filter-select option {
        background: var(--bg-card);
        color: var(--text-primary);
    }

    .ns-course-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .ns-course-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .ns-course-card:hover {
        transform: translateY(-6px);
        border-color: rgba(255,212,0,0.2);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,212,0,0.1);
    }

    .ns-course-thumb {
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .ns-course-thumb i {
        font-size: 42px;
        color: rgba(255,255,255,0.9);
        z-index: 2;
        filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));
    }

    .ns-course-thumb::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40px;
        background: linear-gradient(to top, var(--bg-card), transparent);
        z-index: 1;
    }

    .ns-course-difficulty {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        font-family: var(--font-heading);
        letter-spacing: 0.3px;
        z-index: 3;
        backdrop-filter: blur(8px);
    }

    .ns-course-difficulty.beginner {
        background: rgba(0,210,106,0.15);
        color: #00D26A;
        border: 1px solid rgba(0,210,106,0.2);
    }

    .ns-course-difficulty.intermediate {
        background: rgba(255,152,0,0.15);
        color: #FF9800;
        border: 1px solid rgba(255,152,0,0.2);
    }

    .ns-course-difficulty.advanced {
        background: rgba(255,77,79,0.15);
        color: #FF4D4F;
        border: 1px solid rgba(255,77,79,0.2);
    }

    .ns-course-featured {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 28px;
        height: 28px;
        background: rgba(255,212,0,0.2);
        border: 1px solid rgba(255,212,0,0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
    }

    .ns-course-featured i {
        font-size: 13px !important;
        color: var(--accent-primary) !important;
    }

    .ns-course-body {
        padding: 16px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .ns-course-category {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--accent-primary);
        margin-bottom: 8px;
        font-family: var(--font-heading);
    }

    .ns-course-title {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ns-course-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .ns-course-title a:hover {
        color: var(--accent-primary);
    }

    .ns-course-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ns-course-teacher {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .ns-course-teacher-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(255,212,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        color: var(--accent-primary);
        font-family: var(--font-heading);
        flex-shrink: 0;
    }

    .ns-course-teacher span {
        font-size: 12px;
        color: var(--text-muted);
    }

    .ns-course-meta {
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ns-course-rating {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .ns-course-rating i {
        font-size: 11px;
        color: var(--accent-primary);
    }

    .ns-course-rating span {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .ns-course-students {
        font-size: 12px;
        color: var(--text-muted);
    }

    .ns-course-students i {
        margin-right: 4px;
        font-size: 11px;
    }

    .ns-empty-grid {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
    }

    .ns-empty-grid i {
        font-size: 56px;
        color: var(--text-muted);
        opacity: 0.3;
        margin-bottom: 16px;
        display: block;
    }

    .ns-empty-grid h3 {
        font-family: var(--font-heading);
        font-size: 18px;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .ns-empty-grid p {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 20px;
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
        .ns-course-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 992px) {
        .ns-course-grid { grid-template-columns: repeat(2, 1fr); }
        .ns-browse-hero-inner { padding: 28px; }
    }

    @media (max-width: 576px) {
        .ns-course-grid { grid-template-columns: 1fr; }
        .ns-browse-hero { padding: 16px; }
        .ns-browse-hero-inner { padding: 20px; }
        .ns-search-row { flex-direction: column; }
        .ns-search-field { min-width: 100%; }
        .ns-filter-select { width: 100%; }
    }
</style>
@endpush

<div class="ns-page-content" style="padding: 0 0 32px">
    <div style="max-width: 1400px; margin: 0 auto">
        <div class="ns-browse-hero">
            <div class="ns-browse-hero-inner">
                <h1><i class="bi bi-compass"></i>Browse Courses</h1>
                <p>Explore courses, learn new skills, and earn badges along the way.</p>

                <form action="{{ route('student.courses.index') }}" method="GET">
                    <div class="ns-search-row">
                        <div class="ns-search-field">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" placeholder="Search courses, topics, teachers..." value="{{ request('search') }}">
                        </div>
                        <select name="category_id" class="ns-filter-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="level" class="ns-filter-select" onchange="this.form.submit()">
                            <option value="">All Levels</option>
                            <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        <button type="submit" class="ns-btn ns-btn-primary" style="padding: 12px 24px; border-radius: 12px; font-weight: 600; white-space: nowrap;">
                            <i class="bi bi-search" style="margin-right: 6px"></i>Search
                        </button>
                        @if(request()->hasAny(['search', 'category_id', 'level']))
                            <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-outline" style="padding: 12px 20px; border-radius: 12px; color: var(--danger); border-color: rgba(255,77,79,0.3);">
                                <i class="bi bi-x-circle" style="margin-right: 6px"></i>Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div style="padding: 24px 32px 0">
            <div class="d-flex align-items-center justify-content-between" style="margin-bottom: 20px">
                <p style="color: var(--text-muted); font-size: 14px; margin: 0">
                    <span style="color: var(--text-primary); font-weight: 700; font-family: var(--font-heading)">{{ $courses->total() }}</span> courses found
                </p>
            </div>

            @if($courses->count())
                <div class="ns-course-grid">
                    @foreach($courses as $course)
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
                        <div class="ns-course-card">
                            <div class="ns-course-thumb" style="background: {{ $thumbGradient }}">
                                <i class="bi {{ $thumbIcon }}"></i>

                                @if($course->level)
                                    <span class="ns-course-difficulty {{ $course->level }}">
                                        {{ ucfirst($course->level) }}
                                    </span>
                                @endif

                                @if($course->is_featured)
                                    <span class="ns-course-featured">
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                @endif
                            </div>

                            <div class="ns-course-body">
                                <div class="ns-course-category">{{ $course->category->name ?? 'General' }}</div>

                                <h3 class="ns-course-title">
                                    <a href="{{ route('student.courses.show', $course) }}">{{ $course->title }}</a>
                                </h3>

                                @if($course->short_description)
                                    <p class="ns-course-desc">{{ $course->short_description }}</p>
                                @endif

                                <div class="ns-course-teacher">
                                    <div class="ns-course-teacher-avatar">
                                        {{ strtoupper(substr($course->teacher->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $course->teacher->name ?? 'Unknown' }}</span>
                                </div>

                                <div class="ns-course-meta">
                                    <div class="ns-course-rating">
                                        @php $rating = $course->rating ?? 0; @endphp
                                        <i class="bi bi-star-fill"></i>
                                        <span>{{ number_format($rating, 1) }}</span>
                                    </div>
                                    <div class="ns-course-students">
                                        <i class="bi bi-people"></i>
                                        {{ number_format($course->enrollment_count ?? 0) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="ns-pagination">
                    {{ $courses->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="ns-empty-grid">
                    <i class="bi bi-inbox"></i>
                    <h3>No courses found</h3>
                    <p>Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-primary" style="padding: 10px 24px; border-radius: 10px; display: inline-flex; align-items: center;">
                        <i class="bi bi-arrow-clockwise" style="margin-right: 8px"></i>Browse All Courses
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
