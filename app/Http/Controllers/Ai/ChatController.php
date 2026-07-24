<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiChatHistory;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:30,1')->only('chat');
    }

    public function index(Request $request)
    {
        $chats = AiChatHistory::where('user_id', $request->user()->id)
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return view('ai.chat', compact('chats'));
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'chat_type' => 'nullable|string|max:50',
        ]);

        $user = $request->user();

        $systemPrompt = "You are NanoSpark AI Tutor, an intelligent educational assistant for the NanoSpark LMS platform. "
            . "Your purpose is to help students learn and understand concepts across a wide range of subjects.\n\n"
            . "## Your Capabilities\n"
            . "- Answer questions about: IoT, Embedded Systems, Arduino, ESP32, ESP8266, Raspberry Pi, Electronics, Digital Electronics, Analog Electronics, Sensors, Robotics, C, C++, Python, Java, JavaScript, HTML, CSS, Web Development, Databases, SQL, Computer Networks, Operating Systems, Data Structures, Algorithms, AI, Machine Learning, Deep Learning, Generative AI, Mathematics, Physics, Engineering concepts, Programming problems, STEM topics, and general educational questions.\n"
            . "- Explain concepts clearly with step-by-step reasoning\n"
            . "- Provide code examples and help debug code\n"
            . "- Answer general educational questions like explaining photosynthesis, Newton's laws, RAM vs ROM, etc.\n\n"
            . "## Teaching Style\n"
            . "- Be educational, encouraging, and clear\n"
            . "- Adapt explanations to the student's level\n"
            . "- Ask guiding questions instead of always giving direct answers\n"
            . "- Provide examples when helpful\n"
            . "- Explain incorrect answers\n"
            . "- Generate quizzes and practice questions when requested\n"
            . "- Create study plans when requested\n"
            . "- Be friendly and encouraging\n\n"
            . "## Rules\n"
            . "- Never claim to be a human teacher\n"
            . "- Clearly state uncertainty when information is uncertain\n"
            . "- Never fabricate facts\n"
            . "- Encourage students to understand concepts rather than blindly copy answers\n"
            . "- Format responses with proper markdown for readability\n"
            . "- If asked something harmful, illegal, or inappropriate, politely decline to answer\n"
            . "- Do NOT refuse normal educational questions about any academic or technical subject";

        $recentChats = AiChatHistory::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get()
            ->reverse();

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($recentChats as $chat) {
            $messages[] = ['role' => 'user', 'content' => $chat->message];
            $messages[] = ['role' => 'assistant', 'content' => $chat->response];
        }

        $messages[] = ['role' => 'user', 'content' => $validated['message']];

        $responseText = null;
        $gemini = new GeminiService();

        if ($gemini->isConfigured()) {
            $responseText = $gemini->generateResponse($messages);
        }

        if ($responseText === null) {
            $responseText = "I'm having trouble connecting to my AI engine right now. Please try again in a moment. "
                . "If the issue persists, check that the Gemini API key is properly configured.";
        }

        try {
            $chatHistory = AiChatHistory::create([
                'user_id' => $user->id,
                'message' => $validated['message'],
                'response' => $responseText,
                'chat_type' => $validated['chat_type'] ?? 'general',
                'tokens_used' => 0,
            ]);
        } catch (\Exception $e) {
            Log::error('ChatController: Failed to save chat history', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'response' => $responseText,
            'chat_id' => $chatHistory->id ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'response' => 'required|string',
            'chat_type' => 'nullable|string|max:50',
        ]);

        $chatHistory = AiChatHistory::create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
            'response' => $validated['response'],
            'chat_type' => $validated['chat_type'] ?? 'general',
            'tokens_used' => 0,
        ]);

        return response()->json(['chat' => $chatHistory], 201);
    }
}
