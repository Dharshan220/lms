@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Certificates</h1>
                <p class="text-muted mt-1 mb-0">View and manage issued certificates</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Certificate Number</th>
                                <th>Issued Date</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($certificates ?? [] as $certificate)
                                <tr>
                                    <td class="ps-3">{{ $certificate->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                                <i class="bi bi-person text-primary small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $certificate->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $certificate->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $certificate->course->title ?? 'N/A' }}</td>
                                    <td>
                                        <code class="small">{{ $certificate->certificate_number }}</code>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $certificate->issued_at?->format('M d, Y') ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.certificates.show', $certificate) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $certificate->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal{{ $certificate->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete certificate <strong>{{ $certificate->certificate_number }}</strong>?
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-award" style="font-size: 2.5rem;"></i>
                                        <p class="mt-3">No certificates issued yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($certificates) && $certificates->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
