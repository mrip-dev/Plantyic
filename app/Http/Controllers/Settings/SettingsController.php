<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Http\Requests\Settings\SettingsRequest;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::all();
        return response()->json(['data' => $settings]);
    }

    public function store(SettingsRequest $request, SettingsService $service)
    {
        $settings = $service->create($request->validated());
        return response()->json(['data' => $settings], 201);
    }

    public function show(Settings $settings)
    {
        return response()->json(['data' => $settings]);
    }

    public function update(SettingsRequest $request, Settings $settings, SettingsService $service)
    {
        $service->update($settings, $request->validated());
        return response()->json(['data' => $settings]);
    }

    public function destroy(Settings $settings, SettingsService $service)
    {
        $service->delete($settings);
        return response()->json(['message' => 'Settings deleted successfully']);
    }
}
