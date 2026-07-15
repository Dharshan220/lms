@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Student Profile</h1>
                <p class="text-muted mt-1 mb-0">Detailed view of student account</p>
            </div>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Students
            </a>
        </div>

        <div class="row g-4">

            {{-- Profile Card --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            @if($student->avatar)
                                <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <i class="bi bi-person text-primary" style="font-size: 2.5rem;"></i>
                            @endif
                        </div>
                        <h4 class="mb-1">{{ $student->name }}</h4>
                        <p class="text-muted mb-3">{{ $student->email }}</p>

                        <div class="text-start">
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted"><i class="bi bi-building me-2"></i>School</span>
                                <span class="fw-semibold">{{ $student->school->name ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted"><i class="bi bi-book me-2"></i>Grade</span>
                                <span class="fw-semibold">{{ $student->grade ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted"><i class="bi bi-telephone me-2"></i>Phone</span>
                                <span class="fw-semibold">{{ $student->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted"><i class="bi bi-calendar me-2"></i>Joined</span>
                                <span class="fw-semibold">{{ $student->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- XP, Enrollments, Badges --}}
            <div class="col-lg-8">

                {{-- XP & Level --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 fw-semibold">Level {{ $student->level ?? 1 }}</h5>
                            <span class="text-warning fw-bold"><i class="bi bi-star-fill me-1"></i>{{ $student->xp_points ?? 0 }} XP</span>
                        </div>
                        <div class="progress mb-2" style="height: 12px;">
                            <div class="progress-bar bg-warning" style="width: {{ $xpProgress ?? 0 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $xpProgress ?? 0 }}% to next level ({{ ($student->level ?? 0) * 500 }} / {{ (($student->level ?? 0) + 1) * 500 }} XP)</small>
                    </div>
                </div>

                {{-- Enrolled Courses --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Enrolled Courses</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Course</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th class="pe-3">Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($student->enrollments ?? [] as $enrollment)
                                        <tr>
                                            <td class="ps-3 fw-semibold">{{ $enrollment->course->title ?? 'N/A' }}</td>
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
                                            <td class="pe-3"><small class="text-muted">{{ $enrollment->enrolled_at?->diffForHumans() ?? '' }}</small></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No courses enrolled yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Badges Earned</h5>
                    </div>
                    <div class="card-body">
                        @forelse($student->badges ?? [] as $badge)
                            <div class="d-inline-flex align-items-center border rounded-pill px-3 py-2 me-2 mb-2">
                                <i class="bi bi-award-fill me-2" style="color: {{ $badge->color ?? '#ffc107' }};"></i>
                                <span class="fw-semibold small">{{ $badge->name }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No badges earned yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Recent Activity</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentActivity ?? [] as $activity)
                                <div class="list-group-item border-0 py-3">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                                            <i class="bi bi-activity text-info small"></i>
                                        </div>
                                        <div>
                                            <p class="mb-1 small">{{ $activity['message'] ?? '' }}</p>
                                            <small class="text-muted">{{ $activity['time'] ?? '' }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <p>No recent activity.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
