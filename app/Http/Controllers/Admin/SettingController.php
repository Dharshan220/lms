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

        $settingsArray = $settings->pluck('value', 'key')->toArray();

        return view('admin.settings.index', ['settings' => $settingsArray, 'grouped' => $grouped]);
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');

        $flatKeys = collect($request->except(['_token', 'group']))
            ->filter(fn ($value, $key) => !in_array($key, ['logo', 'favicon']));

        foreach ($flatKeys as $key => $value) {
            $type = 'string';
            if (is_bool($value)) $type = 'boolean';
            elseif (is_numeric($value)) $type = 'integer';
            elseif (is_array($value)) $type = 'json';

            Setting::setValue($key, $value, $type, $group);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::setValue('logo', $path, 'string', 'appearance');
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('favicons', 'public');
            Setting::setValue('favicon', $path, 'string', 'appearance');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
