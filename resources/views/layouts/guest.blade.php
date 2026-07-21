<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name', 'Nano Spark LMS') }} - Master IoT, Robotics & AI">
    <meta name="theme-color" content="#FFD400">

    <title>@yield('title', config('app.name', 'Nano Spark LMS'))</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/nano-spark-logo.jpg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: #050505;
            color: #FFFFFF;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .ns-guest-nav {
            position: fixed; top: 0; left: 0; right: 0;
            padding: 16px 40px;
            display: flex; align-items: center; justify-content: space-between;
            z-index: 1000;
            backdrop-filter: blur(20px);
            background: rgba(5, 5, 5, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            transition: all 0.3s;
        }
        .ns-guest-nav.scrolled {
            background: rgba(5, 5, 5, 0.95);
            border-bottom: 1px solid rgba(255, 212, 0, 0.1);
        }

        .ns-guest-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none;
        }
        .ns-guest-brand .brand-logo {
            width: 40px; height: 40px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 0 16px rgba(255, 212, 0, 0.3);
        }
        .ns-guest-brand .brand-text {
            font-family: 'Space Mono', monospace;
            font-size: 20px; font-weight: 700;
            color: #FFD400;
        }

        .ns-guest-links {
            display: flex; align-items: center; gap: 36px;
            list-style: none; margin: 0; padding: 0;
        }
        .ns-guest-links a {
            color: #888888; font-size: 15px; font-weight: 500;
            text-decoration: none; transition: color 0.2s;
            position: relative;
        }
        .ns-guest-links a:hover { color: #FFD400; }
        .ns-guest-links a::after {
            content: ''; position: absolute;
            bottom: -4px; left: 0; width: 0; height: 2px;
            background: #FFD400; transition: width 0.3s;
        }
        .ns-guest-links a:hover::after { width: 100%; }

        .ns-guest-actions { display: flex; align-items: center; gap: 12px; }
        .ns-guest-btn-login {
            padding: 10px 24px; font-size: 14px; font-weight: 600;
            color: #CFCFCF; background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px; text-decoration: none;
            transition: all 0.2s;
        }
        .ns-guest-btn-login:hover { color: #FFD400; border-color: rgba(255, 212, 0, 0.3); }
        .ns-guest-btn-register {
            padding: 10px 24px; font-size: 14px; font-weight: 700;
            color: #050505;
            background: linear-gradient(135deg, #FFD400, #FF9800);
            border: none; border-radius: 12px; text-decoration: none;
            box-shadow: 0 2px 12px rgba(255, 212, 0, 0.3);
            transition: all 0.2s;
        }
        .ns-guest-btn-register:hover {
            box-shadow: 0 4px 20px rgba(255, 212, 0, 0.5);
            transform: translateY(-1px); color: #050505;
        }

        .ns-guest-mobile-toggle {
            display: none; background: none; border: none;
            color: #FFD400; font-size: 24px; cursor: pointer;
        }

        .ns-guest-footer {
            background: #0B0B0B;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding: 64px 40px 24px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px; max-width: 1200px; margin: 0 auto;
        }
        .footer-brand-text { color: #888888; font-size: 14px; line-height: 1.7; margin-top: 12px; }
        .footer-heading {
            font-family: 'Space Mono', monospace;
            font-size: 12px; font-weight: 700;
            color: #CFCFCF; text-transform: uppercase;
            letter-spacing: 1.5px; margin-bottom: 16px;
        }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: #888888; font-size: 14px; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #FFD400; }
        .footer-social { display: flex; gap: 12px; margin-top: 20px; }
        .footer-social a {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            display: flex; align-items: center; justify-content: center;
            color: #888888; font-size: 18px; transition: all 0.2s; text-decoration: none;
        }
        .footer-social a:hover { background: rgba(255, 212, 0, 0.1); border-color: rgba(255, 212, 0, 0.3); color: #FFD400; }
        .footer-bottom {
            max-width: 1200px; margin: 32px auto 0;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            text-align: center; font-size: 13px; color: #888888;
        }

        @media (max-width: 768px) {
            .ns-guest-nav { padding: 12px 20px; }
            .ns-guest-links, .ns-guest-actions .ns-guest-btn-login { display: none; }
            .ns-guest-mobile-toggle { display: block; }
            .ns-guest-footer { padding: 32px 20px 16px; }
            .footer-grid { grid-template-columns: 1fr; gap: 24px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div style="min-height: 100vh; display: flex; flex-direction: column;">
        <nav class="ns-guest-nav" id="guestNav">
            <a href="{{ url('/') }}" class="ns-guest-brand">
                <img src="{{ asset('images/nano-spark-logo.jpg') }}" alt="Nano Spark" class="brand-logo">
                <span class="brand-text">Nano Spark</span>
            </a>
            <ul class="ns-guest-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/about') }}">About</a></li>
                <li><a href="{{ url('/courses') }}">Courses</a></li>
            </ul>
            <div class="ns-guest-actions">
                @auth
                    <a href="{{ route('login') }}" class="ns-guest-btn-register">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="ns-guest-btn-login">Log In</a>
                    <a href="{{ route('register') }}" class="ns-guest-btn-register">Get Started</a>
                @endauth
                <button class="ns-guest-mobile-toggle" onclick="document.querySelector('.ns-guest-links').classList.toggle('show-mobile')">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </nav>

        @yield('guest-content')

        <div class="ns-guest-footer">
            <div class="footer-grid">
                <div>
                    <a href="{{ url('/') }}" class="ns-guest-brand">
                        <img src="{{ asset('images/nano-spark-logo.jpg') }}" alt="Nano Spark" class="brand-logo">
                        <span class="brand-text" style="font-size:18px">Nano Spark</span>
                    </a>
                    <p class="footer-brand-text">
                        Empowering the next generation with AI-powered IoT, Robotics, and STEM education. Building future-ready innovators.
                    </p>
                    <div class="footer-social">
                        <a href="#" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" title="GitHub"><i class="bi bi-github"></i></a>
                        <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div>
                    <div class="footer-heading">Platform</div>
                    <ul class="footer-links">
                        <li><a href="{{ url('/courses') }}">Browse Courses</a></li>
                        <li><a href="#">STEM Kits</a></li>
                        <li><a href="#">AI Tutor</a></li>
                        <li><a href="#">Live Classes</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-heading">Company</div>
                    <ul class="footer-links">
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Careers</a></li>
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
                &copy; {{ date('Y') }} Nano Spark LMS. All rights reserved.
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('guestNav');
            if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>

    @stack('scripts')
</body>
</html>
