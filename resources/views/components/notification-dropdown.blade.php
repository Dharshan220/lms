@php
    $user = Auth::user();
    $notifications = $user ? $user->notifications()->latest()->take(10)->get() : collect();
    $unreadCount = $user ? $user->notifications()->whereNull('read_at')->count() : 0;
@endphp

<div class="ns-user-dropdown" @click.outside="notificationOpen = false" style="position:relative">
    <button class="ns-navbar-btn" @click="notificationOpen = !notificationOpen" title="Notifications">
        <i class="bi bi-bell-fill"></i>
        @if($unreadCount > 0)
            <span class="ns-notification-dot"></span>
        @endif
    </button>

    <div class="ns-notification-dropdown" :class="{ 'show': notificationOpen }">
        <div class="ns-notification-header">
            <h4>Notifications</h4>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}" style="margin:0">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:var(--ns-primary); font-size:12px; font-weight:600; cursor:pointer;">
                        Mark all read
                    </button>
                </form>
            @endif
        </div>

        <div class="ns-notification-list">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->type ?? 'info';
                    $data = $notification->data ?? [];
                    $message = $data['message'] ?? $data['text'] ?? $notification->type;
                    $title = $data['title'] ?? '';
                    $iconClass = 'bi-info-circle-fill';
                    $iconColor = 'info';

                    if(str_contains($type, 'announcement') || str_contains($type, 'Announcement')) {
                        $iconClass = 'bi-megaphone-fill';
                        $iconColor = 'primary';
                    } elseif(str_contains($type, 'assignment') || str_contains($type, 'Assignment')) {
                        $iconClass = 'bi-clipboard-check-fill';
                        $iconColor = 'warning';
                    } elseif(str_contains($type, 'quiz') || str_contains($type, 'Quiz')) {
                        $iconClass = 'bi-question-circle-fill';
                        $iconColor = 'info';
                    } elseif(str_contains($type, 'certificate') || str_contains($type, 'Certificate')) {
                        $iconClass = 'bi-award-fill';
                        $iconColor = 'success';
                    } elseif(str_contains($type, 'grade') || str_contains($type, 'Grade')) {
                        $iconClass = 'bi-star-fill';
                        $iconColor = 'success';
                    }
                @endphp

                <div class="ns-notification-item {{ $notification->read_at ? '' : 'unread' }}">
                    <div class="ns-notification-icon {{ $iconColor }}">
                        <i class="{{ $iconClass }}"></i>
                    </div>
                    <div class="ns-notification-content">
                        @if($title)
                            <div style="font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:2px;">{{ $title }}</div>
                        @endif
                        <div class="ns-notification-text">{{ $message }}</div>
                        <div class="ns-notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!$notification->read_at)
                        <div class="ns-unread-dot"></div>
                    @endif
                </div>
            @empty
                <div style="padding:40px 20px; text-align:center;">
                    <i class="bi bi-bell-slash" style="font-size:36px; color:var(--text-muted); display:block; margin-bottom:12px;"></i>
                    <p style="font-size:14px; color:var(--text-muted); margin:0;">No notifications yet</p>
                    <p style="font-size:12px; color:var(--text-muted); margin:4px 0 0;">We'll let you know when something happens!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
