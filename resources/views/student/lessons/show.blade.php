@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; }
    .video-container iframe, .video-container video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
    .lesson-content img { max-width: 100%; height: auto; border-radius: 8px; }
</style>
@endpush

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('student.courses.show', $course) }}" class="text-decoration-none">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active">{{ $lesson->title }}</li>
        </ol>
    </nav>

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
                                <video controls class="w-100">
                                    <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    @elseif($lesson->content_type === 'document' && $lesson->document_file)
                        <div class="p-4">
                            @if(Str::contains($lesson->document_file, '.pdf'))
                                <iframe src="{{ asset('storage/' . $lesson->document_file) }}" class="w-100 rounded" style="height:600px;" frameborder="0"></iframe>
                            @else
                                <div class="text-center p-5">
                                    <i class="bi bi-file-earmark-text text-primary" style="font-size:4rem;"></i>
                                    <h5 class="mt-3 fw-bold">{{ $lesson->title }}</h5>
                                    <a href="{{ asset('storage/' . $lesson->document_file) }}" target="_blank" class="btn btn-primary rounded-pill mt-3">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Document
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-4 lesson-content">
                            <h4 class="fw-bold mb-4">{{ $lesson->title }}</h4>
                            {!! $lesson->description !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0">{{ $lesson->title }}</h4>
                        @if($progress && $progress->is_completed)
                            <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if($lesson->duration_minutes)
                            <span class="text-muted"><i class="bi bi-clock me-1"></i>{{ $lesson->duration_minutes }} min</span>
                        @endif
                        <span class="text-muted text-capitalize"><i class="bi bi-tag me-1"></i>{{ $lesson->content_type }}</span>
                        @if($lesson->is_free)
                            <span class="badge bg-success">Free Preview</span>
                        @endif
                    </div>
                    <p class="text-muted">{{ $lesson->description }}</p>
                </div>
            </div>

            @if($learningMaterials->count())
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-paperclip me-2"></i>Downloadable Materials</h5>
                        @foreach($learningMaterials as $material)
                            <div class="d-flex align-items-center justify-content-between py-3 @if(!$loop->last) border-bottom @endif">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                        <i class="bi bi-file-earmark text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 small fw-bold">{{ $material->title }}</h6>
                                        <small class="text-muted">{{ strtoupper($material->file_type ?? 'FILE') }}</small>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $material->file_path) }}" download class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                @if($previousLesson)
                    <a href="{{ route('student.lessons.show', ['lesson' => $previousLesson, 'enrollment' => $enrollment->id]) }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Previous
                    </a>
                @else
                    <div></div>
                @endif
                @if(!$progress || !$progress->is_completed)
                    <form action="{{ route('student.lessons.complete', $lesson) }}" method="POST">
                        @csrf
                        <input type="hidden" name="enrollment" value="{{ $enrollment->id }}">
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> Mark as Complete
                        </button>
                    </form>
                @else
                    <div class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Already Completed</div>
                @endif
                @if($nextLesson)
                    <a href="{{ route('student.lessons.show', ['lesson' => $nextLesson, 'enrollment' => $enrollment->id]) }}" class="btn btn-primary rounded-pill">
                        Next <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('student.courses.show', $course) }}" class="btn btn-success rounded-pill">
                        <i class="bi bi-trophy me-1"></i> Complete Course
                    </a>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:80px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Course Lessons</h6>
                </div>
                <div class="card-body p-0" style="max-height:calc(100vh - 150px);overflow-y:auto;">
                    @foreach($course->lessons as $l)
                        <a href="{{ route('student.lessons.show', ['lesson' => $l, 'enrollment' => $enrollment->id]) }}"
                           class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none text-dark border-bottom {{ $l->id === $lesson->id ? 'bg-primary bg-opacity-5' : '' }}">
                            <div class="flex-shrink-0">
                                @if($completedLessonIds->contains($l->id))
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-circle text-muted"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <small class="fw-bold {{ $l->id === $lesson->id ? 'text-primary' : '' }} text-truncate d-block">{{ $l->title }}</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
