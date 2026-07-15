<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher')->with('school');

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

        $teachers = $query->latest()->paginate(15);
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.teachers.index', compact('teachers', 'schools'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.teachers.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $validated['role'] = 'teacher';
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars/teachers', 'public');
        }

        User::create($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function show(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);

        $teacher->load('school');
        $courses = $teacher->courses()->with('category')->latest()->paginate(10);

        $totalStudents = $teacher->courses()->withCount('enrollments')
            ->get()
            ->sum('enrollments_count');

        return view('admin.teachers.show', compact('teacher', 'courses', 'totalStudents'));
    }

    public function edit(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);

        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.teachers.edit', compact('teacher', 'schools'));
    }

    public function update(Request $request, User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars/teachers', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $teacher->update($validated);

        return redirect()->route('admin.teachers.show', $teacher)
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        abort_unless($teacher->isTeacher(), 404);

        if ($teacher->courses()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete teacher with assigned courses.');
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
