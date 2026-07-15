<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['teacher', 'category', 'school']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $courses = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'categories', 'teachers'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.courses.create', compact('categories', 'teachers', 'schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'trailer_video' => 'nullable|string|max:500',
            'teacher_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['is_published'] = $request->boolean('is_published', false);

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['teacher', 'category', 'school', 'lessons', 'quizzes', 'assignments']);

        $enrollmentsCount = $course->enrollments()->count();
        $completedCount = $course->enrollments()->where('is_completed', true)->count();

        return view('admin.courses.show', compact('course', 'enrollmentsCount', 'completedCount'));
    }

    public function edit(Course $course)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'teachers', 'schools'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'trailer_video' => 'nullable|string|max:500',
            'teacher_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        if ($validated['title'] !== $course->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if ($course->enrollments()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete course with active enrollments.');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
