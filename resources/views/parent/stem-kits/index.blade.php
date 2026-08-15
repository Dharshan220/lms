@extends('layouts.app')

@section('title', 'STEM Kits - Nano Spark LMS')

@section('content')
@push('styles')
<style>
    .pkit-wrap {
        padding: 28px 0 48px;
    }

    .pkit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .pkit-header h1 {
        font-family: 'Space Mono', monospace;
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pkit-header h1 i { color: var(--ns-accent); }

    .pkit-header p {
        color: var(--text-secondary);
        font-size: 13px;
        margin: 6px 0 0;
    }

    .pkit-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .pkit-filters .form-control,
    .pkit-filters .form-select {
        background: var(--card-bg, #121212);
        border: 1px solid var(--border-subtle);
        color: var(--text-primary);
        border-radius: 10px;
        font-size: 13px;
    }

    .pkit-btn {
        background: var(--ns-accent);
        color: #050505;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        padding: 8px 18px;
        font-size: 13px;
    }

    .pkit-btn:hover { background: #e6bf00; color: #050505; }

    .pkit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
    }

    .pkit-card {
        background: var(--card-bg, #121212);
        border: 1px solid var(--border-subtle);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
    }

    .pkit-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 212, 0, 0.35);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
    }

    .pkit-image {
        height: 170px;
        background: linear-gradient(135deg, #1a1a0a 0%, #161616 60%, #101018 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .pkit-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pkit-image i {
        font-size: 3.2rem;
        color: var(--ns-accent);
        opacity: 0.75;
    }

    .pkit-level {
        position: absolute;
        top: 12px;
        right: 12px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: rgba(0, 0, 0, 0.75);
        border: 1px solid rgba(255, 212, 0, 0.4);
        color: var(--ns-accent);
    }

    .pkit-body {
        padding: 18px 20px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .pkit-title {
        font-family: 'Space Mono', monospace;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .pkit-category {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
    }

    .pkit-desc {
        font-size: 13px;
        color: var(--text-secondary);
        line-height: 1.55;
        margin-bottom: 14px;
        flex: 1;
    }

    .pkit-components {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 16px;
    }

    .pkit-tag {
        font-size: 11px;
        color: var(--text-secondary);
        background: var(--elevated-bg, #181818);
        border: 1px solid var(--border-subtle);
        padding: 3px 8px;
        border-radius: 6px;
    }

    .pkit-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 14px;
        border-top: 1px solid var(--border-subtle);
    }

    .pkit-price {
        display: flex;
        flex-direction: column;
    }

    .pkit-price-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 17px;
        font-weight: 700;
        color: var(--ns-accent);
    }

    .pkit-price-sub {
        font-size: 11px;
        color: var(--text-muted);
    }

    .pkit-status {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .pkit-status.available {
        background: rgba(0, 210, 106, 0.12);
        color: #00d26a;
        border: 1px solid rgba(0, 210, 106, 0.3);
    }

    .pkit-status.low {
        background: rgba(255, 152, 0, 0.12);
        color: #ff9800;
        border: 1px solid rgba(255, 152, 0, 0.3);
    }

    .pkit-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .pkit-btn-buy {
        background: #ffffff;
        color: #050505;
        border: none;
        border-radius: 10px;
        font-family: 'Space Mono', monospace;
        font-size: 12px;
        font-weight: 700;
        padding: 9px 16px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        transition: all .25s ease;
    }

    .pkit-btn-buy:hover {
        background: var(--ns-accent);
        color: #050505;
        box-shadow: 0 4px 20px rgba(255, 212, 0, 0.25);
    }

    .pkit-btn-view {
        background: var(--ns-accent);
        color: #050505;
        border: none;
        border-radius: 10px;
        font-family: 'Space Mono', monospace;
        font-size: 12px;
        font-weight: 700;
        padding: 9px 18px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .pkit-btn-view:hover {
        background: #ffdf4d;
        color: #050505;
        box-shadow: 0 4px 20px rgba(255, 212, 0, 0.25);
    }

    .pkit-empty {
        text-align: center;
        padding: 56px 24px;
        background: var(--card-bg, #121212);
        border: 1px dashed var(--border-subtle);
        border-radius: 14px;
        color: var(--text-muted);
    }

    .pkit-empty i { font-size: 44px; opacity: 0.4; margin-bottom: 14px; }

    @media (max-width: 768px) {
        .pkit-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

<div class="pkit-wrap">
    <div class="pkit-header">
        <div>
            <h1><i class="bi bi-box-seam"></i> STEM Kits</h1>
            <p>Explore hands-on learning kits for your children.</p>
        </div>
        <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Dashboard
        </a>
    </div>

    <form method="GET" action="{{ route('parent.stem-kits.index') }}" class="pkit-filters">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search kits..." style="max-width:280px;">
        <select name="category" class="form-select" style="max-width:220px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="submit" class="pkit-btn"><i class="bi bi-search me-1"></i> Filter</button>
        @if(request('search') || request('category'))
            <a href="{{ route('parent.stem-kits.index') }}" class="btn btn-outline-secondary" style="font-size:13px;">Clear</a>
        @endif
    </form>

    @if($kits->count())
        <div class="pkit-grid">
            @foreach($kits as $kit)
                <div class="pkit-card">
                    <div class="pkit-image">
                        @if($kit->image)
                            <img src="{{ asset('storage/' . $kit->image) }}" alt="{{ $kit->name }}">
                        @else
                            <i class="bi bi-cpu"></i>
                        @endif
                        <span class="pkit-level">{{ ucfirst($kit->difficulty_level) }}</span>
                    </div>
                    <div class="pkit-body">
                        <div class="pkit-title">{{ $kit->name }}</div>
                        <div class="pkit-category">{{ $kit->category }}</div>
                        <div class="pkit-desc">{{ Str::limit($kit->description, 130) }}</div>

                        @if(!empty($kit->components))
                            <div class="pkit-components">
                                @foreach(array_slice($kit->components, 0, 5) as $component)
                                    <span class="pkit-tag">{{ $component }}</span>
                                @endforeach
                                @if(count($kit->components) > 5)
                                    <span class="pkit-tag">+{{ count($kit->components) - 5 }} more</span>
                                @endif
                            </div>
                        @endif

                        <div class="pkit-footer">
                            <div class="pkit-price">
                                <span class="pkit-price-value">₹{{ number_format($kit->price, 2) }}</span>
                                <span class="pkit-price-sub">One-time kit</span>
                            </div>
                            <div class="pkit-actions">
                                @if($kit->stock_quantity > 10)
                                    <span class="pkit-status available">In Stock</span>
                                @elseif($kit->stock_quantity > 0)
                                    <span class="pkit-status low">Only {{ $kit->stock_quantity }} left</span>
                                @else
                                    <span class="pkit-status low">Out of Stock</span>
                                @endif
                                <a href="https://steamkit.vercel.app/" target="_blank" rel="noopener noreferrer" class="pkit-btn-buy">
                                    Buy Now <i class="bi bi-cart3"></i>
                                </a>
                                <a href="{{ route('parent.stem-kits.show', $kit) }}" class="pkit-btn-view">
                                    View Kit <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $kits->withQueryString()->links() }}
        </div>
    @else
        <div class="pkit-empty">
            <i class="bi bi-box-seam"></i>
            <h5 style="color:var(--text-secondary);font-family:'Space Mono',monospace;">No STEM Kits are currently available.</h5>
            <p style="font-size:13px;">Please check back soon.</p>
        </div>
    @endif
</div>
@endsection