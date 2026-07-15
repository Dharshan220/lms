<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        $totalSchools = School::count();

        $totalRevenue = Course::sum('price');
        $monthlyRevenue = Enrollment::whereMonth('enrollments.created_at', now()->month)
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');

        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(10)->get();

        $courseStats = [
            'published' => Course::where('is_published', true)->count(),
            'draft' => Course::where('is_published', false)->count(),
            'featured' => Course::where('is_featured', true)->count(),
        ];

        $enrollmentStats = [
            'completed' => Enrollment::where('is_completed', true)->count(),
            'in_progress' => Enrollment::where('is_completed', false)->count(),
        ];

        $popularCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        $monthlyEnrollments = Enrollment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'totalEnrollments',
            'totalSchools',
            'totalRevenue',
            'monthlyRevenue',
            'recentEnrollments',
            'recentUsers',
            'courseStats',
            'enrollmentStats',
            'popularCourses',
            'monthlyEnrollments'
        ));
    }
}
