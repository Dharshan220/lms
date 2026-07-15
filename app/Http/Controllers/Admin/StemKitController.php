<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StemKit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StemKitController extends Controller
{
    public function index(Request $request)
    {
        $query = StemKit::withCount('courses');

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

        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        $stemKits = $query->latest()->paginate(15);

        return view('admin.stem-kits.index', compact('stemKits'));
    }

    public function create()
    {
        return view('admin.stem-kits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'components' => 'nullable|array',
            'components.*' => 'string|max:255',
            'is_available' => 'boolean',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('stemkits', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', true);

        StemKit::create($validated);

        return redirect()->route('admin.stem-kits.index')
            ->with('success', 'STEM Kit created successfully.');
    }

    public function show(StemKit $stemKit)
    {
        $stemKit->load('courses');

        return view('admin.stem-kits.show', compact('stemKit'));
    }

    public function edit(StemKit $stemKit)
    {
        return view('admin.stem-kits.edit', compact('stemKit'));
    }

    public function update(Request $request, StemKit $stemKit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'components' => 'nullable|array',
            'components.*' => 'string|max:255',
            'is_available' => 'boolean',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validated['name'] !== $stemKit->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('stemkits', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');

        $stemKit->update($validated);

        return redirect()->route('admin.stem-kits.show', $stemKit)
            ->with('success', 'STEM Kit updated successfully.');
    }

    public function destroy(StemKit $stemKit)
    {
        if ($stemKit->courses()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete STEM kit linked to courses.');
        }

        $stemKit->delete();

        return redirect()->route('admin.stem-kits.index')
            ->with('success', 'STEM Kit deleted successfully.');
    }
}
