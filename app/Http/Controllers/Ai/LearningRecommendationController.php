<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LearningRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();

        $enrollments = Enrollment::where('user_id', $student->id)
            ->with('course.category')
            ->get();

        $quizAttempts = QuizAttempt::where('user_id', $student->id)
            ->with('quiz.course')
            ->get();

        $completedLessons = LessonProgress::where('user_id', $student->id)
            ->where('is_completed', true)
            ->count();

        $weakAreas = $quizAttempts->where('is_passed', false)
            ->pluck('quiz.course.title')
            ->unique()
            ->values();

        $strongAreas = $quizAttempts->where('is_passed', true)
            ->pluck('quiz.course.title')
            ->unique()
            ->values();

        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));

        $recommendations = null;

        if (!empty($apiKey) && $enrollments->isNotEmpty()) {
            $recommendations = $this->getAiRecommendations($apiKey, $student, $enrollments, $quizAttempts, $completedLessons, $weakAreas, $strongAreas);
        }

        return view('ai.recommendations', compact(
            'enrollments',
            'quizAttempts',
            'completedLessons',
            'weakAreas',
            'strongAreas',
            'recommendations'
        ));
    }

    private function getAiRecommendations(string $apiKey, $student, $enrollments, $quizAttempts, $completedLessons, $weakAreas, $strongAreas): ?string
    {
        $prompt = "Based on the following student learning profile, provide personalized learning recommendations:\n\n";
        $prompt .= "Student Level: {$student->level}\n";
        $prompt .= "XP Points: {$student->xp_points}\n";
        $prompt .= "Daily Streak: {$student->daily_streak} days\n";
        $prompt .= "Enrolled Courses: {$enrollments->count()}\n";
        $prompt .= "Completed Courses: {$enrollments->where('is_completed', true)->count()}\n";
        $prompt .= "Lessons Completed: {$completedLessons}\n";
        $prompt .= "Total Quiz Attempts: {$quizAttempts->count()}\n";
        $prompt .= "Quizzes Passed: {$quizAttempts->where('is_passed', true)->count()}\n";
        $prompt .= "Pass Rate: " . ($quizAttempts->isNotEmpty() ? round($quizAttempts->where('is_passed', true)->count() / $quizAttempts->count() * 100) : 0) . "%\n";

        if ($weakAreas->isNotEmpty()) {
            $prompt .= "Areas Needing Improvement: " . $weakAreas->implode(', ') . "\n";
        }

        if ($strongAreas->isNotEmpty()) {
            $prompt .= "Strong Areas: " . $strongAreas->implode(', ') . "\n";
        }

        $courseNames = $enrollments->pluck('course.title')->unique()->implode(', ');
        $prompt .= "Course Topics: {$courseNames}\n\n";

        $prompt .= "Please provide:\n";
        $prompt .= "1. **Personalized Study Plan**: A weekly study schedule\n";
        $prompt .= "2. **Focus Areas**: Topics to review or practice more\n";
        $prompt .= "3. **Challenge Recommendations**: New topics to explore\n";
        $prompt .= "4. **Study Tips**: Specific to their learning style and progress\n";
        $prompt .= "5. **Motivation**: Based on their streak and achievements\n";
        $prompt .= "6. **Resources**: Suggested learning resources and methods\n";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 4096,
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('AI recommendation error: ' . $e->getMessage());
            return null;
        }
    }
}
