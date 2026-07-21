<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with('school');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $students = $query->latest()->paginate(15);

        $schools = School::where('is_active', true)->orderBy('name')->get();
        $grades = User::where('role', 'student')->whereNotNull('grade')->distinct()->pluck('grade');

        return view('admin.students.index', compact('students', 'schools', 'grades'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.students.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'school_id' => 'nullable|exists:schools,id',
            'grade' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $validated['role'] = 'student';
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        $validated['xp_points'] = 0;
        $validated['level'] = 1;

        User::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(User $student)
    {
        abort_unless($student->isStudent(), 404);

        $student->load('school', 'badges', 'enrollments.course');

        $enrollments = Enrollment::where('user_id', $student->id)
            ->with('course')
            ->latest()
            ->get();

        $completedCourses = $enrollments->where('is_completed', true)->count();
        $inProgressCourses = $enrollments->where('is_completed', false)->count();

        $totalXp = $student->xp_points;
        $totalLessonsCompleted = LessonProgress::where('user_id', $student->id)
            ->where('is_completed', true)
            ->count();

        $quizAttempts = QuizAttempt::where('user_id', $student->id)
            ->with('quiz')
            ->latest()
            ->get();

        $averageScore = $quizAttempts->where('is_passed', true)->isNotEmpty()
            ? round($quizAttempts->where('is_passed', true)->avg('score') / max($quizAttempts->where('is_passed', true)->avg('total_marks'), 1) * 100, 1)
            : 0;

        $badges = $student->badges;

        $xpProgress = $student->xp_points > 0 ? round(($student->xp_points % 500) / 5) : 0;

        $recentActivity = [];
        $recentLessons = LessonProgress::where('user_id', $student->id)->latest()->take(5)->get();
        foreach ($recentLessons as $lp) {
            $recentActivity[] = [
                'message' => 'Completed lesson: ' . ($lp->lesson->title ?? 'Unknown'),
                'time' => $lp->created_at?->diffForHumans() ?? '',
            ];
        }
        foreach ($quizAttempts->take(5) as $qa) {
            $recentActivity[] = [
                'message' => 'Quiz attempt: ' . ($qa->quiz->title ?? 'Unknown') . ' - Score: ' . ($qa->score ?? 0),
                'time' => $qa->created_at?->diffForHumans() ?? '',
            ];
        }
        $recentActivity = collect($recentActivity)->sortByDesc('time')->take(10)->values()->all();

        return view('admin.students.show', compact(
            'student',
            'enrollments',
            'completedCourses',
            'inProgressCourses',
            'totalXp',
            'totalLessonsCompleted',
            'quizAttempts',
            'averageScore',
            'badges',
            'xpProgress',
            'recentActivity'
        ));
    }

    public function edit(User $student)
    {
        abort_unless($student->isStudent(), 404);

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        abort_unless($student->isStudent(), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'grade' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $student->update($validated);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        abort_unless($student->isStudent(), 404);

        if ($student->enrollments()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete student with active enrollments.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
