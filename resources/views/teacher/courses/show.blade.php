@extends('layouts.app')

@section('title', $course->title . ' - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('teacher.courses.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('teacher.courses.index') }}">Courses</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($course->title, 40) }}</li>
            </ol>
        </nav>
        <div class="ms-auto">
            <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit Course
            </a>
        </div>
    </div>

    {{-- Course Header --}}
    <div class="card section-card mb-4 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-4">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" class="w-100 h-100" style="object-fit:cover;min-height:200px;" alt="{{ $course->title }}">
                @else
                    <div class="d-flex align-items-center justify-content-center h-100" style="min-height:200px;background:linear-gradient(135deg,#667eea,#764ba2);">
                        <i class="bi bi-book text-white" style="font-size:4rem;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <h3 class="fw-bold mb-0">{{ $course->title }}</h3>
                        @if($course->is_published)
                            <span class="badge bg-success fs-6">Published</span>
                        @else
                            <span class="badge bg-secondary fs-6">Draft</span>
                        @endif
                    </div>
                    @if($course->category)
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $course->category->name }}</span>
                    @endif
                    <span class="badge bg-info bg-opacity-10 text-info mb-2">{{ ucfirst($course->level) }}</span>
                    <p class="text-muted mt-2">{{ $course->short_description ?? Str::limit(strip_tags($course->description), 200) }}</p>
                    <div class="d-flex gap-4 flex-wrap">
                        <div><i class="bi bi-people text-primary me-1"></i><strong>{{ $enrollmentsCount }}</strong> <span class="text-muted">Students</span></div>
                        <div><i class="bi bi-list-ul text-success me-1"></i><strong>{{ $course->lessons->count() }}</strong> <span class="text-muted">Lessons</span></div>
                        <div><i class="bi bi-clock text-warning me-1"></i><strong>{{ $course->duration_hours }}</strong> <span class="text-muted">Hours</span></div>
                        <div><i class="bi bi-currency-rupee text-info me-1"></i><strong>{{ number_format($course->price, 2) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="courseTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#lessons" type="button">
                <i class="bi bi-list-ul me-1"></i>Lessons ({{ $course->lessons->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#quizzes" type="button">
                <i class="bi bi-question-circle me-1"></i>Quizzes ({{ $course->quizzes->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#assignments" type="button">
                <i class="bi bi-file-earmark-text me-1"></i>Assignments ({{ $course->assignments->count() }})
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#students" type="button">
                <i class="bi bi-people me-1"></i>Students ({{ $enrollmentsCount }})
            </button>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Lessons Tab --}}
        <div class="tab-pane fade show active" id="lessons">
            <div class="card section-card">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Course Lessons</h5>
                </div>
                <div class="card-body p-4">
                    @if($course->lessons->count())
                        <div class="lesson-list" id="sortable-lessons">
                            @foreach($course->lessons as $lesson)
                                <div class="d-flex align-items-center gap-3 p-3 mb-2 bg-light rounded lesson-item" data-id="{{ $lesson->id }}" style="cursor:move;">
                                    <div class="text-muted"><i class="bi bi-grip-vertical"></i></div>
                                    <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                                        <span class="fw-bold text-primary" style="font-size:0.85rem;">{{ $lesson->order_number }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold" style="font-size:0.9rem;">{{ $lesson->title }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $lesson->duration_minutes ?? 0 }}min
                                            &middot; {{ ucfirst(str_replace('_', ' ', $lesson->content_type)) }}
                                            @if($lesson->is_free) &middot; <span class="text-success">Free</span> @endif
                                            @if(!$lesson->is_published) &middot; <span class="text-secondary">Draft</span> @endif
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary btn-sm edit-lesson-btn"
                                                data-id="{{ $lesson->id }}"
                                                data-title="{{ $lesson->title }}"
                                                data-description="{{ $lesson->description }}"
                                                data-content-type="{{ $lesson->content_type }}"
                                                data-video-url="{{ $lesson->video_url }}"
                                                data-duration="{{ $lesson->duration_minutes }}"
                                                data-is-free="{{ $lesson->is_free }}"
                                                data-is-published="{{ $lesson->is_published }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('teacher.lessons.destroy', [$course, $lesson]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this lesson?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-list-ul text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">No lessons yet. Add your first lesson below.</p>
                        </div>
                    @endif

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Add New Lesson</h6>
                    <form action="{{ route('teacher.lessons.store', $course) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lesson Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="e.g. Getting Started with Variables">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Content Type</label>
                                <select name="content_type" class="form-select">
                                    <option value="video">Video</option>
                                    <option value="document">Document</option>
                                    <option value="text">Text</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Duration (min)</label>
                                <input type="number" name="duration_minutes" class="form-control" value="30" min="1">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Brief lesson description..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Video URL</label>
                                <input type="url" name="video_url" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-6 d-flex align-items-end gap-4">
                                <div class="form-check">
                                    <input type="hidden" name="is_free" value="0">
                                    <input type="checkbox" name="is_free" value="1" class="form-check-input" id="newLessonFree">
                                    <label class="form-check-label" for="newLessonFree">Free Lesson</label>
                                </div>
                                <div class="form-check">
                                    <input type="hidden" name="is_published" value="0">
                                    <input type="checkbox" name="is_published" value="1" class="form-check-input" id="newLessonPub" checked>
                                    <label class="form-check-label" for="newLessonPub">Publish</label>
                                </div>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <i class="bi bi-plus-lg me-1"></i>Add Lesson
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Quizzes Tab --}}
        <div class="tab-pane fade" id="quizzes">
            <div class="card section-card">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Course Quizzes</h5>
                    <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Add Quiz
                    </a>
                </div>
                <div class="card-body p-4">
                    @if($course->quizzes->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">Title</th>
                                        <th class="fw-semibold">Questions</th>
                                        <th class="fw-semibold">Passing Marks</th>
                                        <th class="fw-semibold">Status</th>
                                        <th class="fw-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->quizzes as $quiz)
                                        <tr>
                                            <td class="fw-semibold">{{ $quiz->title }}</td>
                                            <td>{{ $quiz->questions->count() }}</td>
                                            <td>{{ $quiz->passing_marks }}</td>
                                            <td>
                                                @if($quiz->is_published)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                                    <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-question-circle text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-3">No quizzes yet for this course.</p>
                            <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Create First Quiz
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Assignments Tab --}}
        <div class="tab-pane fade" id="assignments">
            <div class="card section-card">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Course Assignments</h5>
                    <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Add Assignment
                    </a>
                </div>
                <div class="card-body p-4">
                    @if($course->assignments->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">Title</th>
                                        <th class="fw-semibold">Due Date</th>
                                        <th class="fw-semibold">Submissions</th>
                                        <th class="fw-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->assignments as $assignment)
                                        <tr>
                                            <td class="fw-semibold">{{ $assignment->title }}</td>
                                            <td>
                                                <span class="{{ $assignment->due_date->isPast() ? 'text-danger' : '' }}">
                                                    {{ $assignment->due_date->format('M d, Y h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $assignment->submissions->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                                    <a href="{{ route('teacher.assignments.submissions', $assignment) }}" class="btn btn-outline-success" title="Submissions"><i class="bi bi-inbox"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-text text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-3">No assignments yet for this course.</p>
                            <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Create First Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Students Tab --}}
        <div class="tab-pane fade" id="students">
            <div class="card section-card">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Enrolled Students ({{ $enrollmentsCount }})</h5>
                </div>
                <div class="card-body p-4">
                    @if($course->enrollments->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">Student</th>
                                        <th class="fw-semibold">Enrolled</th>
                                        <th class="fw-semibold">Progress</th>
                                        <th class="fw-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->enrollments as $enrollment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                                        <i class="bi bi-person text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $enrollment->user->name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $enrollment->user->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $enrollment->enrolled_at?->format('M d, Y') ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height:6px;width:80px;">
                                                        <div class="progress-bar" style="width:{{ round($enrollment->progress_percentage ?? 0) }}%;"></div>
                                                    </div>
                                                    <small class="text-muted">{{ round($enrollment->progress_percentage ?? 0) }}%</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($enrollment->is_completed)
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">In Progress</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size:2.5rem;"></i>
                            <p class="text-muted mt-2 mb-0">No students enrolled yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .lesson-item { transition: all 0.15s; }
    .lesson-item:hover { background: #e9ecef !important; }
    .nav-tabs .nav-link.active { border-bottom: 3px solid #0d6efd; font-weight: 600; }
</style>
@endsection
