@extends('layouts.app')

@section('title', 'My Quizzes - Teacher')

@section('content')
<div class="px-3 px-md-4 py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Quizzes</h4>
            <p class="text-muted mb-0">Create and manage quizzes for your courses</p>
        </div>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary fw-semibold">
            <i class="bi bi-plus-lg me-2"></i>Create Quiz
        </a>
    </div>

    {{-- Search --}}
    <div class="card section-card mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search quizzes..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                </div>
            </form>
        </div>
    </div>

    @if($quizzes->count())
        <div class="card section-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold ps-4">Title</th>
                                <th class="fw-semibold">Course</th>
                                <th class="fw-semibold">Questions</th>
                                <th class="fw-semibold">Passing Marks</th>
                                <th class="fw-semibold">Status</th>
                                <th class="fw-semibold text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;">
                                                <i class="bi bi-question-circle text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $quiz->title }}</div>
                                                @if($quiz->description)
                                                    <small class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-muted">{{ $quiz->course->title ?? 'N/A' }}</span></td>
                                    <td><span class="badge bg-info bg-opacity-10 text-info">{{ $quiz->questions->count() }}</span></td>
                                    <td>{{ $quiz->passing_marks }}</td>
                                    <td>
                                        @if($quiz->is_published)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $quizzes->withQueryString()->links() }}
        </div>
    @else
        <div class="card section-card">
            <div class="card-body text-center py-5">
                <i class="bi bi-question-circle text-muted" style="font-size:3rem;"></i>
                <h5 class="mt-3 text-muted">No quizzes found</h5>
                <p class="text-muted">{{ request('search') ? 'Try a different search.' : 'Create your first quiz to get started.' }}</p>
                @if(!request('search'))
                    <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Create Quiz
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .section-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
</style>
@endsection
