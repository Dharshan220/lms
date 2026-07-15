@php
    $user = Auth::user();
    $role = $user->role ?? 'student';
    $currentRoute = request()->route()->getName();
@endphp

<aside class="ns-sidebar" :class="{ 'collapsed': sidebarCollapsed, 'mobile-open': sidebarMobileOpen }">

    {{-- Brand --}}
    <div class="ns-sidebar-brand">
        <a href="{{ $role === 'super_admin' || $role === 'school_admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'student' ? route('student.dashboard') : route('parent.dashboard'))) }}" style="text-decoration:none; display:flex; align-items:center; gap:12px">
            <div class="brand-icon">NS</div>
            <span class="brand-text">Nano Spark</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="ns-sidebar-nav">

        {{-- ========== SUPER ADMIN / SCHOOL ADMIN ========== --}}
        @if(in_array($role, ['super_admin', 'school_admin']))
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ $role === 'super_admin' || $role === 'school_admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'student' ? route('student.dashboard') : route('parent.dashboard'))) }}" class="ns-nav-item {{ $currentRoute === 'admin.dashboard' || $currentRoute === 'teacher.dashboard' || $currentRoute === 'student.dashboard' || $currentRoute === 'parent.dashboard' ? 'active' : '' }}">
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
                    <span class="ns-nav-badge warning">3</span>
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

        {{-- ========== TEACHER ========== --}}
        @elseif($role === 'teacher')
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ $role === 'super_admin' || $role === 'school_admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'student' ? route('student.dashboard') : route('parent.dashboard'))) }}" class="ns-nav-item {{ $currentRoute === 'admin.dashboard' || $currentRoute === 'teacher.dashboard' || $currentRoute === 'student.dashboard' || $currentRoute === 'parent.dashboard' ? 'active' : '' }}">
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
                <div class="ns-nav-section-title">Tools</div>
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

        {{-- ========== STUDENT ========== --}}
        @elseif($role === 'student')
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ $role === 'super_admin' || $role === 'school_admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'student' ? route('student.dashboard') : route('parent.dashboard'))) }}" class="ns-nav-item {{ $currentRoute === 'admin.dashboard' || $currentRoute === 'teacher.dashboard' || $currentRoute === 'student.dashboard' || $currentRoute === 'parent.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>

            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Learning</div>
                <a href="{{ route('student.courses.index') }}" class="ns-nav-item {{ str_starts_with($currentRoute, 'student.courses') ? 'active' : '' }}">
                    <i class="bi bi-compass-fill"></i>
                    <span class="nav-label">Browse Courses</span>
                </a>
                <a href="{{ route('student.courses.my') }}" class="ns-nav-item {{ $currentRoute === 'student.courses.my' ? 'active' : '' }}">
                    <i class="bi bi-book-half"></i>
                    <span class="nav-label">My Courses</span>
                </a>

                <a href="{{ route('student.assignments.index') }}" class="ns-nav-item {{ $currentRoute === 'student.assignments.index' ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span class="nav-label">Assignments</span>
                    <span class="ns-nav-badge">4</span>
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
            </div>

            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Community</div>
                <a href="{{ route('discussions.index') }}" class="ns-nav-item {{ $currentRoute === 'discussions.index' ? 'active' : '' }}">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="nav-label">Discussions</span>
                </a>

            </div>

        {{-- ========== PARENT ========== --}}
        @elseif($role === 'parent')
            <div class="ns-nav-section">
                <div class="ns-nav-section-title">Main</div>
                <a href="{{ $role === 'super_admin' || $role === 'school_admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'student' ? route('student.dashboard') : route('parent.dashboard'))) }}" class="ns-nav-item {{ $currentRoute === 'admin.dashboard' || $currentRoute === 'teacher.dashboard' || $currentRoute === 'student.dashboard' || $currentRoute === 'parent.dashboard' ? 'active' : '' }}">
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
            {{-- Fallback --}}
            <div class="ns-nav-section">
                <a href="{{ $role === 'super_admin' || $role === 'school_admin' ? route('admin.dashboard') : ($role === 'teacher' ? route('teacher.dashboard') : ($role === 'student' ? route('student.dashboard') : route('parent.dashboard'))) }}" class="ns-nav-item {{ $currentRoute === 'admin.dashboard' || $currentRoute === 'teacher.dashboard' || $currentRoute === 'student.dashboard' || $currentRoute === 'parent.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
        @endif

    </nav>

    {{-- Sidebar Footer --}}
    <div class="ns-sidebar-footer">
        <div style="display:flex; align-items:center; gap:12px; padding:4px 0;">
            <div class="ns-user-avatar" style="width:32px; height:32px; font-size:12px; flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="flex:1; min-width:0;" class="nav-label-container">
                <div style="font-size:13px; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $user->name }}
                </div>
                <div style="font-size:11px; color:#6B7280; text-transform:capitalize;">
                    {{ str_replace('_', ' ', $role) }}
                </div>
            </div>
        </div>
    </div>

</aside>
