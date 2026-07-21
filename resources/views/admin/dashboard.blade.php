@extends('layouts.app')

@section('title', 'Admin Dashboard - Nano Spark LMS')

@section('content')
<div style="max-width:1400px">
    <div class="ns-page-header animate-fadeIn">
        <h1 class="ns-page-title">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="ns-page-subtitle">Here's what's happening with your LMS today.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card info">
                <div class="ns-stat-icon info"><i class="bi bi-people"></i></div>
                <div class="ns-stat-value">{{ $totalStudents ?? 0 }}</div>
                <div class="ns-stat-label">Total Students</div>
                <div class="ns-stat-change up"><i class="bi bi-arrow-up"></i> Growing</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card primary">
                <div class="ns-stat-icon primary"><i class="bi bi-book"></i></div>
                <div class="ns-stat-value">{{ $activeCourses ?? 0 }}</div>
                <div class="ns-stat-label">Active Courses</div>
                <div class="ns-stat-change up"><i class="bi bi-arrow-up"></i> Active</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card success">
                <div class="ns-stat-icon success"><i class="bi bi-person-check"></i></div>
                <div class="ns-stat-value">{{ $totalEnrollments ?? 0 }}</div>
                <div class="ns-stat-label">Total Enrollments</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="ns-stat-card warning">
                <div class="ns-stat-icon warning"><i class="bi bi-currency-dollar"></i></div>
                <div class="ns-stat-value">${{ number_format($totalRevenue ?? 0, 2) }}</div>
                <div class="ns-stat-label">Revenue</div>
            </div>
        </div>
    </div>

    {{-- Chart & Activities --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="ns-card">
                <div class="ns-card-header">
                    <h5 class="ns-card-title">Course Performance</h5>
                    <span style="font-size:13px;color:var(--text-muted)">Enrollments over time</span>
                </div>
                <div class="ns-chart-container">
                    <canvas id="coursePerformanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ns-card h-100">
                <div class="ns-card-header">
                    <h5 class="ns-card-title">Recent Activities</h5>
                </div>
                @forelse($recentActivities ?? [] as $activity)
                    <div class="d-flex align-items-start gap-3 py-3 @if(!$loop->last) border-bottom @endif" style="border-color:var(--border-subtle)">
                        <div class="ns-stat-icon primary" style="width:36px;height:36px;font-size:16px;margin-bottom:0;flex-shrink:0">
                            <i class="bi bi-activity"></i>
                        </div>
                        <div>
                            <p style="font-size:13px;color:var(--text-primary);margin-bottom:2px">{{ $activity['message'] ?? 'Activity logged' }}</p>
                            <small style="color:var(--text-muted)">{{ $activity['time'] ?? '' }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size:2rem;color:var(--text-muted)"></i>
                        <p style="color:var(--text-muted);margin-top:8px;font-size:13px">No recent activities</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Enrollments --}}
    <div class="ns-card mb-4">
        <div class="ns-card-header">
            <h5 class="ns-card-title">Recent Enrollments</h5>
            <a href="{{ route('admin.reports.enrollments') }}" class="ns-btn ns-btn-outline ns-btn-sm">View All</a>
        </div>
        <div class="ns-table-wrapper" style="border:none">
            <table class="ns-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Enrolled</th>
                        <th>Progress</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEnrollments ?? [] as $enrollment)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="ns-user-avatar" style="width:32px;height:32px;font-size:11px">{{ substr($enrollment->user->name ?? 'N', 0, 1) }}</div>
                                    {{ $enrollment->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                            <td><span style="color:var(--text-muted);font-size:13px">{{ $enrollment->enrolled_at?->diffForHumans() ?? '' }}</span></td>
                            <td>
                                <div class="ns-progress" style="width:100px;height:6px;margin-bottom:4px">
                                    <div class="ns-progress-bar success" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                </div>
                                <span style="font-size:12px;color:var(--text-muted)">{{ $enrollment->progress_percentage ?? 0 }}%</span>
                            </td>
                            <td>
                                @if($enrollment->is_completed)
                                    <span class="ns-badge success">Completed</span>
                                @else
                                    <span class="ns-badge primary">In Progress</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4" style="color:var(--text-muted)">No recent enrollments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="ns-card">
        <div class="ns-card-header">
            <h5 class="ns-card-title">Quick Actions</h5>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.schools.create') }}" class="ns-card d-flex flex-column align-items-center gap-2 text-center" style="text-decoration:none;padding:24px">
                    <div class="ns-stat-icon info" style="margin-bottom:0"><i class="bi bi-building"></i></div>
                    <span style="font-weight:600;color:var(--text-primary);font-size:14px">Add School</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.teachers.create') }}" class="ns-card d-flex flex-column align-items-center gap-2 text-center" style="text-decoration:none;padding:24px">
                    <div class="ns-stat-icon success" style="margin-bottom:0"><i class="bi bi-person-plus"></i></div>
                    <span style="font-weight:600;color:var(--text-primary);font-size:14px">Add Teacher</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.courses.create') }}" class="ns-card d-flex flex-column align-items-center gap-2 text-center" style="text-decoration:none;padding:24px">
                    <div class="ns-stat-icon primary" style="margin-bottom:0"><i class="bi bi-plus-circle"></i></div>
                    <span style="font-weight:600;color:var(--text-primary);font-size:14px">Add Course</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.announcements.create') }}" class="ns-card d-flex flex-column align-items-center gap-2 text-center" style="text-decoration:none;padding:24px">
                    <div class="ns-stat-icon warning" style="margin-bottom:0"><i class="bi bi-megaphone"></i></div>
                    <span style="font-weight:600;color:var(--text-primary);font-size:14px">Announcement</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('coursePerformanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? ['Jan','Feb','Mar','Apr','May','Jun']) !!},
                datasets: [{
                    label: 'Enrollments',
                    data: {!! json_encode($chartData ?? [12, 19, 8, 15, 22, 30]) !!},
                    borderColor: '#FFD400',
                    backgroundColor: 'rgba(255, 212, 0, 0.08)',
                    fill: true, tension: 0.4,
                    pointRadius: 4, pointBackgroundColor: '#FFD400'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#888888' } },
                    x: { grid: { display: false }, ticks: { color: '#888888' } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
