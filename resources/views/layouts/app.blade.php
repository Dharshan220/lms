<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name', 'Nano Spark LMS') }} - Modern Learning Management System">
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

    @stack('styles')
</head>
<body>
    @auth
    <div class="ns-app-wrapper" x-data="nsApp()">
        {{-- Mobile Overlay --}}
        <div class="ns-sidebar-overlay" :class="{ 'show': sidebarMobileOpen }"
             @click="sidebarMobileOpen = false"></div>

        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main --}}
        <div class="ns-main">
            {{-- Navbar --}}
            <nav class="ns-navbar">
                <button class="ns-navbar-toggle" @click="toggleSidebar()" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <div class="ns-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search courses, quizzes..." x-model="searchQuery" @keydown.escape="searchQuery = ''">
                </div>

                <div class="ns-navbar-actions">
                    {{-- Notifications --}}
                    @include('components.notification-dropdown')

                    {{-- Dark Mode Toggle --}}
                    <label class="ns-theme-toggle" title="Toggle Dark Mode">
                        <input type="checkbox" :checked="darkMode" @change="toggleDarkMode()">
                        <span class="slider"></span>
                    </label>

                    {{-- User Dropdown --}}
                    <div class="ns-user-dropdown" @click.outside="userDropdownOpen = false">
                        <button class="ns-user-trigger" @click="userDropdownOpen = !userDropdownOpen">
                            <div class="ns-user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div style="text-align:left">
                                <div class="ns-user-name">{{ Auth::user()->name }}</div>
                                <div class="ns-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'Student')) }}</div>
                            </div>
                            <i class="bi bi-chevron-down" style="font-size:12px; color:var(--text-muted)"></i>
                        </button>

                        <div class="ns-dropdown-menu" :class="{ 'show': userDropdownOpen }">
                            <a href="{{ route('profile.edit') }}" class="ns-dropdown-item">
                                <i class="bi bi-person"></i> Profile
                            </a>
                            <button class="ns-dropdown-item" @click="toggleDarkMode()">
                                <i :class="darkMode ? 'bi bi-sun' : 'bi bi-moon'"></i>
                                {{ $darkMode ? 'Light Mode' : 'Dark Mode' }}
                            </button>
                            <div class="ns-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="ns-dropdown-item" style="color:var(--ns-danger)">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Page Content --}}
            <div class="ns-page-content">
                @if (session('success'))
                    <div class="ns-toast success" id="auto-toast" x-data="{ show: true }" x-show="show"
                         x-init="setTimeout(() => show = false, 4000)" x-transition>
                        <i class="bi bi-check-circle-fill ns-toast-icon"></i>
                        <div class="ns-toast-content">
                            <div class="ns-toast-title">Success</div>
                            <div class="ns-toast-message">{{ session('success') }}</div>
                        </div>
                        <button class="ns-toast-close" @click="show = false"><i class="bi bi-x"></i></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="ns-toast danger" x-data="{ show: true }" x-show="show"
                         x-init="setTimeout(() => show = false, 5000)" x-transition>
                        <i class="bi bi-exclamation-circle-fill ns-toast-icon"></i>
                        <div class="ns-toast-content">
                            <div class="ns-toast-title">Error</div>
                            <div class="ns-toast-message">{{ session('error') }}</div>
                        </div>
                        <button class="ns-toast-close" @click="show = false"><i class="bi bi-x"></i></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        {{-- AI Chat Widget (hidden on the full AI chat page) --}}
        @if(Auth::user() && in_array(Auth::user()->role, ['student', null]) && request()->route()?->getName() !== 'ai.chat.index')
        <div class="ns-chat-widget" x-data="nsChat()">
            <div class="ns-chat-box" :class="{ 'show': chatOpen }">
                <div class="ns-chat-header">
                    <div class="ns-chat-header-avatar"><i class="bi bi-robot"></i></div>
                    <div>
                        <div style="font-weight:700; font-size:15px">Nano AI Tutor</div>
                        <div style="font-size:12px; opacity:0.85">Always here to help!</div>
                    </div>
                </div>
                <div class="ns-chat-messages" x-ref="chatMessages">
                    <div class="ns-chat-bubble bot">Hi there! I'm your AI tutor. Ask me anything about your courses!</div>
                    <template x-for="(msg, i) in messages" :key="i">
                        <div class="ns-chat-bubble" :class="msg.role" x-text="msg.text"></div>
                    </template>
                </div>
                <div class="ns-chat-input">
                    <input type="text" placeholder="Ask a question..." x-model="chatInput"
                           @keydown.enter="sendMessage()">
                    <button @click="sendMessage()"><i class="bi bi-send-fill"></i></button>
                </div>
            </div>
            <button class="ns-chat-toggle" @click="chatOpen = !chatOpen"
                    :class="{ 'animate-bounceIn': chatOpen }">
                <i :class="chatOpen ? 'bi bi-x-lg' : 'bi bi-chat-dots-fill'"></i>
            </button>
        </div>
        @endif

        {{-- Toast Container --}}
        <div class="ns-toast-container" id="toast-container"></div>
    </div>
    @else
        {{-- Guest --}}
        @yield('guest-content')
    @endauth

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        function nsApp() {
            return {
                sidebarCollapsed: localStorage.getItem('ns-sidebar-collapsed') === 'true',
                sidebarMobileOpen: false,
                darkMode: localStorage.getItem('ns-theme') === 'dark',
                userDropdownOpen: false,
                searchQuery: '',
                notificationOpen: false,
                unreadCount: {{ Auth::user() ? (Auth::user()->notifications()->whereNull('read_at')->count() ?? 0) : 0 }},

                init() {
                    if (this.darkMode) {
                        document.documentElement.setAttribute('data-theme', 'dark');
                    }
                },

                toggleSidebar() {
                    if (window.innerWidth <= 768) {
                        this.sidebarMobileOpen = !this.sidebarMobileOpen;
                    } else {
                        this.sidebarCollapsed = !this.sidebarCollapsed;
                        localStorage.setItem('ns-sidebar-collapsed', this.sidebarCollapsed);
                    }
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('ns-theme', this.darkMode ? 'dark' : 'light');
                    document.documentElement.setAttribute('data-theme', this.darkMode ? 'dark' : 'light');
                }
            };
        }

        function nsChat() {
            return {
                chatOpen: false,
                chatInput: '',
                messages: [],
                loading: false,
                sendMessage() {
                    if (!this.chatInput.trim() || this.loading) return;
                    this.messages.push({ role: 'user', text: this.chatInput });
                    const userMsg = this.chatInput;
                    this.chatInput = '';
                    this.loading = true;
                    this.$nextTick(() => {
                        this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                    });
                    fetch('{{ route("ai.chat.send") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ message: userMsg, chat_type: 'tutor' })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.loading = false;
                        const response = data.response || data.message || 'Sorry, I could not process your request.';
                        this.messages.push({ role: 'bot', text: response });
                        this.$nextTick(() => {
                            this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                        });
                    })
                    .catch(() => {
                        this.loading = false;
                        this.messages.push({ role: 'bot', text: 'Sorry, something went wrong. Please try again.' });
                        this.$nextTick(() => {
                            this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                        });
                    });
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
