<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $parent = $request->user();
        $children = $parent->children()->with('school')->get();

        $childrenData = $children->map(function ($child) {
            $enrollments = Enrollment::where('user_id', $child->id)
                ->with('course')
                ->get();

            $completedCourses = $enrollments->where('is_completed', true)->count();
            $totalCourses = $enrollments->count();

            $avgProgress = $enrollments->isNotEmpty()
                ? round($enrollments->avg('progress_percentage'), 1)
                : 0;

            $lessonsCompleted = LessonProgress::where('user_id', $child->id)
                ->where('is_completed', true)
                ->count();

            $quizAttempts = QuizAttempt::where('user_id', $child->id)->get();
            $averageScore = $quizAttempts->isNotEmpty()
                ? round($quizAttempts->avg('score') / max($quizAttempts->avg('total_marks'), 1) * 100, 1)
                : 0;

            return [
                'child' => $child,
                'total_courses' => $totalCourses,
                'completed_courses' => $completedCourses,
                'avg_progress' => $avgProgress,
                'lessons_completed' => $lessonsCompleted,
                'average_score' => $averageScore,
                'xp_points' => $child->xp_points,
                'level' => $child->level,
                'daily_streak' => $child->daily_streak,
                'recent_enrollments' => $enrollments->sortByDesc('created_at')->take(3),
            ];
        });

        return view('parent.dashboard', compact('childrenData'));
    }

    public function childProgress(Request $request, $childId)
    {
        $parent = $request->user();

        $child = $parent->children()->where('id', $childId)->firstOrFail();

        $enrollments = Enrollment::where('user_id', $child->id)
            ->with(['course.category', 'course.teacher'])
            ->latest()
            ->get();

        $quizAttempts = QuizAttempt::where('user_id', $child->id)
            ->with('quiz.course')
            ->latest()
            ->get();

        $badges = $child->badges;

        $lessonsCompleted = LessonProgress::where('user_id', $child->id)
            ->where('is_completed', true)
            ->count();

        $totalXp = $child->xp_points;
        $currentLevel = $child->level;

        return view('parent.child-progress', compact(
            'child',
            'enrollments',
            'quizAttempts',
            'badges',
            'lessonsCompleted',
            'totalXp',
            'currentLevel'
        ));
    }
}
