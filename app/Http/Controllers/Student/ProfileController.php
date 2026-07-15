<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $student = $request->user()->load(['school', 'badges', 'xpTransactions' => function ($q) {
            $q->latest()->take(20);
        }]);

        $enrollmentsCount = $student->enrollments()->count();
        $completedCount = $student->enrollments()->where('is_completed', true)->count();

        return view('student.profile.show', compact('student', 'enrollmentsCount', 'completedCount'));
    }

    public function edit(Request $request)
    {
        $student = $request->user();

        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $student->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password'], $validated['current_password']);
        }

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars/students', 'public');
        }

        $student->update($validated);

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
