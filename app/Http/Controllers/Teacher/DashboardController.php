<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LiveClass;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();

        $totalCourses = $teacher->courses()->count();
        $publishedCourses = $teacher->courses()->where('is_published', true)->count();

        $totalStudents = Enrollment::whereIn('course_id', $teacher->courses()->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');

        $totalEnrollments = Enrollment::whereIn('course_id', $teacher->courses()->pluck('id'))
            ->count();

        $totalRevenue = Course::where('teacher_id', $teacher->id)
            ->sum('price');

        $pendingAssignments = Assignment::where('teacher_id', $teacher->id)
            ->whereHas('submissions', function ($q) {
                $q->where('status', 'pending');
            })
            ->count();

        $pendingSubmissions = AssignmentSubmission::whereHas('assignment', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->where('status', 'pending')
            ->count();

        $upcomingClasses = LiveClass::where('teacher_id', $teacher->id)
            ->where('scheduled_at', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->with('course')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $recentEnrollments = Enrollment::whereIn('course_id', $teacher->courses()->pluck('id'))
            ->with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $coursesWithStats = $teacher->courses()
            ->withCount(['enrollments', 'lessons'])
            ->withAvg('enrollments', 'progress_percentage')
            ->latest()
            ->take(5)
            ->get();

        $courses = $coursesWithStats;
        $pendingAssignments = $teacher->assignments()
            ->withCount('submissions')
            ->whereHas('submissions', function ($q) {
                $q->where('status', 'pending');
            })
            ->get();

        return view('teacher.dashboard', compact(
            'totalCourses',
            'publishedCourses',
            'totalStudents',
            'totalEnrollments',
            'totalRevenue',
            'pendingSubmissions',
            'pendingAssignments',
            'upcomingClasses',
            'recentEnrollments',
            'courses',
            'coursesWithStats'
        ));
    }
}
