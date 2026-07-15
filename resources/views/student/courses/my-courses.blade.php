@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .course-card { transition: transform 0.2s, box-shadow 0.2s; }
    .course-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.1)!important; }
    .tab-active { border-bottom: 3px solid #0d6efd; color: #0d6efd; font-weight: 600; }
</style>
@endpush

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-collection-play me-2 text-primary"></i>My Courses</h2>
            <p class="text-muted mb-0">Track your enrolled courses and progress</p>
        </div>
    </div>

    <div class="d-flex gap-4 mb-4 border-bottom">
        <a href="{{ route('student.courses.my') }}" class="text-decoration-none pb-2 {{ !request('filter') ? 'tab-active' : 'text-muted' }}">
            All ({{ $enrollments->total() }})
        </a>
        <a href="{{ route('student.courses.my', ['filter' => 'in_progress']) }}" class="text-decoration-none pb-2 {{ request('filter') == 'in_progress' ? 'tab-active' : 'text-muted' }}">
            <i class="bi bi-hourglass-split me-1"></i>In Progress
        </a>
        <a href="{{ route('student.courses.my', ['filter' => 'completed']) }}" class="text-decoration-none pb-2 {{ request('filter') == 'completed' ? 'tab-active' : 'text-muted' }}">
            <i class="bi bi-check-circle me-1"></i>Completed
        </a>
    </div>

    @if($enrollments->count())
        <div class="row g-4">
            @foreach($enrollments as $enrollment)
                @php $course = $enrollment->course; @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="card course-card shadow-sm h-100 rounded-4 overflow-hidden border-0">
                        <div class="position-relative">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="height:180px;width:100%;object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height:180px;background:linear-gradient(135deg,#667eea,#764ba2);">
                                    <i class="bi bi-play-circle text-white" style="font-size:3rem;"></i>
                                </div>
                            @endif
                            @if($enrollment->is_completed)
                                <span class="badge bg-success position-absolute top-0 end-0 m-2"><i class="bi bi-check-circle-fill me-1"></i>Completed</span>
                            @else
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2"><i class="bi bi-hourglass-split me-1"></i>In Progress</span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary bg-opacity-10 text-primary align-self-start mb-2">{{ $course->category->name ?? 'General' }}</span>
                            <h6 class="fw-bold mb-1">{{ $course->title }}</h6>
                            <small class="text-muted mb-3"><i class="bi bi-person me-1"></i>{{ $course->teacher->name ?? 'N/A' }}</small>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="fw-bold {{ $enrollment->progress_percentage >= 100 ? 'text-success' : 'text-primary' }}">{{ $enrollment->progress_percentage }}%</small>
                                </div>
                                <div class="progress mb-2" style="height:8px;">
                                    <div class="progress-bar {{ $enrollment->progress_percentage >= 100 ? 'bg-success' : 'bg-primary' }} rounded-pill" style="width:{{ $enrollment->progress_percentage }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>Last accessed: {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : 'N/A' }}</small>
                                </div>
                            </div>

                            @if(!$enrollment->is_completed)
                                <a href="{{ route('student.courses.learn', $enrollment) }}" class="btn btn-primary rounded-pill mt-3 w-100">
                                    <i class="bi bi-play-fill me-1"></i> Continue Learning
                                </a>
                            @else
                                <a href="{{ route('student.courses.show', $course) }}" class="btn btn-outline-success rounded-pill mt-3 w-100">
                                    <i class="bi bi-arrow-repeat me-1"></i> Review Course
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $enrollments->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-book text-muted" style="font-size:4rem;"></i>
            <h5 class="text-muted mt-3">No courses found</h5>
            <p class="text-muted">You haven't enrolled in any courses yet.</p>
            <a href="{{ route('student.courses.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-search me-1"></i> Browse Courses
            </a>
        </div>
    @endif
</div>
@endsection
