<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $query = School::withCount(['users', 'courses']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $schools = $query->latest()->paginate(15);

        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:2048',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        School::create($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', 'School created successfully.');
    }

    public function show(School $school)
    {
        $school->loadCount(['users', 'courses', 'announcements']);

        $teachers = $school->users()->where('role', 'teacher')->latest()->get();
        $students = $school->users()->where('role', 'student')->latest()->get();
        $courses = $school->courses()->with('category')->latest()->get();

        return view('admin.schools.show', compact('school', 'teachers', 'students', 'courses'));
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|max:2048',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        if ($validated['name'] !== $school->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $school->update($validated);

        return redirect()->route('admin.schools.show', $school)
            ->with('success', 'School updated successfully.');
    }

    public function destroy(School $school)
    {
        if ($school->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete school with associated users.');
        }

        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'School deleted successfully.');
    }
}
