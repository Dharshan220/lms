<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuizGeneratorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $courses = Course::where('is_published', true)
            ->where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)
                  ->orWhere('id', '>', 0);
            })
            ->orderBy('title')
            ->get();

        $questions = session('generated_questions');
        $validated = session('quiz_form_data');
        $course = isset($validated['course_id']) ? Course::find($validated['course_id']) : null;

        return view('ai.quiz-generator', compact('courses', 'questions', 'validated', 'course'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'topic' => 'required|string|max:255',
            'num_questions' => 'required|integer|min:1|max:50',
            'difficulty' => 'required|in:easy,medium,hard,beginner,intermediate,advanced',
            'question_type' => 'required|in:mcq,true_false,mixed',
        ]);

        $difficultyMap = [
            'beginner' => 'easy',
            'intermediate' => 'medium',
            'advanced' => 'hard',
        ];
        if (isset($difficultyMap[$validated['difficulty']])) {
            $validated['difficulty'] = $difficultyMap[$validated['difficulty']];
        }

        $course = Course::findOrFail($validated['course_id']);
        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));

        if (!empty($apiKey)) {
            $questions = $this->generateWithApi($apiKey, $validated, $course);
        } else {
            $questions = $this->generateBuiltIn($validated, $course);
        }

        if (!empty($questions)) {
            session(['generated_questions' => $questions, 'quiz_form_data' => $validated]);
            return back();
        }

        return back()->withInput()->with('error', 'Failed to generate questions. Please try again.');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'questions' => 'nullable|array',
        ]);

        $questions = session('generated_questions');
        $formData = session('quiz_form_data');

        if (empty($questions)) {
            return back()->with('error', 'No questions to save. Please generate a quiz first.');
        }

        $course = Course::find($formData['course_id'] ?? null);
        if (!$course) {
            return back()->with('error', 'Course not found.');
        }

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => ucfirst($formData['topic'] ?? 'AI Generated Quiz'),
            'description' => "Auto-generated quiz on {$formData['topic']} ({$formData['difficulty']})",
            'time_limit_minutes' => count($questions) * 2,
            'passing_marks' => ceil(count($questions) * 0.5),
            'max_attempts' => 3,
            'is_published' => true,
        ]);

        foreach ($questions as $index => $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $q['question'] ?? '',
                'question_type' => $q['question_type'] ?? 'mcq',
                'option_a' => $q['option_a'] ?? $q['options'][0] ?? null,
                'option_b' => $q['option_b'] ?? $q['options'][1] ?? null,
                'option_c' => $q['option_c'] ?? $q['options'][2] ?? null,
                'option_d' => $q['option_d'] ?? $q['options'][3] ?? null,
                'correct_answer' => $q['correct_answer'] ?? 'A',
                'explanation' => $q['explanation'] ?? null,
                'marks' => $q['marks'] ?? 1,
                'order_number' => $index + 1,
            ]);
        }

        session()->forget(['generated_questions', 'quiz_form_data']);

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz saved successfully with ' . count($questions) . ' questions!');
    }

    private function generateWithApi(string $apiKey, array $validated, Course $course): ?array
    {
        $prompt = $this->buildPrompt($validated, $course);

        try {
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-3.5-flash-lite:generateContent?key={$apiKey}", [
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
                    return $this->parseQuestions($generatedContent);
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Quiz Generator API error: ' . $e->getMessage());
            return null;
        }
    }

    private function generateBuiltIn(array $validated, Course $course): array
    {
        $topic = strtolower($validated['topic']);
        $numQuestions = min($validated['num_questions'], 10);
        $type = $validated['question_type'];

        $questionBank = $this->getQuestionBank($topic, $course->title);
        $questions = [];

        if (!empty($questionBank)) {
            $shuffled = collect($questionBank)->shuffle();
            foreach ($shuffled->take($numQuestions) as $q) {
                $questions[] = $q;
            }
        }

        while (count($questions) < $numQuestions) {
            $questions[] = $this->generateGenericQuestion($topic, $course->title, count($questions) + 1, $type);
        }

        return array_slice($questions, 0, $numQuestions);
    }

    private function getQuestionBank(string $topic, string $courseTitle): array
    {
        $banks = [
            'iot' => [
                ['question' => 'What does IoT stand for?', 'question_type' => 'mcq', 'option_a' => 'Internet of Things', 'option_b' => 'Input/Output Technology', 'option_c' => 'Internal Online Tools', 'option_d' => 'Integrated Operating Terminal', 'correct_answer' => 'A', 'explanation' => 'IoT stands for Internet of Things, referring to connected physical devices.', 'marks' => 1],
                ['question' => 'Which protocol is commonly used for IoT device communication?', 'question_type' => 'mcq', 'option_a' => 'MQTT', 'option_b' => 'FTP', 'option_c' => 'SMTP', 'option_d' => 'HTTP only', 'correct_answer' => 'A', 'explanation' => 'MQTT (Message Queuing Telemetry Transport) is a lightweight messaging protocol designed for IoT.', 'marks' => 1],
                ['question' => 'IoT devices collect data through sensors.', 'question_type' => 'true_false', 'option_a' => 'True', 'option_b' => 'False', 'option_c' => null, 'option_d' => null, 'correct_answer' => 'A', 'explanation' => 'Sensors are the primary way IoT devices gather environmental data.', 'marks' => 1],
                ['question' => 'Which of these is NOT an IoT device?', 'question_type' => 'mcq', 'option_a' => 'Smart thermostat', 'option_b' => 'Traditional analog watch', 'option_c' => 'Fitness tracker', 'option_d' => 'Smart speaker', 'correct_answer' => 'B', 'explanation' => 'A traditional analog watch has no internet connectivity or sensors.', 'marks' => 1],
                ['question' => 'What is edge computing in IoT?', 'question_type' => 'mcq', 'option_a' => 'Processing data near the source device', 'option_b' => 'Storing all data in the cloud', 'option_c' => 'Using only wired connections', 'option_d' => 'Running software on a tablet', 'correct_answer' => 'A', 'explanation' => 'Edge computing processes data closer to where it is generated, reducing latency.', 'marks' => 1],
            ],
            'arduino' => [
                ['question' => 'What language is primarily used to program Arduino?', 'question_type' => 'mcq', 'option_a' => 'C/C++', 'option_b' => 'Python', 'option_c' => 'Java', 'option_d' => 'JavaScript', 'correct_answer' => 'A', 'explanation' => 'Arduino uses a C/C++ based language for programming microcontrollers.', 'marks' => 1],
                ['question' => 'How many digital I/O pins does an Arduino Uno have?', 'question_type' => 'mcq', 'option_a' => '14', 'option_b' => '8', 'option_c' => '20', 'option_d' => '6', 'correct_answer' => 'A', 'explanation' => 'Arduino Uno has 14 digital I/O pins (6 of which support PWM).', 'marks' => 1],
                ['question' => 'The setup() function in Arduino runs only once.', 'question_type' => 'true_false', 'option_a' => 'True', 'option_b' => 'False', 'option_c' => null, 'option_d' => null, 'correct_answer' => 'A', 'explanation' => 'setup() runs once when the Arduino is powered on or reset.', 'marks' => 1],
                ['question' => 'What does PWM stand for in Arduino?', 'question_type' => 'mcq', 'option_a' => 'Pulse Width Modulation', 'option_b' => 'Power Wattage Management', 'option_c' => 'Programmable Wire Mode', 'option_d' => 'Parallel Working Memory', 'correct_answer' => 'A', 'explanation' => 'PWM simulates analog output by rapidly switching digital signals on and off.', 'marks' => 1],
                ['question' => 'Which pin is typically used for the built-in LED on Arduino Uno?', 'question_type' => 'mcq', 'option_a' => 'Pin 13', 'option_b' => 'Pin 1', 'option_c' => 'Pin 7', 'option_d' => 'Pin 5', 'correct_answer' => 'A', 'explanation' => 'The built-in LED on Arduino Uno is connected to digital pin 13.', 'marks' => 1],
            ],
            'python' => [
                ['question' => 'Which of the following is used to define a function in Python?', 'question_type' => 'mcq', 'option_a' => 'def', 'option_b' => 'function', 'option_c' => 'func', 'option_d' => 'define', 'correct_answer' => 'A', 'explanation' => 'Python uses the "def" keyword to define functions.', 'marks' => 1],
                ['question' => 'Python is a compiled language.', 'question_type' => 'true_false', 'option_a' => 'False', 'option_b' => 'True', 'option_c' => null, 'option_d' => null, 'correct_answer' => 'A', 'explanation' => 'Python is an interpreted language, not compiled.', 'marks' => 1],
                ['question' => 'What symbol is used for single-line comments in Python?', 'question_type' => 'mcq', 'option_a' => '#', 'option_b' => '//', 'option_c' => '/* */', 'option_d' => '--', 'correct_answer' => 'A', 'explanation' => 'Python uses # for single-line comments.', 'marks' => 1],
                ['question' => 'Which data type is [1, 2, 3] in Python?', 'question_type' => 'mcq', 'option_a' => 'List', 'option_b' => 'Tuple', 'option_c' => 'Array', 'option_d' => 'Dictionary', 'correct_answer' => 'A', 'explanation' => 'Square brackets [] create a list in Python.', 'marks' => 1],
                ['question' => 'What does pip stand for in Python?', 'question_type' => 'mcq', 'option_a' => 'Pip Installs Packages', 'option_b' => 'Python Internal Protocol', 'option_c' => 'Program Interface Parser', 'option_d' => 'Package Integration Platform', 'correct_answer' => 'A', 'explanation' => 'pip is Python\'s package installer for managing libraries.', 'marks' => 1],
            ],
            'robotics' => [
                ['question' => 'What type of sensor detects the distance to an object using sound waves?', 'question_type' => 'mcq', 'option_a' => 'Ultrasonic sensor', 'option_b' => 'Temperature sensor', 'option_c' => 'Light sensor', 'option_d' => 'Pressure sensor', 'correct_answer' => 'A', 'explanation' => 'Ultrasonic sensors emit sound waves and measure the time for the echo to return.', 'marks' => 1],
                ['question' => 'A servo motor can rotate to specific angles.', 'question_type' => 'true_false', 'option_a' => 'True', 'option_b' => 'False', 'option_c' => null, 'option_d' => null, 'correct_answer' => 'A', 'explanation' => 'Servo motors are designed to rotate to precise angular positions.', 'marks' => 1],
                ['question' => 'What is the purpose of an H-bridge in robotics?', 'question_type' => 'mcq', 'option_a' => 'Control motor direction', 'option_b' => 'Measure temperature', 'option_c' => 'Display output', 'option_d' => 'Store data', 'correct_answer' => 'A', 'explanation' => 'An H-bridge circuit allows a DC motor to be driven in both directions.', 'marks' => 1],
                ['question' => 'Which sensor is commonly used in line-following robots?', 'question_type' => 'mcq', 'option_a' => 'Infrared (IR) sensor', 'option_b' => 'Accelerometer', 'option_c' => 'Gyroscope', 'option_d' => 'Microphone', 'correct_answer' => 'A', 'explanation' => 'IR sensors detect the contrast between a dark line and a light surface.', 'marks' => 1],
                ['question' => 'What does PID stand for in robot control?', 'question_type' => 'mcq', 'option_a' => 'Proportional Integral Derivative', 'option_b' => 'Programmable Input Device', 'option_c' => 'Power Internal Drive', 'option_d' => 'Position Indicator Display', 'correct_answer' => 'A', 'explanation' => 'PID is a control loop mechanism used for precise motor control.', 'marks' => 1],
            ],
            'machine learning' => [
                ['question' => 'What is supervised learning?', 'question_type' => 'mcq', 'option_a' => 'Learning from labeled data', 'option_b' => 'Learning without any data', 'option_c' => 'Learning from unlabeled data only', 'option_d' => 'Learning by reinforcement only', 'correct_answer' => 'A', 'explanation' => 'Supervised learning uses labeled training data to learn input-output mappings.', 'marks' => 1],
                ['question' => 'A neural network has only one hidden layer.', 'question_type' => 'true_false', 'option_a' => 'False', 'option_b' => 'True', 'option_c' => null, 'option_d' => null, 'correct_answer' => 'A', 'explanation' => 'Neural networks can have multiple hidden layers (deep learning).', 'marks' => 1],
                ['question' => 'Which algorithm is used for classification?', 'question_type' => 'mcq', 'option_a' => 'Decision Tree', 'option_b' => 'Linear Regression', 'option_c' => 'K-Means Clustering', 'option_d' => 'PCA', 'correct_answer' => 'A', 'explanation' => 'Decision trees are commonly used for classification tasks.', 'marks' => 1],
                ['question' => 'What does overfitting mean in ML?', 'question_type' => 'mcq', 'option_a' => 'Model performs well on training data but poorly on new data', 'option_b' => 'Model is too simple', 'option_c' => 'Model trains too slowly', 'option_d' => 'Model uses too little data', 'correct_answer' => 'A', 'explanation' => 'Overfitting occurs when a model memorizes training data instead of learning general patterns.', 'marks' => 1],
                ['question' => 'What is the purpose of a training dataset?', 'question_type' => 'mcq', 'option_a' => 'To teach the model patterns', 'option_b' => 'To test the final model', 'option_c' => 'To deploy the model', 'option_d' => 'To clean the data', 'correct_answer' => 'A', 'explanation' => 'Training data is used to teach the model to recognize patterns.', 'marks' => 1],
            ],
            'microbit' => [
                ['question' => 'How many LEDs does the micro:bit have?', 'question_type' => 'mcq', 'option_a' => '25', 'option_b' => '16', 'option_c' => '9', 'option_d' => '36', 'correct_answer' => 'A', 'explanation' => 'The micro:bit has a 5x5 LED matrix = 25 LEDs.', 'marks' => 1],
                ['question' => 'The micro:bit has a built-in accelerometer.', 'question_type' => 'true_false', 'option_a' => 'True', 'option_b' => 'False', 'option_c' => null, 'option_d' => null, 'correct_answer' => 'A', 'explanation' => 'The micro:bit includes a built-in accelerometer for motion detection.', 'marks' => 1],
                ['question' => 'Which programming languages can be used with micro:bit?', 'question_type' => 'mcq', 'option_a' => 'MakeCode, Python, JavaScript', 'option_b' => 'Only Java', 'option_c' => 'Only C++', 'option_d' => 'Only Scratch', 'correct_answer' => 'A', 'explanation' => 'micro:bit supports MakeCode (blocks), MicroPython, and JavaScript.', 'marks' => 1],
                ['question' => 'What wireless protocol does micro:bit support?', 'question_type' => 'mcq', 'option_a' => 'Bluetooth Low Energy', 'option_b' => 'Wi-Fi', 'option_c' => 'NFC', 'option_d' => 'RFID', 'correct_answer' => 'A', 'explanation' => 'micro:bit uses Bluetooth Low Energy (BLE) for wireless communication.', 'marks' => 1],
                ['question' => 'How many buttons does the micro:bit have?', 'question_type' => 'mcq', 'option_a' => '2 (A and B)', 'option_b' => '3', 'option_c' => '4', 'option_d' => '1', 'correct_answer' => 'A', 'explanation' => 'The micro:bit has two programmable buttons labeled A and B.', 'marks' => 1],
            ],
        ];

        $allQuestions = [];
        foreach ($banks as $bankTopic => $questions) {
            if (str_contains($topic, $bankTopic) || str_contains($bankTopic, $topic)) {
                $allQuestions = array_merge($allQuestions, $questions);
            }
        }

        if (empty($allQuestions)) {
            foreach ($banks as $bankTopic => $questions) {
                $allQuestions = array_merge($allQuestions, $questions);
            }
        }

        return $allQuestions;
    }

    private function generateGenericQuestion(string $topic, string $courseTitle, int $num, string $type): array
    {
        $templates = [
            ['question' => "What is a key concept in {$topic}?", 'option_a' => "Core principle", 'option_b' => "Unrelated topic", 'option_c' => "Random concept", 'option_d' => "Wrong answer", 'correct_answer' => 'A', 'explanation' => "The core principle is fundamental to understanding {$topic}.", 'marks' => 1],
            ['question' => "Which of the following best describes {$topic}?", 'option_a' => "A field of study or technology", 'option_b' => "A type of food", 'option_c' => "A weather pattern", 'option_d' => "A musical instrument", 'correct_answer' => 'A', 'explanation' => "{$topic} is a field of study/technology, not the other options.", 'marks' => 1],
            ['question' => "{$topic} is an important topic in STEM education.", 'option_a' => 'True', 'option_b' => 'False', 'correct_answer' => 'A', 'explanation' => "STEM education covers Science, Technology, Engineering, and Mathematics, including {$topic}.", 'marks' => 1],
        ];

        $template = $templates[($num - 1) % count($templates)];
        $template['question_type'] = ($type === 'true_false' || ($type === 'mixed' && $num % 3 === 0)) ? 'true_false' : 'mcq';

        if ($template['question_type'] === 'true_false') {
            $template['option_c'] = null;
            $template['option_d'] = null;
        }

        return $template;
    }

    private function buildPrompt(array $data, Course $course): string
    {
        $difficultyMap = [
            'easy' => 'basic recall and understanding',
            'medium' => 'application and analysis',
            'hard' => 'synthesis, evaluation, and complex problem-solving',
        ];

        $typeMap = [
            'mcq' => 'multiple choice (4 options: A, B, C, D)',
            'true_false' => 'true/false',
            'mixed' => 'mixed format (multiple choice and true/false)',
        ];

        $prompt = "Generate {$data['num_questions']} quiz questions about \"{$data['topic']}\" for the course \"{$course->title}\".\n\n";
        $prompt .= "Difficulty Level: {$difficultyMap[$data['difficulty']]}\n";
        $prompt .= "Question Format: {$typeMap[$data['question_type']]}\n\n";
        $prompt .= "Please generate the questions in the following JSON format:\n";
        $prompt .= "[\n";
        $prompt .= "  {\n";
        $prompt .= "    \"question\": \"Question text here\",\n";
        $prompt .= "    \"question_type\": \"mcq\" or \"true_false\",\n";
        $prompt .= "    \"option_a\": \"Option A\",\n";
        $prompt .= "    \"option_b\": \"Option B\",\n";
        $prompt .= "    \"option_c\": \"Option C (null if true/false)\",\n";
        $prompt .= "    \"option_d\": \"Option D (null if true/false)\",\n";
        $prompt .= "    \"correct_answer\": \"A\",\n";
        $prompt .= "    \"explanation\": \"Brief explanation of the correct answer\",\n";
        $prompt .= "    \"marks\": 1\n";
        $prompt .= "  }\n";
        $prompt .= "]\n\n";
        $prompt .= "Return ONLY the JSON array, no additional text or markdown code blocks.";

        return $prompt;
    }

    private function parseQuestions(string $content): array
    {
        $content = trim($content);
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);

        $questions = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $questions;
    }
}
