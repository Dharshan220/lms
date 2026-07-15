<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        $enrolledCourseIds = $student->enrollments()->pluck('course_id');

        $assignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['course', 'submissions' => function ($q) use ($student) {
                $q->where('user_id', $student->id);
            }])
            ->latest()
            ->paginate(15);

        return view('student.assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $student = request()->user();

        abort_unless($assignment->is_published, 404);

        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        abort_unless($enrolledCourseIds->contains($assignment->course_id), 403);

        $assignment->load('course');

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $student->id)
            ->first();

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $student = $request->user();

        abort_unless($assignment->is_published, 404);

        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        abort_unless($enrolledCourseIds->contains($assignment->course_id), 403);

        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.assignments.show', $assignment)
                ->with('error', 'You have already submitted this assignment.');
        }

        $validated = $request->validate([
            'file' => 'nullable|file|max:' . ($assignment->max_file_size_mb * 1024),
            'submission_text' => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments/submissions', 'public');
        }

        if (!$filePath && empty($validated['submission_text'])) {
            return redirect()->back()->with('error', 'Please upload a file or provide a text submission.');
        }

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'file_path' => $filePath,
            'submission_text' => $validated['submission_text'] ?? null,
            'submitted_at' => now(),
            'status' => 'pending',
        ]);

        $student->increment('xp_points', 15);

        return redirect()->route('student.assignments.show', $assignment)
            ->with('success', 'Assignment submitted successfully! +15 XP');
    }
}
