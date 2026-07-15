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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $course->title ?? 'Course Details' }}</h1>
                <p class="text-muted mt-1 mb-0">
                    {{ $course->category->name ?? '' }} &middot;
                    {{ ucfirst($course->level ?? '') }} &middot;
                    @if($course->is_published)
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                    @if($course->is_featured)
                        <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Featured</span>
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </div>
        </div>

        <div class="row g-4">

            {{-- Course Info --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="rounded mb-3 w-100" style="max-height: 300px; object-fit: cover;">
                        @endif

                        <h5 class="fw-semibold">Description</h5>
                        <p class="text-muted">{!! nl2br(e($course->description ?? 'No description available.')) !!}</p>

                        <div class="row g-3 mt-3">
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-clock text-primary" style="font-size: 1.5rem;"></i>
                                    <div class="fw-bold mt-1">{{ $course->duration_hours ?? 0 }}h</div>
                                    <small class="text-muted">Duration</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-currency-dollar text-success" style="font-size: 1.5rem;"></i>
                                    <div class="fw-bold mt-1">${{ number_format($course->price ?? 0, 2) }}</div>
                                    <small class="text-muted">Price</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-people text-info" style="font-size: 1.5rem;"></i>
                                    <div class="fw-bold mt-1">{{ $course->enrollment_count ?? 0 }}</div>
                                    <small class="text-muted">Students</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-star text-warning" style="font-size: 1.5rem;"></i>
                                    <div class="fw-bold mt-1">{{ number_format($course->rating ?? 0, 1) }}</div>
                                    <small class="text-muted">Rating</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lessons --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Lessons ({{ $course->lessons->count() ?? 0 }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($course->lessons ?? [] as $lesson)
                                <div class="list-group-item d-flex align-items-center py-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                        <span class="text-primary fw-bold small">{{ $lesson->order_number ?? '' }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $lesson->title ?? 'Lesson' }}</div>
                                        <small class="text-muted">{{ $lesson->type ?? 'video' }} &middot; {{ $lesson->duration_minutes ?? 0 }} min</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">No lessons added yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Quizzes --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold"><i class="bi bi-question-circle me-2"></i>Quizzes ({{ $course->quizzes->count() ?? 0 }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($course->quizzes ?? [] as $quiz)
                                <div class="list-group-item d-flex align-items-center py-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                        <i class="bi bi-question text-success small"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $quiz->title ?? 'Quiz' }}</div>
                                        <small class="text-muted">{{ $quiz->questions_count ?? $quiz->questions->count() ?? 0 }} questions &middot; {{ $quiz->time_limit ?? 0 }} min</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">No quizzes added yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Assignments --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold"><i class="bi bi-journal-text me-2"></i>Assignments ({{ $course->assignments->count() ?? 0 }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($course->assignments ?? [] as $assignment)
                                <div class="list-group-item d-flex align-items-center py-3">
                                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                        <i class="bi bi-journal-text text-warning small"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $assignment->title ?? 'Assignment' }}</div>
                                        <small class="text-muted">Due: {{ $assignment->due_date?->format('M d, Y') ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">No assignments added yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Teacher --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Teacher</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-workspace text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="mb-1">{{ $course->teacher->name ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ $course->teacher->email ?? '' }}</small>
                    </div>
                </div>

                {{-- Enrolled Students --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">Enrolled Students ({{ $course->enrollments->count() ?? 0 }})</h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                        <div class="list-group list-group-flush">
                            @forelse($course->enrollments->take(10) ?? [] as $enrollment)
                                <div class="list-group-item d-flex align-items-center py-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-person text-primary small"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-semibold">{{ $enrollment->user->name ?? 'N/A' }}</div>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-success" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                    <small class="text-muted ms-2">{{ $enrollment->progress_percentage ?? 0 }}%</small>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">No enrollments yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Created</span>
                            <span class="fw-semibold small">{{ $course->created_at?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted small">Updated</span>
                            <span class="fw-semibold small">{{ $course->updated_at?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted small">Slug</span>
                            <span class="fw-semibold small">{{ $course->slug ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div class="modal fade" id="deleteCourseModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong>{{ $course->title }}</strong>? This will also remove all lessons, quizzes, and assignments.
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Course</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
