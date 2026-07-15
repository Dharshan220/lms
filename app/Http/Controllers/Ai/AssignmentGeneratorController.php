<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssignmentGeneratorController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('is_published', true)
            ->where('teacher_id', $request->user()->id)
            ->orderBy('title')
            ->get();

        return view('ai.assignment-generator', compact('courses'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'type' => 'required|in:essay,project,worksheet,lab,code',
            'difficulty' => 'required|in:easy,medium,hard',
            'estimated_time' => 'nullable|string|max:100',
            'instructions' => 'nullable|string|max:1000',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            return back()->withInput()->with('error', 'AI API key is not configured. Please set GEMINI_API_KEY in your .env file.');
        }

        $prompt = $this->buildPrompt($validated, $course);

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
                $generatedContent = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($generatedContent) {
                    return view('ai.assignment-generator', compact('generatedContent', 'validated', 'course'));
                }
            }

            return back()->withInput()->with('error', 'AI service returned an error. Please try again.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to connect to AI service: ' . $e->getMessage());
        }
    }

    private function buildPrompt(array $data, Course $course): string
    {
        $typeMap = [
            'essay' => 'essay/written assignment',
            'project' => 'hands-on project',
            'worksheet' => 'worksheet with exercises',
            'lab' => 'lab/practical exercise',
            'code' => 'coding assignment',
        ];

        $difficultyMap = [
            'easy' => 'beginner level',
            'medium' => 'intermediate level',
            'hard' => 'advanced level',
        ];

        $prompt = "Create a detailed assignment for the course \"{$course->title}\".\n\n";
        $prompt .= "Topic: {$data['topic']}\n";
        $prompt .= "Type: {$typeMap[$data['type']]}\n";
        $prompt .= "Difficulty: {$difficultyMap[$data['difficulty']]}\n";

        if (!empty($data['estimated_time'])) {
            $prompt .= "Estimated Completion Time: {$data['estimated_time']}\n";
        }

        if (!empty($data['instructions'])) {
            $prompt .= "Additional Instructions: {$data['instructions']}\n";
        }

        $prompt .= "\nPlease generate a comprehensive assignment including:\n";
        $prompt .= "1. Assignment Title\n";
        $prompt .= "2. Overview/Objective\n";
        $prompt .= "3. Detailed Instructions\n";
        $prompt .= "4. Requirements/Deliverables\n";
        $prompt .= "5. Evaluation Criteria/Rubric\n";
        $prompt .= "6. Tips for Success\n";
        $prompt .= "7. Sample References (if applicable)\n";

        if ($data['type'] === 'code') {
            $prompt .= "\nFor coding assignments, also include:\n";
            $prompt .= "- Starter code template\n";
            $prompt .= "- Expected input/output examples\n";
            $prompt .= "- Test cases\n";
        }

        return $prompt;
    }
}
