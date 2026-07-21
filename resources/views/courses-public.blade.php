@extends('layouts.guest')

@section('title', 'All Courses - Nano Spark LMS')

@section('guest-content')
<style>
    :root {
        --ns-bg: #050505;
        --ns-card: #121212;
        --ns-elevated: #181818;
        --ns-accent: #FFD400;
        --ns-success: #00D26A;
        --ns-warning: #FF9800;
        --ns-danger: #FF4D4F;
        --ns-info: #3B82F6;
        --ns-text: #FFFFFF;
        --ns-text-secondary: #A0A0A0;
        --ns-text-muted: #666666;
        --ns-border: rgba(255,255,255,0.06);
        --font-heading: 'Space Mono', monospace;
        --font-body: 'IBM Plex Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
    }

    .ns-courses-hero {
        padding: 140px 24px 48px;
        text-align: center;
        position: relative;
        z-index: 10;
    }
    .ns-courses-hero::before {
        content: '';
        position: absolute;
        top: -100px;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,212,0,0.06) 0%, transparent 60%);
        pointer-events: none;
    }
    .ns-courses-hero h1 {
        font-family: var(--font-heading);
        font-size: clamp(32px, 5vw, 52px);
        font-weight: 700;
        color: var(--ns-text);
        margin-bottom: 16px;
        position: relative;
    }
    .ns-courses-hero h1 .gradient-text {
        background: linear-gradient(135deg, #FFD400, #FF9800);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ns-courses-hero p {
        font-size: 17px;
        color: var(--ns-text-muted);
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.7;
        position: relative;
    }

    .ns-courses-section {
        padding: 0 24px 100px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    .ns-search-filter {
        display: flex;
        gap: 12px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }
    .ns-search-bar {
        flex: 1;
        min-width: 280px;
        position: relative;
    }
    .ns-search-bar i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ns-text-muted);
        font-size: 16px;
    }
    .ns-search-bar input {
        width: 100%;
        padding: 14px 20px 14px 46px;
        border-radius: 14px;
        border: 1px solid var(--ns-border);
        background: var(--ns-card);
        color: var(--ns-text);
        font-family: var(--font-body);
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
    }
    .ns-search-bar input::placeholder {
        color: var(--ns-text-muted);
    }
    .ns-search-bar input:focus {
        border-color: rgba(255,212,0,0.4);
    }

    .ns-filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    .ns-filter-tab {
        padding: 10px 20px;
        border-radius: 100px;
        border: 1px solid var(--ns-border);
        background: transparent;
        color: var(--ns-text-secondary);
        font-family: var(--font-body);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .ns-filter-tab:hover {
        border-color: rgba(255,212,0,0.3);
        color: var(--ns-text);
    }
    .ns-filter-tab.active {
        background: rgba(255,212,0,0.12);
        border-color: rgba(255,212,0,0.3);
        color: var(--ns-accent);
    }

    .ns-courses-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    .ns-course-card {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.3s;
        position: relative;
    }
    .ns-course-card:hover {
        transform: translateY(-6px);
        border-color: rgba(255,212,0,0.15);
        box-shadow: 0 12px 40px rgba(0,0,0,0.5);
    }
    .ns-course-thumb {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        position: relative;
    }
    .ns-course-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 5px 12px;
        border-radius: 100px;
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(8px);
        color: #fff;
    }
    .ns-course-level {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 5px 12px;
        border-radius: 100px;
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .ns-course-body {
        padding: 24px;
    }
    .ns-course-category {
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .ns-course-body h4 {
        font-family: var(--font-heading);
        font-size: 16px;
        font-weight: 700;
        color: var(--ns-text);
        margin-bottom: 8px;
    }
    .ns-course-body p {
        font-size: 13px;
        color: var(--ns-text-muted);
        line-height: 1.7;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ns-course-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid var(--ns-border);
    }
    .ns-course-meta {
        display: flex;
        gap: 14px;
        font-size: 13px;
        color: var(--ns-text-muted);
    }
    .ns-course-meta i {
        margin-right: 4px;
    }
    .ns-course-enroll {
        padding: 7px 18px;
        border-radius: 10px;
        background: rgba(255,212,0,0.1);
        border: 1px solid rgba(255,212,0,0.25);
        color: var(--ns-accent);
        font-family: var(--font-body);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }
    .ns-course-enroll:hover {
        background: var(--ns-accent);
        color: #050505;
        box-shadow: 0 4px 16px rgba(255,212,0,0.3);
    }

    .ns-empty-state {
        text-align: center;
        padding: 60px 20px;
        display: none;
    }
    .ns-empty-state i {
        font-size: 48px;
        color: var(--ns-text-muted);
        margin-bottom: 16px;
        display: block;
        opacity: 0.3;
    }
    .ns-empty-state h4 {
        font-family: var(--font-heading);
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-empty-state p {
        color: var(--ns-text-muted);
        font-size: 14px;
    }

    .ns-fade-up {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    .ns-fade-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .ns-pagination {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }
    .ns-pagination a,
    .ns-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        border-radius: 10px;
        font-family: var(--font-mono);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .ns-pagination a {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        color: var(--ns-text-secondary);
    }
    .ns-pagination a:hover {
        border-color: rgba(255,212,0,0.3);
        color: var(--ns-accent);
    }
    .ns-pagination span.active {
        background: rgba(255,212,0,0.12);
        border: 1px solid rgba(255,212,0,0.3);
        color: var(--ns-accent);
    }

    @media (max-width: 1024px) {
        .ns-courses-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .ns-courses-grid { grid-template-columns: 1fr; }
        .ns-courses-hero { padding: 120px 16px 32px; }
        .ns-courses-section { padding: 0 16px 60px; }
        .ns-search-filter { flex-direction: column; }
    }
</style>

<section class="ns-courses-hero">
    <h1>Explore <span class="gradient-text">Courses</span></h1>
    <p>Browse our collection of hands-on courses designed for school students in grades 6-12.</p>
</section>

<section class="ns-courses-section">
    <div class="ns-search-filter">
        <div class="ns-search-bar">
            <i class="bi bi-search"></i>
            <input type="text" id="courseSearch" placeholder="Search courses by title, topic, or category..." oninput="filterCourses()">
        </div>
    </div>

    <div class="ns-filter-tabs" style="justify-content: center; margin-bottom: 40px;">
        <button class="ns-filter-tab active" data-category="all" onclick="setCategory('all', this)">All Courses</button>
        <button class="ns-filter-tab" data-category="iot" onclick="setCategory('iot', this)">IoT</button>
        <button class="ns-filter-tab" data-category="robotics" onclick="setCategory('robotics', this)">Robotics</button>
        <button class="ns-filter-tab" data-category="ai" onclick="setCategory('ai', this)">AI & ML</button>
        <button class="ns-filter-tab" data-category="coding" onclick="setCategory('coding', this)">Coding</button>
        <button class="ns-filter-tab" data-category="electronics" onclick="setCategory('electronics', this)">Electronics</button>
    </div>

    @if(isset($courses) && $courses->count())
        <div class="ns-courses-grid" id="coursesGrid">
            @foreach($courses as $course)
                @php
                    $gradients = [
                        'iot' => ['#FFD400, #FF9800', 'var(--ns-warning)'],
                        'robotics' => ['#00D26A, #00B894', 'var(--ns-success)'],
                        'ai' => ['#3B82F6, #2563EB', 'var(--ns-info)'],
                        'coding' => ['#FF4D4F, #E74C3C', 'var(--ns-danger)'],
                        'electronics' => ['#9B59B6, #8E44AD', '#9B59B6'],
                    ];
                    $cat = strtolower($course->category->name ?? $course->category ?? 'electronics');
                    $gradient = $gradients[$cat] ?? $gradients['electronics'];
                    $levelColors = [
                        'Beginner' => ['rgba(0,210,106,0.15)', 'var(--ns-success)'],
                        'Intermediate' => ['rgba(255,152,0,0.15)', 'var(--ns-warning)'],
                        'Advanced' => ['rgba(255,77,79,0.15)', 'var(--ns-danger)'],
                    ];
                    $level = $course->difficulty ?? $course->level ?? 'Beginner';
                    $levelColor = $levelColors[$level] ?? $levelColors['Beginner'];
                    $icons = [
                        'iot' => 'bi-wifi',
                        'robotics' => 'bi-robot',
                        'ai' => 'bi-braces-asterisk',
                        'coding' => 'bi-code-slash',
                        'electronics' => 'bi-motherboard',
                    ];
                    $icon = $icons[$cat] ?? 'bi-book';
                @endphp
                <div class="ns-course-card ns-fade-up" data-category="{{ $cat }}" data-title="{{ strtolower($course->title) }}">
                    <div class="ns-course-thumb" style="background: linear-gradient(135deg, {{ $gradient[0] }});">
                        <i class="bi {{ $icon }}" style="color: rgba(0,0,0,0.5);"></i>
                        @if(isset($course->is_featured) && $course->is_featured)
                            <span class="ns-course-badge">Featured</span>
                        @endif
                        <span class="ns-course-level" style="background: {{ $levelColor[0] }}; color: {{ $levelColor[1] }};">{{ $level }}</span>
                    </div>
                    <div class="ns-course-body">
                        <div class="ns-course-category" style="color: {{ $gradient[1] }};">{{ ucfirst($cat) }}</div>
                        <h4>{{ $course->title }}</h4>
                        <p>{{ Str::limit($course->description ?? '', 100) }}</p>
                        <div class="ns-course-footer">
                            <div class="ns-course-meta">
                                <span><i class="bi bi-clock"></i> {{ $course->lessons_count ?? $course->lessons->count() ?? 0 }} Lessons</span>
                                <span><i class="bi bi-people"></i> {{ $course->enrollments_count ?? 0 }}</span>
                            </div>
                            <a href="{{ route('login') }}" class="ns-course-enroll">Enroll</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(method_exists($courses, 'links'))
            <div class="ns-pagination">
                {{ $courses->links() }}
            </div>
        @endif
    @else
        <div class="ns-courses-grid" id="coursesGrid">
            @php
                $fallbackCourses = [
                    ['title' => 'Introduction to Arduino', 'desc' => 'Build your first circuit and program it. Perfect for complete beginners.', 'cat' => 'electronics', 'icon' => 'bi-motherboard', 'level' => 'Beginner', 'lessons' => 12, 'students' => 156, 'badge' => 'Popular'],
                    ['title' => 'Robotics with Micro:bit', 'desc' => 'Design, build, and program your own robot from scratch.', 'cat' => 'robotics', 'icon' => 'bi-robot', 'level' => 'Beginner', 'lessons' => 16, 'students' => 98, 'badge' => 'New'],
                    ['title' => 'AI for Young Minds', 'desc' => 'Understand machine learning through visual tools and experiments.', 'cat' => 'ai', 'icon' => 'bi-braces-asterisk', 'level' => 'Intermediate', 'lessons' => 10, 'students' => 210, 'badge' => 'Trending'],
                    ['title' => 'Smart Home with ESP32', 'desc' => 'Build a fully connected smart home system with sensors and apps.', 'cat' => 'iot', 'icon' => 'bi-wifi', 'level' => 'Intermediate', 'lessons' => 20, 'students' => 178, 'badge' => 'Bestseller'],
                    ['title' => 'Python for Beginners', 'desc' => 'Learn programming from scratch with fun projects and games.', 'cat' => 'coding', 'icon' => 'bi-code-slash', 'level' => 'Beginner', 'lessons' => 14, 'students' => 240, 'badge' => ''],
                    ['title' => 'Computer Vision Basics', 'desc' => 'Explore image recognition and object detection using ML models.', 'cat' => 'ai', 'icon' => 'bi-camera', 'level' => 'Advanced', 'lessons' => 12, 'students' => 65, 'badge' => ''],
                ];
                $gradients = [
                    'iot' => ['#FFD400, #FF9800', 'var(--ns-warning)'],
                    'robotics' => ['#00D26A, #00B894', 'var(--ns-success)'],
                    'ai' => ['#3B82F6, #2563EB', 'var(--ns-info)'],
                    'coding' => ['#FF4D4F, #E74C3C', 'var(--ns-danger)'],
                    'electronics' => ['#9B59B6, #8E44AD', '#9B59B6'],
                ];
                $levelColors = [
                    'Beginner' => ['rgba(0,210,106,0.15)', 'var(--ns-success)'],
                    'Intermediate' => ['rgba(255,152,0,0.15)', 'var(--ns-warning)'],
                    'Advanced' => ['rgba(255,77,79,0.15)', 'var(--ns-danger)'],
                ];
            @endphp
            @foreach($fallbackCourses as $fc)
                @php
                    $gradient = $gradients[$fc['cat']];
                    $levelColor = $levelColors[$fc['level']];
                @endphp
                <div class="ns-course-card ns-fade-up" data-category="{{ $fc['cat'] }}" data-title="{{ strtolower($fc['title']) }}">
                    <div class="ns-course-thumb" style="background: linear-gradient(135deg, {{ $gradient[0] }});">
                        <i class="bi {{ $fc['icon'] }}" style="color: rgba(0,0,0,0.5);"></i>
                        @if($fc['badge'])
                            <span class="ns-course-badge">{{ $fc['badge'] }}</span>
                        @endif
                        <span class="ns-course-level" style="background: {{ $levelColor[0] }}; color: {{ $levelColor[1] }};">{{ $fc['level'] }}</span>
                    </div>
                    <div class="ns-course-body">
                        <div class="ns-course-category" style="color: {{ $gradient[1] }};">{{ ucfirst($fc['cat']) }}</div>
                        <h4>{{ $fc['title'] }}</h4>
                        <p>{{ $fc['desc'] }}</p>
                        <div class="ns-course-footer">
                            <div class="ns-course-meta">
                                <span><i class="bi bi-clock"></i> {{ $fc['lessons'] }} Lessons</span>
                                <span><i class="bi bi-people"></i> {{ $fc['students'] }}</span>
                            </div>
                            <a href="{{ route('login') }}" class="ns-course-enroll">Enroll</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="ns-empty-state" id="emptyState">
        <i class="bi bi-search"></i>
        <h4>No courses found</h4>
        <p>Try a different search term or category.</p>
    </div>
</section>

@endsection

@section('scripts')
<script>
    let currentCategory = 'all';

    function setCategory(cat, btn) {
        currentCategory = cat;
        document.querySelectorAll('.ns-filter-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        filterCourses();
    }

    function filterCourses() {
        const search = document.getElementById('courseSearch')?.value.toLowerCase() || '';
        const cards = document.querySelectorAll('.ns-course-card');
        let visible = 0;

        cards.forEach(card => {
            const category = card.getAttribute('data-category');
            const title = card.getAttribute('data-title') || '';
            const text = card.textContent.toLowerCase();
            const matchCat = currentCategory === 'all' || category === currentCategory;
            const matchSearch = !search || text.includes(search) || title.includes(search);

            if (matchCat && matchSearch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('emptyState');
        const grid = document.getElementById('coursesGrid');
        if (emptyState) emptyState.style.display = visible === 0 ? 'block' : 'none';
        if (grid) grid.style.display = visible === 0 ? 'none' : '';
    }

    const fadeEls = document.querySelectorAll('.ns-fade-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 60);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    fadeEls.forEach(el => observer.observe(el));
</script>
@endsection
