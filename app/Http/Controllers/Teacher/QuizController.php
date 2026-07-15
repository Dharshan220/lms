<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();

        $query = Quiz::whereIn('course_id', $teacher->courses()->pluck('id'))
            ->with(['course', 'questions', 'attempts']);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $quizzes = $query->latest()->paginate(15);

        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
        $courses = $request->user()->courses()->where('is_published', true)->orderBy('title')->get();

        return view('teacher.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $teacher = $request->user();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
            'max_attempts' => 'required|integer|min:1',
            'is_published' => 'boolean',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        abort_unless($course->teacher_id === $teacher->id, 403);

        $validated['is_published'] = $request->boolean('is_published', false);

        $quiz = Quiz::create($validated);

        if ($request->has('questions')) {
            foreach ($request->questions as $index => $questionData) {
                $quiz->questions()->create([
                    'question' => $questionData['question'],
                    'question_type' => $questionData['question_type'] ?? 'mcq',
                    'option_a' => $questionData['option_a'] ?? null,
                    'option_b' => $questionData['option_b'] ?? null,
                    'option_c' => $questionData['option_c'] ?? null,
                    'option_d' => $questionData['option_d'] ?? null,
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'marks' => $questionData['marks'] ?? 1,
                    'order_number' => $index + 1,
                ]);
            }
        }

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully.');
    }

    public function show(Quiz $quiz)
    {
        $teacher = request()->user();
        abort_unless($quiz->course->teacher_id === $teacher->id, 403);

        $quiz->load(['course', 'questions', 'attempts.user']);

        return view('teacher.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $teacher = request()->user();
        abort_unless($quiz->course->teacher_id === $teacher->id, 403);

        $courses = $teacher->courses()->where('is_published', true)->orderBy('title')->get();
        $quiz->load('questions');

        return view('teacher.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $teacher = $request->user();
        abort_unless($quiz->course->teacher_id === $teacher->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
            'max_attempts' => 'required|integer|min:1',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $quiz->update($validated);

        if ($request->has('questions')) {
            $quiz->questions()->delete();

            foreach ($request->questions as $index => $questionData) {
                $quiz->questions()->create([
                    'question' => $questionData['question'],
                    'question_type' => $questionData['question_type'] ?? 'mcq',
                    'option_a' => $questionData['option_a'] ?? null,
                    'option_b' => $questionData['option_b'] ?? null,
                    'option_c' => $questionData['option_c'] ?? null,
                    'option_d' => $questionData['option_d'] ?? null,
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'marks' => $questionData['marks'] ?? 1,
                    'order_number' => $index + 1,
                ]);
            }
        }

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully.');
    }
}
