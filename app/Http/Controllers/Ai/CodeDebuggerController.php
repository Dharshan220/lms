<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Services\GroqService;
use Illuminate\Http\Request;

class CodeDebuggerController extends Controller
{
    public function index()
    {
        return view('ai.code-debugger');
    }

    public function debug(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10000',
            'language' => 'required|string|max:50',
            'error_message' => 'nullable|string|max:2000',
            'description' => 'nullable|string|max:1000',
        ]);

        $groq = new GroqService();
        if (!$groq->isConfigured()) {
            return back()->withInput()->with('error', 'AI service is not configured. Please set GROQ_API_KEY.');
        }

        $prompt = $this->buildPrompt($validated);
        $systemPrompt = 'You are an expert code debugger. Analyze code and return issues in the exact JSON format requested. Return ONLY valid JSON.';

        $response = $groq->chat($systemPrompt, $prompt, ['temperature' => 0.3, 'maxOutputTokens' => 8192]);

        if ($response) {
            $results = $this->parseResults($response);
            return view('ai.code-debugger', compact('results', 'validated'));
        }

        return back()->withInput()->with('error', 'AI service returned an error. Please try again.');
    }

    private function buildPrompt(array $data): string
    {
        $prompt = "Analyze the following {$data['language']} code and identify bugs, errors, warnings, and suggest fixes.\n\n";
        $prompt .= "Code:\n```{$data['language']}\n{$data['code']}\n```\n\n";

        if (!empty($data['error_message'])) {
            $prompt .= "Error Message: {$data['error_message']}\n\n";
        }

        if (!empty($data['description'])) {
            $prompt .= "Description of Expected Behavior: {$data['description']}\n\n";
        }

        $prompt .= "Return ONLY a JSON object with this exact structure:\n";
        $prompt .= "{\n";
        $prompt .= '  "errors": [{"line": 1, "message": "Error description", "suggestion": "How to fix"}],' . "\n";
        $prompt .= '  "warnings": [{"line": 2, "message": "Warning description", "suggestion": "Improvement suggestion"}],' . "\n";
        $prompt .= '  "suggestions": ["Best practice tip 1", "Best practice tip 2"],' . "\n";
        $prompt .= '  "fixed_code": "The complete corrected code here"\n';
        $prompt .= "}\n\n";
        $prompt .= 'If no errors are found, return {"errors":[], "warnings":[], "suggestions":["Code looks good!"], "fixed_code": "' . addslashes($data['code']) . '"}' . "\n";
        $prompt .= "No markdown code blocks, no additional text.";

        return $prompt;
    }

    private function parseResults(string $content): array
    {
        $content = trim($content);
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);

        $results = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::warning('CodeDebugger: Failed to parse JSON', [
                'error' => json_last_error_msg(),
                'snippet' => substr($content, 0, 300),
            ]);
            return [
                'errors' => [],
                'warnings' => [],
                'suggestions' => ['Could not parse AI response. Please try again.'],
                'fixed_code' => null,
            ];
        }

        return $results;
    }
}
