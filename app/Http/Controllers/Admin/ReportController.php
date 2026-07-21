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

        $monthlyEnrollments = Enrollment::selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, COUNT(*) as count")
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

        $enrollmentsByMonth = Enrollment::selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, COUNT(*) as count")
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

        $dailyEnrollments = Enrollment::selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalEnrollments = $enrollmentsByMonth->sum('count');
        $completedEnrollments = $completionStats->where('is_completed', true)->sum('count');
        $activeEnrollments = $completionStats->where('is_completed', false)->sum('count');

        $enrolledStudents = Enrollment::whereBetween('created_at', [$dateFrom, $dateTo])->pluck('user_id')->unique();
        $avgProgress = $enrolledStudents->isNotEmpty()
            ? round(Enrollment::whereIn('user_id', $enrolledStudents)->avg('progress_percentage') ?? 0, 1)
            : 0;

        $courses = Course::orderBy('title')->get();

        $chartLabels = $enrollmentsByMonth->pluck('month')->toArray();
        $chartData = $enrollmentsByMonth->pluck('count')->toArray();

        $enrollments = Enrollment::with(['user', 'course'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->paginate(15);

        return view('admin.reports.enrollment', compact(
            'enrollmentsByMonth',
            'enrollmentsByCategory',
            'completionStats',
            'dailyEnrollments',
            'dateFrom',
            'dateTo',
            'totalEnrollments',
            'completedEnrollments',
            'activeEnrollments',
            'avgProgress',
            'courses',
            'chartLabels',
            'chartData',
            'enrollments'
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

        $totalCourses = Course::count();
        $publishedCourses = Course::where('is_published', true)->count();
        $avgRating = round(Course::avg('rating') ?? 0, 1);
        $avgCompletion = round(Course::withCount('enrollments')->get()->avg('enrollments_count') > 0
            ? Enrollment::where('is_completed', true)->count() / max(Enrollment::count(), 1) * 100
            : 0, 1);

        $courseStats = $courses->map(function ($c) {
            return [
                'title' => $c->title,
                'teacher' => $c->teacher->name ?? 'N/A',
                'enrollments' => $c->enrollments_count ?? 0,
                'completion_rate' => $c->enrollments_count > 0 ? round(($c->completed_enrollments ?? 0) / $c->enrollments_count * 100) : 0,
                'rating' => round($c->rating ?? 0, 1),
                'revenue' => ($c->price ?? 0) * ($c->enrollments_count ?? 0),
            ];
        });

        $chartLabels = $categoryStats->pluck('category_name')->toArray();
        $chartData = $categoryStats->pluck('course_count')->toArray();

        return view('admin.reports.course', compact('courses', 'categoryStats', 'totalCourses', 'publishedCourses', 'avgRating', 'avgCompletion', 'courseStats', 'chartLabels', 'chartData'));
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
        $totalStudents = $students->count();
        $avgXp = round($averageXp ?? 0);
        $avgLevel = round($students->avg('level') ?? 1, 1);
        $activeStudents = User::where('role', 'student')->where('is_active', true)
            ->where('updated_at', '>=', now()->subDays(30))->count();

        $studentStats = $students->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'school' => $s->school->name ?? 'N/A',
                'grade' => $s->grade ?? 'N/A',
                'xp' => $s->xp_points ?? 0,
                'level' => $s->level ?? 1,
                'courses_count' => $s->enrollments_count ?? 0,
                'completion_rate' => $s->enrollments_count > 0 ? round(($s->completed_enrollments ?? 0) / $s->enrollments_count * 100) : 0,
            ];
        });

        $gradeLabels = $students->pluck('grade')->filter()->unique()->values()->toArray();
        $gradeData = $gradeLabels->map(fn($g) => $students->where('grade', $g)->count())->toArray();

        $topStudentNames = $topStudents->pluck('name')->toArray();
        $topStudentXp = $topStudents->pluck('xp_points')->toArray();

        return view('admin.reports.student', compact(
            'students', 'topStudents', 'averageXp',
            'totalStudents', 'avgXp', 'avgLevel', 'activeStudents',
            'studentStats', 'gradeLabels', 'gradeData',
            'topStudentNames', 'topStudentXp'
        ));
    }
}
