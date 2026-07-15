@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">{{ $stemKit->name }}</h1>
            <p style="color: var(--text-secondary); margin:0;">
                <span class="badge bg-info">{{ ucfirst($stemKit->difficulty_level ?? 'medium') }}</span>
                <span class="badge bg-secondary">{{ $stemKit->category ?? 'General' }}</span>
                @if($stemKit->is_available) <span class="badge bg-success">Available</span> @else <span class="badge bg-danger">Unavailable</span> @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stem-kits.edit', $stemKit) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('admin.stem-kits.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    @if($stemKit->image)
                        <img src="{{ asset('storage/' . $stemKit->image) }}" alt="{{ $stemKit->name }}" class="rounded mb-3 w-100" style="max-height: 300px; object-fit: cover;">
                    @endif
                    <h5>Description</h5>
                    <p style="color: var(--text-secondary);">{!! nl2br(e($stemKit->description ?? 'No description.')) !!}</p>

                    @if($stemKit->components && count($stemKit->components) > 0)
                        <h5 class="mt-4">Components</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($stemKit->components as $component)
                                <li class="list-group-item"><i class="bi bi-check2-circle text-success me-2"></i>{{ $component }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Price</span>
                        <span class="fw-bold text-success">${{ number_format($stemKit->price ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Stock</span>
                        <span class="fw-bold">{{ $stemKit->stock_quantity ?? 0 }} units</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Linked Courses</span>
                        <span class="fw-bold">{{ $stemKit->courses->count() ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Slug</span>
                        <span class="fw-bold small">{{ $stemKit->slug ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            @if($stemKit->courses && $stemKit->courses->count() > 0)
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white border-bottom"><h6 class="mb-0 fw-semibold">Linked Courses</h6></div>
                    <div class="list-group list-group-flush">
                        @foreach($stemKit->courses as $course)
                            <div class="list-group-item">
                                <div class="fw-semibold">{{ $course->title }}</div>
                                <small class="text-muted">{{ $course->teacher->name ?? 'N/A' }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
