<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\School;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['author', 'school', 'course']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $announcements = $query->latest()->paginate(15);
        $schools = School::where('is_active', true)->orderBy('name')->get();

        return view('admin.announcements.index', compact('announcements', 'schools'));
    }

    public function create()
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_published', true)->orderBy('title')->get();

        return view('admin.announcements.create', compact('schools', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'school_id' => 'nullable|exists:schools,id',
            'course_id' => 'nullable|exists:courses,id',
            'priority' => 'required|in:low,medium,high',
            'is_global' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['author_id'] = $request->user()->id;
        $validated['published_at'] = $validated['published_at'] ?? now();

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load(['author', 'school', 'course']);

        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_published', true)->orderBy('title')->get();

        return view('admin.announcements.edit', compact('announcement', 'schools', 'courses'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'school_id' => 'nullable|exists:schools,id',
            'course_id' => 'nullable|exists:courses,id',
            'priority' => 'required|in:low,medium,high',
            'is_global' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
