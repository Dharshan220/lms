@extends('layouts.app')

@section('title', 'AI Project Ideas - Nano Spark')

@section('content')
@push('styles')
<style>
    .project-card { transition: transform 0.2s, box-shadow 0.2s; }
    .project-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .generate-btn { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
    .generate-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(102,126,234,0.4); }
    .fav-btn { transition: all 0.2s; }
    .fav-btn.active { color: #e83e8c; }
    .difficulty-badge { font-size: 0.7rem; }
    .component-chip { display: inline-block; background: #e9ecef; padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; margin: 2px; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded bg-info bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
            <i class="bi bi-lightbulb text-info" style="font-size:1.4rem;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-1">AI Project Idea Generator</h4>
            <p class="text-muted mb-0">Get creative STEM project ideas tailored to your interests</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Input Form --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="bi bi-sliders me-2 text-primary"></i>Preferences</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('ai.project-ideas.generate') }}" method="POST" id="projectForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="iot" {{ old('category') == 'iot' ? 'selected' : '' }}>Internet of Things (IoT)</option>
                                <option value="robotics" {{ old('category') == 'robotics' ? 'selected' : '' }}>Robotics</option>
                                <option value="ai" {{ old('category') == 'ai' ? 'selected' : '' }}>Artificial Intelligence</option>
                                <option value="programming" {{ old('category') == 'programming' ? 'selected' : '' }}>Programming</option>
                                <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="web" {{ old('category') == 'web' ? 'selected' : '' }}>Web Development</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Difficulty Level</label>
                            <select name="difficulty" class="form-select">
                                <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty', 'intermediate') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Components Available <small class="text-muted">(optional)</small></label>
                            <textarea name="components" class="form-control" rows="3"
                                placeholder="e.g. Arduino Uno, sensors, LEDs, breadboard">{{ old('components') }}</textarea>
                            <small class="text-muted">List the components you have access to</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold generate-btn" id="generateBtn">
                                <i class="bi bi-magic me-2"></i>Generate Ideas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Generated Ideas --}}
        <div class="col-lg-8">
            @if(isset($projects) && count($projects))
                <div class="row g-4">
                    @foreach($projects as $index => $project)
                        @php
                            $colors = ['#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#0d6efd', '#0dcaf0'];
                            $color = $colors[$index % count($colors)];
                            $diffColors = ['beginner' => 'success', 'intermediate' => 'warning', 'advanced' => 'danger'];
                        @endphp
                        <div class="col-md-6">
                            <div class="card project-card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge rounded-pill" style="background-color:{{ $color }};">
                                            {{ $project['category'] ?? 'Project' }}
                                        </span>
                                        <button class="btn btn-sm btn-light fav-btn" onclick="toggleFavorite(this, {{ $index }})">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                    <h5 class="fw-bold mt-2 mb-2">{{ $project['title'] ?? '' }}</h5>
                                    <p class="text-muted mb-3" style="font-size:0.85rem;">{{ $project['description'] ?? '' }}</p>

                                    @if(isset($project['components']) && count($project['components']))
                                        <div class="mb-3">
                                            <small class="fw-semibold text-muted d-block mb-1">Required Components:</small>
                                            @foreach($project['components'] as $component)
                                                <span class="component-chip">{{ $component }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="d-flex gap-3 flex-wrap mb-3">
                                        <div>
                                            <small class="text-muted d-block" style="font-size:0.7rem;">Difficulty</small>
                                            <span class="badge difficulty-badge bg-{{ $diffColors[$project['difficulty'] ?? 'beginner'] }}">
                                                {{ ucfirst($project['difficulty'] ?? 'beginner') }}
                                            </span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size:0.7rem;">Est. Time</small>
                                            <span class="fw-semibold small">{{ $project['estimated_time'] ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    @if(isset($project['learning_outcomes']) && count($project['learning_outcomes']))
                                        <div class="mb-3">
                                            <small class="fw-semibold text-muted d-block mb-1">Learning Outcomes:</small>
                                            <ul class="list-unstyled mb-0" style="font-size:0.8rem;">
                                                @foreach($project['learning_outcomes'] as $outcome)
                                                    <li><i class="bi bi-check2 text-success me-1"></i>{{ $outcome }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-center py-5" id="emptyState">
                            <div class="rounded bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                <i class="bi bi-lightbulb text-info" style="font-size:2.5rem;"></i>
                            </div>
                            <h5 class="text-muted">Your project ideas will appear here</h5>
                            <p class="text-muted mb-0">Select a category and click "Generate Ideas" to get creative project suggestions.</p>
                        </div>

                        <div id="loadingState" class="text-center py-5" style="display:none;">
                            <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;" role="status"></div>
                            <h5 class="text-muted">Generating project ideas...</h5>
                            <p class="text-muted mb-0">AI is coming up with creative project ideas for you.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('projectForm')?.addEventListener('submit', function() {
    document.getElementById('generateBtn').disabled = true;
    document.getElementById('generateBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('loadingState').style.display = 'block';
});

function toggleFavorite(btn, index) {
    btn.classList.toggle('active');
    const icon = btn.querySelector('i');
    if (btn.classList.contains('active')) {
        icon.classList.replace('bi-heart', 'bi-heart-fill');
    } else {
        icon.classList.replace('bi-heart-fill', 'bi-heart');
    }
}
</script>
@endpush
@endsection
