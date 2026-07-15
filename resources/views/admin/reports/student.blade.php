@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Student Report</h1>
                <p class="text-muted mt-1 mb-0">Student performance and grade analysis</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Reports
            </a>
        </div>

        {{-- Stats --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-primary mb-0">{{ $totalStudents ?? 0 }}</h3>
                    <small class="text-muted">Total Students</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-success mb-0">{{ $avgXp ?? 0 }}</h3>
                    <small class="text-muted">Avg. XP</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-warning mb-0">{{ $avgLevel ?? 1 }}</h3>
                    <small class="text-muted">Avg. Level</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h3 class="fw-bold text-info mb-0">{{ $activeStudents ?? 0 }}</h3>
                    <small class="text-muted">Active (30d)</small>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Grade Distribution Chart --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Grade Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="gradeChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            {{-- Performance Chart --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Top Performers by XP</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">Student Performance</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Student</th>
                                <th>School</th>
                                <th>Grade</th>
                                <th>XP</th>
                                <th>Level</th>
                                <th>Courses</th>
                                <th>Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentStats ?? [] as $student)
                                <tr>
                                    <td class="ps-3">{{ $student['id'] ?? '' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                                <i class="bi bi-person text-primary small"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $student['name'] ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $student['school'] ?? 'N/A' }}</td>
                                    <td>{{ $student['grade'] ?? 'N/A' }}</td>
                                    <td><span class="text-warning fw-bold">{{ $student['xp'] ?? 0 }}</span></td>
                                    <td><span class="badge bg-info">Lvl {{ $student['level'] ?? 1 }}</span></td>
                                    <td>{{ $student['courses_count'] ?? 0 }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px; width: 80px;">
                                            <div class="progress-bar bg-success" style="width: {{ $student['completion_rate'] ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $student['completion_rate'] ?? 0 }}%</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No student data available.</td>
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
    var gradeCtx = document.getElementById('gradeChart');
    if (gradeCtx) {
        new Chart(gradeCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($gradeLabels ?? ['Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6']) !!},
                datasets: [{
                    data: {!! json_encode($gradeData ?? [0,0,0,0,0,0]) !!},
                    backgroundColor: ['#667eea', '#f5576c', '#4facfe', '#43e97b', '#f093fb', '#ffd93d'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    var perfCtx = document.getElementById('performanceChart');
    if (perfCtx) {
        new Chart(perfCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topStudentNames ?? []) !!},
                datasets: [{
                    label: 'XP Points',
                    data: {!! json_encode($topStudentXp ?? []) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    y: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
