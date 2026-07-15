@extends('layouts.guest')

@section('title', 'Nano Spark LMS - Master IoT, Robotics & AI')

@section('guest-content')
<style>
    /* ── Landing Page Styles ── */
    .ns-hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 80px 24px 60px;
        position: relative;
    }

    .ns-hero-content {
        max-width: 820px;
        position: relative;
        z-index: 10;
    }

    .ns-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        border-radius: 100px;
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid rgba(255, 107, 53, 0.25);
        color: #FF8F65;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 28px;
        animation: fadeInDown 0.6s ease;
    }

    .ns-hero h1 {
        font-size: clamp(36px, 6vw, 68px);
        font-weight: 800;
        line-height: 1.1;
        color: #fff;
        margin-bottom: 20px;
        animation: fadeInUp 0.8s ease;
    }

    .ns-hero h1 .gradient-text {
        background: linear-gradient(135deg, #FF6B35, #4ECDC4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .ns-hero p {
        font-size: 18px;
        color: #9CA3AF;
        max-width: 580px;
        margin: 0 auto 36px;
        line-height: 1.7;
        animation: fadeInUp 1s ease;
    }

    .ns-hero-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeInUp 1.2s ease;
    }

    .ns-btn-primary {
        padding: 14px 32px;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #FF6B35, #E55A24);
        border: none;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 20px rgba(255, 107, 53, 0.35);
        transition: all 0.3s;
    }

    .ns-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(255, 107, 53, 0.5);
        color: #fff;
    }

    .ns-btn-secondary {
        padding: 14px 32px;
        font-size: 15px;
        font-weight: 700;
        color: #D1D5DB;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .ns-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        color: #fff;
        transform: translateY(-2px);
    }

    .ns-hero-visuals {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 60px;
        animation: fadeInUp 1.4s ease;
    }

    .ns-hero-stat {
        text-align: center;
    }

    .ns-hero-stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
    }

    .ns-hero-stat-label {
        font-size: 13px;
        color: #6B7280;
        margin-top: 4px;
    }

    /* ── Sections Common ── */
    .ns-section {
        padding: 100px 24px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    .ns-section-dark {
        background: #131520;
    }

    .ns-section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .ns-section-header h2 {
        font-size: 36px;
        font-weight: 800;
        color: #F9FAFB;
        margin-bottom: 12px;
    }

    .ns-section-header p {
        font-size: 16px;
        color: #6B7280;
        max-width: 520px;
        margin: 0 auto;
    }

    .ns-section-header .ns-tag {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 100px;
        background: rgba(78, 205, 196, 0.1);
        border: 1px solid rgba(78, 205, 196, 0.25);
        color: #4ECDC4;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }

    /* ── Features ── */
    .ns-features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .ns-feature-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 36px 28px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .ns-feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent, #FF6B35), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .ns-feature-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.05);
    }

    .ns-feature-card:hover::before {
        opacity: 1;
    }

    .ns-feature-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .ns-feature-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 10px;
    }

    .ns-feature-card p {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.6;
        margin: 0;
    }

    /* ── Stats ── */
    .ns-stats-section {
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.08), rgba(78, 205, 196, 0.08));
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 60px 24px;
        position: relative;
        z-index: 10;
    }

    .ns-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        max-width: 1000px;
        margin: 0 auto;
        text-align: center;
    }

    .ns-stat-item h3 {
        font-size: 42px;
        font-weight: 800;
        background: linear-gradient(135deg, #FF6B35, #4ECDC4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 4px;
    }

    .ns-stat-item p {
        font-size: 14px;
        color: #6B7280;
        font-weight: 500;
    }

    /* ── Learning Paths ── */
    .ns-paths-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .ns-path-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 36px 28px;
        text-align: center;
        transition: all 0.3s;
    }

    .ns-path-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 255, 255, 0.12);
    }

    .ns-path-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 20px;
    }

    .ns-path-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 8px;
    }

    .ns-path-card p {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .ns-path-levels {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .ns-path-levels span {
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ── Courses ── */
    .ns-courses-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
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

    .ns-course-body {
        padding: 20px;
    }

    .ns-course-body h4 {
        font-size: 16px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 8px;
    }

    .ns-course-body p {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .ns-course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #9CA3AF;
    }

    .ns-course-meta i {
        margin-right: 4px;
    }

    /* ── Testimonials ── */
    .ns-testimonials-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .ns-testimonial-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 32px;
        transition: all 0.3s;
    }

    .ns-testimonial-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
    }

    .ns-testimonial-stars {
        color: #FBBF24;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .ns-testimonial-card blockquote {
        font-size: 14px;
        color: #D1D5DB;
        line-height: 1.7;
        margin-bottom: 20px;
        font-style: italic;
    }

    .ns-testimonial-author {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ns-testimonial-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: #fff;
    }

    .ns-testimonial-name {
        font-size: 14px;
        font-weight: 700;
        color: #F3F4F6;
    }

    .ns-testimonial-role {
        font-size: 12px;
        color: #6B7280;
    }

    /* ── CTA ── */
    .ns-cta {
        text-align: center;
        padding: 100px 24px;
        position: relative;
        z-index: 10;
    }

    .ns-cta-box {
        max-width: 700px;
        margin: 0 auto;
        padding: 60px 40px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(78, 205, 196, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .ns-cta-box h2 {
        font-size: 32px;
        font-weight: 800;
        color: #F9FAFB;
        margin-bottom: 16px;
    }

    .ns-cta-box p {
        font-size: 16px;
        color: #9CA3AF;
        margin-bottom: 32px;
    }

    /* ── Animations ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .ns-courses-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .ns-features-grid,
        .ns-paths-grid,
        .ns-testimonials-grid { grid-template-columns: 1fr; }

        .ns-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }

        .ns-hero-visuals { gap: 20px; }

        .ns-section { padding: 60px 16px; }

        .ns-courses-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- ═══ HERO ═══ --}}
<section class="ns-hero">
    <div class="ns-hero-content">
        <div class="ns-hero-badge">
            <i class="bi bi-lightning-charge-fill"></i>
            Designed for Grades 6-12 Students
        </div>
        <h1>Master <span class="gradient-text">IoT, Robotics & AI</span></h1>
        <p>The next-generation learning platform for school students. Build real projects, earn certificates, and explore the future of technology with interactive courses and hands-on STEM kits.</p>
        <div class="ns-hero-buttons">
            @auth
                <a href="{{ route('login') }}" class="ns-btn-primary">
                    <i class="bi bi-play-fill"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="ns-btn-primary">
                    <i class="bi bi-play-fill"></i> Start Learning
                </a>
            @endauth
            <a href="#courses" class="ns-btn-secondary">
                <i class="bi bi-compass"></i> Explore Courses
            </a>
        </div>
        <div class="ns-hero-visuals">
            <div class="ns-hero-stat">
                <div class="ns-hero-stat-value">500+</div>
                <div class="ns-hero-stat-label">Active Students</div>
            </div>
            <div class="ns-hero-stat">
                <div class="ns-hero-stat-value">50+</div>
                <div class="ns-hero-stat-label">Expert Courses</div>
            </div>
            <div class="ns-hero-stat">
                <div class="ns-hero-stat-value">200+</div>
                <div class="ns-hero-stat-label">Projects Built</div>
            </div>
            <div class="ns-hero-stat">
                <div class="ns-hero-stat-value">95%</div>
                <div class="ns-hero-stat-label">Satisfaction</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FEATURES ═══ --}}
<section class="ns-section" id="features">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Why Nano Spark</div>
        <h2>Everything You Need to Learn & Build</h2>
        <p>From IoT sensors to AI models, our platform covers the full spectrum of modern technology education.</p>
    </div>

    <div class="ns-features-grid">
        <div class="ns-feature-card ns-fade-up" style="--accent: #FF6B35">
            <div class="ns-feature-icon" style="background: rgba(255, 107, 53, 0.12); color: #FF6B35;">
                <i class="bi bi-cpu"></i>
            </div>
            <h3>IoT & Embedded Learning</h3>
            <p>Hands-on projects with Arduino, Raspberry Pi, and ESP32. Learn to connect the physical world to the digital.</p>
        </div>

        <div class="ns-feature-card ns-fade-up" style="--accent: #4ECDC4">
            <div class="ns-feature-icon" style="background: rgba(78, 205, 196, 0.12); color: #4ECDC4;">
                <i class="bi bi-robot"></i>
            </div>
            <h3>Robotics Projects</h3>
            <p>Build and program robots from scratch. Learn mechanics, electronics, and programming in one exciting journey.</p>
        </div>

        <div class="ns-feature-card ns-fade-up" style="--accent: #A78BFA">
            <div class="ns-feature-icon" style="background: rgba(167, 139, 250, 0.12); color: #A78BFA;">
                <i class="bi bi-braces-asterisk"></i>
            </div>
            <h3>AI & Machine Learning</h3>
            <p>Discover artificial intelligence through fun experiments. Train models, build chatbots, and explore computer vision.</p>
        </div>

        <div class="ns-feature-card ns-fade-up" style="--accent: #FBBF24">
            <div class="ns-feature-icon" style="background: rgba(251, 191, 36, 0.12); color: #FBBF24;">
                <i class="bi bi-question-circle"></i>
            </div>
            <h3>Interactive Quizzes</h3>
            <p>Test your knowledge with adaptive quizzes. Earn points, climb the leaderboard, and track your progress.</p>
        </div>

        <div class="ns-feature-card ns-fade-up" style="--accent: #F472B6">
            <div class="ns-feature-icon" style="background: rgba(244, 114, 182, 0.12); color: #F472B6;">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3>STEM Kits</h3>
            <p>Receive curated hardware kits delivered to your door. Each kit includes components, guides, and project ideas.</p>
        </div>

        <div class="ns-feature-card ns-fade-up" style="--accent: #34D399">
            <div class="ns-feature-icon" style="background: rgba(52, 211, 153, 0.12); color: #34D399;">
                <i class="bi bi-award"></i>
            </div>
            <h3>Certificates</h3>
            <p>Earn verified certificates upon course completion. Showcase your skills on your academic portfolio.</p>
        </div>
    </div>
</section>

{{-- ═══ STATS ═══ --}}
<section class="ns-stats-section">
    <div class="ns-stats-grid">
        <div class="ns-stat-item ns-fade-up">
            <h3 data-target="500">0</h3>
            <p>Students Enrolled</p>
        </div>
        <div class="ns-stat-item ns-fade-up">
            <h3 data-target="50">0</h3>
            <p>Courses Available</p>
        </div>
        <div class="ns-stat-item ns-fade-up">
            <h3 data-target="200">0</h3>
            <p>Projects Completed</p>
        </div>
        <div class="ns-stat-item ns-fade-up">
            <h3 data-target="100">0</h3>
            <p>Certificates Issued</p>
        </div>
    </div>
</section>

{{-- ═══ LEARNING PATHS ═══ --}}
<section class="ns-section" id="paths">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Learning Paths</div>
        <h2>Choose Your Journey</h2>
        <p>Pick the path that matches your skill level and ambition. Every path leads to mastery.</p>
    </div>

    <div class="ns-paths-grid">
        <div class="ns-path-card ns-fade-up">
            <div class="ns-path-icon" style="background: rgba(52, 211, 153, 0.12); color: #34D399;">
                <i class="bi bi-seedling"></i>
            </div>
            <h3>Beginner</h3>
            <p>Start from scratch with foundational concepts in electronics, coding, and computational thinking.</p>
            <div class="ns-path-levels">
                <span style="background: rgba(52, 211, 153, 0.12); color: #34D399;">Grades 6-7</span>
                <span style="background: rgba(52, 211, 153, 0.12); color: #34D399;">12 Courses</span>
            </div>
        </div>

        <div class="ns-path-card ns-fade-up">
            <div class="ns-path-icon" style="background: rgba(255, 107, 53, 0.12); color: #FF6B35;">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <h3>Intermediate</h3>
            <p>Dive deeper into IoT systems, build robots, and create your first AI projects with real hardware.</p>
            <div class="ns-path-levels">
                <span style="background: rgba(255, 107, 53, 0.12); color: #FF6B35;">Grades 8-9</span>
                <span style="background: rgba(255, 107, 53, 0.12); color: #FF6B35;">18 Courses</span>
            </div>
        </div>

        <div class="ns-path-card ns-fade-up">
            <div class="ns-path-icon" style="background: rgba(167, 139, 250, 0.12); color: #A78BFA;">
                <i class="bi bi-trophy"></i>
            </div>
            <h3>Advanced</h3>
            <p>Master complex systems, lead team projects, and develop portfolio-ready prototypes for competitions.</p>
            <div class="ns-path-levels">
                <span style="background: rgba(167, 139, 250, 0.12); color: #A78BFA;">Grades 10-12</span>
                <span style="background: rgba(167, 139, 250, 0.12); color: #A78BFA;">20 Courses</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FEATURED COURSES ═══ --}}
<section class="ns-section" id="courses">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Popular Courses</div>
        <h2>Featured Courses</h2>
        <p>Explore our most popular courses chosen by thousands of students worldwide.</p>
    </div>

    <div class="ns-courses-grid">
        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FF6B35, #E55A24);">
                <i class="bi bi-motherboard" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Popular</span>
            </div>
            <div class="ns-course-body">
                <h4>Introduction to Arduino</h4>
                <p>Build your first circuit and program it. Perfect for complete beginners in electronics.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 12 Lessons</span>
                    <span><i class="bi bi-people"></i> 156 Students</span>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #4ECDC4, #2BA89E);">
                <i class="bi bi-robot" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">New</span>
            </div>
            <div class="ns-course-body">
                <h4>Robotics with Micro:bit</h4>
                <p>Design, build, and program your own robot from scratch using the Micro:bit platform.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 16 Lessons</span>
                    <span><i class="bi bi-people"></i> 98 Students</span>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #A78BFA, #7C3AED);">
                <i class="bi bi-braces-asterisk" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Trending</span>
            </div>
            <div class="ns-course-body">
                <h4>AI for Young Minds</h4>
                <p>Understand machine learning through visual tools. Train image classifiers and chatbots.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 10 Lessons</span>
                    <span><i class="bi bi-people"></i> 210 Students</span>
                </div>
            </div>
        </div>

        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">
                <i class="bi bi-wifi" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Bestseller</span>
            </div>
            <div class="ns-course-body">
                <h4>Smart Home with ESP32</h4>
                <p>Build a fully connected smart home system with sensors, actuators, and a mobile app.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 20 Lessons</span>
                    <span><i class="bi bi-people"></i> 178 Students</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TESTIMONIALS ═══ --}}
<section class="ns-section" id="testimonials">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Testimonials</div>
        <h2>What Our Students Say</h2>
        <p>Hear from students who have transformed their learning journey with Nano Spark.</p>
    </div>

    <div class="ns-testimonials-grid">
        <div class="ns-testimonial-card ns-fade-up">
            <div class="ns-testimonial-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <blockquote>"Nano Spark made me fall in love with robotics. I built my first robot in just 3 weeks! The courses are so easy to follow and the projects are really fun."</blockquote>
            <div class="ns-testimonial-author">
                <div class="ns-testimonial-avatar" style="background: linear-gradient(135deg, #FF6B35, #E55A24);">A</div>
                <div>
                    <div class="ns-testimonial-name">Arjun K.</div>
                    <div class="ns-testimonial-role">Grade 8 Student</div>
                </div>
            </div>
        </div>

        <div class="ns-testimonial-card ns-fade-up">
            <div class="ns-testimonial-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <blockquote>"The IoT course helped me win my school science fair! I built a weather station that sends data to the cloud. My teacher was amazed."</blockquote>
            <div class="ns-testimonial-author">
                <div class="ns-testimonial-avatar" style="background: linear-gradient(135deg, #4ECDC4, #2BA89E);">P</div>
                <div>
                    <div class="ns-testimonial-name">Priya M.</div>
                    <div class="ns-testimonial-role">Grade 10 Student</div>
                </div>
            </div>
        </div>

        <div class="ns-testimonial-card ns-fade-up">
            <div class="ns-testimonial-stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <blockquote>"I never thought I could understand AI, but Nano Spark's visual approach made it so simple. Now I'm building my own chatbot project!"</blockquote>
            <div class="ns-testimonial-author">
                <div class="ns-testimonial-avatar" style="background: linear-gradient(135deg, #A78BFA, #7C3AED);">R</div>
                <div>
                    <div class="ns-testimonial-name">Rahul S.</div>
                    <div class="ns-testimonial-role">Grade 7 Student</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ CTA ═══ --}}
<section class="ns-cta">
    <div class="ns-cta-box ns-fade-up">
        <h2>Ready to Start Your Journey?</h2>
        <p>Join hundreds of students already building the future. It's free to get started.</p>
        @auth
            <a href="{{ route('login') }}" class="ns-btn-primary">
                <i class="bi bi-arrow-right"></i> Go to Dashboard
            </a>
        @else
            <a href="{{ route('register') }}" class="ns-btn-primary">
                <i class="bi bi-rocket-takeoff"></i> Get Started Free
            </a>
        @endauth
    </div>
</section>

@endsection

@section('scripts')
<script>
    // ── Scroll Fade-In Observer ──
    const fadeEls = document.querySelectorAll('.ns-fade-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    fadeEls.forEach(el => observer.observe(el));

    // ── Animated Counters ──
    const counters = document.querySelectorAll('.ns-stat-item h3[data-target]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target'));
                const suffix = '+';
                let current = 0;
                const step = Math.ceil(target / 60);
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    el.textContent = current + suffix;
                }, 30);
                counterObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(c => counterObserver.observe(c));

    // ── Smooth Scroll ──
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(a.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endsection
