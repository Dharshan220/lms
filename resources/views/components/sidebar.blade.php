@php
    $user = Auth::user();
    $role = $user->role ?? 'student';
    $currentRoute = request()->route()->getName();
    $dashboardUrl = match($role) {
        'super_admin', 'school_admin' => route('admin.dashboard'),
        'teacher' => route('teacher.dashboard'),
        'parent' => route('parent.dashboard'),
        default => route('student.dashboard'),
    };
@endphp

<aside class="ns-sidebar" :class="{ 'collapsed': sidebarCollapsed, 'mobile-open': sidebarMobileOpen }">
    <div class="ns-sidebar-brand">
        <a href="{{ $dashboardUrl }}" style="text-decoration:none; display:flex; align-items:center; gap:12px">
            <img src="{{ asset('images/nano-spark-logo.jpg') }}" alt="Nano Spark" class="brand-logo">
            <span class="brand-text">Nano Spark</span>
        </a>
    </div>

    <nav class="ns-sidebar-nav">
        @if(in_array($role, ['super_admin', 'school_admin']))
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="ns-nav-item {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Management</div>
                <a href="{{ route('admin.schools.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.schools') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span class="nav-label">Schools</span>
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.teachers') ? 'active' : '' }}">
                    <i class="bi bi-person-video3"></i>
                    <span class="nav-label">Teachers</span>
                </a>
                <a href="{{ route('admin.students.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.students') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span class="nav-label">Students</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Content</div>
                <a href="{{ route('admin.courses.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.courses') ? 'active' : '' }}">
                    <i class="bi bi-book-half"></i>
                    <span class="nav-label">Courses</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.categories') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i>
                    <span class="nav-label">Categories</span>
                </a>
                <a href="{{ route('admin.stem-kits.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.stem') ? 'active' : '' }}">
                    <i class="bi bi-cpu"></i>
                    <span class="nav-label">STEM Kits</span>
                    <span class="ns-nav-badge info">New</span>
                </a>
                <a href="{{ route('admin.certificates.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.certificates') ? 'active' : '' }}">
                    <i class="bi bi-award-fill"></i>
                    <span class="nav-label">Certificates</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Communication</div>
                <a href="{{ route('admin.announcements.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.announcements') ? 'active' : '' }}">
                    <i class="bi bi-megaphone-fill"></i>
                    <span class="nav-label">Announcements</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Insights</div>
                <a href="{{ route('admin.reports.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.reports') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span class="nav-label">Reports</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span class="nav-label">Settings</span>
                </a>
            </div>

        @elseif($role === 'teacher')
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ route('teacher.dashboard') }}" class="ns-nav-item {{ $currentRoute === 'teacher.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Teaching</div>
                <a href="{{ route('teacher.courses.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'teacher.courses') ? 'active' : '' }}">
                    <i class="bi bi-book-half"></i>
                    <span class="nav-label">My Courses</span>
                </a>
                <a href="{{ route('teacher.quizzes.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'teacher.quizzes') ? 'active' : '' }}">
                    <i class="bi bi-question-circle-fill"></i>
                    <span class="nav-label">Quizzes</span>
                </a>
                <a href="{{ route('teacher.assignments.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'teacher.assignments') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span class="nav-label">Assignments</span>
                    <span class="ns-nav-badge">5</span>
                </a>
                <a href="{{ route('teacher.live-classes.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'teacher.live') ? 'active' : '' }}">
                    <i class="bi bi-camera-video-fill"></i>
                    <span class="nav-label">Live Classes</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">AI Tools</div>
                <a href="{{ route('teacher.ai-lesson-planner.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'teacher.ai-lesson-planner') ? 'active' : '' }}">
                    <i class="bi bi-magic"></i>
                    <span class="nav-label">AI Lesson Planner</span>
                    <span class="ns-nav-badge accent">AI</span>
                </a>
                <a href="{{ route('discussions.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'discussions') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="nav-label">Discussions</span>
                </a>
            </div>

        @elseif($role === 'student')
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ route('student.dashboard') }}" class="ns-nav-item {{ $currentRoute === 'student.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Learning</div>
                <a href="{{ route('student.courses.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'student.courses') && $currentRoute !== 'student.courses.my' ? 'active' : '' }}">
                    <i class="bi bi-compass-fill"></i>
                    <span class="nav-label">Browse Courses</span>
                </a>
                <a href="{{ route('student.courses.my') }}" class="ns-nav-item {{ $currentRoute === 'student.courses.my' ? 'active' : '' }}">
                    <i class="bi bi-book-half"></i>
                    <span class="nav-label">My Courses</span>
                </a>
                <a href="{{ route('student.assignments.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'student.assignments') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span class="nav-label">Assignments</span>
                </a>
                <a href="{{ route('student.certificates.index') }}" class="ns-nav-item {{ $currentRoute === 'student.certificates.index' ? 'active' : '' }}">
                    <i class="bi bi-award-fill"></i>
                    <span class="nav-label">Certificates</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">AI Tools</div>
                <a href="{{ route('ai.chat.index') }}" class="ns-nav-item {{ $currentRoute === 'ai.chat.index' ? 'active' : '' }}">
                    <i class="bi bi-robot"></i>
                    <span class="nav-label">AI Tutor</span>
                    <span class="ns-nav-badge accent">AI</span>
                </a>
                <a href="{{ route('ai.quiz-generator.index') }}" class="ns-nav-item {{ $currentRoute === 'ai.quiz-generator.index' ? 'active' : '' }}">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span class="nav-label">AI Quiz Generator</span>
                    <span class="ns-nav-badge accent">AI</span>
                </a>
                <a href="{{ route('ai.project-ideas.index') }}" class="ns-nav-item {{ $currentRoute === 'ai.project-ideas.index' ? 'active' : '' }}">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span class="nav-label">AI Project Ideas</span>
                    <span class="ns-nav-badge accent">AI</span>
                </a>
                <a href="{{ route('ai.code-debugger.index') }}" class="ns-nav-item {{ $currentRoute === 'ai.code-debugger.index' ? 'active' : '' }}">
                    <i class="bi bi-bug-fill"></i>
                    <span class="nav-label">AI Code Debugger</span>
                    <span class="ns-nav-badge accent">AI</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Community</div>
                <a href="{{ route('discussions.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'discussions') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="nav-label">Discussions</span>
                </a>
            </div>

        @elseif($role === 'parent')
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ route('parent.dashboard') }}" class="ns-nav-item {{ $currentRoute === 'parent.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Family</div>
                <a href="{{ route('parent.dashboard') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'parent.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span class="nav-label">My Children</span>
                </a>
            </div>
        @else
            <div class="ns-nav-section">
                <a href="{{ $dashboardUrl }}" class="ns-nav-item {{ str_contains($currentRoute, 'dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
        @endif
    </nav>

    <div class="ns-sidebar-footer">
        <div style="display:flex; align-items:center; gap:12px; padding:4px 0;">
            <div class="ns-user-avatar" style="width:32px; height:32px; font-size:12px; flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="flex:1; min-width:0;" class="nav-label-container">
                <div style="font-size:13px; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $user->name }}
                </div>
                <div style="font-size:11px; color:var(--text-muted); text-transform:capitalize;">
                    {{ str_replace('_', ' ', $role) }}
                </div>
            </div>
        </div>
    </div>
</aside>
