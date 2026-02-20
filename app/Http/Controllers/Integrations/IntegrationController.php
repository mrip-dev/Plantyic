<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Http\Requests\Integrations\IntegrationRequest;
use App\Services\IntegrationService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index()
    {
        $integrations = Integration::all();
        return response()->json(['data' => $integrations]);
    }

    public function store(IntegrationRequest $request, IntegrationService $service)
    {
        $integration = $service->create($request->validated());
        return response()->json(['data' => $integration], 201);
    }

    public function show(Integration $integration)
    {
        return response()->json(['data' => $integration]);
    }

    public function update(IntegrationRequest $request, Integration $integration, IntegrationService $service)
    {
        $service->update($integration, $request->validated());
        return response()->json(['data' => $integration]);
    }

    public function destroy(Integration $integration, IntegrationService $service)
    {
        $service->delete($integration);
        return response()->json(['message' => 'Integration deleted successfully']);
    }
}
