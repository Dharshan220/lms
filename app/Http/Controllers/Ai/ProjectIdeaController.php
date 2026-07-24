<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\GroqService;
use Illuminate\Http\Request;

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

        $groq = new GroqService();
        if (!$groq->isConfigured()) {
            return back()->withInput()->with('error', 'AI service is not configured. Please set GROQ_API_KEY.');
        }

        $prompt = $this->buildPrompt($validated, $course);
        $systemPrompt = 'You are a project ideas generator for STEM education. Return ONLY valid JSON matching the requested format.';

        $response = $groq->chat($systemPrompt, $prompt, ['temperature' => 0.8, 'maxOutputTokens' => 8192]);

        if ($response) {
            $projects = $this->parseProjects($response);
            if (!empty($projects)) {
                return view('ai.project-ideas', compact('projects', 'validated', 'course'));
            }
        }

        return back()->withInput()->with('error', 'Failed to generate project ideas. Please try again.');
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

        $prompt .= "\nReturn ONLY a JSON array of project objects with this exact structure:\n";
        $prompt .= "[\n";
        $prompt .= "  {\n";
        $prompt .= "    \"title\": \"Project Title\",\n";
        $prompt .= "    \"category\": \"Project Category\",\n";
        $prompt .= "    \"description\": \"Brief description\",\n";
        $prompt .= "    \"components\": [\"Component1\", \"Component2\"],\n";
        $prompt .= "    \"difficulty\": \"beginner|intermediate|advanced\",\n";
        $prompt .= "    \"estimated_time\": \"2-3 hours\",\n";
        $prompt .= "    \"learning_outcomes\": [\"Outcome1\", \"Outcome2\"]\n";
        $prompt .= "  }\n";
        $prompt .= "]\n\n";
        $prompt .= "No markdown code blocks, no additional text.";

        return $prompt;
    }

    private function parseProjects(string $content): array
    {
        $content = trim($content);
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);

        $projects = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::warning('ProjectIdea: Failed to parse JSON', [
                'error' => json_last_error_msg(),
                'snippet' => substr($content, 0, 300),
            ]);
            return [];
        }

        return $projects;
    }
}
