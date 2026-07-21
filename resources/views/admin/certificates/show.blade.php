@extends('layouts.app')

@section('title', 'Certificate Details - Admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0">Certificate Details</h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-semibold">Certificate Number</small>
                            <div class="fs-5 fw-bold">{{ $certificate->certificate_number }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-semibold">Student</small>
                            <div>
                                <div class="fw-semibold">{{ $certificate->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $certificate->user->email ?? '' }}</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-semibold">Course</small>
                            <div class="fw-semibold">{{ $certificate->course->title ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-semibold">Teacher</small>
                            <div class="fw-semibold">{{ $certificate->teacher->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-semibold">Issued Date</small>
                            <div class="fw-semibold">{{ $certificate->issued_at?->format('F d, Y') ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-semibold">Status</small>
                            <div>
                                <span class="badge bg-success">Issued</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to List
                    </a>
                    <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this certificate?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
