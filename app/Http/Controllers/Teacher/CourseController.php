<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();

        $query = $teacher->courses()->with(['category', 'school']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $courses = $query->latest()->paginate(15);

        return view('teacher.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('teacher.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'trailer_video' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['teacher_id'] = $teacher->id;
        $validated['school_id'] = $teacher->school_id;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', false);

        Course::create($validated);

        return redirect()->route('teacher.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Request $request, Course $course)
    {
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $course->load(['category', 'lessons', 'quizzes', 'assignments', 'enrollments.user']);

        $enrollmentsCount = $course->enrollments()->count();
        $completedCount = $course->enrollments()->where('is_completed', true)->count();

        return view('teacher.courses.show', compact('course', 'enrollmentsCount', 'completedCount'));
    }

    public function edit(Request $request, Course $course)
    {
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('teacher.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'trailer_video' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'is_published' => 'boolean',
        ]);

        if ($validated['title'] !== $course->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published');

        $course->update($validated);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }
}
