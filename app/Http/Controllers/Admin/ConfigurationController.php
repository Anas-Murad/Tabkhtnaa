<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        return Configuration::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => ['required'],
            'config_key' => ['required'],
            'config_value' => ['required'],
            'config_bool' => ['required'],
        ]);

        return Configuration::create($request->validated());
    }

    public function show(Configuration $configuration)
    {
        return $configuration;
    }

    public function update(Request $request, Configuration $configuration)
    {
        $request->validate([
            'country_id' => ['required'],
            'config_key' => ['required'],
            'config_value' => ['required'],
            'config_bool' => ['required'],
        ]);

        $configuration->update($request->validated());

        return $configuration;
    }

    public function destroy(Configuration $configuration)
    {
        $configuration->delete();

        return response()->json();
    }
}
