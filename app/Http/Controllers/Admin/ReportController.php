<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        $completionRate = $totalEnrollments > 0
            ? round(Enrollment::where('is_completed', true)->count() / $totalEnrollments * 100, 1)
            : 0;

        $monthlyEnrollments = Enrollment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $topCourses = Course::withCount(['enrollments', 'lessons'])
            ->withAvg('enrollments', 'progress_percentage')
            ->orderByDesc('enrollments_count')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'totalEnrollments',
            'completionRate',
            'monthlyEnrollments',
            'topCourses'
        ));
    }

    public function enrollmentReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(6)->startOfMonth());
        $dateTo = $request->get('date_to', now()->endOfMonth());

        $enrollmentsByMonth = Enrollment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $enrollmentsByCategory = Course::select('categories.name as category_name', DB::raw('COUNT(enrollments.id) as count'))
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->whereBetween('enrollments.created_at', [$dateFrom, $dateTo])
            ->groupBy('categories.name')
            ->orderByDesc('count')
            ->get();

        $completionStats = Enrollment::selectRaw('is_completed, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('is_completed')
            ->get();

        $dailyEnrollments = Enrollment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.enrollment', compact(
            'enrollmentsByMonth',
            'enrollmentsByCategory',
            'completionStats',
            'dailyEnrollments',
            'dateFrom',
            'dateTo'
        ));
    }

    public function courseReport(Request $request)
    {
        $courses = Course::withCount(['enrollments', 'lessons', 'quizzes'])
            ->withAvg('enrollments', 'progress_percentage')
            ->withCount(['enrollments as completed_enrollments' => function ($q) {
                $q->where('is_completed', true);
            }])
            ->latest()
            ->paginate(15);

        $categoryStats = Course::select('categories.name as category_name', DB::raw('COUNT(*) as course_count'), DB::raw('AVG(courses.enrollment_count) as avg_enrollments'))
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderByDesc('course_count')
            ->get();

        return view('admin.reports.course', compact('courses', 'categoryStats'));
    }

    public function studentReport(Request $request)
    {
        $query = User::where('role', 'student')
            ->withCount('enrollments')
            ->withCount(['enrollments as completed_enrollments' => function ($q) {
                $q->where('is_completed', true);
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->get()->map(function ($student) {
            $quizAttempts = QuizAttempt::where('user_id', $student->id)->get();
            $student->average_score = $quizAttempts->isNotEmpty()
                ? round($quizAttempts->avg('score') / max($quizAttempts->avg('total_marks'), 1) * 100, 1)
                : 0;
            $student->lessons_completed = LessonProgress::where('user_id', $student->id)
                ->where('is_completed', true)
                ->count();
            return $student;
        });

        $topStudents = $students->sortByDesc('xp_points')->take(10);

        $averageXp = $students->avg('xp_points');

        return view('admin.reports.student', compact('students', 'topStudents', 'averageXp'));
    }
}
