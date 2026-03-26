<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Team;
use App\Http\Requests\Team\TeamRequest;
use App\Services\TeamService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    private function authorizeTeamOwnership(Team $team)
    {
        if ($team->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this team.', 403, false);
        }

        return null;
    }

    private function authorizeProjectOwnership(Project $project)
    {
        if (!$project->workspace || $project->workspace->user_id !== auth()->guard('api')->id()) {
            return $this->apiResponse(null, 'Unauthorized access to this project.', 403, false);
        }

        return null;
    }

    public function index()
    {
        $teams = Team::where('user_id', auth()->guard('api')->id())->get();
        return $this->apiResponse($teams, 'Team list fetched successfully');
    }

    public function store(TeamRequest $request, TeamService $service)
    {
        $team = $service->create($request->validated());
        return $this->apiResponse($team, 'Team created successfully', null, true, 201);
    }

    public function show(Team $team)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        return $this->apiResponse($team, 'Team fetched successfully');
    }

    public function update(TeamRequest $request, Team $team, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        DB::beginTransaction();
        try {
            $updatedTeam = $service->update($team, $request->validated());
            DB::commit();
            return $this->apiResponse($updatedTeam, 'Team updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }

    public function destroy(Team $team, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        $service->delete($team);
        return $this->apiResponse(null, 'Team deleted successfully');
    }

    public function listMembers(Team $team, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        return $this->apiResponse($service->getMembers($team), 'Team members fetched successfully');
    }

    public function addMember(Team $team, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'project_id' => 'nullable|integer|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(), 'Validation failed', 422, false, 422);
        }

        $validated = $validator->validated();

        if (!empty($validated['project_id'])) {
            $project = Project::find($validated['project_id']);
            if (!$project) {
                return $this->apiResponse(null, 'Project not found.', 404, false, 404);
            }

            $projectOwnershipError = $this->authorizeProjectOwnership($project);
            if ($projectOwnershipError) {
                return $projectOwnershipError;
            }
        }

        DB::beginTransaction();
        try {
            $result = $service->inviteMember($team, $validated);
            DB::commit();

            if (($result['type'] ?? null) === 'existing_user') {
                return $this->apiResponse($result, 'Existing user added to team successfully');
            }

            if (($result['type'] ?? null) === 'invited' && !($result['email_sent'] ?? false)) {
                return $this->apiResponse(
                    $result,
                    'Invitation created, but email delivery failed. Please retry email sending later.'
                );
            }

            return $this->apiResponse($result, 'Invitation sent successfully');
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return $this->apiResponse(null, $e->getMessage(), 422, false, 422);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Team invitation query failed', [
                'team_id' => $team->id,
                'error' => $e->getMessage(),
            ]);

            $errorMessage = $e->getMessage();
            if (
                str_contains($errorMessage, 'team_invitations') ||
                str_contains($errorMessage, 'team_members') ||
                str_contains($errorMessage, 'team_projects')
            ) {
                return $this->apiResponse(
                    null,
                    'Invitation tables are missing. Please run database migrations first.',
                    500,
                    false,
                    500
                );
            }

            return $this->apiResponse(null, 'Database error occurred: ' . $errorMessage, 500, false, 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Team invitation failed', [
                'team_id' => $team->id,
                'error' => $e->getMessage(),
            ]);
            return $this->apiResponse(null, 'Database error occurred: ' . $e->getMessage(), 500, false, 500);
        }
    }

    public function updateMember(Team $team, string $memberId, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        $validated = request()->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'avatar' => 'nullable|string',
            'role' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'tasksAssigned' => 'nullable|integer|min:0',
            'tasksCompleted' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $updatedTeam = $service->updateMember($team, $memberId, $validated);
            DB::commit();
            return $this->apiResponse($updatedTeam, 'Team member updated successfully');
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return $this->apiResponse(null, $e->getMessage(), 404, false, 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }

    public function removeMember(Team $team, string $memberId, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        DB::beginTransaction();
        try {
            $updatedTeam = $service->removeMember($team, $memberId);
            DB::commit();
            return $this->apiResponse($updatedTeam, 'Team member removed successfully');
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return $this->apiResponse(null, $e->getMessage(), 404, false, 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }

    public function listProjects(Team $team, TeamService $service)
    {
        $ownershipError = $this->authorizeTeamOwnership($team);
        if ($ownershipError) {
            return $ownershipError;
        }

        return $this->apiResponse($service->getProjects($team), 'Team projects fetched successfully');
    }

    public function assignProject(Team $team, Project $project, TeamService $service)
    {
        $teamOwnershipError = $this->authorizeTeamOwnership($team);
        if ($teamOwnershipError) {
            return $teamOwnershipError;
        }

        $projectOwnershipError = $this->authorizeProjectOwnership($project);
        if ($projectOwnershipError) {
            return $projectOwnershipError;
        }

        DB::beginTransaction();
        try {
            $updatedProject = $service->assignProject($team, $project);
            DB::commit();
            return $this->apiResponse($updatedProject, 'Project assigned to team successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }

    public function unassignProject(Team $team, Project $project, TeamService $service)
    {
        $teamOwnershipError = $this->authorizeTeamOwnership($team);
        if ($teamOwnershipError) {
            return $teamOwnershipError;
        }

        $projectOwnershipError = $this->authorizeProjectOwnership($project);
        if ($projectOwnershipError) {
            return $projectOwnershipError;
        }

        DB::beginTransaction();
        try {
            $updatedProject = $service->unassignProject($team, $project);
            DB::commit();
            return $this->apiResponse($updatedProject, 'Project removed from team successfully');
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return $this->apiResponse(null, $e->getMessage(), 404, false, 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(null, 'Database error occurred.', 500, false);
        }
    }
}
