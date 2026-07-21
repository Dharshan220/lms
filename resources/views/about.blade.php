@extends('layouts.guest')

@section('title', 'About - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-about-hero {
        min-height: 60vh; display: flex; align-items: center; justify-content: center;
        text-align: center; padding: 120px 24px 60px; position: relative;
    }
    .ns-about-hero h1 {
        font-family: 'Space Mono', monospace;
        font-size: clamp(32px, 5vw, 56px); font-weight: 700;
        color: #FFFFFF; margin-bottom: 16px;
    }
    .ns-about-hero p { font-size: 18px; color: #888888; max-width: 600px; margin: 0 auto; line-height: 1.8; }
    .ns-about-section { padding: 80px 24px; max-width: 1000px; margin: 0 auto; }
    .ns-about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
    .ns-about-grid h2 {
        font-family: 'Space Mono', monospace;
        font-size: 28px; font-weight: 700; color: #FFFFFF; margin-bottom: 16px;
    }
    .ns-about-grid p { font-size: 15px; color: #888888; line-height: 1.8; }
    .ns-about-values { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .ns-about-value {
        background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; padding: 32px 24px; text-align: center;
    }
    .ns-about-value i { font-size: 32px; color: #FFD400; margin-bottom: 16px; display: block; }
    .ns-about-value h3 {
        font-family: 'Space Mono', monospace;
        font-size: 16px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px;
    }
    .ns-about-value p { font-size: 13px; color: #888888; line-height: 1.6; }
    .ns-team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .ns-team-card {
        background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; padding: 32px; text-align: center;
    }
    .ns-team-avatar {
        width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 16px;
        background: linear-gradient(135deg, #FFD400, #FF9800);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 700; color: #050505;
        font-family: 'Space Mono', monospace;
    }
    .ns-team-card h3 { font-family: 'Space Mono', monospace; font-size: 16px; font-weight: 700; color: #FFFFFF; margin-bottom: 4px; }
    .ns-team-card p { font-size: 13px; color: #888888; }
    @media (max-width: 768px) {
        .ns-about-grid { grid-template-columns: 1fr; gap: 32px; }
        .ns-about-values, .ns-team-grid { grid-template-columns: 1fr; }
    }
</style>

<section class="ns-about-hero">
    <div>
        <span class="ns-tag" style="display:inline-block;padding:4px 14px;border-radius:100px;background:rgba(255,212,0,0.08);border:1px solid rgba(255,212,0,0.2);color:#FFD400;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:16px">About Us</span>
        <h1>Building the Future of<br><span style="color:#FFD400">STEM Education</span></h1>
        <p>Nano Spark is on a mission to make IoT, Robotics, and AI education accessible to every student in grades 6-12.</p>
    </div>
</section>

<section class="ns-about-section">
    <div class="ns-about-grid">
        <div>
            <h2>Our Mission</h2>
            <p>We believe every student deserves hands-on experience with cutting-edge technology. Nano Spark provides interactive courses, real hardware kits, and AI-powered learning tools to make STEM education engaging and effective.</p>
            <p style="margin-top:16px">Founded in 2024, we've already impacted hundreds of students across multiple schools, helping them build real projects and develop skills for the future.</p>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="ns-card" style="text-align:center;padding:32px 16px">
                <div style="font-family:var(--font-dashboard);font-size:32px;font-weight:700;color:#FFD400">500+</div>
                <div style="font-size:13px;color:var(--text-muted)">Students</div>
            </div>
            <div class="ns-card" style="text-align:center;padding:32px 16px">
                <div style="font-family:var(--font-dashboard);font-size:32px;font-weight:700;color:#FFD400">50+</div>
                <div style="font-size:13px;color:var(--text-muted)">Courses</div>
            </div>
            <div class="ns-card" style="text-align:center;padding:32px 16px">
                <div style="font-family:var(--font-dashboard);font-size:32px;font-weight:700;color:#00D26A">20+</div>
                <div style="font-size:13px;color:var(--text-muted)">Schools</div>
            </div>
            <div class="ns-card" style="text-align:center;padding:32px 16px">
                <div style="font-family:var(--font-dashboard);font-size:32px;font-weight:700;color:#FF9800">95%</div>
                <div style="font-size:13px;color:var(--text-muted)">Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<section class="ns-about-section" style="background:var(--bg-secondary);border-radius:24px;max-width:1200px">
    <div class="ns-section-header" style="margin-bottom:48px">
        <div class="ns-tag">Our Values</div>
        <h2 style="font-family:'Space Mono',monospace;font-size:32px;font-weight:700;color:#FFFFFF">What Drives Us</h2>
    </div>
    <div class="ns-about-values">
        <div class="ns-about-value">
            <i class="bi bi-lightbulb-fill"></i>
            <h3>Innovation</h3>
            <p>Constantly pushing boundaries in STEM education with AI-powered tools and modern pedagogy.</p>
        </div>
        <div class="ns-about-value">
            <i class="bi bi-people-fill"></i>
            <h3>Inclusivity</h3>
            <p>Making quality STEM education accessible to every student, regardless of background.</p>
        </div>
        <div class="ns-about-value">
            <i class="bi bi-tools"></i>
            <h3>Hands-On Learning</h3>
            <p>Learning by doing. Every course includes real projects and practical experiments.</p>
        </div>
    </div>
</section>

<section class="ns-about-section">
    <div class="ns-section-header" style="margin-bottom:48px">
        <div class="ns-tag">Meet the Team</div>
        <h2 style="font-family:'Space Mono',monospace;font-size:32px;font-weight:700;color:#FFFFFF">The People Behind Nano Spark</h2>
    </div>
    <div class="ns-team-grid">
        <div class="ns-team-card">
            <div class="ns-team-avatar">AK</div>
            <h3>Dr. Arun Kumar</h3>
            <p style="color:var(--accent-primary);font-size:12px;margin-bottom:8px">Founder & CEO</p>
            <p>Passionate about making STEM education accessible to every student.</p>
        </div>
        <div class="ns-team-card">
            <div class="ns-team-avatar" style="background:linear-gradient(135deg,#00D26A,#00B894)">PM</div>
            <h3>Priya Mehta</h3>
            <p style="color:var(--success);font-size:12px;margin-bottom:8px">Head of Curriculum</p>
            <p>Designing engaging learning experiences for young innovators.</p>
        </div>
        <div class="ns-team-card">
            <div class="ns-team-avatar" style="background:linear-gradient(135deg,#3B82F6,#2563EB)">RS</div>
            <h3>Raj Singh</h3>
            <p style="color:var(--info);font-size:12px;margin-bottom:8px">CTO</p>
            <p>Building the technology platform that powers the future of learning.</p>
        </div>
    </div>
</section>
@endsection
