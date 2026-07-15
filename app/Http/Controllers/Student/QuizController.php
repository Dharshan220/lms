<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $student = request()->user();

        $quiz->load(['course', 'questions']);

        $attempts = QuizAttempt::where('user_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->orderBy('started_at', 'desc')
            ->get();

        $attemptsRemaining = $quiz->max_attempts - $attempts->count();

        $bestAttempt = $attempts->sortByDesc('score')->first();

        return view('student.quizzes.show', compact('quiz', 'attempts', 'attemptsRemaining', 'bestAttempt'));
    }

    public function start(Request $request, Quiz $quiz)
    {
        $student = $request->user();

        $existingAttempts = QuizAttempt::where('user_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($existingAttempts >= $quiz->max_attempts) {
            return redirect()->route('student.quizzes.show', $quiz)
                ->with('error', 'You have used all available attempts for this quiz.');
        }

        $incompleteAttempt = QuizAttempt::where('user_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->whereNull('completed_at')
            ->first();

        if ($incompleteAttempt) {
            $attempt = $incompleteAttempt;
        } else {
            $attempt = QuizAttempt::create([
                'user_id' => $student->id,
                'quiz_id' => $quiz->id,
                'score' => 0,
                'total_marks' => $quiz->questions->sum('marks'),
                'is_passed' => false,
                'started_at' => now(),
            ]);
        }

        $quiz->load('questions');

        return view('student.quizzes.attempt', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $student = $request->user();

        $attempt = QuizAttempt::where('id', $request->attempt_id)
            ->where('user_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.selected_answer' => 'required|string|in:A,B,C,D',
        ]);

        $quiz->load('questions');
        $totalMarks = 0;
        $score = 0;

        foreach ($validated['answers'] as $answerData) {
            $question = QuizQuestion::findOrFail($answerData['question_id']);
            $isCorrect = $question->correct_answer === $answerData['selected_answer'];

            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $question->id,
                'selected_answer' => $answerData['selected_answer'],
                'is_correct' => $isCorrect,
                'marks_obtained' => $isCorrect ? $question->marks : 0,
            ]);

            $totalMarks += $question->marks;
            if ($isCorrect) {
                $score += $question->marks;
            }
        }

        $timeTaken = $attempt->started_at->diffInSeconds(now());

        $isPassed = $score >= $quiz->passing_marks;

        $attempt->update([
            'score' => $score,
            'total_marks' => $totalMarks,
            'is_passed' => $isPassed,
            'completed_at' => now(),
            'time_taken_seconds' => $timeTaken,
        ]);

        if ($isPassed) {
            $student->increment('xp_points', 25);
        }

        return redirect()->route('student.quizzes.show', $quiz)
            ->with('success', $isPassed ? 'Congratulations! You passed the quiz! +25 XP' : 'Quiz completed. You did not meet the passing marks.');
    }
}
