<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('is_published', true)
            ->with(['teacher', 'category']);

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

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('price')) {
            switch ($request->price) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'paid':
                    $query->where('price', '>', 0);
                    break;
            }
        }

        $courses = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('student.courses.index', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        $course->load(['teacher', 'category', 'lessons' => function ($q) {
            $q->where('is_published', true);
        }, 'quizzes' => function ($q) {
            $q->where('is_published', true);
        }]);

        $enrolled = false;
        $enrollment = null;

        if (auth()->check()) {
            $enrollment = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();
            $enrolled = $enrollment !== null;
        }

        $totalLessons = $course->lessons->count();
        $enrollmentCount = $course->enrollments()->count();

        return view('student.courses.show', compact('course', 'enrolled', 'enrollment', 'totalLessons', 'enrollmentCount'));
    }

    public function enroll(Request $request, Course $course)
    {
        $student = $request->user();

        $existingEnrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('student.courses.show', $course)
                ->with('error', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percentage' => 0,
            'is_completed' => false,
        ]);

        $course->increment('enrollment_count');

        return redirect()->route('student.courses.show', $course)
            ->with('success', 'Successfully enrolled in the course!');
    }

    public function myCourses(Request $request)
    {
        $student = $request->user();

        $query = Enrollment::where('user_id', $student->id)
            ->with(['course.teacher', 'course.category']);

        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'completed':
                    $query->where('is_completed', true);
                    break;
                case 'in_progress':
                    $query->where('is_completed', false);
                    break;
            }
        }

        $enrollments = $query->latest()->paginate(12);

        return view('student.courses.my-courses', compact('enrollments'));
    }

    public function continueLesson(Request $request, Enrollment $enrollment)
    {
        $student = $request->user();
        abort_unless($enrollment->user_id === $student->id, 403);

        $course = $enrollment->course->load(['lessons' => function ($q) {
            $q->where('is_published', true)->orderBy('order_number');
        }]);

        $completedLessonIds = LessonProgress::where('user_id', $student->id)
            ->where('is_completed', true)
            ->where('enrollment_id', $enrollment->id)
            ->pluck('lesson_id');

        $nextLesson = $course->lessons->first(function ($lesson) use ($completedLessonIds) {
            return !$completedLessonIds->contains($lesson->id);
        });

        if ($nextLesson) {
            return redirect()->route('student.lessons.show', ['lesson' => $nextLesson, 'enrollment' => $enrollment]);
        }

        return redirect()->route('student.courses.show', $course)
            ->with('info', 'You have completed all lessons in this course!');
    }
}
