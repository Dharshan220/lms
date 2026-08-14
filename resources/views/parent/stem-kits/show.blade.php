@extends('layouts.app')

@section('title', $stemKit->name . ' - Nano Spark LMS')

@section('content')
@push('styles')
<style>
    .pks-wrap {
        max-width: 960px;
        margin: 0 auto;
        padding: 28px 0 48px;
    }

    .pks-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .pks-header h1 {
        font-family: 'Space Mono', monospace;
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pks-header h1 i { color: var(--ns-accent); }

    .pks-card {
        background: var(--card-bg, #121212);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        overflow: hidden;
    }

    .pks-hero {
        height: 260px;
        background: linear-gradient(135deg, #1a1a0a 0%, #161616 60%, #101018 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .pks-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pks-hero i {
        font-size: 5rem;
        color: var(--ns-accent);
        opacity: 0.8;
    }

    .pks-level {
        position: absolute;
        top: 16px;
        right: 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: rgba(0, 0, 0, 0.8);
        border: 1px solid rgba(255, 212, 0, 0.4);
        color: var(--ns-accent);
    }

    .pks-body { padding: 28px; }

    .pks-title {
        font-family: 'Space Mono', monospace;
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .pks-category {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 14px;
    }

    .pks-desc {
        font-size: 14px;
        color: var(--text-secondary);
        line-height: 1.65;
        margin-bottom: 20px;
    }

    .pks-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .pks-meta-item {
        background: var(--elevated-bg, #181818);
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
        padding: 14px 16px;
    }

    .pks-meta-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .pks-meta-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .pks-meta-value.accent { color: var(--ns-accent); }

    .pks-components-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
    }

    .pks-components {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 22px;
    }

    .pks-tag {
        font-size: 12px;
        color: var(--text-secondary);
        background: var(--elevated-bg, #181818);
        border: 1px solid var(--border-subtle);
        padding: 5px 10px;
        border-radius: 8px;
    }

    .pks-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid var(--border-subtle);
    }

    .pks-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 13px;
        border-radius: 10px;
        padding: 10px 20px;
        text-decoration: none;
    }

    .pks-btn-accent {
        background: var(--ns-accent);
        color: #050505;
        border: none;
    }

    .pks-btn-accent:hover {
        background: #ffdf4d;
        color: #050505;
        box-shadow: 0 4px 20px rgba(255, 212, 0, 0.25);
    }

    .pks-btn-ghost {
        background: var(--border-subtle);
        color: var(--text-secondary);
        border: 1px solid var(--border-strong);
    }

    .pks-btn-ghost:hover {
        background: var(--elevated-bg);
        color: var(--text-primary);
    }
</style>
@endpush

<div class="pks-wrap">
    <div class="pks-header">
        <div>
            <h1><i class="bi bi-box-seam"></i> STEM Kit Details</h1>
        </div>
        <a href="{{ route('parent.stem-kits.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> All Kits
        </a>
    </div>

    <div class="pks-card">
        <div class="pks-hero">
            @if($stemKit->image)
                <img src="{{ asset('storage/' . $stemKit->image) }}" alt="{{ $stemKit->name }}">
            @else
                <i class="bi bi-cpu"></i>
            @endif
            <span class="pks-level">{{ ucfirst($stemKit->difficulty_level) }}</span>
        </div>
        <div class="pks-body">
            <div class="pks-title">{{ $stemKit->name }}</div>
            <div class="pks-category"><i class="bi bi-tag me-1"></i>{{ $stemKit->category }}</div>
            <div class="pks-desc">{{ $stemKit->description }}</div>

            <div class="pks-meta">
                <div class="pks-meta-item">
                    <div class="pks-meta-label">Price</div>
                    <div class="pks-meta-value accent">₹{{ number_format($stemKit->price, 2) }}</div>
                </div>
                <div class="pks-meta-item">
                    <div class="pks-meta-label">Availability</div>
                    <div class="pks-meta-value">
                        @if($stemKit->stock_quantity > 0)
                            In Stock ({{ $stemKit->stock_quantity }})
                        @else
                            Out of Stock
                        @endif
                    </div>
                </div>
                <div class="pks-meta-item">
                    <div class="pks-meta-label">Difficulty</div>
                    <div class="pks-meta-value">{{ ucfirst($stemKit->difficulty_level) }}</div>
                </div>
            </div>

            @if(!empty($stemKit->components))
                <div class="pks-components-title">What's in the box</div>
                <div class="pks-components">
                    @foreach($stemKit->components as $component)
                        <span class="pks-tag"><i class="bi bi-box me-1" style="color:var(--ns-accent);"></i>{{ $component }}</span>
                    @endforeach
                </div>
            @endif

            <div class="pks-actions">
                <a href="{{ route('parent.dashboard') }}" class="pks-btn pks-btn-accent">
                    <i class="bi bi-people-fill"></i> My Children
                </a>
                <a href="{{ route('parent.stem-kits.index') }}" class="pks-btn pks-btn-ghost">
                    <i class="bi bi-collection"></i> Browse More Kits
                </a>
            </div>
        </div>
    </div>
</div>
@endsection