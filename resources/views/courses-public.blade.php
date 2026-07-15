@extends('layouts.guest')

@section('title', 'Courses - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-page-hero {
        padding: 120px 24px 40px;
        text-align: center;
        position: relative;
        z-index: 10;
    }

    .ns-page-hero h1 {
        font-size: clamp(32px, 5vw, 52px);
        font-weight: 800;
        color: #fff;
        margin-bottom: 16px;
    }

    .ns-page-hero p {
        font-size: 17px;
        color: #9CA3AF;
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .ns-courses-section {
        padding: 40px 24px 100px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    .ns-search-bar {
        max-width: 560px;
        margin: 0 auto 32px;
        position: relative;
    }

    .ns-search-bar input {
        width: 100%;
        padding: 14px 20px 14px 48px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: #F3F4F6;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
    }

    .ns-search-bar input::placeholder { color: #6B7280; }
    .ns-search-bar input:focus { border-color: #FF6B35; }

    .ns-search-bar i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6B7280;
        font-size: 18px;
    }

    .ns-filter-tabs {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }

    .ns-filter-tab {
        padding: 8px 20px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: transparent;
        color: #9CA3AF;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ns-filter-tab:hover {
        border-color: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .ns-filter-tab.active {
        background: linear-gradient(135deg, #FF6B35, #E55A24);
        border-color: transparent;
        color: #fff;
    }

    .ns-courses-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .ns-course-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .ns-course-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 255, 255, 0.12);
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
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        color: #fff;
    }

    .ns-course-level {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
    }

    .ns-course-body {
        padding: 24px;
    }

    .ns-course-category {
        font-size: 12px;
        font-weight: 600;
        color: #4ECDC4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .ns-course-body h4 {
        font-size: 17px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 8px;
    }

    .ns-course-body p {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .ns-course-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .ns-course-footer span {
        font-size: 13px;
        color: #9CA3AF;
    }

    .ns-course-footer i { margin-right: 4px; }

    .ns-course-view {
        padding: 6px 16px;
        border-radius: 8px;
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid rgba(255, 107, 53, 0.25);
        color: #FF6B35;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .ns-course-view:hover {
        background: #FF6B35;
        color: #fff;
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

    .ns-empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6B7280;
        display: none;
    }

    .ns-empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }

    @media (max-width: 1024px) {
        .ns-courses-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .ns-courses-grid { grid-template-columns: 1fr; }
    }
</style>

<section class="ns-page-hero">
    <h1>Explore <span style="background: linear-gradient(135deg, #FF6B35, #4ECDC4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Courses</span></h1>
    <p>Browse our collection of hands-on courses designed for school students in grades 6-12.</p>
</section>

<section class="ns-courses-section">
    <div class="ns-search-bar">
        <i class="bi bi-search"></i>
        <input type="text" id="courseSearch" placeholder="Search courses..." oninput="filterCourses()">
    </div>

    <div class="ns-filter-tabs">
        <button class="ns-filter-tab active" data-category="all" onclick="setCategory('all', this)">All Courses</button>
        <button class="ns-filter-tab" data-category="iot" onclick="setCategory('iot', this)">IoT</button>
        <button class="ns-filter-tab" data-category="robotics" onclick="setCategory('robotics', this)">Robotics</button>
        <button class="ns-filter-tab" data-category="ai" onclick="setCategory('ai', this)">AI & ML</button>
        <button class="ns-filter-tab" data-category="coding" onclick="setCategory('coding', this)">Coding</button>
        <button class="ns-filter-tab" data-category="electronics" onclick="setCategory('electronics', this)">Electronics</button>
    </div>

    <div class="ns-courses-grid" id="coursesGrid">

        <div class="ns-course-card ns-fade-up" data-category="electronics">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FF6B35, #E55A24);">
                <i class="bi bi-motherboard" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Popular</span>
                <span class="ns-course-level" style="background: rgba(52,211,153,0.2); color: #34D399;">Beginner</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">Electronics</div>
                <h4>Introduction to Arduino</h4>
                <p>Build your first circuit and program it. Perfect for complete beginners in electronics and programming.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 12 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="robotics">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #4ECDC4, #2BA89E);">
                <i class="bi bi-robot" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">New</span>
                <span class="ns-course-level" style="background: rgba(52,211,153,0.2); color: #34D399;">Beginner</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">Robotics</div>
                <h4>Robotics with Micro:bit</h4>
                <p>Design, build, and program your own robot from scratch using the Micro:bit platform.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 16 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="ai">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #A78BFA, #7C3AED);">
                <i class="bi bi-braces-asterisk" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Trending</span>
                <span class="ns-course-level" style="background: rgba(255,107,53,0.2); color: #FF6B35;">Intermediate</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">AI & ML</div>
                <h4>AI for Young Minds</h4>
                <p>Understand machine learning through visual tools. Train image classifiers and chatbots.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 10 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="iot">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">
                <i class="bi bi-wifi" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Bestseller</span>
                <span class="ns-course-level" style="background: rgba(255,107,53,0.2); color: #FF6B35;">Intermediate</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">IoT</div>
                <h4>Smart Home with ESP32</h4>
                <p>Build a fully connected smart home system with sensors, actuators, and a mobile app.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 20 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="coding">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #F472B6, #EC4899);">
                <i class="bi bi-code-slash" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-level" style="background: rgba(52,211,153,0.2); color: #34D399;">Beginner</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">Coding</div>
                <h4>Python for Beginners</h4>
                <p>Learn programming from scratch with fun projects, games, and interactive challenges.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 14 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="iot">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #34D399, #059669);">
                <i class="bi bi-cloud" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-level" style="background: rgba(167,139,250,0.2); color: #A78BFA;">Advanced</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">IoT</div>
                <h4>Weather Station Project</h4>
                <p>Build a cloud-connected weather station that collects, stores, and visualizes real-time data.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 18 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="ai">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #60A5FA, #3B82F6);">
                <i class="bi bi-camera" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-level" style="background: rgba(167,139,250,0.2); color: #A78BFA;">Advanced</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">AI & ML</div>
                <h4>Computer Vision Basics</h4>
                <p>Explore image recognition and object detection using machine learning models.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 12 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="robotics">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FB923C, #EA580C);">
                <i class="bi bi-car-front" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Project</span>
                <span class="ns-course-level" style="background: rgba(255,107,53,0.2); color: #FF6B35;">Intermediate</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">Robotics</div>
                <h4>Autonomous Line Follower</h4>
                <p>Build an autonomous robot that follows lines and navigates through obstacles.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 15 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up" data-category="electronics">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #C084FC, #A855F7);">
                <i class="bi bi-lightning" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-level" style="background: rgba(52,211,153,0.2); color: #34D399;">Beginner</span>
            </div>
            <div class="ns-course-body">
                <div class="ns-course-category">Electronics</div>
                <h4>Electronics Fundamentals</h4>
                <p>Understand resistors, capacitors, LEDs, and sensors through fun experiments.</p>
                <div class="ns-course-footer">
                    <span><i class="bi bi-clock"></i> 10 Lessons</span>
                    <a href="{{ route('login') }}" class="ns-course-view">Enroll</a>
                </div>
            </div>
        </div>

    </div>

    <div class="ns-empty-state" id="emptyState">
        <i class="bi bi-search"></i>
        <h4 style="color: #F3F4F6; margin-bottom: 8px;">No courses found</h4>
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
        const search = document.getElementById('courseSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.ns-course-card');
        let visible = 0;

        cards.forEach(card => {
            const category = card.getAttribute('data-category');
            const text = card.textContent.toLowerCase();
            const matchCat = currentCategory === 'all' || category === currentCategory;
            const matchSearch = !search || text.includes(search);

            if (matchCat && matchSearch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('emptyState').style.display = visible === 0 ? 'block' : 'none';
    }

    // Scroll fade-in
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
