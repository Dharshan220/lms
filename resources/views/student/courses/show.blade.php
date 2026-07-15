@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .course-hero { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
    .module-header { cursor: pointer; transition: background 0.2s; }
    .module-header:hover { background: rgba(0,0,0,0.02); }
    .star-rating .bi-star-fill { color: #ffc107; }
    .star-rating .bi-star { color: #dee2e6; }
</style>
@endpush

<div class="course-hero text-white">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                @if($course->category)
                    <span class="badge bg-opacity-25 text-primary-light mb-3" style="background:rgba(102,126,234,0.2)!important;color:#a5b4fc!important;">{{ $course->category->name }}</span>
                @endif
                <h1 class="fw-bold mb-3">{{ $course->title }}</h1>
                @if($course->short_description)
                    <p class="lead mb-3 opacity-75">{{ $course->short_description }}</p>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="star-rating me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($course->rating ?? 0))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - ($course->rating ?? 0) < 1 && $i - ($course->rating ?? 0) > 0)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span>{{ number_format($course->rating ?? 0, 1) }} rating</span>
                    </div>
                    <span><i class="bi bi-people me-1"></i>{{ number_format($enrollmentCount) }} students</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $course->duration_hours ?? 0 }} hours</span>
                    <span class="text-capitalize"><i class="bi bi-bar-chart me-1"></i>{{ $course->level }}</span>
                </div>

                <div class="d-flex align-items-center mb-3">
                    @if($course->teacher && $course->teacher->avatar)
                        <img src="{{ asset('storage/' . $course->teacher->avatar) }}" class="rounded-circle me-2" style="width:35px;height:35px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width:35px;height:35px;">
                            <span class="text-white fw-bold small">{{ substr($course->teacher->name ?? 'U', 0, 1) }}</span>
                        </div>
                    @endif
                    <span>Created by <strong>{{ $course->teacher->name ?? 'Unknown' }}</strong></span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-white shadow-lg rounded-4 border-0">
                    <div class="card-body p-4">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="rounded-3 mb-3 w-100" style="height:200px;object-fit:cover;" alt="{{ $course->title }}">
                        @elseif($course->trailer_video)
                            <div class="ratio ratio-16x9 rounded-3 mb-3 overflow-hidden bg-dark">
                                <iframe src="{{ $course->trailer_video }}" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="rounded-3 mb-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="height:200px;">
                                <i class="bi bi-play-circle text-primary" style="font-size:4rem;"></i>
                            </div>
                        @endif

                        @if($course->price > 0)
                            <h3 class="fw-bold text-primary mb-3">${{ number_format($course->price, 2) }}</h3>
                        @else
                            <h3 class="fw-bold text-success mb-3">Free</h3>
                        @endif

                        @if($enrolled)
                            <a href="{{ route('student.courses.learn', $enrollment) }}" class="btn btn-success w-100 rounded-pill py-2 mb-2 fw-bold">
                                <i class="bi bi-play-fill me-1"></i> Continue Learning
                            </a>
                            @if($enrollment)
                                <div class="progress mb-2" style="height:8px;">
                                    <div class="progress-bar bg-success" style="width:{{ $enrollment->progress_percentage }}%"></div>
                                </div>
                                <small class="text-muted text-center d-block">{{ $enrollment->progress_percentage }}% complete</small>
                            @endif
                        @else
                            <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mb-2 fw-bold">
                                    <i class="bi bi-cart-plus me-1"></i> Enroll Now
                                </button>
                            </form>
                        @endif

                        <ul class="list-unstyled mt-3 mb-0">
                            <li class="py-2 border-bottom"><i class="bi bi-clock me-2 text-muted"></i>{{ $course->duration_hours ?? 0 }} hours of content</li>
                            <li class="py-2 border-bottom"><i class="bi bi-collection-play me-2 text-muted"></i>{{ $totalLessons }} lessons</li>
                            <li class="py-2 border-bottom"><i class="bi bi-bar-chart me-2 text-muted"></i>{{ ucfirst($course->level) }} level</li>
                            <li class="py-2 border-bottom"><i class="bi bi-infinity me-2 text-muted"></i>Full lifetime access</li>
                            <li class="py-2"><i class="bi bi-phone me-2 text-muted"></i>Access on mobile & desktop</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">About This Course</h4>
                    <div class="text-muted">{!! nl2br(e($course->description)) !!}</div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">What You'll Learn</h4>
                    <div class="row g-2">
                        @foreach(explode("\n", $course->description ?? '') as $point)
                            @if(trim($point))
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-muted small">{{ trim($point) }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Course Curriculum</h4>
                    @if($course->lessons->count())
                        <div class="accordion" id="curriculumAccordion">
                            @php $grouped = $course->lessons->groupBy(fn($l) => $l->course->title ?? 'Module'); @endphp
                            @foreach($grouped as $moduleName => $lessons)
                                <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden" style="border:1px solid #e9ecef!important;">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button fw-bold module-header" type="button" data-bs-toggle="collapse" data-bs-target="#module{{ $loop->index }}">
                                            <i class="bi bi-folder2-open me-2 text-primary"></i>
                                            Module {{ $loop->index + 1 }}
                                            <span class="badge bg-secondary ms-auto me-3">{{ $lessons->count() }} lessons</span>
                                        </button>
                                    </h2>
                                    <div id="module{{ $loop->index }}" class="accordion-collapse collapse show" data-bs-parent="#curriculumAccordion">
                                        <div class="accordion-body p-0">
                                            @foreach($lessons as $lesson)
                                                <div class="d-flex align-items-center gap-3 px-4 py-3 @if(!$loop->last) border-bottom @endif">
                                                    <div class="flex-shrink-0">
                                                        @if($lesson->content_type === 'video')
                                                            <i class="bi bi-play-circle text-primary"></i>
                                                        @elseif($lesson->content_type === 'document')
                                                            <i class="bi bi-file-earmark-text text-warning"></i>
                                                        @else
                                                            <i class="bi bi-journal-text text-success"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-bold small">{{ $lesson->title }}</span>
                                                        @if($lesson->duration_minutes)
                                                            <small class="text-muted ms-2">({{ $lesson->duration_minutes }} min)</small>
                                                        @endif
                                                    </div>
                                                    @if($lesson->is_free)
                                                        <span class="badge bg-success">Free</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No lessons available yet.</p>
                    @endif
                </div>
            </div>

            @if(isset($course->stemKits) && $course->stemKits->count())
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3"><i class="bi bi-cpu me-2 text-info"></i>STEM Kit Requirements</h4>
                        <div class="row g-3">
                            @foreach($course->stemKits as $kit)
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 rounded-3">
                                        <div class="card-body d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:45px;height:45px;">
                                                <i class="bi bi-box-seam text-info"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 small">{{ $kit->name }}</h6>
                                                <small class="text-muted">{{ $kit->description ?? '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2 text-info"></i>Student Reviews</h4>
                    <div class="text-center py-4">
                        <div class="display-4 fw-bold text-warning mb-1">{{ number_format($course->rating ?? 0, 1) }}</div>
                        <div class="star-rating mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($course->rating ?? 0))
                                    <i class="bi bi-star-fill fs-4"></i>
                                @else
                                    <i class="bi bi-star fs-4"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">Based on {{ number_format($enrollmentCount) }} enrollments</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:80px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Related Courses</h5>
                    @if(isset($relatedCourses) && count($relatedCourses))
                        @foreach($relatedCourses as $related)
                            <a href="{{ route('student.courses.show', $related) }}" class="text-decoration-none">
                                <div class="d-flex gap-3 mb-3 pb-3 @if(!$loop->last) border-bottom @endif">
                                    @if($related->thumbnail)
                                        <img src="{{ asset('storage/' . $related->thumbnail) }}" class="rounded" style="width:60px;height:45px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:60px;height:45px;">
                                            <i class="bi bi-play-btn text-primary small"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="fw-bold mb-0 small text-dark">{{ $related->title }}</h6>
                                        <small class="text-muted">{{ $related->teacher->name ?? '' }}</small>
                                        <div class="star-rating">
                                            <small>{{ number_format($related->rating ?? 0, 1) }}</small>
                                            <i class="bi bi-star-fill small text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-muted small">No related courses found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
