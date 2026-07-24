<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\GroqService;
use Illuminate\Http\Request;

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

        $groq = new GroqService();
        if (!$groq->isConfigured()) {
            return back()->withInput()->with('error', 'AI service is not configured. Please set GROQ_API_KEY.');
        }

        $prompt = $this->buildPrompt($validated, $course);
        $systemPrompt = 'You are an expert lesson planner for educators. Create detailed, engaging lesson plans that follow best practices in pedagogy.';

        $lessonPlan = $groq->chat($systemPrompt, $prompt, ['temperature' => 0.7, 'maxOutputTokens' => 8192]);

        if ($lessonPlan) {
            return view('teacher.ai-lesson-planner.index', compact('lessonPlan', 'validated', 'course', 'courses'));
        }

        return back()->withInput()->with('error', 'AI service returned an error. Please try again.');
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
