<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        $this->model = config('services.gemini.model', 'gemini-3.5-flash-lite');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1/models/{$this->model}:generateContent";
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    public function generateResponse(array $messages, array $config = []): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('GeminiService: API key not configured');
            return null;
        }

        $temperature = $config['temperature'] ?? 0.7;
        $maxTokens = $config['maxOutputTokens'] ?? 2048;

        $contents = $this->buildContents($messages);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->apiUrl}?key={$this->apiKey}", [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => $maxTokens,
                    ],
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($text === null) {
                    Log::error('GeminiService: Empty response - unexpected format', [
                        'response_structure' => array_keys($result),
                    ]);
                }

                return $text;
            }

            $status = $response->status();
            $body = $response->json();
            $errorMsg = $body['error']['message'] ?? 'Unknown error';

            Log::error("GeminiService: API error [{$status}]", [
                'error' => $errorMsg,
                'status' => $status,
            ]);

            if ($status === 429) {
                Log::warning('GeminiService: Rate limit hit');
            } elseif ($status === 403 || $status === 401) {
                Log::error('GeminiService: Authentication failed - check API key');
            } elseif ($status === 400) {
                Log::error('GeminiService: Bad request', [
                    'error' => $errorMsg,
                ]);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('GeminiService: HTTP request failed', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
            ]);
            return null;
        }
    }

    private function buildContents(array $messages): array
    {
        $contents = [];
        $systemInstruction = '';

        foreach ($messages as $msg) {
            $role = $msg['role'] ?? 'user';
            $content = $msg['content'] ?? '';

            if ($role === 'system') {
                $systemInstruction = $content;
                continue;
            }

            $geminiRole = $role === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $geminiRole,
                'parts' => [['text' => $content]],
            ];
        }

        if (!empty($systemInstruction) && !empty($contents)) {
            $contents[0]['parts'][0]['text'] = "{$systemInstruction}\n\nUser: {$contents[0]['parts'][0]['text']}";
        }

        return $contents;
    }
}
