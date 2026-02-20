<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Http\Requests\Team\TeamRequest;
use App\Services\TeamService;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return $this->apiResponse($teams, 'Team list fetched successfully');
    }

    public function store(TeamRequest $request, TeamService $service)
    {
        $team = $service->create($request->validated());
        return $this->apiResponse($team, 'Team created successfully', null, true, 201);
    }

    public function show(Team $team)
    {
        return $this->apiResponse($team, 'Team fetched successfully');
    }

    public function update(TeamRequest $request, Team $team, TeamService $service)
    {
        $service->update($team, $request->validated());
        return $this->apiResponse($team, 'Team updated successfully');
    }

    public function destroy(Team $team, TeamService $service)
    {
        $service->delete($team);
        return $this->apiResponse(null, 'Team deleted successfully');
    }
}
