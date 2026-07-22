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

        $systemPrompt = "You are NanoSpark AI, an intelligent learning assistant for the NanoSpark LMS platform. "
            . "You help students understand concepts, solve problems, generate quiz questions, and provide learning recommendations. "
            . "Be educational, encouraging, and clear in your responses. "
            . "Explain concepts step-by-step. "
            . "Adapt explanations to the student's level. "
            . "Ask guiding questions instead of always giving direct answers. "
            . "Provide examples when helpful. "
            . "Help with IoT, Embedded Systems, electronics, C programming, Python, Arduino, ESP8266/ESP32, "
            . "Raspberry Pi, basic robotics, VLSI, semiconductor fundamentals, mathematics, and engineering subjects. "
            . "Generate quizzes when requested. "
            . "Explain incorrect answers. "
            . "Generate practice questions. "
            . "Create study plans when requested. "
            . "Be friendly and encouraging. "
            . "Avoid unnecessarily complicated language. "
            . "Never claim to be a human teacher. "
            . "Clearly state uncertainty when information is uncertain. "
            . "Never fabricate facts. "
            . "Encourage students to understand concepts rather than blindly copy answers. "
            . "Format responses with proper markdown for readability.";

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
            $responseText = $this->builtInResponse($validated['message'], $validated['chat_type'] ?? 'general');
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

    private function builtInResponse(string $message, string $chatType): string
    {
        $lower = strtolower(trim($message));

        $knowledge = [
            'what is iot' => "**IoT (Internet of Things)** refers to the network of physical devices — like sensors, appliances, and vehicles — that are connected to the internet and can exchange data.\n\n**Key components of IoT:**\n- **Sensors/Devices** – Collect data from the environment\n- **Connectivity** – Wi-Fi, Bluetooth, MQTT protocols\n- **Data Processing** – Cloud or edge computing\n- **User Interface** – Dashboards and mobile apps\n\n**Examples in daily life:**\n- Smart thermostats (Nest)\n- Fitness trackers (Fitbit)\n- Smart home devices (Alexa, Google Home)\n- Industrial sensors for predictive maintenance",

            'what is arduino' => "**Arduino** is an open-source electronics platform based on easy-to-use hardware and software.\n\n**Arduino Uno specifications:**\n- Microcontroller: ATmega328P\n- Digital I/O Pins: 14 (6 PWM output)\n- Analog Input Pins: 6\n- Flash Memory: 32 KB\n- Clock Speed: 16 MHz\n\n**Basic Arduino Code:**\n```cpp\nvoid setup() {\n  pinMode(13, OUTPUT);\n}\nvoid loop() {\n  digitalWrite(13, HIGH);\n  delay(1000);\n  digitalWrite(13, LOW);\n  delay(1000);\n}\n```\nThis blinks an LED connected to pin 13!",

            'what is robotics' => "**Robotics** is the interdisciplinary branch of engineering and science that deals with the design, construction, operation, and use of robots.\n\n**Key areas of robotics:**\n1. **Mechanical Engineering** – Robot structure and movement\n2. **Electrical Engineering** – Sensors, motors, circuits\n3. **Computer Science** – Programming and AI\n4. **Mathematics** – Kinematics, algorithms\n\n**Types of robots:**\n- Industrial robots (manufacturing)\n- Service robots (cleaning, surgery)\n- Educational robots (micro:bit, LEGO Mindstorms)\n- Autonomous vehicles\n\n**Getting started:** Try building with Arduino or micro:bit!",

            'what is python' => "**Python** is a high-level, interpreted programming language known for its simplicity and readability.\n\n**Why learn Python?**\n- Easy syntax, great for beginners\n- Huge community and library support\n- Used in AI/ML, web dev, data science, IoT\n\n**Basic example:**\n```python\n# Hello World\nprint(\"Hello, World!\")\n\n# Variables\nname = \"Student\"\nage = 15\nprint(f\"My name is {name} and I am {age} years old.\")\n\n# Loop\nfor i in range(5):\n    print(f\"Count: {i}\")\n```\n\n**Popular libraries:** NumPy, Pandas, TensorFlow, Flask",

            'what is machine learning' => "**Machine Learning (ML)** is a subset of AI that enables systems to learn from data and improve without being explicitly programmed.\n\n**Types of ML:**\n1. **Supervised Learning** – Learns from labeled data (classification, regression)\n2. **Unsupervised Learning** – Finds patterns in unlabeled data (clustering)\n3. **Reinforcement Learning** – Learns through trial and error with rewards\n\n**Simple Python example:**\n```python\nfrom sklearn.linear_model import LinearRegression\n\n# Training data\nX = [[1], [2], [3], [4], [5]]\ny = [2, 4, 6, 8, 10]\n\nmodel = LinearRegression()\nmodel.fit(X, y)\nprint(model.predict([[6]]))\n```\n\n**Applications:** Image recognition, voice assistants, recommendation systems",

            'what is micro:bit' => "**micro:bit** is a pocket-sized computer designed to make learning to code easy and fun!\n\n**micro:bit v2 features:**\n- 25 red LEDs (5x5 display)\n- 2 programmable buttons\n- Motion sensor (accelerometer & compass)\n- Temperature sensor\n- Light sensor\n- Bluetooth & USB connectivity\n- Speaker & microphone\n\n**Programming options:**\n- MakeCode (block-based, great for beginners)\n- Python (MicroPython)\n- JavaScript\n\n**Fun project:** Make a step counter using the accelerometer!",
        ];

        $greetings = ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'howdy'];
        $thanks = ['thank', 'thanks', 'thx', 'appreciate'];
        $help = ['help', 'what can you do', 'features', 'capabilities'];

        foreach ($greetings as $g) {
            if (str_contains($lower, $g)) {
                return "Hello! Welcome to **NanoSpark AI Tutor**! 🎓\n\nI'm here to help you learn about:\n- **IoT & Embedded Systems** – Arduino, micro:bit, ESP32\n- **Robotics** – Building and programming robots\n- **Programming** – Python, C++, JavaScript\n- **Machine Learning** – AI concepts and projects\n\nAsk me anything about these topics, or try:\n- \"What is IoT?\"\n- \"Explain Arduino\"\n- \"Tell me about Python\"\n- \"What is machine learning?\"";
            }
        }

        foreach ($thanks as $t) {
            if (str_contains($lower, $t)) {
                return "You're welcome! I'm always here to help. Feel free to ask if you have more questions about IoT, robotics, programming, or any other STEM topics!";
            }
        }

        foreach ($help as $h) {
            if (str_contains($lower, $h)) {
                return "**Here's what I can help you with:**\n\n📚 **Learn Concepts**\n- Ask about IoT, Arduino, micro:bit, ESP32\n- Understand robotics fundamentals\n- Learn Python, C++, or JavaScript\n\n🧠 **Understand Topics**\n- Machine Learning basics\n- Electronics and circuits\n- Programming concepts\n\n💡 **Get Project Ideas**\n- Smart home automation\n- Weather stations\n- Line-following robots\n- And more!\n\nJust type your question and I'll do my best to help!";
            }
        }

        foreach ($knowledge as $key => $value) {
            $words = explode(' ', $key);
            $matchCount = 0;
            foreach ($words as $word) {
                if (str_contains($lower, $word)) {
                    $matchCount++;
                }
            }
            if ($matchCount >= count($words) * 0.6) {
                return $value;
            }
        }

        $topicKeywords = ['explain', 'tell me about', 'what is', 'how does', 'describe', 'define', 'learn', 'teach me'];
        foreach ($topicKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $topic = str_replace($topicKeywords, '', $lower);
                $topic = trim($topic, ' ?!.,:;');
                if (strlen($topic) > 2) {
                    return "**Great question about " . ucfirst($topic) . "!**\n\nThat's an interesting topic. While I have limited built-in knowledge, here are some things I know about related STEM topics:\n\n- **IoT & Sensors** – Connected devices, Arduino, micro:bit\n- **Programming** – Python, C++, JavaScript basics\n- **Robotics** – Motors, sensors, autonomous systems\n- **Machine Learning** – Data, models, predictions\n\nTry asking me about: *Arduino*, *Python*, *IoT*, *Machine Learning*, or *micro:bit* for detailed explanations!";
                }
            }
        }

        $codingKeywords = ['code', 'program', 'function', 'loop', 'variable', 'array', 'class', 'debug', 'error', 'syntax'];
        foreach ($codingKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return "**I'd love to help with coding!**\n\nHere are some programming topics I can explain:\n\n- **Python basics** – variables, loops, functions\n- **Arduino programming** – C++ for microcontrollers\n- **JavaScript** – web development\n- **Debugging tips** – common errors and fixes\n\nTry asking:\n- \"What is Python?\"\n- \"How does Arduino work?\"\n- \"Explain a for loop\"";
            }
        }

        if ($chatType === 'coding') {
            return "**Coding Assistant**\n\nI can help you with programming questions! Here are some areas I can assist with:\n\n- **Python** – syntax, libraries, data structures\n- **Arduino/C++** – embedded programming\n- **JavaScript** – web development\n- **Debugging** – finding and fixing errors\n\nPlease paste your code or describe your coding problem, and I'll do my best to help!";
        }

        if ($chatType === 'quiz') {
            return "**Quiz Generator**\n\nI can help you prepare for quizzes! Tell me a topic and I'll provide:\n- Key concepts to review\n- Practice questions\n- Study tips\n\nTry: *\"Generate quiz on IoT basics\"* or *\"Test me on Python\"*\n\nFor full quiz generation, visit the **AI Quiz Generator** page from the navigation menu!";
        }

        return "Thanks for your message!\n\nI'm your **NanoSpark AI Tutor**, and I can help you with:\n\n- **IoT** – Internet of Things, sensors, connectivity\n- **Arduino** – Hardware, coding, projects\n- **Python** – Programming fundamentals\n- **Robotics** – Building and programming robots\n- **Machine Learning** – AI concepts\n\nTry asking me about one of these topics, for example:\n- \"What is IoT?\"\n- \"Tell me about Python\"\n- \"How does Arduino work?\"\n- \"What is machine learning?\"";
    }
}
