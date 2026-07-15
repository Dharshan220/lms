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
                <h1 class="text-2xl font-bold text-gray-900">Manage STEM Kits</h1>
                <p class="text-muted mt-1 mb-0">Physical and digital learning kits</p>
            </div>
            <a href="{{ route('admin.stem-kits.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Kit
            </a>
        </div>

        <div class="row g-4">
            @forelse($stemKits ?? [] as $kit)
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        @if($kit->image)
                            <img src="{{ asset('storage/' . $kit->image) }}" class="card-img-top" alt="{{ $kit->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $kit->name }}</h5>
                                <span class="badge bg-info">{{ ucfirst($kit->difficulty_level ?? 'medium') }}</span>
                            </div>
                            <p class="text-muted small">{{ Str::limit($kit->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success fs-5">${{ number_format($kit->price ?? 0, 2) }}</span>
                                <span class="text-muted small">
                                    <i class="bi bi-box-seam me-1"></i>{{ $kit->stock_quantity ?? 0 }} in stock
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-secondary">{{ $kit->category ?? 'General' }}</span>
                                @if($kit->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.stem-kits.edit', $kit) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $kit->id }}">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal{{ $kit->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete <strong>{{ $kit->name }}</strong>?
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.stem-kits.destroy', $kit) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                        <p class="mt-3">No STEM kits found. Create your first kit!</p>
                    </div>
                @endforelse
            </div>

    </div>
</div>
@endsection
