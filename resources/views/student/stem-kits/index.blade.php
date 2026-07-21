@extends('layouts.app')

@section('title', 'STEM Kits - Nano Spark LMS')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=IBM+Plex+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap');

    :root {
        --bg: #050505;
        --card: #121212;
        --elevated: #181818;
        --accent: #FFD400;
        --success: #00D26A;
        --warning: #FF9800;
        --danger: #FF4D4F;
        --info: #3B82F6;
    }

    .stem-kits-page {
        background: var(--bg);
        min-height: 100vh;
        font-family: 'IBM Plex Sans', sans-serif;
        color: #E0E0E0;
        padding: 2rem 0;
    }

    .stem-kits-page h1,
    .stem-kits-page h2,
    .stem-kits-page h3 {
        font-family: 'Space Mono', monospace;
    }

    .stem-kits-page .mono-num {
        font-family: 'JetBrains Mono', monospace;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 0.5rem;
    }

    .page-header h1 span {
        color: var(--accent);
    }

    .page-header p {
        color: #888;
        font-size: 0.95rem;
    }

    .kits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 1.5rem;
    }

    .kit-card {
        background: var(--card);
        border: 1px solid #1E1E1E;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .kit-card:hover {
        border-color: #333;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
    }

    .kit-image {
        width: 100%;
        height: 180px;
        background: var(--elevated);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .kit-image i {
        font-size: 3.5rem;
        color: #2A2A2A;
    }

    .kit-image .kit-level-badge {
        position: absolute;
        top: 0.8rem;
        right: 0.8rem;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .kit-level-badge.level-1 {
        background: rgba(0, 210, 106, 0.15);
        color: var(--success);
        border: 1px solid rgba(0, 210, 106, 0.3);
    }

    .kit-level-badge.level-2 {
        background: rgba(255, 152, 0, 0.15);
        color: var(--warning);
        border: 1px solid rgba(255, 152, 0, 0.3);
    }

    .kit-level-badge.level-3 {
        background: rgba(255, 77, 79, 0.15);
        color: var(--danger);
        border: 1px solid rgba(255, 77, 79, 0.3);
    }

    .kit-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .kit-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 0.5rem;
    }

    .kit-desc {
        font-size: 0.875rem;
        color: #777;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .kit-components {
        margin-bottom: 1.2rem;
    }

    .kit-components-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        font-weight: 700;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.5rem;
    }

    .kit-components-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .component-tag {
        font-size: 0.72rem;
        color: #999;
        background: var(--elevated);
        border: 1px solid #222;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
    }

    .kit-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #1A1A1A;
    }

    .kit-price {
        display: flex;
        flex-direction: column;
    }

    .kit-price-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--accent);
    }

    .kit-price-sub {
        font-size: 0.7rem;
        color: #555;
    }

    .kit-subscribed-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--success);
        background: rgba(0, 210, 106, 0.08);
        border: 1px solid rgba(0, 210, 106, 0.2);
        padding: 0.35rem 0.8rem;
        border-radius: 8px;
    }

    .kit-action-btn {
        background: var(--accent);
        color: #050505;
        border: none;
        padding: 0.55rem 1.3rem;
        border-radius: 10px;
        font-family: 'Space Mono', monospace;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
    }

    .kit-action-btn:hover {
        background: #FFDF4D;
        transform: scale(1.04);
        box-shadow: 0 4px 20px rgba(255, 212, 0, 0.25);
    }

    .kit-action-btn.subscribed {
        background: rgba(0, 210, 106, 0.12);
        color: var(--success);
        border: 1px solid rgba(0, 210, 106, 0.3);
    }

    .kit-action-btn.subscribed:hover {
        background: rgba(0, 210, 106, 0.2);
        transform: scale(1.04);
        box-shadow: 0 4px 20px rgba(0, 210, 106, 0.15);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #555;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #333;
    }

    .empty-state h3 {
        font-size: 1.2rem;
        color: #777;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .stem-kits-page { padding: 1rem 0; }
        .kits-grid { grid-template-columns: 1fr; gap: 1rem; }
        .page-header h1 { font-size: 1.5rem; }
        .kit-image { height: 140px; }
    }
</style>

@section('content')
<div class="stem-kits-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-box-seam"></i> STEM <span>Kits</span></h1>
            <p>Physical and virtual kits designed for hands-on learning. Pick a kit, unbox it, and start building.</p>
        </div>

        @php
            $fallbackKits = [
                [
                    'id' => 1,
                    'title' => 'IoT Starter Kit',
                    'description' => 'Everything you need to build your first IoT project. Includes sensors, a microcontroller, and guided tutorials.',
                    'image_icon' => 'bi-wifi',
                    'level' => 1,
                    'level_label' => 'Beginner',
                    'price' => '$49',
                    'price_note' => 'one-time',
                    'subscribed' => false,
                    'components' => ['Arduino Nano', 'DHT22 Sensor', 'Breadboard', 'Jumper Wires', 'USB Cable'],
                ],
                [
                    'id' => 2,
                    'title' => 'Robotics Explorer Kit',
                    'description' => 'Build and program a mobile robot with obstacle avoidance. Perfect for learning robotics fundamentals.',
                    'image_icon' => 'bi-robot',
                    'level' => 2,
                    'level_label' => 'Intermediate',
                    'price' => '$89',
                    'price_note' => 'one-time',
                    'subscribed' => false,
                    'components' => ['Arduino Uno', 'L298N Motor Driver', '2x DC Motors', 'Ultrasonic Sensor', 'Chassis'],
                ],
                [
                    'id' => 3,
                    'title' => 'AI Vision Module',
                    'description' => 'A camera-equipped board preloaded with ML models for object detection, face recognition, and gesture control.',
                    'image_icon' => 'bi-cpu',
                    'level' => 3,
                    'level_label' => 'Advanced',
                    'price' => '$12/mo',
                    'price_note' => 'subscription',
                    'subscribed' => true,
                    'components' => ['ESP32-CAM', 'OV2640 Lens', 'SD Card', 'USB-Serial Adapter', 'Standalone Board'],
                ],
                [
                    'id' => 4,
                    'title' => 'Electronics Lab Box',
                    'description' => 'A complete analog and digital electronics lab in a box. Includes components, a multimeter, and PCB prototyping tools.',
                    'image_icon' => 'bi-motherboard',
                    'level' => 1,
                    'level_label' => 'Beginner',
                    'price' => '$65',
                    'price_note' => 'one-time',
                    'subscribed' => false,
                    'components' => ['Resistor Set', 'Capacitor Set', 'LEDs', '555 Timer IC', 'Mini Multimeter'],
                ],
                [
                    'id' => 5,
                    'title' => 'Coding & Logic Kit',
                    'description' => 'Hands-on logic gates, binary boards, and a programmable micro-computer for learning computational thinking.',
                    'image_icon' => 'bi-code-slash',
                    'level' => 1,
                    'level_label' => 'Beginner',
                    'price' => '$39',
                    'price_note' => 'one-time',
                    'subscribed' => false,
                    'components' => ['Logic Gate Board', 'Binary Display', 'Micro:bit', 'USB Cable', 'Project Book'],
                ],
                [
                    'id' => 6,
                    'title' => 'Advanced Robotics Kit',
                    'description' => 'Servo-driven robotic arm with 5 degrees of freedom. Program in Python or via visual block coding.',
                    'image_icon' => 'bi-hand-index',
                    'level' => 3,
                    'level_label' => 'Advanced',
                    'price' => '$15/mo',
                    'price_note' => 'subscription',
                    'subscribed' => true,
                    'components' => ['5-DOF Arm Frame', '5x Servos', 'PCA9685 Driver', 'Raspberry Pi Pico', 'Power Supply'],
                ],
            ];

            $kits = $kits ?? $fallbackKits;
        @endphp

        @if(count($kits) > 0)
            <div class="kits-grid">
                @foreach($kits as $kit)
                    <div class="kit-card">
                        <div class="kit-image">
                            <i class="bi {{ $kit['image_icon'] }}"></i>
                            <span class="kit-level-badge level-{{ $kit['level'] }}">
                                {{ $kit['level_label'] }}
                            </span>
                        </div>
                        <div class="kit-body">
                            <h3 class="kit-title">{{ $kit['title'] }}</h3>
                            <p class="kit-desc">{{ $kit['description'] }}</p>

                            <div class="kit-components">
                                <div class="kit-components-title">Components</div>
                                <div class="kit-components-list">
                                    @foreach($kit['components'] as $component)
                                        <span class="component-tag">{{ $component }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="kit-footer">
                                <div class="kit-price">
                                    <span class="kit-price-value">{{ $kit['price'] }}</span>
                                    <span class="kit-price-sub">{{ $kit['price_note'] }}</span>
                                </div>

                                @if($kit['subscribed'])
                                    <a href="{{ route('student.stem-kits.show', $kit['id']) }}" class="kit-action-btn subscribed">
                                        <i class="bi bi-check-circle"></i> Open Kit
                                    </a>
                                @else
                                    <a href="{{ route('student.stem-kits.show', $kit['id']) }}" class="kit-action-btn">
                                        View Kit <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                <h3>No Kits Available</h3>
                <p>STEM kits are being assembled. Check back soon!</p>
            </div>
        @endif
    </div>
</div>
@endsection
