<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChildController extends Controller
{
    public function create(Request $request)
    {
        $parent = $request->user();

        return view('parent.children.create', compact('parent'));
    }

    public function store(Request $request)
    {
        $parent = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'grade' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['role'] = 'student';
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        $validated['xp_points'] = 0;
        $validated['level'] = 1;

        $child = User::create($validated);

        $parent->children()->attach($child->id);

        return redirect()->route('parent.dashboard')
            ->with('success', $child->name . ' has been added to your account.');
    }

    public function edit(Request $request, User $child)
    {
        $parent = $request->user();

        $this->assertChildBelongsToParent($parent, $child);

        return view('parent.children.edit', compact('child'));
    }

    public function update(Request $request, User $child)
    {
        $parent = $request->user();

        $this->assertChildBelongsToParent($parent, $child);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $child->id,
            'password' => 'nullable|string|min:8|confirmed',
            'grade' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $child->update($validated);

        return redirect()->route('parent.child.progress', $child)
            ->with('success', $child->name . "'s details have been updated.");
    }

    public function destroy(Request $request, User $child)
    {
        $parent = $request->user();

        $this->assertChildBelongsToParent($parent, $child);

        $parent->children()->detach($child->id);

        return redirect()->route('parent.dashboard')
            ->with('success', $child->name . ' has been removed from your account.');
    }

    private function assertChildBelongsToParent(User $parent, User $child): void
    {
        abort_unless($parent->children()->where('users.id', $child->id)->exists(), 404);
    }
}