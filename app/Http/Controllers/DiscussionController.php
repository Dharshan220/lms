<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function index(Request $request)
    {
        $query = Discussion::with(['user', 'course', 'lesson', 'replies.user']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('resolved')) {
            $query->where('is_resolved', $request->resolved === 'true');
        }

        $discussions = $query->latest()->paginate(20);

        return view('discussions.index', compact('discussions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['is_resolved'] = false;

        $discussion = Discussion::create($validated);

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Discussion created successfully.');
    }

    public function show(Discussion $discussion)
    {
        $discussion->load(['user', 'course', 'lesson', 'replies.user' => function ($q) {
            $q->select('id', 'name', 'avatar', 'role');
        }]);

        $replies = $discussion->replies()->latest()->get();

        return view('discussions.show', compact('discussion', 'replies'));
    }

    public function discussionReplies(Discussion $discussion)
    {
        $replies = $discussion->replies()
            ->with('user:id,name,avatar,role')
            ->latest()
            ->get();

        return response()->json(['replies' => $replies]);
    }

    public function addReply(Request $request, Discussion $discussion)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'is_solution' => 'boolean',
        ]);

        $reply = DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'is_solution' => $request->boolean('is_solution', false),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'reply' => $reply->load('user:id,name,avatar,role'),
            ]);
        }

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Reply posted successfully.');
    }
}
