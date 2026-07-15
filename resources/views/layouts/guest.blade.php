<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name', 'Nano Spark LMS') }} - Spark Your Learning Journey">
    <meta name="theme-color" content="#FF6B35">

    <title>@yield('title', config('app.name', 'Nano Spark LMS'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Pre-built Assets -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-uiRKhBIp.css') }}">
    <script src="{{ asset('build/assets/app-CIomGrQN.js') }}" defer></script>

    <style>
        .guest-hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0F1117 0%, #1A1D29 50%, #0F1117 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .guest-hero::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .guest-hero::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -150px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(78, 205, 196, 0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .guest-nav {
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }

        .guest-nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .guest-nav-brand .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #FF6B35, #9B59B6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 16px;
        }

        .guest-nav-brand .brand-text {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, #FF8F65, #4ECDC4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .guest-nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .guest-nav-links a {
            color: #A1A5B7;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .guest-nav-links a:hover { color: #fff; }

        .guest-nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .guest-btn-login {
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            color: #A1A5B7;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .guest-btn-login:hover { color: #fff; border-color: rgba(255, 255, 255, 0.3); }

        .guest-btn-register {
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #FF6B35, #E55A24);
            border: none;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 2px 12px rgba(255, 107, 53, 0.3);
            transition: all 0.2s;
        }

        .guest-btn-register:hover {
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.5);
            transform: translateY(-1px);
            color: #fff;
        }

        .guest-mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        .guest-footer {
            background: #131520;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 48px 40px 24px;
            position: relative;
            z-index: 10;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-brand-text { color: #6B7280; font-size: 14px; line-height: 1.7; margin-top: 12px; }

        .footer-heading {
            font-size: 14px;
            font-weight: 700;
            color: #F3F4F6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: #6B7280; font-size: 14px; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #FF6B35; }

        .footer-social { display: flex; gap: 12px; margin-top: 20px; }
        .footer-social a {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
            font-size: 18px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .footer-social a:hover { background: #FF6B35; color: #fff; }

        .footer-bottom {
            max-width: 1200px;
            margin: 32px auto 0;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
            font-size: 13px;
            color: #4B5563;
        }

        @media (max-width: 768px) {
            .guest-nav { padding: 12px 20px; }
            .guest-nav-links, .guest-nav-actions .guest-btn-login { display: none; }
            .guest-mobile-toggle { display: block; }
            .guest-footer { padding: 32px 20px 16px; }
            .footer-grid { grid-template-columns: 1fr; gap: 24px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="guest-hero">
        {{-- Navbar --}}
        <nav class="guest-nav">
            <a href="{{ url('/') }}" class="guest-nav-brand">
                <div class="brand-icon">NS</div>
                <span class="brand-text">Nano Spark</span>
            </a>

            <ul class="guest-nav-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#courses">Courses</a></li>
            </ul>

            <div class="guest-nav-actions">
                @auth
                    <a href="{{ route('login') }}" class="guest-btn-register">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="guest-btn-login">Log In</a>
                    <a href="{{ route('register') }}" class="guest-btn-register">Get Started Free</a>
                @endauth
                <button class="guest-mobile-toggle" onclick="document.querySelector('.guest-nav-links').classList.toggle('show-mobile')">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </nav>

        {{-- Guest Content --}}
        @yield('guest-content')

        {{-- Footer --}}
        <div class="guest-footer">
            <div class="footer-grid">
                <div>
                    <a href="{{ url('/') }}" class="guest-nav-brand">
                        <div class="brand-icon">NS</div>
                        <span class="brand-text" style="font-size:20px">Nano Spark</span>
                    </a>
                    <p class="footer-brand-text">
                        Empowering the next generation of learners with AI-powered education, gamified experiences, and interactive STEM kits.
                    </p>
                    <div class="footer-social">
                        <a href="#" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div>
                    <div class="footer-heading">Platform</div>
                    <ul class="footer-links">
                        <li><a href="#courses">Browse Courses</a></li>
                        <li><a href="#">STEM Kits</a></li>
                        <li><a href="#">AI Tutor</a></li>
                        <li><a href="#">Live Classes</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-heading">Company</div>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-heading">Support</div>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Nano Spark LMS. All rights reserved. Made with <span style="color:#FF6B35">&#9829;</span> for students.
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
