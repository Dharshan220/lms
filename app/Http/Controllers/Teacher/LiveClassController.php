<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LiveClass;
use Illuminate\Http\Request;

class LiveClassController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();

        $query = LiveClass::where('teacher_id', $teacher->id)->with('course');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $liveClasses = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        return view('teacher.live-classes.index', compact('liveClasses'));
    }

    public function create(Request $request)
    {
        $courses = $request->user()->courses()->where('is_published', true)->orderBy('title')->get();

        return view('teacher.live-classes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'meeting_link' => 'required|url|max:500',
            'meeting_password' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        abort_unless($course->teacher_id === $teacher->id, 403);

        $validated['teacher_id'] = $teacher->id;
        $validated['status'] = 'scheduled';

        LiveClass::create($validated);

        return redirect()->route('teacher.live-classes.index')
            ->with('success', 'Live class scheduled successfully.');
    }

    public function update(Request $request, LiveClass $liveClass)
    {
        $teacher = $request->user();
        abort_unless($liveClass->teacher_id === $teacher->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'meeting_link' => 'required|url|max:500',
            'meeting_password' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:1',
        ]);

        $liveClass->update($validated);

        return redirect()->route('teacher.live-classes.index')
            ->with('success', 'Live class updated successfully.');
    }

    public function cancel(LiveClass $liveClass)
    {
        $teacher = request()->user();
        abort_unless($liveClass->teacher_id === $teacher->id, 403);

        $liveClass->update(['status' => 'cancelled']);

        return redirect()->route('teacher.live-classes.index')
            ->with('success', 'Live class cancelled.');
    }
}
