@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Enrollment Report</h1>
                <p class="text-muted mt-1 mb-0">Enrollment trends and analytics</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Reports
            </a>
        </div>

        {{-- Date Filter --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.reports.enrollments') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">From Date</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">To Date</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">All Courses</option>
                            @foreach($courses ?? [] as $c)
                                <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-primary mb-0">{{ $totalEnrollments ?? 0 }}</h3>
                    <small class="text-muted">Total Enrollments</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-success mb-0">{{ $completedEnrollments ?? 0 }}</h3>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-warning mb-0">{{ $activeEnrollments ?? 0 }}</h3>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-info mb-0">{{ $avgProgress ?? 0 }}%</h3>
                    <small class="text-muted">Avg. Progress</small>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">Enrollment Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" height="300"></canvas>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">Enrollment Details</h5>
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments ?? [] as $enrollment)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $enrollment->user->name ?? 'N/A' }}</td>
                                    <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                                    <td><small class="text-muted">{{ $enrollment->enrolled_at?->format('M d, Y') ?? '' }}</small></td>
                                    <td>
                                        <div class="progress" style="height: 6px; width: 80px;">
                                            <div class="progress-bar bg-success" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $enrollment->progress_percentage ?? 0 }}%</small>
                                    </td>
                                    <td>
                                        @if($enrollment->is_completed)
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No enrollment data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('enrollmentChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Enrollments',
                    data: {!! json_encode($chartData ?? []) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    borderRadius: 6
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
