@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-trophy" style="color: #FFD700;"></i> Leaderboard
            </h1>
            <p style="color: var(--text-secondary); margin:0;">Top performers ranked by XP points</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">Rank</th>
                            <th>Student</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">XP Points</th>
                            <th class="text-center">Streak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php $rank = $index + 1; @endphp
                            <tr class="{{ $student->id === auth()->id() ? 'table-primary' : '' }}">
                                <td class="text-center">
                                    @if($rank == 1)
                                        <span class="badge bg-warning text-dark" style="font-size: 1rem; padding: 6px 10px;">🥇 #1</span>
                                    @elseif($rank == 2)
                                        <span class="badge bg-secondary" style="font-size: 1rem; padding: 6px 10px;">🥈 #2</span>
                                    @elseif($rank == 3)
                                        <span class="badge bg-danger" style="font-size: 1rem; padding: 6px 10px;">🥉 #3</span>
                                    @else
                                        <span class="fw-bold" style="color: var(--text-secondary);">#{{ $rank }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info" style="font-size: 0.85rem;">Lv. {{ $student->level ?? 1 }}</span>
                                </td>
                                <td class="text-center fw-bold" style="color: var(--ns-primary);">{{ number_format($student->xp_points ?? 0) }} XP</td>
                                <td class="text-center">
                                    <span style="color: var(--ns-accent);">{{ $student->daily_streak ?? 0 }} 🔥</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-trophy" style="font-size: 3rem; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                                    No students on the leaderboard yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
