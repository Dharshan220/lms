@extends('layouts.app')

@section('title', 'Virtual Lab - Nano Spark LMS')

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

    .virtual-lab-page {
        background: var(--bg);
        min-height: 100vh;
        font-family: 'IBM Plex Sans', sans-serif;
        color: #E0E0E0;
        padding: 2rem 0;
    }

    .virtual-lab-page h1,
    .virtual-lab-page h2,
    .virtual-lab-page h3 {
        font-family: 'Space Mono', monospace;
    }

    .virtual-lab-page .mono-num {
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

    .category-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .category-filters .filter-btn {
        background: var(--card);
        border: 1px solid #2A2A2A;
        color: #AAA;
        padding: 0.5rem 1.2rem;
        border-radius: 999px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .category-filters .filter-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(255, 212, 0, 0.06);
    }

    .category-filters .filter-btn.active {
        background: var(--accent);
        color: #050505;
        border-color: var(--accent);
        font-weight: 600;
    }

    .category-filters .filter-btn i {
        margin-right: 0.4rem;
    }

    .labs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    .lab-card {
        background: var(--card);
        border: 1px solid #1E1E1E;
        border-radius: 16px;
        padding: 1.8rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .lab-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lab-card:hover {
        border-color: #333;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
    }

    .lab-card:hover::before {
        opacity: 1;
    }

    .lab-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.2rem;
        flex-shrink: 0;
    }

    .lab-card-icon.iot { background: rgba(255, 212, 0, 0.1); color: var(--accent); }
    .lab-card-icon.robotics { background: rgba(0, 210, 106, 0.1); color: var(--success); }
    .lab-card-icon.ai-ml { background: rgba(59, 130, 246, 0.1); color: var(--info); }
    .lab-card-icon.electronics { background: rgba(255, 152, 0, 0.1); color: var(--warning); }
    .lab-card-icon.coding { background: rgba(255, 77, 79, 0.1); color: var(--danger); }

    .lab-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 0.5rem;
    }

    .lab-card-desc {
        font-size: 0.875rem;
        color: #777;
        line-height: 1.6;
        margin-bottom: 1.2rem;
        flex: 1;
    }

    .lab-card-meta {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1.2rem;
        flex-wrap: wrap;
    }

    .difficulty-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.3rem 0.7rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .difficulty-badge.beginner {
        background: rgba(0, 210, 106, 0.12);
        color: var(--success);
        border: 1px solid rgba(0, 210, 106, 0.25);
    }

    .difficulty-badge.intermediate {
        background: rgba(255, 152, 0, 0.12);
        color: var(--warning);
        border: 1px solid rgba(255, 152, 0, 0.25);
    }

    .difficulty-badge.advanced {
        background: rgba(255, 77, 79, 0.12);
        color: var(--danger);
        border: 1px solid rgba(255, 77, 79, 0.25);
    }

    .lab-card-time {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.78rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .lab-card-time i {
        color: #555;
    }

    .lab-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .lab-category-tag {
        font-size: 0.72rem;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
    }

    .start-lab-btn {
        background: var(--accent);
        color: #050505;
        border: none;
        padding: 0.55rem 1.4rem;
        border-radius: 10px;
        font-family: 'Space Mono', monospace;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .start-lab-btn:hover {
        background: #FFDF4D;
        transform: scale(1.04);
        box-shadow: 0 4px 20px rgba(255, 212, 0, 0.25);
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
        .virtual-lab-page { padding: 1rem 0; }
        .labs-grid { grid-template-columns: 1fr; gap: 1rem; }
        .page-header h1 { font-size: 1.5rem; }
        .lab-card { padding: 1.4rem; }
    }

    @media (max-width: 480px) {
        .category-filters { gap: 0.4rem; }
        .category-filters .filter-btn { padding: 0.4rem 0.9rem; font-size: 0.8rem; }
    }
</style>

@section('content')
<div class="virtual-lab-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="bi bi-easel2"></i> Virtual <span>Lab</span></h1>
            <p>Hands-on experiments in a simulated environment. Learn by doing.</p>
        </div>

        <div class="category-filters">
            <button class="filter-btn active" data-category="all"><i class="bi bi-grid-3x3-gap"></i> All</button>
            <button class="filter-btn" data-category="iot"><i class="bi bi-wifi"></i> IoT</button>
            <button class="filter-btn" data-category="robotics"><i class="bi bi-robot"></i> Robotics</button>
            <button class="filter-btn" data-category="ai-ml"><i class="bi bi-cpu"></i> AI/ML</button>
            <button class="filter-btn" data-category="electronics"><i class="bi bi-motherboard"></i> Electronics</button>
            <button class="filter-btn" data-category="coding"><i class="bi bi-code-slash"></i> Coding</button>
        </div>

        @php
            $fallbackLabs = [
                [
                    'id' => 1,
                    'title' => 'IoT Weather Station',
                    'description' => 'Build a virtual weather station using sensors to measure temperature, humidity, and pressure. Deploy to a simulated Arduino.',
                    'category' => 'iot',
                    'category_label' => 'IoT',
                    'icon' => 'bi-cloud-rain-heavy',
                    'difficulty' => 'beginner',
                    'estimated_time' => '45 min',
                ],
                [
                    'id' => 2,
                    'title' => 'Line Following Robot',
                    'description' => 'Program a robot to follow a path using IR sensors and PID control logic in a virtual arena.',
                    'category' => 'robotics',
                    'category_label' => 'Robotics',
                    'icon' => 'bi-robot',
                    'difficulty' => 'intermediate',
                    'estimated_time' => '90 min',
                ],
                [
                    'id' => 3,
                    'title' => 'Handwritten Digit Recognition',
                    'description' => 'Train a neural network to recognize digits 0-9 using the MNIST dataset and TensorFlow.',
                    'category' => 'ai-ml',
                    'category_label' => 'AI / ML',
                    'icon' => 'bi-braces-asterisk',
                    'difficulty' => 'advanced',
                    'estimated_time' => '120 min',
                ],
                [
                    'id' => 4,
                    'title' => 'LED Blink Circuit',
                    'description' => 'Simulate a basic LED circuit with resistors, transistors, and a 555 timer IC for blinking output.',
                    'category' => 'electronics',
                    'category_label' => 'Electronics',
                    'icon' => 'bi-lightning',
                    'difficulty' => 'beginner',
                    'estimated_time' => '30 min',
                ],
                [
                    'id' => 5,
                    'title' => 'Python Data Structures',
                    'description' => 'Explore lists, dictionaries, sets, and tuples through interactive coding challenges and visualizers.',
                    'category' => 'coding',
                    'category_label' => 'Coding',
                    'icon' => 'bi-terminal',
                    'difficulty' => 'beginner',
                    'estimated_time' => '60 min',
                ],
                [
                    'id' => 6,
                    'title' => 'Smart Home Automation',
                    'description' => 'Design an IoT-based smart home system with virtual lights, locks, and environmental controls via MQTT.',
                    'category' => 'iot',
                    'category_label' => 'IoT',
                    'icon' => 'bi-house-gear',
                    'difficulty' => 'intermediate',
                    'estimated_time' => '75 min',
                ],
                [
                    'id' => 7,
                    'title' => 'Robotic Arm Control',
                    'description' => 'Simulate a 4-DOF robotic arm and program inverse kinematics to reach target coordinates.',
                    'category' => 'robotics',
                    'category_label' => 'Robotics',
                    'icon' => 'bi-hand-index',
                    'difficulty' => 'advanced',
                    'estimated_time' => '150 min',
                ],
                [
                    'id' => 8,
                    'title' => 'Sentiment Analyzer',
                    'description' => 'Build an NLP pipeline to classify product reviews as positive, negative, or neutral using Python.',
                    'category' => 'ai-ml',
                    'category_label' => 'AI / ML',
                    'icon' => 'bi-chat-dots',
                    'difficulty' => 'intermediate',
                    'estimated_time' => '100 min',
                ],
                [
                    'id' => 9,
                    'title' => 'FM Radio Transmitter',
                    'description' => 'Simulate an FM transmitter circuit with a Varactor diode and tune it to broadcast on custom frequencies.',
                    'category' => 'electronics',
                    'category_label' => 'Electronics',
                    'icon' => 'bi-broadcast',
                    'difficulty' => 'advanced',
                    'estimated_time' => '90 min',
                ],
            ];

            $labs = $labs ?? $fallbackLabs;
        @endphp

        @if(count($labs) > 0)
            <div class="labs-grid" id="labsGrid">
                @foreach($labs as $lab)
                    <div class="lab-card" data-category="{{ $lab['category'] }}">
                        <div class="lab-card-icon {{ $lab['category'] }}">
                            <i class="bi {{ $lab['icon'] }}"></i>
                        </div>
                        <h3 class="lab-card-title">{{ $lab['title'] }}</h3>
                        <p class="lab-card-desc">{{ $lab['description'] }}</p>
                        <div class="lab-card-meta">
                            <span class="difficulty-badge {{ $lab['difficulty'] }}">{{ $lab['difficulty'] }}</span>
                            <span class="lab-card-time">
                                <i class="bi bi-clock"></i> {{ $lab['estimated_time'] }}
                            </span>
                        </div>
                        <div class="lab-card-footer">
                            <span class="lab-category-tag">{{ $lab['category_label'] }}</span>
                            <a href="{{ route('student.virtual-lab.show', $lab['id']) }}" class="start-lab-btn">
                                Start Lab <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-easel2"></i>
                <h3>No Labs Available</h3>
                <p>Lab experiments are being prepared. Check back soon!</p>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.category-filters .filter-btn');
        const labCards = document.querySelectorAll('.lab-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const category = this.dataset.category;

                labCards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = '';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(12px)';
                        requestAnimationFrame(() => {
                            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endsection
