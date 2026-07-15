@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-signpost" style="color: var(--ns-accent);"></i> Learning Paths
            </h1>
            <p style="color: var(--text-secondary); margin:0;">Curated course sequences for structured learning</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($learningPaths as $path)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $path->name }}</h5>
                            <span class="badge bg-info">{{ $path->courses->count() }} courses</span>
                        </div>
                        <p class="text-muted small">{{ Str::limit($path->description ?? '', 120) }}</p>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-clock me-1"></i>{{ $path->estimated_hours ?? 'N/A' }} hours</span>
                            <span><i class="bi bi-bar-chart me-1"></i>{{ ucfirst($path->difficulty ?? 'beginner') }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <div class="small text-muted mb-2">Includes:</div>
                        @foreach($path->courses->take(3) as $course)
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-check2 text-success me-2"></i>
                                <span class="small">{{ $course->title }}</span>
                            </div>
                        @endforeach
                        @if($path->courses->count() > 3)
                            <div class="small text-muted mt-1">+{{ $path->courses->count() - 3 }} more courses</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5" style="color: var(--text-muted);">
                <i class="bi bi-signpost" style="font-size: 64px; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
                <h5>No learning paths available</h5>
                <p>Check back later for curated learning paths</p>
            </div>
        @endforelse
    </div>

    @if($learningPaths->hasPages())
        <div class="mt-4">{{ $learningPaths->links() }}</div>
    @endif
</div>
@endsection
