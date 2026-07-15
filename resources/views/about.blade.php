@extends('layouts.guest')

@section('title', 'About Us - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-page-hero {
        padding: 120px 24px 60px;
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

    .ns-about-section {
        padding: 80px 24px;
        max-width: 1100px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    .ns-about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    .ns-about-grid h2 {
        font-size: 32px;
        font-weight: 800;
        color: #F9FAFB;
        margin-bottom: 16px;
    }

    .ns-about-grid p {
        font-size: 15px;
        color: #9CA3AF;
        line-height: 1.8;
        margin-bottom: 16px;
    }

    .ns-about-icon-box {
        width: 100%;
        aspect-ratio: 1;
        max-width: 360px;
        margin: 0 auto;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        position: relative;
        overflow: hidden;
    }

    .ns-about-icon-box::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.15), rgba(78, 205, 196, 0.15));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
    }

    .ns-mv-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .ns-mv-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 36px;
        transition: all 0.3s;
    }

    .ns-mv-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    .ns-mv-card .ns-mv-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 20px;
    }

    .ns-mv-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 10px;
    }

    .ns-mv-card p {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.7;
    }

    .ns-team-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .ns-team-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 32px 20px;
        text-align: center;
        transition: all 0.3s;
    }

    .ns-team-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
        transform: translateY(-4px);
    }

    .ns-team-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        margin: 0 auto 16px;
    }

    .ns-team-card h4 {
        font-size: 16px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 4px;
    }

    .ns-team-card .ns-team-role {
        font-size: 13px;
        color: #FF6B35;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .ns-team-card p {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
    }

    .ns-why-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .ns-why-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        transition: all 0.3s;
    }

    .ns-why-card:hover {
        border-color: rgba(255, 255, 255, 0.12);
        transform: translateY(-3px);
    }

    .ns-why-card .ns-why-num {
        font-size: 36px;
        font-weight: 800;
        background: linear-gradient(135deg, #FF6B35, #4ECDC4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 12px;
    }

    .ns-why-card h4 {
        font-size: 16px;
        font-weight: 700;
        color: #F3F4F6;
        margin-bottom: 8px;
    }

    .ns-why-card p {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
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

    @media (max-width: 768px) {
        .ns-about-grid,
        .ns-mv-grid { grid-template-columns: 1fr; }
        .ns-team-grid { grid-template-columns: repeat(2, 1fr); }
        .ns-why-grid { grid-template-columns: 1fr; }
        .ns-about-icon-box { max-width: 260px; }
    }
</style>

<section class="ns-page-hero">
    <h1>About <span style="background: linear-gradient(135deg, #FF6B35, #4ECDC4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Nano Spark</span></h1>
    <p>We're on a mission to make STEM education accessible, engaging, and hands-on for every school student.</p>
</section>

<section class="ns-about-section">
    <div class="ns-about-grid ns-fade-up">
        <div>
            <div class="ns-tag" style="display:inline-block; padding:4px 14px; border-radius:100px; background:rgba(78,205,196,0.1); border:1px solid rgba(78,205,196,0.25); color:#4ECDC4; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:16px;">Our Story</div>
            <h2>Empowering Young Innovators</h2>
            <p>Founded in 2024, Nano Spark started with a simple belief: every student deserves the chance to explore technology hands-on. We noticed that most STEM programs were either too theoretical or too expensive for everyday students.</p>
            <p>We built Nano Spark to bridge that gap. Our platform combines interactive online courses with physical STEM kits, guided projects, and an AI-powered tutor that adapts to each student's learning pace.</p>
            <p>Today, we serve hundreds of students across multiple schools, helping them discover their passion for IoT, robotics, and artificial intelligence.</p>
        </div>
        <div class="ns-about-icon-box">
            <i class="bi bi-lightning-charge" style="position:relative; z-index:1; color: #FF6B35;"></i>
        </div>
    </div>
</section>

<section class="ns-about-section">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Mission & Vision</div>
        <h2>What Drives Us</h2>
    </div>

    <div class="ns-mv-grid">
        <div class="ns-mv-card ns-fade-up">
            <div class="ns-mv-icon" style="background: rgba(255, 107, 53, 0.12); color: #FF6B35;">
                <i class="bi bi-bullseye"></i>
            </div>
            <h3>Our Mission</h3>
            <p>To make quality STEM education accessible and affordable for every school student. We aim to build a generation of confident problem-solvers who can use technology to create positive change in their communities.</p>
        </div>

        <div class="ns-mv-card ns-fade-up">
            <div class="ns-mv-icon" style="background: rgba(78, 205, 196, 0.12); color: #4ECDC4;">
                <i class="bi bi-eye"></i>
            </div>
            <h3>Our Vision</h3>
            <p>To become the world's leading platform for youth STEM education, where every student has the tools, mentorship, and confidence to innovate. We envision a future where technology education is not a privilege but a right.</p>
        </div>
    </div>
</section>

<section class="ns-about-section">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Our Team</div>
        <h2>Meet the People Behind Nano Spark</h2>
        <p>A passionate team of educators, engineers, and technologists.</p>
    </div>

    <div class="ns-team-grid">
        <div class="ns-team-card ns-fade-up">
            <div class="ns-team-avatar" style="background: linear-gradient(135deg, #FF6B35, #E55A24);">V</div>
            <h4>Vikram Patel</h4>
            <div class="ns-team-role">Founder & CEO</div>
            <p>Former robotics engineer with 10+ years in STEM education.</p>
        </div>

        <div class="ns-team-card ns-fade-up">
            <div class="ns-team-avatar" style="background: linear-gradient(135deg, #4ECDC4, #2BA89E);">S</div>
            <h4>Sarah Chen</h4>
            <div class="ns-team-role">Head of Curriculum</div>
            <p>PhD in Computer Science, passionate about making AI accessible to kids.</p>
        </div>

        <div class="ns-team-card ns-fade-up">
            <div class="ns-team-avatar" style="background: linear-gradient(135deg, #A78BFA, #7C3AED);">R</div>
            <h4>Raj Mehta</h4>
            <div class="ns-team-role">CTO</div>
            <p>Full-stack developer and IoT enthusiast building the platform's core.</p>
        </div>

        <div class="ns-team-card ns-fade-up">
            <div class="ns-team-avatar" style="background: linear-gradient(135deg, #FBBF24, #F59E0B);">A</div>
            <h4>Anita Rao</h4>
            <div class="ns-team-role">Lead Instructor</div>
            <p>Award-winning teacher who has mentored 500+ student projects.</p>
        </div>
    </div>
</section>

<section class="ns-about-section">
    <div class="ns-section-header ns-fade-up">
        <div class="ns-tag">Why Choose Us</div>
        <h2>The Nano Spark Difference</h2>
    </div>

    <div class="ns-why-grid">
        <div class="ns-why-card ns-fade-up">
            <div class="ns-why-num">01</div>
            <h4>Learn by Building</h4>
            <p>Every course includes real hardware projects, not just videos and quizzes.</p>
        </div>

        <div class="ns-why-card ns-fade-up">
            <div class="ns-why-num">02</div>
            <h4>AI-Powered Tutoring</h4>
            <p>Our AI tutor adapts to your pace, answering questions and guiding you through challenges.</p>
        </div>

        <div class="ns-why-card ns-fade-up">
            <div class="ns-why-num">03</div>
            <h4>STEM Kits Included</h4>
            <p>Get physical components delivered to your door so you can build projects at home.</p>
        </div>

        <div class="ns-why-card ns-fade-up">
            <div class="ns-why-num">04</div>
            <h4>Gamified Progress</h4>
            <p>Earn points, badges, and climb the leaderboard as you complete courses and projects.</p>
        </div>

        <div class="ns-why-card ns-fade-up">
            <div class="ns-why-num">05</div>
            <h4>Verified Certificates</h4>
            <p>Earn certificates recognized by schools and institutions to showcase your skills.</p>
        </div>

        <div class="ns-why-card ns-fade-up">
            <div class="ns-why-num">06</div>
            <h4>Community Support</h4>
            <p>Join a vibrant community of young innovators, mentors, and educators.</p>
        </div>
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
</script>
@endsection
