@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-bell-fill" style="color: var(--ns-primary);"></i> Notifications
            </h1>
            <p style="color: var(--text-secondary); margin:0;">
                @if($unreadCount > 0)
                    You have <strong>{{ $unreadCount }}</strong> unread notification{{ $unreadCount > 1 ? 's' : '' }}
                @else
                    You're all caught up!
                @endif
            </p>
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-check-all me-1"></i>Mark all as read
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <div class="d-flex align-items-start gap-3 p-3 border-bottom {{ is_null($notification->read_at) ? '' : 'opacity-75' }}" style="transition: background 0.2s;" onmouseover="this.style.background='var(--hover-bg, #f8f9fa)'" onmouseout="this.style.background='transparent'">
                    <div class="flex-shrink-0 mt-1">
                        @if(is_null($notification->read_at))
                            <span class="badge bg-primary rounded-pill p-2"><i class="bi bi-envelope-fill"></i></span>
                        @else
                            <span class="badge bg-secondary rounded-pill p-2"><i class="bi bi-envelope-open"></i></span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1" style="color: var(--text-primary);">{{ $notification->data['message'] ?? $notification->data['title'] ?? 'Notification' }}</p>
                        <small style="color: var(--text-muted);">
                            <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                    @if(is_null($notification->read_at))
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as read">
                                <i class="bi bi-check"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-center py-5" style="color: var(--text-muted);">
                    <i class="bi bi-bell-slash" style="font-size: 48px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                    <h5>No notifications</h5>
                    <p>You're all caught up!</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($notifications->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
