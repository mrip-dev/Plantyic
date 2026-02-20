<?php

namespace App\Http\Controllers\Goals;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Http\Requests\Goals\GoalRequest;
use App\Services\GoalService;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::all();
        return $this->apiResponse($goals, 'Goal list fetched successfully');
    }

    public function store(GoalRequest $request, GoalService $service)
    {
        $goal = $service->create($request->validated());
        return $this->apiResponse($goal, 'Goal created successfully', null, true, 201);
    }

    public function show(Goal $goal)
    {
        return $this->apiResponse($goal, 'Goal fetched successfully');
    }

    public function update(GoalRequest $request, Goal $goal, GoalService $service)
    {
        $service->update($goal, $request->validated());
        return $this->apiResponse($goal, 'Goal updated successfully');
    }

    public function destroy(Goal $goal, GoalService $service)
    {
        $service->delete($goal);
        return $this->apiResponse(null, 'Goal deleted successfully');
    }
}
