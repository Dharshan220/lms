@extends('layouts.app')

@section('title', 'Search - Nano Spark LMS')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-search me-2"></i>Search Results</h4>
    </div>

    <form action="{{ route('search') }}" method="GET" class="mb-4">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" name="q" class="form-control border-start-0" placeholder="Search courses, lessons, STEM kits..." value="{{ $query }}">
            <select name="type" class="form-select" style="max-width:180px;">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                <option value="courses" {{ $type === 'courses' ? 'selected' : '' }}>Courses</option>
                <option value="lessons" {{ $type === 'lessons' ? 'selected' : '' }}>Lessons</option>
                <option value="stemkits" {{ $type === 'stemkits' ? 'selected' : '' }}>STEM Kits</option>
                <option value="categories" {{ $type === 'categories' ? 'selected' : '' }}>Categories</option>
            </select>
            <button type="submit" class="btn btn-primary px-4">Search</button>
        </div>
    </form>

    @if(strlen($query) < 2)
        <div class="text-center py-5 text-muted">
            <i class="bi bi-search" style="font-size:3rem;"></i>
            <p class="mt-3">Type at least 2 characters to search.</p>
        </div>
    @elseif(empty($results) || (is_array($results) && empty(array_filter($results))) || (is_object($results) && method_exists($results, 'isEmpty') && $results->isEmpty()))
        <div class="text-center py-5 text-muted">
            <i class="bi bi-emoji-frown" style="font-size:3rem;"></i>
            <p class="mt-3">No results found for "<strong>{{ $query }}</strong>".</p>
        </div>
    @else
        @if(is_array($results))
            @foreach(['courses' => 'Courses', 'lessons' => 'Lessons', 'stemkits' => 'STEM Kits'] as $key => $label)
                @if(!empty($results[$key]))
                    <h5 class="fw-bold mb-3 mt-4">{{ $label }} ({{ count($results[$key]) }})</h5>
                    @foreach($results[$key] as $item)
                        <div class="card section-card mb-3">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                                    <i class="bi bi-{{ $key === 'courses' ? 'book' : ($key === 'lessons' ? 'play-circle' : 'cpu') }} text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">{{ $item->title ?? $item->name }}</h6>
                                    <small class="text-muted">{{ Str::limit($item->description ?? '', 100) }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        @elseif($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card section-card">
                <div class="card-body">
                    @foreach($results as $item)
                        <div class="d-flex align-items-center gap-3 p-3 mb-2 bg-light rounded">
                            <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;">
                                <i class="bi bi-book text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">{{ $item->title ?? $item->name ?? 'Untitled' }}</h6>
                                <small class="text-muted">{{ Str::limit($item->description ?? '', 100) }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($results->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $results->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
