<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();

        $query = Assignment::whereIn('course_id', $teacher->courses()->pluck('id'))
            ->with(['course', 'submissions']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $assignments = $query->latest()->paginate(15);

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create(Request $request)
    {
        $courses = $request->user()->courses()->where('is_published', true)->orderBy('title')->get();

        return view('teacher.assignments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'max_marks' => 'required|integer|min:1',
            'allowed_file_types' => 'nullable|string|max:255',
            'max_file_size_mb' => 'nullable|integer|min:1|max:100',
            'is_published' => 'boolean',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        abort_unless($course->teacher_id === $teacher->id, 403);

        $validated['teacher_id'] = $teacher->id;
        $validated['is_published'] = $request->boolean('is_published', false);

        Assignment::create($validated);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $teacher = request()->user();
        abort_unless($assignment->teacher_id === $teacher->id, 403);

        $assignment->load(['course', 'submissions.user']);

        return view('teacher.assignments.show', compact('assignment'));
    }

    public function submissions(Assignment $assignment)
    {
        $teacher = request()->user();
        abort_unless($assignment->teacher_id === $teacher->id, 403);

        $assignment->load(['course', 'submissions.user']);

        return view('teacher.assignments.submissions', compact('assignment'));
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        $teacher = $request->user();
        abort_unless($submission->assignment->teacher_id === $teacher->id, 403);

        $validated = $request->validate([
            'grade' => 'required|integer|min:0|max:' . $submission->assignment->max_marks,
            'feedback' => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'] ?? null,
            'graded_by' => $teacher->id,
            'graded_at' => now(),
            'status' => 'graded',
        ]);

        return redirect()->route('teacher.assignments.show', $submission->assignment)
            ->with('success', 'Submission graded successfully.');
    }
}
