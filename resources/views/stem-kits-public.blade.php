@extends('layouts.guest')

@section('title', 'STEM Kits - Nano Spark LMS')

@section('guest-content')
<style>
    .ns-sk-hero {
        min-height: 50vh; display: flex; align-items: center; justify-content: center;
        text-align: center; padding: 120px 24px 60px; position: relative;
    }
    .ns-sk-hero h1 {
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: clamp(32px, 5vw, 52px); font-weight: 800;
        color: #FFFFFF; margin-bottom: 16px; letter-spacing: -1px;
    }
    .ns-sk-hero h1 span { color: #FFC107; }
    .ns-sk-hero p { font-size: 18px; color: #888888; max-width: 600px; margin: 0 auto; line-height: 1.8; }
    .ns-sk-section { padding: 80px 24px 120px; max-width: 1200px; margin: 0 auto; position: relative; z-index: 10; }

    .ns-sk-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
    }
    .ns-sk-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px; overflow: hidden;
        transition: all 0.3s; backdrop-filter: blur(10px);
    }
    .ns-sk-card:hover {
        transform: translateY(-6px);
        border-color: rgba(255, 193, 7, 0.15);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    .ns-sk-img {
        height: 200px; display: flex; align-items: center; justify-content: center;
        font-size: 48px; position: relative; overflow: hidden;
    }
    .ns-sk-body { padding: 24px; }
    .ns-sk-body h3 {
        font-family: 'Poppins', 'Inter', sans-serif;
        font-size: 18px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px;
    }
    .ns-sk-body p { font-size: 14px; color: #888888; line-height: 1.7; margin-bottom: 16px; }
    .ns-sk-tags { display: flex; gap: 8px; flex-wrap: wrap; }
    .ns-sk-tags span {
        padding: 4px 12px; border-radius: 100px;
        font-size: 12px; font-weight: 600;
    }
    .ns-sk-empty {
        text-align: center; padding: 80px 24px; color: #888888;
        grid-column: 1 / -1;
    }
    .ns-sk-empty i { font-size: 48px; color: #FFC107; margin-bottom: 16px; display: block; }

    @media (max-width: 1024px) { .ns-sk-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
        .ns-sk-grid { grid-template-columns: 1fr; }
        .ns-sk-section { padding: 48px 16px 80px; }
    }
</style>

<section class="ns-sk-hero">
    <div>
        <div style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;border-radius:100px;background:rgba(255,193,7,0.08);border:1px solid rgba(255,193,7,0.2);color:#FFC107;font-size:13px;font-weight:600;margin-bottom:32px">
            <i class="bi bi-box-seam"></i> Hardware Kits
        </div>
        <h1>Nano Spark <span>STEM Kits</span></h1>
        <p>Curated hardware kits with quality components, step-by-step guides, and real-world project ideas to bring your code to life.</p>
    </div>
</section>

<section class="ns-sk-section">
    @if($kits->count() > 0)
        <div class="ns-sk-grid">
            @foreach($kits as $kit)
                <div class="ns-sk-card">
                    <div class="ns-sk-img" style="background: linear-gradient(135deg, #FFC107, #FF9800);">
                        <i class="bi bi-box-seam" style="color: rgba(0,0,0,0.5)"></i>
                    </div>
                    <div class="ns-sk-body">
                        <h3>{{ $kit->name }}</h3>
                        <p>{{ $kit->description ? Str::limit($kit->description, 120) : 'A premium STEM kit for hands-on learning and building real-world projects.' }}</p>
                        <div class="ns-sk-tags">
                            @if($kit->difficulty_level)
                                <span style="background:rgba(255,193,7,0.1);color:#FFC107">{{ ucfirst($kit->difficulty_level) }}</span>
                            @endif
                            @if($kit->category)
                                <span style="background:rgba(59,130,246,0.1);color:#3B82F6">{{ $kit->category }}</span>
                            @endif
                            @if($kit->price > 0)
                                <span style="background:rgba(0,210,106,0.1);color:#00D26A">${{ number_format($kit->price, 2) }}</span>
                            @else
                                <span style="background:rgba(0,210,106,0.1);color:#00D26A">Free</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($kits->hasPages())
            <div style="margin-top:48px">
                {{ $kits->links() }}
            </div>
        @endif
    @else
        <div class="ns-sk-empty">
            <i class="bi bi-box-seam"></i>
            <h3 style="color:#CFCFCF;margin-bottom:8px">No STEM Kits Available Yet</h3>
            <p>We are preparing our STEM kits. Check back soon!</p>
        </div>
    @endif
</section>
@endsection
