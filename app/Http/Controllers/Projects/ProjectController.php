<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workspace;
use App\Http\Requests\Projects\ProjectRequest;
use App\Services\ProjectService;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::whereHas('workspace', function ($query) {
            $query->where('user_id', auth()->guard('api')->id());
        })->get();

        return $this->apiResponse($projects, 'Project list fetched successfully');
    }

    public function store(ProjectRequest $request, ProjectService $service)
    {
        $data = $request->validated();

        if (empty($data['workspace_id'])) {
            return $this->apiResponse(null, 'Workspace ID is required.', 422, false, 422);
        }

        $workspace = Workspace::find($data['workspace_id']);
        if (!$workspace || $workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this workspace.', 403, false);
        }

        $project = $service->create($data);
        return $this->apiResponse($project, 'Project created successfully', null, true, 201);
    }

    public function show(Project $project)
    {
        if (!$project->workspace || $project->workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this project.', 403, false);
        }

        return $this->apiResponse($project, 'Project fetched successfully');
    }

    public function update(ProjectRequest $request, Project $project, ProjectService $service)
    {
        if (!$project->workspace || $project->workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this project.', 403, false);
        }

        $data = $request->validated();
        if (!empty($data['workspace_id'])) {
            $workspace = Workspace::find($data['workspace_id']);
            if (!$workspace || $workspace->user_id !== auth()->guard('api')->id()) {
                return $this->apiResponse(null, 'Unauthorized access to this workspace.', 403, false);
            }
        }

        DB::beginTransaction();
        try {
            $updatedProject = $service->update($project, $data);
            DB::commit();
            return $this->apiResponse($updatedProject, 'Project updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }

    public function destroy(Project $project, ProjectService $service)
    {
        if (!$project->workspace || $project->workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this project.', 403, false);
        }

        $service->delete($project);
        return $this->apiResponse(null, 'Project deleted successfully');
    }
}
