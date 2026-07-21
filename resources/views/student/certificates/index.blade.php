@extends('layouts.app')

@section('title', 'My Certificates - Nano Spark LMS')

@section('content')
<div style="max-width:1200px">
    <div class="ns-page-header animate-fadeIn">
        <h1 class="ns-page-title">My Certificates</h1>
        <p class="ns-page-subtitle">Your verified achievements and accomplishments.</p>
    </div>

    <div class="row g-4">
        @forelse($certificates ?? [] as $certificate)
            <div class="col-md-6 col-lg-4">
                <div class="ns-certificate-card">
                    <div class="ns-certificate-seal">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="ns-certificate-title">{{ $certificate->course->title ?? 'Course Completion' }}</div>
                    <div class="ns-certificate-course">{{ Auth::user()->name }}</div>
                    <div class="ns-certificate-date">
                        <i class="bi bi-calendar3"></i> {{ $certificate->issued_at?->format('M d, Y') ?? 'N/A' }}
                    </div>
                    <div style="margin-top:16px;display:flex;gap:8px;justify-content:center">
                        <a href="{{ route('student.certificates.download', $certificate) }}" class="ns-btn ns-btn-primary ns-btn-sm">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <span class="ns-badge success" style="padding:6px 12px">
                            <i class="bi bi-patch-check-fill"></i> Verified
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="ns-card text-center" style="padding:80px 24px">
                    <i class="bi bi-award" style="font-size:4rem;color:var(--text-muted)"></i>
                    <h3 style="font-family:var(--font-heading);color:var(--text-primary);margin-top:16px">No Certificates Yet</h3>
                    <p style="color:var(--text-muted);margin:8px 0 24px">Complete courses to earn verified certificates.</p>
                    <a href="{{ route('student.courses.index') }}" class="ns-btn ns-btn-primary">
                        <i class="bi bi-compass"></i> Browse Courses
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
