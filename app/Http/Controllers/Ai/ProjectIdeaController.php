<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProjectIdeaController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('is_published', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view('ai.project-ideas', compact('courses'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'num_ideas' => 'required|integer|min:1|max:10',
            'difficulty' => 'required|in:easy,medium,hard',
            'team_size' => 'nullable|integer|min:1|max:10',
            'tools' => 'nullable|string|max:500',
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
            ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 4096,
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $generatedContent = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($generatedContent) {
                    return view('ai.project-ideas', compact('generatedContent', 'validated', 'course'));
                }
            }

            return back()->withInput()->with('error', 'AI service returned an error. Please try again.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to connect to AI service: ' . $e->getMessage());
        }
    }

    private function buildPrompt(array $data, Course $course): string
    {
        $difficultyMap = [
            'easy' => 'beginner-friendly',
            'medium' => 'intermediate',
            'hard' => 'advanced/challenging',
        ];

        $prompt = "Generate {$data['num_ideas']} creative project ideas for the course \"{$course->title}\".\n\n";
        $prompt .= "Topic/Theme: {$data['topic']}\n";
        $prompt .= "Difficulty: {$difficultyMap[$data['difficulty']]}\n";

        if (!empty($data['team_size'])) {
            $prompt .= "Team Size: {$data['team_size']} students\n";
        }

        if (!empty($data['tools'])) {
            $prompt .= "Available Tools/Tech: {$data['tools']}\n";
        }

        $prompt .= "\nFor each project idea, please provide:\n";
        $prompt .= "1. Project Title\n";
        $prompt .= "2. Brief Description\n";
        $prompt .= "3. Learning Objectives\n";
        $prompt .= "4. Key Features/Requirements\n";
        $prompt .= "5. Suggested Technologies/Tools\n";
        $prompt .= "6. Step-by-step Implementation Plan\n";
        $prompt .= "7. Expected Outcome\n";
        $prompt .= "8. Extension Ideas (for advanced students)\n";

        return $prompt;
    }
}
