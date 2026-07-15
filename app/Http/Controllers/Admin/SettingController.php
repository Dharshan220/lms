<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();

        $grouped = $settings->groupBy('group');

        return view('admin.settings.index', compact('settings', 'grouped'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable|string',
            'settings.*.type' => 'required|in:string,integer,float,boolean,json',
            'settings.*.group' => 'required|string',
        ]);

        foreach ($validated['settings'] as $setting) {
            Setting::setValue(
                $setting['key'],
                $setting['value'],
                $setting['type'],
                $setting['group']
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
