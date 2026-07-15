@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($announcement->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="fw-bold mb-0" style="color: var(--text-primary);">{{ $announcement->title }}</h3>
                        <span class="badge bg-{{ $announcement->priority === 'high' ? 'danger' : ($announcement->priority === 'medium' ? 'warning' : 'info') }} rounded-pill px-3 py-2">
                            {{ ucfirst($announcement->priority) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <small style="color: var(--text-muted);">
                            <i class="bi bi-person me-1"></i>{{ $announcement->author->name ?? 'System' }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-calendar me-1"></i>{{ $announcement->created_at->format('M d, Y h:i A') }}
                        </small>
                    </div>

                    <div class="mb-4" style="color: var(--text-primary); line-height: 1.8;">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3" style="color: var(--text-primary);">Details</h6>
                    <table class="table table-borderless mb-0" style="color: var(--text-secondary);">
                        <tr>
                            <td class="fw-semibold">Status</td>
                            <td>
                                <span class="badge bg-success rounded-pill">Published</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Priority</td>
                            <td>{{ ucfirst($announcement->priority) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">School</td>
                            <td>{{ $announcement->school->name ?? 'All Schools' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Course</td>
                            <td>{{ $announcement->course->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Published</td>
                            <td>{{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Draft' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
