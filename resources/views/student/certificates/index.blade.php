@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .certificate-card { transition: transform 0.2s, box-shadow 0.2s; }
    .certificate-card:hover { transform: translateY(-4px); box-shadow: 0 12px 35px rgba(0,0,0,0.1)!important; }
    .cert-border { border: 2px solid transparent; background-image: linear-gradient(white, white), linear-gradient(135deg, #667eea, #e83e8c, #fd7e14); background-origin: border-box; background-clip: padding-box, border-box; }
    .cert-pattern { background: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(102,126,234,0.03) 10px, rgba(102,126,234,0.03) 20px); }
</style>
@endpush

<div class="container py-4">
    <div class="text-center mb-5">
        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
            <i class="bi bi-award text-warning" style="font-size:2.5rem;"></i>
        </div>
        <h2 class="fw-bold">My Certificates</h2>
        <p class="text-muted">Your achievements and course completions</p>
    </div>

    @if($certificates->count())
        <div class="row g-4">
            @foreach($certificates as $certificate)
                <div class="col-md-6 col-xl-4">
                    <div class="card certificate-card cert-border rounded-4 shadow-sm h-100 overflow-hidden">
                        <div class="cert-pattern">
                            <div class="card-body p-4">
                                <div class="text-center mb-3">
                                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                        <i class="bi bi-award-fill text-warning" style="font-size:1.8rem;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1">Certificate of Completion</h5>
                                    <div class="small text-muted">{{ $certificate->course->title ?? 'Course' }}</div>
                                </div>

                                <div class="text-center py-3 border-top border-bottom">
                                    <div class="small text-muted mb-1">Awarded to</div>
                                    <div class="fw-bold fs-5">{{ $certificate->user->name ?? Auth::user()->name }}</div>
                                </div>

                                <div class="row g-2 my-3">
                                    <div class="col-6">
                                        <div class="small text-muted">Certificate No.</div>
                                        <div class="fw-bold small">{{ $certificate->certificate_number }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Issue Date</div>
                                        <div class="fw-bold small">{{ $certificate->issued_at ? $certificate->issued_at->format('M d, Y') : 'N/A' }}</div>
                                    </div>
                                </div>

                                @if($certificate->teacher)
                                    <div class="small text-muted mb-3">
                                        <i class="bi bi-person me-1"></i> Instructor: {{ $certificate->teacher->name }}
                                    </div>
                                @endif

                                <div class="d-grid gap-2">
                                    <a href="{{ route('student.certificates.download', $certificate) }}" class="btn btn-primary rounded-pill">
                                        <i class="bi bi-download me-2"></i>Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $certificates->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-award text-muted" style="font-size:4rem;"></i>
            <h5 class="text-muted mt-3">No Certificates Yet</h5>
            <p class="text-muted">Complete courses to earn certificates!</p>
            <a href="{{ route('student.courses.my') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-collection-play me-1"></i> My Courses
            </a>
        </div>
    @endif
</div>
@endsection
