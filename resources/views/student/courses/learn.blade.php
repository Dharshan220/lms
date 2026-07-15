@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .lesson-sidebar { position: sticky; top: 70px; max-height: calc(100vh - 90px); overflow-y: auto; }
    .lesson-item { cursor: pointer; transition: background 0.2s; border-left: 3px solid transparent; }
    .lesson-item:hover, .lesson-item.active { background: #f8f9fa; border-left-color: #0d6efd; }
    .lesson-item.completed { border-left-color: #198754; }
    .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; }
    .video-container iframe, .video-container video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
</style>
@endpush

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('student.courses.show', $course) }}" class="text-decoration-none text-muted">
                <i class="bi bi-arrow-left me-1"></i> {{ $course->title }}
            </a>
            <h5 class="fw-bold mb-0 mt-1">{{ $lesson->title }}</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(!$completedLessonIds->contains($lesson->id))
                <form action="{{ route('student.lessons.complete', $lesson) }}" method="POST">
                    @csrf
                    <input type="hidden" name="enrollment" value="{{ $enrollment->id }}">
                    <button type="submit" class="btn btn-success rounded-pill">
                        <i class="bi bi-check-circle me-1"></i> Mark Complete
                    </button>
                </form>
            @else
                <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-0">
                    @if($lesson->content_type === 'video' && $lesson->video_url)
                        <div class="video-container">
                            @if(Str::contains($lesson->video_url, ['youtube.com', 'youtu.be']))
                                <iframe src="{{ str_replace('watch?v=', 'embed/', $lesson->video_url) }}" allowfullscreen></iframe>
                            @elseif(Str::contains($lesson->video_url, ['vimeo.com']))
                                <iframe src="https://player.vimeo.com/video/{{ basename($lesson->video_url) }}" allowfullscreen></iframe>
                            @else
                                <video controls class="w-100" style="border-radius:12px;">
                                    <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    @elseif($lesson->content_type === 'document' && $lesson->document_file)
                        <div class="p-4 text-center">
                            <div class="bg-primary bg-opacity-10 rounded-4 p-5 mb-3">
                                <i class="bi bi-file-earmark-text text-primary" style="font-size:4rem;"></i>
                            </div>
                            <h5 class="fw-bold">{{ $lesson->title }}</h5>
                            <a href="{{ asset('storage/' . $lesson->document_file) }}" target="_blank" class="btn btn-primary rounded-pill mt-2">
                                <i class="bi bi-download me-1"></i> Download Document
                            </a>
                            <iframe src="{{ asset('storage/' . $lesson->document_file) }}" class="w-100 mt-3 rounded" style="height:500px;" frameborder="0"></iframe>
                        </div>
                    @else
                        <div class="p-4">
                            <div class="bg-light rounded-4 p-4 mb-3">
                                <i class="bi bi-journal-text text-success" style="font-size:3rem;"></i>
                            </div>
                            <div class="lesson-content">{!! $lesson->description !!}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">About This Lesson</h5>
                    <div class="text-muted">{!! nl2br(e($lesson->description)) !!}</div>
                    <div class="d-flex align-items-center gap-3 mt-3 pt-3 border-top">
                        @if($lesson->duration_minutes)
                            <span class="text-muted"><i class="bi bi-clock me-1"></i>{{ $lesson->duration_minutes }} minutes</span>
                        @endif
                        <span class="text-muted text-capitalize"><i class="bi bi-tag me-1"></i>{{ $lesson->content_type }}</span>
                    </div>
                </div>
            </div>

            @if($learningMaterials->count())
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-paperclip me-2"></i>Learning Materials</h5>
                        @foreach($learningMaterials as $material)
                            <div class="d-flex align-items-center gap-3 py-3 @if(!$loop->last) border-bottom @endif">
                                <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:45px;height:45px;">
                                    <i class="bi bi-file-earmark text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold small">{{ $material->title }}</h6>
                                    <small class="text-muted">{{ strtoupper($material->file_type ?? 'file') }} &middot; {{ $material->file_size_kb ? round($material->file_size_kb / 1024, 1) . ' MB' : '' }}</small>
                                </div>
                                <a href="{{ asset('storage/' . $material->file_path) }}" download class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>My Notes</h5>
                    <textarea class="form-control border-0 bg-light rounded-3" rows="4" placeholder="Write your notes about this lesson here...">{{ old('notes') }}</textarea>
                    <button class="btn btn-sm btn-primary rounded-pill mt-2"><i class="bi bi-save me-1"></i> Save Notes</button>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4">
                @if($previousLesson)
                    <a href="{{ route('student.lessons.show', ['lesson' => $previousLesson, 'enrollment' => $enrollment->id]) }}" class="btn btn-outline-primary rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Previous Lesson
                    </a>
                @else
                    <div></div>
                @endif
                @if($nextLesson)
                    <a href="{{ route('student.lessons.show', ['lesson' => $nextLesson, 'enrollment' => $enrollment->id]) }}" class="btn btn-primary rounded-pill">
                        Next Lesson <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('student.courses.show', $course) }}" class="btn btn-success rounded-pill">
                        <i class="bi bi-trophy me-1"></i> Finish Course
                    </a>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="lesson-sidebar">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>Course Content</h6>
                        @php
                            $completedCount = $completedLessonIds->count();
                            $totalLessonsCount = $course->lessons->count();
                            $progressPercent = $totalLessonsCount > 0 ? round(($completedCount / $totalLessonsCount) * 100) : 0;
                        @endphp
                        <div class="progress mt-2" style="height:6px;">
                            <div class="progress-bar bg-success" style="width:{{ $progressPercent }}%"></div>
                        </div>
                        <small class="text-muted">{{ $completedCount }}/{{ $totalLessonsCount }} lessons ({{ $progressPercent }}%)</small>
                    </div>
                    <div class="card-body p-0" style="max-height:calc(100vh - 200px);overflow-y:auto;">
                        @foreach($course->lessons as $l)
                            <a href="{{ route('student.lessons.show', ['lesson' => $l, 'enrollment' => $enrollment->id]) }}"
                               class="d-flex align-items-center gap-3 px-3 py-3 text-decoration-none text-dark lesson-item {{ $l->id === $lesson->id ? 'active' : '' }} {{ $completedLessonIds->contains($l->id) ? 'completed' : '' }}">
                                <div class="flex-shrink-0">
                                    @if($completedLessonIds->contains($l->id))
                                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width:30px;height:30px;">
                                            <i class="bi bi-check text-white small"></i>
                                        </div>
                                    @else
                                        <div class="rounded-circle border d-flex align-items-center justify-content-center {{ $l->id === $lesson->id ? 'border-primary bg-primary' : 'border-secondary' }}" style="width:30px;height:30px;">
                                            <span class="small {{ $l->id === $lesson->id ? 'text-white' : 'text-muted' }}">{{ $loop->index + 1 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="small fw-bold text-truncate {{ $l->id === $lesson->id ? 'text-primary' : '' }}">{{ $l->title }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($l->duration_minutes)
                                            <small class="text-muted">{{ $l->duration_minutes }}m</small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
