<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:video,document,text',
            'video_url' => 'nullable|string|max:500',
            'document_file' => 'nullable|file|max:10240',
            'order_number' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('document_file')) {
            $validated['document_file'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        $validated['is_free'] = $request->boolean('is_free', false);
        $validated['is_published'] = $request->boolean('is_published', false);

        Lesson::create($validated);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Lesson added successfully.');
    }

    public function edit(Request $request, Lesson $lesson)
    {
        $course = $lesson->course;
        abort_unless($course->teacher_id === $request->user()->id, 403);

        return view('teacher.lessons.edit', compact('lesson', 'course'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $course = $lesson->course;
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:video,document,text',
            'video_url' => 'nullable|string|max:500',
            'document_file' => 'nullable|file|max:10240',
            'order_number' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validated['title'] !== $lesson->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('document_file')) {
            $validated['document_file'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        $validated['is_free'] = $request->boolean('is_free');
        $validated['is_published'] = $request->boolean('is_published');

        $lesson->update($validated);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Request $request, Lesson $lesson)
    {
        $course = $lesson->course;
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $lesson->delete();

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Lesson deleted successfully.');
    }
}
