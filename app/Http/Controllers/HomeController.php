<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Course::where('is_published', true)
            ->where('is_featured', true)
            ->with(['teacher', 'category'])
            ->take(8)
            ->get();

        $latestCourses = Course::where('is_published', true)
            ->with(['teacher', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $popularCourses = Course::where('is_published', true)
            ->with(['teacher', 'category'])
            ->orderByDesc('enrollment_count')
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('courses')
            ->orderBy('order')
            ->take(12)
            ->get();

        $stats = [
            'total_courses' => Course::where('is_published', true)->count(),
            'total_students' => \App\Models\User::where('role', 'student')->count(),
            'total_teachers' => \App\Models\User::where('role', 'teacher')->count(),
        ];

        return view('welcome', compact('featuredCourses', 'latestCourses', 'popularCourses', 'categories', 'stats'));
    }

    public function about()
    {
        return view('about');
    }

    public function courses(Request $request)
    {
        $query = Course::where('is_published', true)->with(['teacher', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('price')) {
            switch ($request->price) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'paid':
                    $query->where('price', '>', 0);
                    break;
            }
        }

        $courses = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('courses-public', compact('courses', 'categories'));
    }
}
