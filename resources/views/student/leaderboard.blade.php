@extends('layouts.app')

@section('title', 'Leaderboard - Nano Spark LMS')

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
        margin-bottom: 40px;
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

    .ns-podium {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 16px;
        margin-bottom: 48px;
        padding: 0 24px;
    }
    .ns-podium-card {
        flex: 1;
        max-width: 240px;
        border-radius: 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s;
    }
    .ns-podium-card:hover {
        transform: translateY(-6px);
    }
    .ns-podium-card.gold {
        background: linear-gradient(180deg, rgba(255,212,0,0.15) 0%, var(--ns-card) 100%);
        border: 1px solid rgba(255,212,0,0.25);
    }
    .ns-podium-card.silver {
        background: linear-gradient(180deg, rgba(192,192,192,0.12) 0%, var(--ns-card) 100%);
        border: 1px solid rgba(192,192,192,0.2);
    }
    .ns-podium-card.bronze {
        background: linear-gradient(180deg, rgba(205,127,50,0.12) 0%, var(--ns-card) 100%);
        border: 1px solid rgba(205,127,50,0.2);
    }
    .ns-podium-card.gold .ns-podium-rank {
        background: linear-gradient(135deg, #FFD400, #FF9800);
        color: #050505;
        box-shadow: 0 4px 20px rgba(255,212,0,0.4);
    }
    .ns-podium-card.silver .ns-podium-rank {
        background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
        color: #050505;
        box-shadow: 0 4px 20px rgba(192,192,192,0.3);
    }
    .ns-podium-card.bronze .ns-podium-rank {
        background: linear-gradient(135deg, #CD7F32, #B8690E);
        color: #050505;
        box-shadow: 0 4px 20px rgba(205,127,50,0.3);
    }
    .ns-podium-rank {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-mono);
        font-size: 18px;
        font-weight: 700;
        margin: 0 auto;
    }
    .ns-podium-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 28px;
        margin: 16px auto 12px;
    }
    .ns-podium-card.gold .ns-podium-avatar {
        background: linear-gradient(135deg, rgba(255,212,0,0.2), rgba(255,152,0,0.2));
        color: var(--ns-accent);
        border: 2px solid rgba(255,212,0,0.3);
    }
    .ns-podium-card.silver .ns-podium-avatar {
        background: linear-gradient(135deg, rgba(192,192,192,0.15), rgba(160,160,160,0.15));
        color: #C0C0C0;
        border: 2px solid rgba(192,192,192,0.2);
    }
    .ns-podium-card.bronze .ns-podium-avatar {
        background: linear-gradient(135deg, rgba(205,127,50,0.15), rgba(184,105,14,0.15));
        color: #CD7F32;
        border: 2px solid rgba(205,127,50,0.2);
    }
    .ns-podium-name {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-text);
        margin-bottom: 4px;
    }
    .ns-podium-xp {
        font-family: var(--font-mono);
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .ns-podium-card.gold .ns-podium-xp { color: var(--ns-accent); }
    .ns-podium-card.silver .ns-podium-xp { color: #C0C0C0; }
    .ns-podium-card.bronze .ns-podium-xp { color: #CD7F32; }
    .ns-podium-label {
        font-size: 12px;
        color: var(--ns-text-muted);
        margin-bottom: 16px;
    }
    .ns-podium-bar {
        height: 8px;
        border-radius: 8px 8px 0 0;
    }
    .ns-podium-card.gold .ns-podium-bar { background: linear-gradient(90deg, #FFD400, #FF9800); height: 10px; }
    .ns-podium-card.silver .ns-podium-bar { background: linear-gradient(90deg, #C0C0C0, #A0A0A0); height: 8px; }
    .ns-podium-card.bronze .ns-podium-bar { background: linear-gradient(90deg, #CD7F32, #B8690E); height: 6px; }

    .ns-leaderboard-table-wrap {
        background: var(--ns-card);
        border: 1px solid var(--ns-border);
        border-radius: 20px;
        overflow: hidden;
    }
    .ns-leaderboard-table-header {
        display: grid;
        grid-template-columns: 80px 1fr 120px 140px 100px;
        padding: 16px 24px;
        border-bottom: 1px solid var(--ns-border);
    }
    .ns-leaderboard-table-header span {
        font-family: var(--font-heading);
        font-size: 11px;
        font-weight: 700;
        color: var(--ns-text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }
    .ns-leaderboard-row {
        display: grid;
        grid-template-columns: 80px 1fr 120px 140px 100px;
        padding: 16px 24px;
        align-items: center;
        border-bottom: 1px solid var(--ns-border);
        transition: background 0.2s;
    }
    .ns-leaderboard-row:last-child {
        border-bottom: none;
    }
    .ns-leaderboard-row:hover {
        background: rgba(255,212,0,0.03);
    }
    .ns-leaderboard-row.is-current-user {
        background: rgba(255,212,0,0.06);
        border-left: 3px solid var(--ns-accent);
    }
    .ns-leaderboard-rank {
        font-family: var(--font-mono);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-text-muted);
        text-align: center;
    }
    .ns-leaderboard-rank.top-1 { color: var(--ns-accent); }
    .ns-leaderboard-rank.top-2 { color: #C0C0C0; }
    .ns-leaderboard-rank.top-3 { color: #CD7F32; }
    .ns-leaderboard-student {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ns-leaderboard-student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: #050505;
        flex-shrink: 0;
    }
    .ns-leaderboard-student-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--ns-text);
    }
    .ns-leaderboard-student-email {
        font-size: 12px;
        color: var(--ns-text-muted);
    }
    .ns-leaderboard-level {
        text-align: center;
    }
    .ns-level-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 8px;
        font-family: var(--font-mono);
        font-size: 12px;
        font-weight: 700;
        background: rgba(59,130,246,0.12);
        color: var(--ns-info);
        border: 1px solid rgba(59,130,246,0.2);
    }
    .ns-leaderboard-xp {
        text-align: center;
        font-family: var(--font-mono);
        font-size: 15px;
        font-weight: 700;
        color: var(--ns-accent);
    }
    .ns-leaderboard-streak {
        text-align: center;
        font-family: var(--font-mono);
        font-size: 14px;
        font-weight: 600;
        color: var(--ns-warning);
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
        .ns-podium {
            flex-direction: column;
            align-items: center;
        }
        .ns-podium-card {
            max-width: 100%;
            width: 100%;
        }
        .ns-leaderboard-table-header,
        .ns-leaderboard-row {
            grid-template-columns: 60px 1fr 100px;
        }
        .ns-leaderboard-table-header span:nth-child(4),
        .ns-leaderboard-table-header span:nth-child(5),
        .ns-leaderboard-row > *:nth-child(4),
        .ns-leaderboard-row > *:nth-child(5) {
            display: none;
        }
    }
</style>

<div style="padding: 24px; max-width: 1200px; margin: 0 auto;">
    <div class="ns-page-header">
        <div class="ns-page-header-icon">
            <i class="bi bi-trophy-fill"></i>
        </div>
        <div>
            <h1>Leaderboard</h1>
            <p>Top performers ranked by XP points</p>
        </div>
    </div>

    @php
        $leaderboardData = $leaderboard ?? $students ?? collect();
        $top3 = $leaderboardData->take(3);
        $gold = $top3->get(0);
        $silver = $top3->get(1);
        $bronze = $top3->get(2);
    @endphp

    @if($leaderboardData->count())
        <div class="ns-podium">
            @if($silver)
                <div class="ns-podium-card silver">
                    <div class="ns-podium-rank">2</div>
                    <div class="ns-podium-avatar">{{ strtoupper(substr($silver->name, 0, 1)) }}</div>
                    <div class="ns-podium-name">{{ $silver->name }}</div>
                    <div class="ns-podium-xp">{{ number_format($silver->xp_points ?? 0) }}</div>
                    <div class="ns-podium-label">XP Points</div>
                    <div class="ns-podium-bar"></div>
                </div>
            @endif
            @if($gold)
                <div class="ns-podium-card gold" style="margin-bottom: 20px;">
                    <div class="ns-podium-rank" style="width:56px;height:56px;font-size:22px;">1</div>
                    <div class="ns-podium-avatar" style="width:84px;height:84px;font-size:32px;">{{ strtoupper(substr($gold->name, 0, 1)) }}</div>
                    <div class="ns-podium-name" style="font-size:17px;">{{ $gold->name }}</div>
                    <div class="ns-podium-xp" style="font-size:24px;">{{ number_format($gold->xp_points ?? 0) }}</div>
                    <div class="ns-podium-label">XP Points</div>
                    <div class="ns-podium-bar"></div>
                </div>
            @endif
            @if($bronze)
                <div class="ns-podium-card bronze">
                    <div class="ns-podium-rank">3</div>
                    <div class="ns-podium-avatar">{{ strtoupper(substr($bronze->name, 0, 1)) }}</div>
                    <div class="ns-podium-name">{{ $bronze->name }}</div>
                    <div class="ns-podium-xp">{{ number_format($bronze->xp_points ?? 0) }}</div>
                    <div class="ns-podium-label">XP Points</div>
                    <div class="ns-podium-bar"></div>
                </div>
            @endif
        </div>

        <div class="ns-leaderboard-table-wrap">
            <div class="ns-leaderboard-table-header">
                <span>Rank</span>
                <span>Student</span>
                <span style="text-align:center;">Level</span>
                <span style="text-align:center;">XP Points</span>
                <span style="text-align:center;">Streak</span>
            </div>
            @foreach($leaderboardData as $index => $student)
                @php $rank = $index + 1; @endphp
                <div class="ns-leaderboard-row {{ ($student->id ?? null) === auth()->id() ? 'is-current-user' : '' }}">
                    <div class="ns-leaderboard-rank {{ $rank <= 3 ? 'top-' . $rank : '' }}">
                        @if($rank == 1)
                            <i class="bi bi-trophy-fill"></i>
                        @elseif($rank == 2)
                            <i class="bi bi-trophy"></i>
                        @elseif($rank == 3)
                            <i class="bi bi-award-fill"></i>
                        @else
                            #{{ $rank }}
                        @endif
                    </div>
                    <div class="ns-leaderboard-student">
                        <div class="ns-leaderboard-student-avatar" style="background: linear-gradient(135deg, {{ ['#FFD400','#C0C0C0','#CD7F32','#3B82F6','#00D26A'][$index % 5] }}, {{ ['#FF9800','#A0A0A0','#B8690E','#2563EB','#00B894'][$index % 5] }});">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="ns-leaderboard-student-name">{{ $student->name }}</div>
                            <div class="ns-leaderboard-student-email">{{ $student->email }}</div>
                        </div>
                    </div>
                    <div class="ns-leaderboard-level">
                        <span class="ns-level-badge">Lv. {{ $student->level ?? 1 }}</span>
                    </div>
                    <div class="ns-leaderboard-xp">{{ number_format($student->xp_points ?? 0) }} XP</div>
                    <div class="ns-leaderboard-streak">{{ $student->daily_streak ?? 0 }} <i class="bi bi-fire"></i></div>
                </div>
            @endforeach
        </div>
    @else
        <div class="ns-empty-state">
            <div class="ns-empty-icon">
                <i class="bi bi-trophy"></i>
            </div>
            <h3>No students on the leaderboard yet</h3>
            <p>Start earning XP by completing courses and quizzes!</p>
        </div>
    @endif
</div>
@endsection
