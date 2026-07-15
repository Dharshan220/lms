<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            return back()->withInput()->with('error', 'AI API key is not configured. Please set GEMINI_API_KEY in your .env file.');
        }

        $prompt = $this->buildPrompt($validated);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 4096,
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $generatedContent = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($generatedContent) {
                    return view('ai.code-debugger', compact('generatedContent', 'validated'));
                }
            }

            return back()->withInput()->with('error', 'AI service returned an error. Please try again.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to connect to AI service: ' . $e->getMessage());
        }
    }

    private function buildPrompt(array $data): string
    {
        $prompt = "You are an expert code debugger. Analyze the following {$data['language']} code and help identify and fix any issues.\n\n";
        $prompt .= "Code:\n```\n{$data['code']}\n```\n\n";

        if (!empty($data['error_message'])) {
            $prompt .= "Error Message: {$data['error_message']}\n\n";
        }

        if (!empty($data['description'])) {
            $prompt .= "Description of Expected Behavior: {$data['description']}\n\n";
        }

        $prompt .= "Please provide:\n";
        $prompt .= "1. **Issue Identification**: List all bugs, errors, or potential issues found\n";
        $prompt .= "2. **Root Cause**: Explain why each issue occurs\n";
        $prompt .= "3. **Fixed Code**: Provide the corrected code\n";
        $prompt .= "4. **Explanation**: Explain what was changed and why\n";
        $prompt .= "5. **Best Practices**: Any improvements or best practices suggestions\n";
        $prompt .= "6. **Prevention Tips**: How to avoid similar issues in the future\n";

        return $prompt;
    }
}
