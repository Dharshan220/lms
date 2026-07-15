@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h3 mb-1" style="color: var(--text-primary);">
            <i class="bi bi-patch-check-fill" style="color: var(--ns-primary);"></i> My Badges
        </h1>
        <p style="color: var(--text-secondary); margin:0;">Track your achievements and earned badges</p>
    </div>

    <div class="row g-4">
        @forelse($badges as $badge)
            @php $earned = $earnedBadgeIds->contains($badge->id); @endphp
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center {{ $earned ? '' : 'opacity-50' }}" style="border-radius: 16px; {{ $earned ? 'border: 2px solid ' . ($badge->color ?? '#6f42c1') . '30;' : '' }}">
                    <div class="card-body p-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: {{ $badge->color ?? '#6f42c1' }}15;">
                            <i class="bi {{ $badge->icon ?? 'bi-trophy' }}" style="font-size: 32px; color: {{ $badge->color ?? '#6f42c1' }};"></i>
                        </div>
                        <h6 class="fw-bold" style="color: var(--text-primary);">{{ $badge->name }}</h6>
                        <p class="small mb-2" style="color: var(--text-muted);">{{ $badge->description ?? '' }}</p>
                        <span class="badge rounded-pill" style="background-color: {{ $badge->color ?? '#6f42c1' }}20; color: {{ $badge->color ?? '#6f42c1' }};">
                            <i class="bi bi-lightning-fill me-1"></i>{{ $badge->xp_reward ?? 0 }} XP
                        </span>
                        @if($earned)
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Earned</span>
                            </div>
                        @else
                            <div class="mt-2">
                                <span class="badge bg-secondary">Locked</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5" style="color: var(--text-muted);">
                    <i class="bi bi-patch-check" style="font-size: 64px; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
                    <h5>No badges available</h5>
                    <p>Keep learning to earn badges!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
