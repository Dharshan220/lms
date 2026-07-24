<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Services\GroqService;
use Illuminate\Http\Request;

class QuizGeneratorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $courses = Course::where('is_published', true)
            ->where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)
                  ->orWhere('id', '>', 0);
            })
            ->orderBy('title')
            ->get();

        $questions = session('generated_questions');
        $validated = session('quiz_form_data');
        $course = isset($validated['course_id']) ? Course::find($validated['course_id']) : null;

        return view('ai.quiz-generator', compact('courses', 'questions', 'validated', 'course'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'num_questions' => 'required|integer|min:1|max:50',
            'difficulty' => 'required|in:easy,medium,hard,beginner,intermediate,advanced',
            'question_type' => 'required|in:mcq,true_false,mixed',
        ]);

        $difficultyMap = [
            'beginner' => 'easy',
            'intermediate' => 'medium',
            'advanced' => 'hard',
        ];
        if (isset($difficultyMap[$validated['difficulty']])) {
            $validated['difficulty'] = $difficultyMap[$validated['difficulty']];
        }

        $course = Course::findOrFail($validated['course_id']);

        $groq = new GroqService();
        if (!$groq->isConfigured()) {
            return back()->withInput()->with('error', 'AI service is not configured. Please set GROQ_API_KEY.');
        }

        $prompt = $this->buildPrompt($validated, $course);
        $systemPrompt = 'You are a quiz generation expert. Generate quiz questions in the exact JSON format requested. Return ONLY valid JSON.';

        $response = $groq->chat($systemPrompt, $prompt, ['temperature' => 0.5, 'maxOutputTokens' => 8192]);

        if ($response) {
            $questions = $this->parseQuestions($response);
            if (!empty($questions)) {
                session(['generated_questions' => $questions, 'quiz_form_data' => $validated]);
                return back();
            }
        }

        return back()->withInput()->with('error', 'Failed to generate questions. Please try again.');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'questions' => 'nullable|array',
        ]);

        $questions = session('generated_questions');
        $formData = session('quiz_form_data');

        if (empty($questions)) {
            return back()->with('error', 'No questions to save. Please generate a quiz first.');
        }

        $course = Course::find($formData['course_id'] ?? null);
        if (!$course) {
            return back()->with('error', 'Course not found.');
        }

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => ucfirst($formData['topic'] ?? 'AI Generated Quiz'),
            'description' => "Auto-generated quiz on {$formData['topic']} ({$formData['difficulty']})",
            'time_limit_minutes' => count($questions) * 2,
            'passing_marks' => ceil(count($questions) * 0.5),
            'max_attempts' => 3,
            'is_published' => true,
        ]);

        foreach ($questions as $index => $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $q['question'] ?? '',
                'question_type' => $q['question_type'] ?? 'mcq',
                'option_a' => $q['option_a'] ?? $q['options'][0] ?? null,
                'option_b' => $q['option_b'] ?? $q['options'][1] ?? null,
                'option_c' => $q['option_c'] ?? $q['options'][2] ?? null,
                'option_d' => $q['option_d'] ?? $q['options'][3] ?? null,
                'correct_answer' => $q['correct_answer'] ?? 'A',
                'explanation' => $q['explanation'] ?? null,
                'marks' => $q['marks'] ?? 1,
                'order_number' => $index + 1,
            ]);
        }

        session()->forget(['generated_questions', 'quiz_form_data']);

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz saved successfully with ' . count($questions) . ' questions!');
    }

    private function buildPrompt(array $data, Course $course): string
    {
        $difficultyMap = [
            'easy' => 'basic recall and understanding',
            'medium' => 'application and analysis',
            'hard' => 'synthesis, evaluation, and complex problem-solving',
        ];

        $typeMap = [
            'mcq' => 'multiple choice (4 options: A, B, C, D)',
            'true_false' => 'true/false',
            'mixed' => 'mixed format (multiple choice and true/false)',
        ];

        $prompt = "Generate {$data['num_questions']} quiz questions about \"{$data['topic']}\" for the course \"{$course->title}\".\n\n";
        $prompt .= "Difficulty Level: {$difficultyMap[$data['difficulty']]}\n";
        $prompt .= "Question Format: {$typeMap[$data['question_type']]}\n\n";
        $prompt .= "Please generate the questions in the following JSON format:\n";
        $prompt .= "[\n";
        $prompt .= "  {\n";
        $prompt .= "    \"question\": \"Question text here\",\n";
        $prompt .= "    \"question_type\": \"mcq\" or \"true_false\",\n";
        $prompt .= "    \"option_a\": \"Option A\",\n";
        $prompt .= "    \"option_b\": \"Option B\",\n";
        $prompt .= "    \"option_c\": \"Option C (null if true/false)\",\n";
        $prompt .= "    \"option_d\": \"Option D (null if true/false)\",\n";
        $prompt .= "    \"correct_answer\": \"A\",\n";
        $prompt .= "    \"explanation\": \"Brief explanation of the correct answer\",\n";
        $prompt .= "    \"marks\": 1\n";
        $prompt .= "  }\n";
        $prompt .= "]\n\n";
        $prompt .= "Return ONLY the JSON array. No markdown code blocks, no additional text.";

        return $prompt;
    }

    private function parseQuestions(string $content): array
    {
        $content = trim($content);
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);

        $questions = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::warning('QuizGenerator: Failed to parse JSON from AI', [
                'error' => json_last_error_msg(),
                'content_snippet' => substr($content, 0, 200),
            ]);
            return [];
        }

        return $questions;
    }
}
