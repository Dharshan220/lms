@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Course Report</h1>
                <p class="text-muted mt-1 mb-0">Course performance and completion analytics</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Reports
            </a>
        </div>

        {{-- Stats --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-primary mb-0">{{ $totalCourses ?? 0 }}</h3>
                    <small class="text-muted">Total Courses</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-success mb-0">{{ $publishedCourses ?? 0 }}</h3>
                    <small class="text-muted">Published</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-warning mb-0">{{ $avgRating ?? '0.0' }}</h3>
                    <small class="text-muted">Avg. Rating</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-info mb-0">{{ $avgCompletion ?? 0 }}%</h3>
                    <small class="text-muted">Avg. Completion</small>
                </div>
            </div>
        </div>

        {{-- Performance Chart --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">Course Performance by Enrollment</h5>
            </div>
            <div class="card-body">
                <canvas id="coursePerformanceChart" height="300"></canvas>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">Course Details</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Course</th>
                                <th>Teacher</th>
                                <th>Enrollments</th>
                                <th>Completion Rate</th>
                                <th>Rating</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courseStats ?? [] as $stat)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $stat['title'] ?? 'N/A' }}</td>
                                    <td>{{ $stat['teacher'] ?? 'N/A' }}</td>
                                    <td>{{ $stat['enrollments'] ?? 0 }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px; width: 80px;">
                                            <div class="progress-bar bg-success" style="width: {{ $stat['completion_rate'] ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $stat['completion_rate'] ?? 0 }}%</small>
                                    </td>
                                    <td><span class="text-warning"><i class="bi bi-star-fill"></i></span> {{ $stat['rating'] ?? '0.0' }}</td>
                                    <td class="fw-semibold">${{ number_format($stat['revenue'] ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No course data available.</td>
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
    var ctx = document.getElementById('coursePerformanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($chartData ?? []) !!},
                    backgroundColor: ['#667eea', '#f5576c', '#4facfe', '#43e97b', '#f093fb', '#ffd93d', '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
