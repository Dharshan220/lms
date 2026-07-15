<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\StemKit;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $results = collect();

        if (strlen($query) < 2) {
            return view('search.index', compact('query', 'type', 'results'));
        }

        $searchQuery = "%{$query}%";

        switch ($type) {
            case 'courses':
                $results = Course::where('is_published', true)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', $searchQuery)
                          ->orWhere('description', 'like', $searchQuery);
                    })
                    ->with(['teacher', 'category'])
                    ->latest()
                    ->paginate(15);
                break;

            case 'lessons':
                $results = Lesson::where('is_published', true)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', $searchQuery)
                          ->orWhere('description', 'like', $searchQuery);
                    })
                    ->with('course')
                    ->latest()
                    ->paginate(15);
                break;

            case 'stemkits':
                $results = StemKit::where(function ($q) use ($searchQuery) {
                        $q->where('name', 'like', $searchQuery)
                          ->orWhere('description', 'like', $searchQuery);
                    })
                    ->latest()
                    ->paginate(15);
                break;

            case 'categories':
                $results = Category::where('name', 'like', $searchQuery)
                    ->withCount('courses')
                    ->orderBy('name')
                    ->paginate(15);
                break;

            default:
                $courses = Course::where('is_published', true)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', $searchQuery)
                          ->orWhere('description', 'like', $searchQuery);
                    })
                    ->with(['teacher', 'category'])
                    ->take(5)
                    ->get();

                $lessons = Lesson::where('is_published', true)
                    ->where(function ($q) use ($searchQuery) {
                        $q->where('title', 'like', $searchQuery)
                          ->orWhere('description', 'like', $searchQuery);
                    })
                    ->with('course')
                    ->take(5)
                    ->get();

                $stemKits = StemKit::where(function ($q) use ($searchQuery) {
                        $q->where('name', 'like', $searchQuery)
                          ->orWhere('description', 'like', $searchQuery);
                    })
                    ->take(5)
                    ->get();

                $results = [
                    'courses' => $courses,
                    'lessons' => $lessons,
                    'stemkits' => $stemKits,
                ];
                break;
        }

        return view('search.index', compact('query', 'type', 'results'));
    }
}
