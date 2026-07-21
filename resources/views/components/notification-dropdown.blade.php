@php
    $notifications = Auth::user()->notifications()->latest()->take(10)->get();
    $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
@endphp

<div class="ns-notification-dropdown" :class="{ 'show': notificationOpen }">
    <div class="ns-notification-header">
        <h4>Notifications</h4>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--accent-primary);font-size:12px;font-weight:600;cursor:pointer;font-family:var(--font-body)">Mark all read</button>
            </form>
        @endif
    </div>
    <div class="ns-notification-list">
        @forelse($notifications as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}" class="ns-notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}" style="text-decoration:none">
                <div class="ns-notification-icon {{ $notification->data['type'] ?? 'info' }}">
                    <i class="bi bi-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                </div>
                <div class="ns-notification-content">
                    <div class="ns-notification-text">{!! $notification->data['message'] ?? 'New notification' !!}</div>
                    <div class="ns-notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if(is_null($notification->read_at))
                    <div class="ns-unread-dot"></div>
                @endif
            </a>
        @empty
            <div class="text-center py-4">
                <i class="bi bi-bell-slash" style="font-size:2rem;color:var(--text-muted)"></i>
                <p style="color:var(--text-muted);margin-top:8px;font-size:13px">No notifications yet</p>
            </div>
        @endforelse
    </div>
</div>
