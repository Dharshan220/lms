@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
            <p class="text-muted mt-1 mb-0">Analytics and insights for your platform</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-person-check text-primary" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalEnrollments ?? 0 }}</h3>
                    <small class="text-muted">Total Enrollments</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-book text-success" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalCourses ?? 0 }}</h3>
                    <small class="text-muted">Total Courses</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalStudents ?? 0 }}</h3>
                    <small class="text-muted">Total Students</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-graph-up text-warning" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $completionRate ?? 0 }}%</h3>
                    <small class="text-muted">Completion Rate</small>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('admin.reports.enrollments') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 report-card">
                        <div class="card-body text-center p-5">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-check text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="fw-bold">Enrollment Report</h4>
                            <p class="text-muted">Track enrollment trends, growth over time, and student engagement metrics.</p>
                            <span class="btn btn-outline-primary">View Report <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.reports.courses') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 report-card">
                        <div class="card-body text-center p-5">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-book text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="fw-bold">Course Report</h4>
                            <p class="text-muted">Analyze course performance, completion rates, and student feedback.</p>
                            <span class="btn btn-outline-success">View Report <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.reports.students') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 report-card">
                        <div class="card-body text-center p-5">
                            <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="fw-bold">Student Report</h4>
                            <p class="text-muted">Review student performance, XP distribution, and grade analysis.</p>
                            <span class="btn btn-outline-info">View Report <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

<style>
.report-card { transition: transform 0.2s, box-shadow 0.2s; }
.report-card:hover { transform: translateY(-4px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.12) !important; }
</style>
@endsection
