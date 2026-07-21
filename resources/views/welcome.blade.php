@extends('layouts.guest')

@section('title', 'Nano Spark LMS - Master IoT, Robotics & AI')

@section('guest-content')
<style>
    .ns-hero {
        min-height: 100vh; display: flex; align-items: center;
        justify-content: center; text-align: center;
        padding: 120px 24px 80px; position: relative; overflow: hidden;
    }
    .ns-hero::before {
        content: ''; position: absolute; top: -200px; right: -200px;
        width: 700px; height: 700px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 212, 0, 0.08) 0%, transparent 60%);
        pointer-events: none; animation: float 6s ease-in-out infinite;
    }
    .ns-hero::after {
        content: ''; position: absolute; bottom: -150px; left: -150px;
        width: 500px; height: 500px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 152, 0, 0.06) 0%, transparent 60%);
        pointer-events: none; animation: float 8s ease-in-out infinite reverse;
    }
    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }

    .circuit-bg {
        position: absolute; inset: 0; overflow: hidden; opacity: 0.04; pointer-events: none;
    }
    .circuit-line {
        position: absolute; background: var(--accent-primary);
    }

    .ns-hero-content { max-width: 860px; position: relative; z-index: 10; }

    .ns-hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 20px; border-radius: 100px;
        background: rgba(255, 212, 0, 0.08);
        border: 1px solid rgba(255, 212, 0, 0.2);
        color: #FFD400; font-size: 13px; font-weight: 600;
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease;
    }

    .ns-hero h1 {
        font-family: 'Space Mono', monospace;
        font-size: clamp(38px, 6vw, 72px);
        font-weight: 700; line-height: 1.05;
        color: #FFFFFF; margin-bottom: 24px;
        animation: fadeInUp 0.8s ease;
    }
    .ns-hero h1 .gradient-text {
        background: linear-gradient(135deg, #FFD400, #FF9800, #FFD400);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 4s ease infinite;
    }
    @keyframes gradientShift { 0%, 100% { background-position: 0% center; } 50% { background-position: 200% center; } }

    .ns-hero p {
        font-size: 18px; color: #888888; max-width: 600px;
        margin: 0 auto 40px; line-height: 1.8;
        animation: fadeInUp 1s ease;
    }

    .ns-hero-buttons {
        display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;
        animation: fadeInUp 1.2s ease;
    }

    .ns-hero-btn-primary {
        padding: 16px 36px; font-size: 16px; font-weight: 700;
        font-family: 'IBM Plex Sans', sans-serif;
        color: #050505;
        background: linear-gradient(135deg, #FFD400, #FF9800);
        border: none; border-radius: 14px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 10px;
        box-shadow: 0 4px 24px rgba(255, 212, 0, 0.35);
        transition: all 0.3s;
    }
    .ns-hero-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 40px rgba(255, 212, 0, 0.5);
        color: #050505;
    }
    .ns-hero-btn-secondary {
        padding: 16px 36px; font-size: 16px; font-weight: 600;
        font-family: 'IBM Plex Sans', sans-serif;
        color: #CFCFCF; background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 10px;
        transition: all 0.3s;
    }
    .ns-hero-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 212, 0, 0.2);
        color: #FFD400; transform: translateY(-3px);
    }

    .ns-hero-visuals {
        display: flex; justify-content: center; gap: 48px;
        margin-top: 72px; animation: fadeInUp 1.4s ease;
    }
    .ns-hero-stat-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 32px; font-weight: 700; color: #FFD400;
    }
    .ns-hero-stat-label { font-size: 13px; color: #888888; margin-top: 4px; }

    /* Sections */
    .ns-section { padding: 120px 24px; max-width: 1200px; margin: 0 auto; position: relative; z-index: 10; }
    .ns-section-header { text-align: center; margin-bottom: 64px; }
    .ns-section-header h2 {
        font-family: 'Space Mono', monospace;
        font-size: 38px; font-weight: 700;
        color: #FFFFFF; margin-bottom: 12px;
    }
    .ns-section-header p { font-size: 16px; color: #888888; max-width: 520px; margin: 0 auto; }
    .ns-section-header .ns-tag {
        display: inline-block; padding: 4px 14px; border-radius: 100px;
        background: rgba(255, 212, 0, 0.08);
        border: 1px solid rgba(255, 212, 0, 0.2);
        color: #FFD400; font-family: 'JetBrains Mono', monospace;
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 2px; margin-bottom: 16px;
    }

    /* Stats */
    .ns-stats-section {
        background: linear-gradient(135deg, rgba(255, 212, 0, 0.04), rgba(255, 152, 0, 0.04));
        border-top: 1px solid rgba(255, 212, 0, 0.06);
        border-bottom: 1px solid rgba(255, 212, 0, 0.06);
        padding: 72px 24px; position: relative; z-index: 10;
    }
    .ns-stats-grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 40px; max-width: 1000px; margin: 0 auto; text-align: center;
    }
    .ns-stat-item h3 {
        font-family: 'JetBrains Mono', monospace;
        font-size: 44px; font-weight: 700; color: #FFD400;
        margin-bottom: 4px;
    }
    .ns-stat-item p { font-size: 14px; color: #888888; font-weight: 500; }

    /* Features */
    .ns-features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .ns-feature-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; padding: 36px 28px;
        transition: all 0.3s; position: relative; overflow: hidden;
    }
    .ns-feature-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--accent, #FFD400), transparent);
        opacity: 0; transition: opacity 0.3s;
    }
    .ns-feature-card:hover {
        border-color: rgba(255, 212, 0, 0.15);
        transform: translateY(-6px);
        background: rgba(255, 212, 0, 0.03);
        box-shadow: 0 0 30px rgba(255, 212, 0, 0.05);
    }
    .ns-feature-card:hover::before { opacity: 1; }
    .ns-feature-icon {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; margin-bottom: 20px;
    }
    .ns-feature-card h3 {
        font-family: 'Space Mono', monospace;
        font-size: 17px; font-weight: 700;
        color: #FFFFFF; margin-bottom: 10px;
    }
    .ns-feature-card p { font-size: 14px; color: #888888; line-height: 1.7; margin: 0; }

    /* Paths */
    .ns-paths-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .ns-path-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; padding: 40px 28px;
        text-align: center; transition: all 0.3s;
    }
    .ns-path-card:hover { transform: translateY(-6px); border-color: rgba(255, 212, 0, 0.15); }
    .ns-path-icon {
        width: 72px; height: 72px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; margin: 0 auto 20px;
    }
    .ns-path-card h3 {
        font-family: 'Space Mono', monospace;
        font-size: 20px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px;
    }
    .ns-path-card p { font-size: 14px; color: #888888; line-height: 1.7; margin-bottom: 20px; }
    .ns-path-levels { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
    .ns-path-levels span { padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; }

    /* Courses */
    .ns-courses-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .ns-course-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; overflow: hidden; transition: all 0.3s;
    }
    .ns-course-card:hover { transform: translateY(-6px); border-color: rgba(255, 212, 0, 0.15); }
    .ns-course-thumb {
        height: 160px; display: flex; align-items: center; justify-content: center;
        font-size: 40px; position: relative;
    }
    .ns-course-badge {
        position: absolute; top: 12px; right: 12px;
        padding: 4px 10px; border-radius: 100px;
        font-size: 11px; font-weight: 700;
        background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); color: #fff;
    }
    .ns-course-body { padding: 20px; }
    .ns-course-body h4 {
        font-family: 'Space Mono', monospace;
        font-size: 15px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px;
    }
    .ns-course-body p { font-size: 13px; color: #888888; line-height: 1.5; margin-bottom: 16px; }
    .ns-course-meta { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #888888; }
    .ns-course-meta i { margin-right: 4px; }

    /* Testimonials */
    .ns-testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .ns-testimonial-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; padding: 32px; transition: all 0.3s;
    }
    .ns-testimonial-card:hover { border-color: rgba(255, 212, 0, 0.15); }
    .ns-testimonial-stars { color: #FFD400; font-size: 14px; margin-bottom: 16px; }
    .ns-testimonial-card blockquote {
        font-size: 14px; color: #CFCFCF; line-height: 1.8;
        margin-bottom: 20px; font-style: italic;
    }
    .ns-testimonial-author { display: flex; align-items: center; gap: 12px; }
    .ns-testimonial-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; color: #050505;
    }
    .ns-testimonial-name { font-size: 14px; font-weight: 700; color: #FFFFFF; }
    .ns-testimonial-role { font-size: 12px; color: #888888; }

    /* CTA */
    .ns-cta { text-align: center; padding: 120px 24px; position: relative; z-index: 10; }
    .ns-cta-box {
        max-width: 700px; margin: 0 auto; padding: 64px 40px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 212, 0, 0.06), rgba(255, 152, 0, 0.04));
        border: 1px solid rgba(255, 212, 0, 0.1);
    }
    .ns-cta-box h2 {
        font-family: 'Space Mono', monospace;
        font-size: 34px; font-weight: 700; color: #FFFFFF; margin-bottom: 16px;
    }
    .ns-cta-box p { font-size: 16px; color: #888888; margin-bottom: 32px; }

    /* Animations */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    .ns-fade-up { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
    .ns-fade-up.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 1024px) { .ns-courses-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
        .ns-features-grid, .ns-paths-grid, .ns-testimonials-grid { grid-template-columns: 1fr; }
        .ns-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .ns-hero-visuals { gap: 20px; flex-wrap: wrap; }
        .ns-section { padding: 72px 16px; }
        .ns-courses-grid { grid-template-columns: 1fr; }
        .ns-hero { padding: 100px 16px 60px; }
    }
</style>

<section class="ns-hero">
    <div class="ns-hero-content">
        <div class="ns-hero-badge">
            <i class="bi bi-lightning-charge-fill"></i>
            Built for Grades 6-12 Students
        </div>
        <h1>Future Starts With<br><span class="gradient-text">Smart Learning</span></h1>
        <p>Master IoT, Robotics, Embedded Systems and Artificial Intelligence through practical, hands-on learning experiences designed for the next generation.</p>
        <div class="ns-hero-buttons">
            @auth
                <a href="{{ route('login') }}" class="ns-hero-btn-primary">
                    <i class="bi bi-play-fill"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="ns-hero-btn-primary">
                    <i class="bi bi-play-fill"></i> Start Learning
                </a>
            @endauth
            <a href="#courses" class="ns-hero-btn-secondary">
                <i class="bi bi-compass"></i> Explore Courses
            </a>
        </div>
        <div class="ns-hero-visuals">
            <div>
                <div class="ns-hero-stat-value" data-target="500">0</div>
                <div class="ns-hero-stat-label">Active Students</div>
            </div>
            <div>
                <div class="ns-hero-stat-value" data-target="50">0</div>
                <div class="ns-hero-stat-label">Expert Courses</div>
            </div>
            <div>
                <div class="ns-hero-stat-value" data-target="200">0</div>
                <div class="ns-hero-stat-label">Projects Built</div>
            </div>
            <div>
                <div class="ns-hero-stat-value" data-target="95">0</div>
                <div class="ns-hero-stat-label">% Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<section class="ns-section" id="features">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Why Nano Spark</div>
        <h2>Everything You Need to<br>Learn & Build</h2>
        <p>From IoT sensors to AI models, our platform covers the full spectrum of modern technology education.</p>
    </div>
    <div class="ns-features-grid">
        <div class="ns-feature-card ns-fade-up" style="--accent: #FFD400">
            <div class="ns-feature-icon" style="background: rgba(255, 212, 0, 0.1); color: #FFD400;">
                <i class="bi bi-cpu"></i>
            </div>
            <h3>IoT & Embedded</h3>
            <p>Hands-on projects with Arduino, Raspberry Pi, and ESP32. Connect the physical world to the digital.</p>
        </div>
        <div class="ns-feature-card ns-fade-up" style="--accent: #FF9800">
            <div class="ns-feature-icon" style="background: rgba(255, 152, 0, 0.1); color: #FF9800;">
                <i class="bi bi-robot"></i>
            </div>
            <h3>Robotics</h3>
            <p>Build and program robots from scratch. Mechanics, electronics, and programming in one journey.</p>
        </div>
        <div class="ns-feature-card ns-fade-up" style="--accent: #00D26A">
            <div class="ns-feature-icon" style="background: rgba(0, 210, 106, 0.1); color: #00D26A;">
                <i class="bi bi-braces-asterisk"></i>
            </div>
            <h3>AI & Machine Learning</h3>
            <p>Discover artificial intelligence through experiments. Train models, build chatbots, explore CV.</p>
        </div>
        <div class="ns-feature-card ns-fade-up" style="--accent: #3B82F6">
            <div class="ns-feature-icon" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                <i class="bi bi-question-circle"></i>
            </div>
            <h3>Interactive Quizzes</h3>
            <p>Test knowledge with adaptive quizzes. Earn XP, climb the leaderboard, track progress.</p>
        </div>
        <div class="ns-feature-card ns-fade-up" style="--accent: #FF4D4F">
            <div class="ns-feature-icon" style="background: rgba(255, 77, 79, 0.1); color: #FF4D4F;">
                <i class="bi bi-box-seam"></i>
            </div>
            <h3>STEM Kits</h3>
            <p>Curated hardware kits delivered to your door with components, guides, and project ideas.</p>
        </div>
        <div class="ns-feature-card ns-fade-up" style="--accent: #FFD400">
            <div class="ns-feature-icon" style="background: rgba(255, 212, 0, 0.1); color: #FFD400;">
                <i class="bi bi-award"></i>
            </div>
            <h3>Certificates</h3>
            <p>Earn verified certificates upon completion. Showcase skills on your academic portfolio.</p>
        </div>
    </div>
</section>

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

<section class="ns-section" id="paths">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Learning Paths</div>
        <h2>Choose Your Journey</h2>
        <p>Pick the path that matches your skill level. Every path leads to mastery.</p>
    </div>
    <div class="ns-paths-grid">
        <div class="ns-path-card ns-fade-up">
            <div class="ns-path-icon" style="background: rgba(0, 210, 106, 0.1); color: #00D26A;">
                <i class="bi bi-seedling"></i>
            </div>
            <h3>Beginner</h3>
            <p>Start from scratch with foundational concepts in electronics, coding, and computational thinking.</p>
            <div class="ns-path-levels">
                <span style="background: rgba(0, 210, 106, 0.1); color: #00D26A;">Grades 6-7</span>
                <span style="background: rgba(0, 210, 106, 0.1); color: #00D26A;">12 Courses</span>
            </div>
        </div>
        <div class="ns-path-card ns-fade-up">
            <div class="ns-path-icon" style="background: rgba(255, 212, 0, 0.1); color: #FFD400;">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <h3>Intermediate</h3>
            <p>Dive deeper into IoT systems, build robots, and create your first AI projects with real hardware.</p>
            <div class="ns-path-levels">
                <span style="background: rgba(255, 212, 0, 0.1); color: #FFD400;">Grades 8-9</span>
                <span style="background: rgba(255, 212, 0, 0.1); color: #FFD400;">18 Courses</span>
            </div>
        </div>
        <div class="ns-path-card ns-fade-up">
            <div class="ns-path-icon" style="background: rgba(255, 152, 0, 0.1); color: #FF9800;">
                <i class="bi bi-trophy"></i>
            </div>
            <h3>Advanced</h3>
            <p>Master complex systems, lead team projects, and develop portfolio-ready prototypes.</p>
            <div class="ns-path-levels">
                <span style="background: rgba(255, 152, 0, 0.1); color: #FF9800;">Grades 10-12</span>
                <span style="background: rgba(255, 152, 0, 0.1); color: #FF9800;">20 Courses</span>
            </div>
        </div>
    </div>
</section>

<section class="ns-section" id="courses">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Popular Courses</div>
        <h2>Featured Courses</h2>
        <p>Explore our most popular courses chosen by thousands of students worldwide.</p>
    </div>
    <div class="ns-courses-grid">
        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FFD400, #FF9800);">
                <i class="bi bi-motherboard" style="color: rgba(0,0,0,0.6)"></i>
                <span class="ns-course-badge">Popular</span>
            </div>
            <div class="ns-course-body">
                <h4>Introduction to Arduino</h4>
                <p>Build your first circuit and program it. Perfect for complete beginners.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 12 Lessons</span>
                    <span><i class="bi bi-people"></i> 156</span>
                </div>
            </div>
        </div>
        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #00D26A, #00B894);">
                <i class="bi bi-robot" style="color: rgba(0,0,0,0.6)"></i>
                <span class="ns-course-badge">New</span>
            </div>
            <div class="ns-course-body">
                <h4>Robotics with Micro:bit</h4>
                <p>Design, build, and program your own robot from scratch.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 16 Lessons</span>
                    <span><i class="bi bi-people"></i> 98</span>
                </div>
            </div>
        </div>
        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">
                <i class="bi bi-braces-asterisk" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Trending</span>
            </div>
            <div class="ns-course-body">
                <h4>AI for Young Minds</h4>
                <p>Understand machine learning through visual tools and experiments.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 10 Lessons</span>
                    <span><i class="bi bi-people"></i> 210</span>
                </div>
            </div>
        </div>
        <div class="ns-course-card ns-fade-up">
            <div class="ns-course-thumb" style="background: linear-gradient(135deg, #FF4D4F, #E74C3C);">
                <i class="bi bi-wifi" style="color: rgba(255,255,255,0.9)"></i>
                <span class="ns-course-badge">Bestseller</span>
            </div>
            <div class="ns-course-body">
                <h4>Smart Home with ESP32</h4>
                <p>Build a fully connected smart home system with sensors and apps.</p>
                <div class="ns-course-meta">
                    <span><i class="bi bi-clock"></i> 20 Lessons</span>
                    <span><i class="bi bi-people"></i> 178</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ns-section" id="testimonials">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Testimonials</div>
        <h2>What Our Students Say</h2>
        <p>Hear from students who transformed their learning with Nano Spark.</p>
    </div>
    <div class="ns-testimonials-grid">
        <div class="ns-testimonial-card ns-fade-up">
            <div class="ns-testimonial-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <blockquote>"Nano Spark made me fall in love with robotics. I built my first robot in just 3 weeks! The courses are so easy to follow."</blockquote>
            <div class="ns-testimonial-author">
                <div class="ns-testimonial-avatar" style="background: linear-gradient(135deg, #FFD400, #FF9800);">A</div>
                <div>
                    <div class="ns-testimonial-name">Arjun K.</div>
                    <div class="ns-testimonial-role">Grade 8 Student</div>
                </div>
            </div>
        </div>
        <div class="ns-testimonial-card ns-fade-up">
            <div class="ns-testimonial-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <blockquote>"The IoT course helped me win my school science fair! I built a weather station that sends data to the cloud."</blockquote>
            <div class="ns-testimonial-author">
                <div class="ns-testimonial-avatar" style="background: linear-gradient(135deg, #00D26A, #00B894);">P</div>
                <div>
                    <div class="ns-testimonial-name">Priya M.</div>
                    <div class="ns-testimonial-role">Grade 10 Student</div>
                </div>
            </div>
        </div>
        <div class="ns-testimonial-card ns-fade-up">
            <div class="ns-testimonial-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <blockquote>"I never thought I could understand AI, but Nano Spark's visual approach made it simple. Now I'm building my own chatbot!"</blockquote>
            <div class="ns-testimonial-author">
                <div class="ns-testimonial-avatar" style="background: linear-gradient(135deg, #3B82F6, #2563EB);">R</div>
                <div>
                    <div class="ns-testimonial-name">Rahul S.</div>
                    <div class="ns-testimonial-role">Grade 7 Student</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ns-cta">
    <div class="ns-cta-box ns-fade-up">
        <h2>Ready to Start Your<br>Learning Journey?</h2>
        <p>Join hundreds of students already building the future. It's free to get started.</p>
        @auth
            <a href="{{ route('login') }}" class="ns-hero-btn-primary">
                <i class="bi bi-arrow-right"></i> Go to Dashboard
            </a>
        @else
            <a href="{{ route('register') }}" class="ns-hero-btn-primary">
                <i class="bi bi-rocket-takeoff"></i> Get Started Free
            </a>
        @endauth
    </div>
</section>

@endsection

@section('scripts')
<script>
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

    const counters = document.querySelectorAll('.ns-stat-item h3[data-target]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target'));
                let current = 0;
                const step = Math.ceil(target / 60);
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) { current = target; clearInterval(timer); }
                    el.textContent = current + '+';
                }, 30);
                counterObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(c => counterObserver.observe(c));

    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(a.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endsection
