<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        $enrollments = Enrollment::where('user_id', $student->id)
            ->with(['course.teacher', 'course.category'])
            ->latest()
            ->get();

        $enrolledCourses = $enrollments->count();
        $completedCourses = $enrollments->where('is_completed', true)->count();
        $inProgressCourses = $enrolledCourses - $completedCourses;

        $totalLessonsCompleted = LessonProgress::where('user_id', $student->id)
            ->where('is_completed', true)
            ->count();

        $totalXp = $student->xp_points;
        $currentLevel = $student->level;
        $dailyStreak = $student->daily_streak;

        $badges = $student->badges;

        $recentQuizAttempts = QuizAttempt::where('user_id', $student->id)
            ->with('quiz.course')
            ->latest()
            ->take(5)
            ->get();

        $activeEnrollments = $enrollments->where('is_completed', false)->take(5);

        $upcomingQuizzes = \App\Models\Quiz::whereIn('course_id', function ($q) use ($student) {
                $q->select('course_id')
                  ->from('enrollments')
                  ->where('user_id', $student->id)
                  ->where('is_completed', false);
            })
            ->where('is_published', true)
            ->with('course')
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'enrolledCourses',
            'completedCourses',
            'inProgressCourses',
            'totalLessonsCompleted',
            'totalXp',
            'currentLevel',
            'dailyStreak',
            'badges',
            'recentQuizAttempts',
            'activeEnrollments',
            'upcomingQuizzes'
        ));
    }
}
