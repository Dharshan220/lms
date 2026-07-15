@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: var(--text-primary);">
                <i class="bi bi-chat-dots" style="color: var(--ns-accent);"></i> Discussions
            </h1>
            <p style="color: var(--text-secondary); margin:0;">Join the learning community</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <p class="text-muted">Visit the main discussions page to participate in community conversations.</p>
            <a href="{{ route('discussions.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-right me-1"></i> Go to Discussions
            </a>
        </div>
    </div>
</div>
@endsection
