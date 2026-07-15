@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-muted mt-1 mb-0">Configure your LMS platform settings</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-gear me-2"></i>General Settings</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="site_name" class="form-label fw-semibold">Site Name</label>
                            <input type="text" name="site_name" id="site_name" class="form-control"
                                   value="{{ $settings['site_name'] ?? config('app.name', 'Nano Spark LMS') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="site_email" class="form-label fw-semibold">Site Email</label>
                            <input type="email" name="site_email" id="site_email" class="form-control"
                                   value="{{ $settings['site_email'] ?? config('mail.from.address', '') }}">
                        </div>
                        <div class="col-12">
                            <label for="site_description" class="form-label fw-semibold">Site Description</label>
                            <textarea name="site_description" id="site_description" rows="3" class="form-control">{{ $settings['site_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="group" value="general" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save General Settings
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-palette me-2"></i>Appearance Settings</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label for="primary_color" class="form-label fw-semibold">Primary Color</label>
                            <input type="color" name="primary_color" id="primary_color" class="form-control form-control-color"
                                   value="{{ $settings['primary_color'] ?? '#667eea' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="sidebar_color" class="form-label fw-semibold">Sidebar Color</label>
                            <input type="color" name="sidebar_color" id="sidebar_color" class="form-control form-control-color"
                                   value="{{ $settings['sidebar_color'] ?? '#1a1a2e' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="dark_mode_default" class="form-label fw-semibold">Default Dark Mode</label>
                            <select name="dark_mode_default" id="dark_mode_default" class="form-select">
                                <option value="0" {{ ($settings['dark_mode_default'] ?? '0') == '0' ? 'selected' : '' }}>Light</option>
                                <option value="1" {{ ($settings['dark_mode_default'] ?? '0') == '1' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="logo" class="form-label fw-semibold">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label for="favicon" class="form-label fw-semibold">Favicon</label>
                            <input type="file" name="favicon" id="favicon" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="group" value="appearance" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Appearance Settings
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-trophy me-2"></i>Gamification Settings</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label for="xp_per_lesson" class="form-label fw-semibold">XP per Lesson Completed</label>
                            <input type="number" name="xp_per_lesson" id="xp_per_lesson" class="form-control"
                                   value="{{ $settings['xp_per_lesson'] ?? 50 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="xp_per_quiz" class="form-label fw-semibold">XP per Quiz Passed</label>
                            <input type="number" name="xp_per_quiz" id="xp_per_quiz" class="form-control"
                                   value="{{ $settings['xp_per_quiz'] ?? 100 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="xp_per_assignment" class="form-label fw-semibold">XP per Assignment</label>
                            <input type="number" name="xp_per_assignment" id="xp_per_assignment" class="form-control"
                                   value="{{ $settings['xp_per_assignment'] ?? 75 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="xp_per_course" class="form-label fw-semibold">XP per Course Completed</label>
                            <input type="number" name="xp_per_course" id="xp_per_course" class="form-control"
                                   value="{{ $settings['xp_per_course'] ?? 200 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="streak_bonus" class="form-label fw-semibold">Daily Streak Bonus XP</label>
                            <input type="number" name="streak_bonus" id="streak_bonus" class="form-control"
                                   value="{{ $settings['streak_bonus'] ?? 25 }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="xp_per_level" class="form-label fw-semibold">XP Required per Level</label>
                            <input type="number" name="xp_per_level" id="xp_per_level" class="form-control"
                                   value="{{ $settings['xp_per_level'] ?? 500 }}" min="100">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="group" value="gamification" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Gamification Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
