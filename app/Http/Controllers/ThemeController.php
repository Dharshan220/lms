<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $user = $request->user();

        $currentTheme = Setting::getValue("theme_{$user->id}", 'light');
        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';

        Setting::setValue("theme_{$user->id}", $newTheme, 'string', 'theme');

        if ($request->ajax()) {
            return response()->json(['theme' => $newTheme]);
        }

        return redirect()->back()->with('success', "Switched to {$newTheme} mode.");
    }
}
