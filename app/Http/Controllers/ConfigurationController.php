<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = Configuration::all()->groupBy('group');

        return view('configuration.index', compact('configurations'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*' => 'nullable|string',
        ]);

        foreach ($validated['configs'] as $key => $value) {
            Configuration::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'Configuration mise à jour avec succès.');
    }
}
