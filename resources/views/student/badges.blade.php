@extends('layouts.app')

@section('title', 'My Badges - Nano Spark LMS')

@section('content')
<style>
    :root {
        --ns-bg: #050505;
        --ns-card: #121212;
        --ns-elevated: #181818;
        --ns-accent: #FFD400;
        --ns-success: #00D26A;
        --ns-warning: #FF9800;
        --ns-danger: #FF4D4F;
        --ns-info: #3B82F6;
        --ns-text: #FFFFFF;
        --ns-text-secondary: #A0A0A0;
        --ns-text-muted: #666666;
        --ns-border: rgba(255,255,255,0.06);
        --font-heading: 'Space Mono', monospace;
        --font-body: 'IBM Plex Sans', sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
    }

    .ns-page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }
    .ns-page-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255,212,0,0.1);
        border: 1px solid rgba(255,212,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: var(--ns-accent);
        flex-shrink: 0;
    }
    .ns-page-header h1 {
        font-family: var(--font-heading);
        font-size: 22px;
        font-weight: 700;
        color: var(--ns-text);
        margin: 0;
    }
    .ns-page-header p {
        font-size: 14px;
        color: var(--ns-text-muted);
        margin: 2px 0 0;
    }

    .ns-badge-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }
    .ns-badge-stat {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 14px;
        flex: 1;
        min-width: 180px;
    }
    .ns-badge-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .ns-badge-stat-value {
        font-family: var(--font-mono);
        font-size: 22px;
        font-weight: 700;
        color: var(--ns-text);
    }
    .ns-badge-stat-label {
        font-size: 12px;
        color: var(--ns-text-muted);
    }

    .ns-section-title {
        font-family: var(--font-heading);
        font-size: 14px;
        font-weight: 700;
        color: var(--ns-text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--ns-border);
    }

    .ns-badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 16px;
    }

    .ns-badge-card {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 16px;
        padding: 28px 24px;
        text-align: center;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    .ns-badge-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }
    .ns-badge-card.earned {
        border-color: rgba(255,212,0,0.15);
    }
    .ns-badge-card.earned::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ns-accent), transparent);
    }
    .ns-badge-card.locked {
        opacity: 0.45;
    }
    .ns-badge-card.locked:hover {
        opacity: 0.65;
    }

    .ns-badge-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        position: relative;
    }
    .ns-badge-card.earned .ns-badge-icon-wrap::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid;
        border-color: inherit;
        opacity: 0.3;
    }
    .ns-badge-card.locked .ns-badge-icon-wrap {
        filter: grayscale(1);
    }
    .ns-badge-icon-wrap i {
        font-size: 36px;
    }

    .ns-badge-name {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-text);
        margin-bottom: 8px;
    }
    .ns-badge-desc {
        font-size: 13px;
        color: var(--ns-text-muted);
        line-height: 1.6;
        margin-bottom: 14px;
    }
    .ns-badge-xp {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 100px;
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .ns-badge-card.earned .ns-badge-xp {
        background: rgba(255,212,0,0.12);
        color: var(--ns-accent);
    }
    .ns-badge-card.locked .ns-badge-xp {
        background: rgba(255,255,255,0.04);
        color: var(--ns-text-muted);
    }

    .ns-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
    }
    .ns-badge-status.earned-status {
        background: rgba(0,210,106,0.12);
        color: var(--ns-success);
        border: 1px solid rgba(0,210,106,0.2);
    }
    .ns-badge-status.locked-status {
        background: rgba(255,255,255,0.04);
        color: var(--ns-text-muted);
        border: 1px solid var(--ns-border);
    }
    .ns-badge-date {
        font-family: var(--font-mono);
        font-size: 11px;
        color: var(--ns-text-muted);
        margin-top: 10px;
    }

    .ns-empty-state {
        text-align: center;
        padding: 80px 24px;
    }
    .ns-empty-icon {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: rgba(255,212,0,0.06);
        border: 1px solid rgba(255,212,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: var(--ns-accent);
        margin: 0 auto 20px;
        opacity: 0.4;
    }
    .ns-empty-state h3 {
        font-family: var(--font-heading);
        font-size: 18px;
        color: var(--ns-text-secondary);
        margin-bottom: 8px;
    }
    .ns-empty-state p {
        font-size: 14px;
        color: var(--ns-text-muted);
    }

    @media (max-width: 768px) {
        .ns-badge-stats {
            flex-direction: column;
        }
        .ns-badges-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="padding: 24px; max-width: 1200px; margin: 0 auto;">
    <div class="ns-page-header">
        <div class="ns-page-header-icon">
            <i class="bi bi-patch-check-fill"></i>
        </div>
        <div>
            <h1>My Badges</h1>
            <p>Track your achievements and earned badges</p>
        </div>
    </div>

    @php
        $earnedBadgeIds = isset($badges) ? $badges->pluck('id') : collect();
        $allBadgesList = $allBadges ?? $badges ?? collect();
        $earnedCount = $allBadgesList->filter(fn($b) => $earnedBadgeIds->contains($b->id))->count();
        $totalXP = $allBadgesList->filter(fn($b) => $earnedBadgeIds->contains($b->id))->sum('xp_reward');
    @endphp

    <div class="ns-badge-stats">
        <div class="ns-badge-stat">
            <div class="ns-badge-stat-icon" style="background:rgba(255,212,0,0.1); color:var(--ns-accent);">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="ns-badge-stat-value">{{ $earnedCount }}/{{ $allBadgesList->count() }}</div>
                <div class="ns-badge-stat-label">Badges Earned</div>
            </div>
        </div>
        <div class="ns-badge-stat">
            <div class="ns-badge-stat-icon" style="background:rgba(0,210,106,0.1); color:var(--ns-success);">
                <i class="bi bi-lightning-fill"></i>
            </div>
            <div>
                <div class="ns-badge-stat-value">{{ number_format($totalXP) }}</div>
                <div class="ns-badge-stat-label">XP from Badges</div>
            </div>
        </div>
        <div class="ns-badge-stat">
            <div class="ns-badge-stat-icon" style="background:rgba(59,130,246,0.1); color:var(--ns-info);">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <div class="ns-badge-stat-value">{{ $allBadgesList->count() - $earnedCount }}</div>
                <div class="ns-badge-stat-label">Locked Badges</div>
            </div>
        </div>
    </div>

    @if($allBadgesList->count())
        @php $earnedList = $allBadgesList->filter(fn($b) => $earnedBadgeIds->contains($b->id)); @endphp
        @if($earnedList->count())
            <div class="ns-section-title"><i class="bi bi-check-circle-fill" style="color:var(--ns-success); margin-right:6px;"></i> Earned ({{ $earnedList->count() }})</div>
            <div class="ns-badges-grid" style="margin-bottom: 40px;">
                @foreach($earnedList as $badge)
                    @php $color = $badge->color ?? '#FFD400'; @endphp
                    <div class="ns-badge-card earned">
                        <div class="ns-badge-icon-wrap" style="background: {{ $color }}15; border-color: {{ $color }};">
                            <i class="bi {{ $badge->icon ?? 'bi-trophy' }}" style="color: {{ $color }};"></i>
                        </div>
                        <div class="ns-badge-name">{{ $badge->name }}</div>
                        <div class="ns-badge-desc">{{ $badge->description ?? '' }}</div>
                        <div class="ns-badge-xp"><i class="bi bi-lightning-fill"></i> {{ $badge->xp_reward ?? 0 }} XP</div>
                        <div>
                            <span class="ns-badge-status earned-status"><i class="bi bi-check-circle-fill"></i> Earned</span>
                        </div>
                        @if(isset($badge->pivot->earned_at) || isset($badge->earned_at))
                            <div class="ns-badge-date">Earned {{ \Carbon\Carbon::parse($badge->pivot->earned_at ?? $badge->earned_at)->format('M d, Y') }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @php $lockedList = $allBadgesList->filter(fn($b) => !$earnedBadgeIds->contains($b->id)); @endphp
        @if($lockedList->count())
            <div class="ns-section-title"><i class="bi bi-lock-fill" style="color:var(--ns-text-muted); margin-right:6px;"></i> Locked ({{ $lockedList->count() }})</div>
            <div class="ns-badges-grid">
                @foreach($lockedList as $badge)
                    @php $color = $badge->color ?? '#FFD400'; @endphp
                    <div class="ns-badge-card locked">
                        <div class="ns-badge-icon-wrap" style="background: {{ $color }}15; border-color: {{ $color }};">
                            <i class="bi {{ $badge->icon ?? 'bi-trophy' }}" style="color: {{ $color }};"></i>
                        </div>
                        <div class="ns-badge-name">{{ $badge->name }}</div>
                        <div class="ns-badge-desc">{{ $badge->description ?? '' }}</div>
                        <div class="ns-badge-xp"><i class="bi bi-lightning-fill"></i> {{ $badge->xp_reward ?? 0 }} XP</div>
                        <div>
                            <span class="ns-badge-status locked-status"><i class="bi bi-lock-fill"></i> Locked</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="ns-empty-state">
            <div class="ns-empty-icon">
                <i class="bi bi-patch-check"></i>
            </div>
            <h3>No badges available</h3>
            <p>Keep learning to earn badges!</p>
        </div>
    @endif
</div>
@endsection
