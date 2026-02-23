<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Http\Requests\Workspace\WorkspaceRequest;
use App\Services\WorkspaceService;
use Illuminate\Support\Facades\DB;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = Workspace::where('user_id', auth()->guard('api')->id())->get();
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
        /// owenrship check.
        if ($workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this workspace.', 403, false);
        }

        // If the code reaches here, the data check has already PASSED.
        DB::beginTransaction();
        try {
            $updatedWorkspace = $service->update($workspace, $request->validated());
            DB::commit();
            return $this->apiResponse($updatedWorkspace, 'Workspace updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }

    public function destroy(Workspace $workspace, WorkspaceService $service)
    {
        /// owenrship check.
        if ($workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this workspace.', 403, false);
        }
        $service->delete($workspace);
        return $this->apiResponse(null, 'Workspace deleted successfully');
    }
}
