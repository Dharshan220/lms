@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .course-card { transition: transform 0.2s, box-shadow 0.2s; border: none; }
    .course-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.12)!important; }
    .course-card .card-img-top { height: 180px; object-fit: cover; }
    .filter-sidebar { position: sticky; top: 80px; }
    .star-rating .bi-star-fill { color: #ffc107; }
    .star-rating .bi-star { color: #dee2e6; }
    .featured-banner { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="featured-banner rounded-4 p-4 p-md-5 mb-4 text-white shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-1"><i class="bi bi-compass me-2"></i>Course Marketplace</h2>
                <p class="mb-0 opacity-75">Explore courses, learn new skills, and earn badges!</p>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <form action="{{ route('student.courses.index') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0" placeholder="Search courses..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Filter Sidebar --}}
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 fw-bold">
                        <i class="bi bi-funnel me-2"></i>Filters
                    </div>
                    <div class="card-body">
                        <form action="{{ route('student.courses.index') }}" method="GET" id="filterForm">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Category</label>
                                <select name="category_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Level</label>
                                <select name="level" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Levels</option>
                                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Price</label>
                                <select name="price" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Prices</option>
                                    <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                            @if(request()->hasAny(['category_id', 'level', 'price']))
                                <a href="{{ route('student.courses.index') }}" class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                                    <i class="bi bi-x-circle me-1"></i>Clear Filters
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Grid --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">{{ $courses->total() }} courses found</h5>
            </div>

            @if($courses->count())
                <div class="row g-4">
                    @foreach($courses as $course)
                        <div class="col-md-6 col-xl-4">
                            <div class="card course-card shadow-sm h-100 rounded-4 overflow-hidden">
                                <div class="position-relative">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center" style="height:180px; background: linear-gradient(135deg, #667eea, #764ba2);">
                                            <i class="bi bi-play-circle text-white" style="font-size:3rem;"></i>
                                        </div>
                                    @endif
                                    @if($course->is_featured)
                                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                            <i class="bi bi-star-fill me-1"></i>Featured
                                        </span>
                                    @endif
                                    @if($course->level)
                                        <span class="badge bg-dark position-absolute top-0 end-0 m-2 text-capitalize">{{ $course->level }}</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $course->category->name ?? 'General' }}</span>
                                    </div>
                                    <h6 class="fw-bold mb-1 text-truncate">{{ $course->title }}</h6>
                                    <small class="text-muted mb-2"><i class="bi bi-person me-1"></i>{{ $course->teacher->name ?? 'Unknown' }}</small>

                                    <div class="d-flex align-items-center mb-2">
                                        <div class="star-rating me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($course->rating ?? 0))
                                                    <i class="bi bi-star-fill small"></i>
                                                @elseif($i - ($course->rating ?? 0) < 1 && $i - ($course->rating ?? 0) > 0)
                                                    <i class="bi bi-star-half small"></i>
                                                @else
                                                    <i class="bi bi-star small"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">({{ number_format($course->rating ?? 0, 1) }})</small>
                                    </div>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($course->price > 0)
                                                <span class="fw-bold text-primary fs-5">${{ number_format($course->price, 2) }}</span>
                                            @else
                                                <span class="badge bg-success fs-6">Free</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('student.courses.show', $course) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            View <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                                    <small class="text-muted">
                                        <i class="bi bi-people me-1"></i>{{ number_format($course->enrollment_count ?? 0) }} students
                                        &middot;
                                        <i class="bi bi-clock me-1"></i>{{ $course->duration_hours ?? 0 }}h
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $courses->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size:4rem;"></i>
                    <h5 class="text-muted mt-3">No courses found</h5>
                    <p class="text-muted">Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('student.courses.index') }}" class="btn btn-primary rounded-pill">Browse All Courses</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
