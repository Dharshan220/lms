<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\StemKit;
use Illuminate\Http\Request;

class StemKitController extends Controller
{
    public function index(Request $request)
    {
        $query = StemKit::where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $kits = $query->latest()->paginate(12);

        $categories = StemKit::where('status', 'published')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('parent.stem-kits.index', compact('kits', 'categories'));
    }

    public function show(StemKit $stemKit)
    {
        abort_unless($stemKit->status === 'published', 404);

        return view('parent.stem-kits.show', compact('stemKit'));
    }
}