<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiLessonPlannerController extends Controller
{
    public function index(Request $request)
    {
        $courses = $request->user()->courses()->orderBy('title')->get();

        return view('teacher.ai-lesson-planner.index', compact('courses'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'grade_level' => 'nullable|string|max:50',
            'duration_minutes' => 'nullable|integer|min:5|max:180',
            'learning_objectives' => 'nullable|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        abort_unless($course->teacher_id === $request->user()->id, 403);

        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            return back()->withInput()->with('error', 'AI API key is not configured. Please set GEMINI_API_KEY in your .env file.');
        }

        $prompt = $this->buildPrompt($validated, $course);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1/models/" . config('services.gemini.model') . ":generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 4096,
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $lessonPlan = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';

                return view('teacher.ai-lesson-planner.index', compact('lessonPlan', 'validated', 'course', 'courses'));
            }

            return back()->withInput()->with('error', 'AI service returned an error. Please try again.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to connect to AI service: ' . $e->getMessage());
        }
    }

    private function buildPrompt(array $data, Course $course): string
    {
        $prompt = "Create a detailed lesson plan for the following:\n\n";
        $prompt .= "Course: {$course->title}\n";
        $prompt .= "Topic: {$data['topic']}\n";

        if (!empty($data['grade_level'])) {
            $prompt .= "Grade Level: {$data['grade_level']}\n";
        }

        if (!empty($data['duration_minutes'])) {
            $prompt .= "Duration: {$data['duration_minutes']} minutes\n";
        }

        if (!empty($data['learning_objectives'])) {
            $prompt .= "Learning Objectives: {$data['learning_objectives']}\n";
        }

        if (!empty($data['additional_notes'])) {
            $prompt .= "Additional Notes: {$data['additional_notes']}\n";
        }

        $prompt .= "\nPlease generate a comprehensive lesson plan including:\n";
        $prompt .= "1. Title and Overview\n";
        $prompt .= "2. Learning Objectives (specific, measurable)\n";
        $prompt .= "3. Materials Needed\n";
        $prompt .= "4. Lesson Structure with time allocations:\n";
        $prompt .= "   - Warm-up/Introduction\n";
        $prompt .= "   - Main Instruction\n";
        $prompt .= "   - Guided Practice\n";
        $prompt .= "   - Independent Practice\n";
        $prompt .= "   - Closure/Assessment\n";
        $prompt .= "5. Differentiation Strategies\n";
        $prompt .= "6. Assessment Methods\n";
        $prompt .= "7. Homework/Follow-up Activities\n";

        return $prompt;
    }
}
