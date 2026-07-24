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
        $this->apiKey = config('services.groq.key', env('GROQ_API_KEY'));
        $this->model = config('services.groq.model', 'llama-3.3-70b-versatile');
        $this->apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
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
        $maxTokens = $config['maxOutputTokens'] ?? 4096;

        $groqMessages = $this->buildMessages($messages);

        try {
            $response = Http::timeout(45)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => $groqMessages,
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['choices'][0]['message']['content'] ?? null;

                if ($text === null) {
                    Log::error('GeminiService: Empty response - unexpected format', [
                        'response_structure' => $result,
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

    private function buildMessages(array $messages): array
    {
        $groqMessages = [];

        foreach ($messages as $msg) {
            $role = $msg['role'] ?? 'user';
            $content = $msg['content'] ?? '';

            if ($role === 'system') {
                $groqMessages[] = [
                    'role' => 'system',
                    'content' => $content,
                ];
            } elseif ($role === 'assistant') {
                $groqMessages[] = [
                    'role' => 'assistant',
                    'content' => $content,
                ];
            } else {
                $groqMessages[] = [
                    'role' => 'user',
                    'content' => $content,
                ];
            }
        }

        return $groqMessages;
    }
}
