<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\Projects\ProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response()->json(['data' => $projects]);
    }

    public function store(ProjectRequest $request, ProjectService $service)
    {
        $project = $service->create($request->validated());
        return response()->json(['data' => $project], 201);
    }

    public function show(Project $project)
    {
        return response()->json(['data' => $project]);
    }

    public function update(ProjectRequest $request, Project $project, ProjectService $service)
    {
        $service->update($project, $request->validated());
        return response()->json(['data' => $project]);
    }

    public function destroy(Project $project, ProjectService $service)
    {
        $service->delete($project);
        return response()->json(['message' => 'Project deleted successfully']);
    }
}
