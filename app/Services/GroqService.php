<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
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

    public function chat(string $systemPrompt, string $userMessage, array $config = []): ?string
    {
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userMessage],
        ];

        return $this->generateResponse($messages, $config);
    }

    public function generateResponse(array $messages, array $config = []): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('GroqService: API key not configured');
            return null;
        }

        $temperature = $config['temperature'] ?? 0.7;
        $maxTokens = $config['maxOutputTokens'] ?? 4096;

        $groqMessages = $this->buildMessages($messages);

        try {
            $response = Http::timeout(60)
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
                    Log::error('GroqService: Empty response - unexpected format', [
                        'response_structure' => $result,
                    ]);
                }

                return $text;
            }

            $status = $response->status();
            $body = $response->json();
            $errorMsg = $body['error']['message'] ?? 'Unknown error';

            Log::error("GroqService: API error [{$status}]", [
                'error' => $errorMsg,
                'status' => $status,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('GroqService: HTTP request failed', [
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

            $groqMessages[] = [
                'role' => $role === 'assistant' ? 'assistant' : ($role === 'system' ? 'system' : 'user'),
                'content' => $content,
            ];
        }

        return $groqMessages;
    }
}
