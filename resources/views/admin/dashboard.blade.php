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

        {{-- Welcome --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-1">Here's what's happening with your LMS today.</p>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-4 mb-6">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75 small text-uppercase fw-semibold">Total Students</p>
                                <h2 class="mb-0 fw-bold">{{ $totalStudents ?? 0 }}</h2>
                            </div>
                            <div class="opacity-50">
                                <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75 small text-uppercase fw-semibold">Active Courses</p>
                                <h2 class="mb-0 fw-bold">{{ $activeCourses ?? 0 }}</h2>
                            </div>
                            <div class="opacity-50">
                                <i class="bi bi-book" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75 small text-uppercase fw-semibold">Total Enrollments</p>
                                <h2 class="mb-0 fw-bold">{{ $totalEnrollments ?? 0 }}</h2>
                            </div>
                            <div class="opacity-50">
                                <i class="bi bi-person-check" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75 small text-uppercase fw-semibold">Revenue</p>
                                <h2 class="mb-0 fw-bold">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                            </div>
                            <div class="opacity-50">
                                <i class="bi bi-currency-dollar" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Performance Chart & Recent Activities --}}
        <div class="row g-4 mb-6">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">Course Performance</h5>
                        <span class="text-muted small">Enrollments over time</span>
                    </div>
                    <div class="card-body">
                        <canvas id="coursePerformanceChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Recent Activities</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 360px; overflow-y: auto;">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                            <i class="bi bi-activity text-primary small"></i>
                                        </div>
                                        <div>
                                            <p class="mb-1 small">{{ $activity['message'] ?? 'Activity logged' }}</p>
                                            <small class="text-muted">{{ $activity['time'] ?? '' }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 small">No recent activities</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Enrollments --}}
        <div class="card border-0 shadow-sm mb-6">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Recent Enrollments</h5>
                <a href="{{ route('admin.reports.enrollments') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Student</th>
                                <th>Course</th>
                                <th>Enrolled</th>
                                <th>Progress</th>
                                <th class="pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEnrollments ?? [] as $enrollment)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                                <i class="bi bi-person text-secondary small"></i>
                                            </div>
                                            {{ $enrollment->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                                    <td><small class="text-muted">{{ $enrollment->enrolled_at?->diffForHumans() ?? '' }}</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px; width: 100px;">
                                            <div class="progress-bar bg-success" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $enrollment->progress_percentage ?? 0 }}%</small>
                                    </td>
                                    <td class="pe-3">
                                        @if($enrollment->is_completed)
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No recent enrollments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.schools.create') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
                            <i class="bi bi-building" style="font-size: 1.5rem;"></i>
                            <span>Add School</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2">
                            <i class="bi bi-person-plus" style="font-size: 1.5rem;"></i>
                            <span>Add Teacher</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-info w-100 py-3 d-flex flex-column align-items-center gap-2">
                            <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
                            <span>Add Course</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-outline-warning w-100 py-3 d-flex flex-column align-items-center gap-2">
                            <i class="bi bi-megaphone" style="font-size: 1.5rem;"></i>
                            <span>New Announcement</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#667eea'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
