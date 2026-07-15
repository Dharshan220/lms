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
                <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
                <p class="text-muted mt-1 mb-0">Manage platform announcements</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Announcement
            </a>
        </div>

        <div class="row g-4">
            @forelse($announcements ?? [] as $announcement)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $announcement->title }}</h5>
                                @switch($announcement->priority)
                                    @case('high')
                                        <span class="badge bg-danger">High</span>
                                        @break
                                    @case('medium')
                                        <span class="badge bg-warning text-dark">Medium</span>
                                        @break
                                    @default
                                        <span class="badge bg-info">Low</span>
                                @endswitch
                            </div>
                            <p class="text-muted small mb-3">{{ Str::limit($announcement->content, 150) }}</p>
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                @if($announcement->is_global)
                                    <span class="badge bg-primary"><i class="bi bi-globe me-1"></i>Global</span>
                                @endif
                                @if($announcement->school)
                                    <span class="badge bg-secondary"><i class="bi bi-building me-1"></i>{{ $announcement->school->name }}</span>
                                @endif
                                @if($announcement->course)
                                    <span class="badge bg-info"><i class="bi bi-book me-1"></i>{{ $announcement->course->title }}</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>{{ $announcement->author->name ?? 'System' }}
                                    &middot; {{ $announcement->published_at?->diffForHumans() ?? '' }}
                                </small>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $announcement->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal{{ $announcement->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete <strong>{{ $announcement->title }}</strong>?
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline">
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
                        <i class="bi bi-megaphone" style="font-size: 3rem;"></i>
                        <p class="mt-3">No announcements yet.</p>
                    </div>
                @endforelse
            </div>

    </div>
</div>
@endsection
