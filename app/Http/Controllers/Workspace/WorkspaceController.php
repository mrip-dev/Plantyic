<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Http\Requests\Workspace\WorkspaceRequest;
use App\Services\WorkspaceService;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = Workspace::all();
        return $this->apiResponse($workspaces, 'Workspace list fetched successfully');
    }

    public function store(WorkspaceRequest $request, WorkspaceService $service)
    {
        $workspace = $service->create($request->validated());
        return $this->apiResponse($workspace, 'Workspace created successfully', null, true, 201);
    }

    public function show(Workspace $workspace)
    {
        return $this->apiResponse($workspace, 'Workspace fetched successfully');
    }

    public function update(WorkspaceRequest $request, Workspace $workspace, WorkspaceService $service)
    {
        $service->update($workspace, $request->validated());
        return $this->apiResponse($workspace, 'Workspace updated successfully');
    }

    public function destroy(Workspace $workspace, WorkspaceService $service)
    {
        $service->delete($workspace);
        return $this->apiResponse(null, 'Workspace deleted successfully');
    }
}
