<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use InvalidArgumentException;

class TeamService
{
    public function create(array $data): Team
    {
        if (empty($data['user_id']) && Auth::guard('api')->check()) {
            $data['user_id'] = Auth::guard('api')->id();
        }

        return Team::create($data);
    }

    public function update(Team $team, array $data): Team
    {
        $team->update($data);
        return $team;
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }

    public function getMembers(Team $team): array
    {
        return $team->teamMembers()
            ->with('user')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => (string) $member->id,
                    'user_id' => $member->user_id ? (string) $member->user_id : null,
                    'name' => $member->name,
                    'email' => $member->email,
                    'avatar' => $member->user && $member->user->photo ? asset('storage/' . $member->user->photo) : null,
                    'role' => $member->role,
                    'department' => $member->department,
                    'status' => $member->status,
                    'tasksAssigned' => (int) $member->tasks_assigned,
                    'tasksCompleted' => (int) $member->tasks_completed,
                    'invitation_status' => $member->invitation_status,
                ];
            })
            ->toArray();
    }

    public function inviteMember(Team $team, array $memberData): array
    {
        $email = strtolower(trim($memberData['email']));
        $name = $memberData['name'];
        $project = null;

        if (!empty($memberData['project_id'])) {
            $project = Project::find($memberData['project_id']);
            if (!$project) {
                throw new InvalidArgumentException('Project not found.');
            }
        }

        $existingMember = TeamMember::where('team_id', $team->id)
            ->where('email', $email)
            ->exists();

        if ($existingMember) {
            throw new InvalidArgumentException('This email is already a member of the team.');
        }

        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $updatedTeam = $this->attachUserToTeam($team, $existingUser, $name);
            $updatedProject = null;

            if ($project) {
                $this->assignProject($team, $project);
                $updatedProject = $this->attachUserToProject($project, $existingUser);
            }

            return [
                'type' => 'existing_user',
                'team' => $updatedTeam,
                'project' => $updatedProject,
            ];
        }

        $token = Str::random(64);
        $expiresAt = Carbon::now()->addDays(7);

        DB::table('team_invitations')
            ->where('team_id', $team->id)
            ->where('email', $email)
            ->where('status', 'pending')
            ->delete();

        DB::table('team_invitations')->insert([
            'team_id' => $team->id,
            'project_id' => $project?->id,
            'invited_by' => Auth::guard('api')->id(),
            'name' => $name,
            'email' => $email,
            'token' => $token,
            'status' => 'pending',
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $registrationLink = rtrim(config('app.front_url'), '/')
            . '/login?invitation_token=' . urlencode($token)
            . '&email=' . urlencode($email)
            . '&name=' . urlencode($name);

        $emailSent = false;
        $mailError = null;

        try {
            Mail::send('emails.team-invitation', [
                'name' => $name,
                'team' => $team,
                'registrationLink' => $registrationLink,
                'expiresAt' => $expiresAt,
            ], function ($message) use ($email, $team) {
                $message->to($email)->subject('You are invited to join ' . $team->name);
            });

            $emailSent = true;
        } catch (\Exception $e) {
            $mailError = $e->getMessage();
            Log::error('Team invitation email sending failed', [
                'team_id' => $team->id,
                'email' => $email,
                'error' => $mailError,
            ]);
        }

        return [
            'type' => 'invited',
            'invite_email' => $email,
            'registration_link' => $registrationLink,
            'email_sent' => $emailSent,
            'mail_error' => $mailError,
        ];
    }

    public function addMember(Team $team, array $memberData): Team
    {
        $email = strtolower(trim($memberData['email'] ?? ''));
        if (empty($email)) {
            throw new InvalidArgumentException('Member email is required.');
        }

        if (TeamMember::where('team_id', $team->id)->where('email', $email)->exists()) {
            throw new InvalidArgumentException('A member with this email already exists in the team.');
        }

        TeamMember::create([
            'team_id' => $team->id,
            'email' => $email,
            'name' => $memberData['name'] ?? $email,
            'role' => $memberData['role'] ?? null,
            'department' => $memberData['department'] ?? null,
            'status' => $memberData['status'] ?? 'active',
            'tasks_assigned' => (int) ($memberData['tasksAssigned'] ?? 0),
            'tasks_completed' => (int) ($memberData['tasksCompleted'] ?? 0),
            'invitation_status' => $memberData['invitation_status'] ?? 'accepted',
        ]);

        return $team->fresh();
    }

    public function updateMember(Team $team, string $memberId, array $memberData): Team
    {
        $member = TeamMember::where('team_id', $team->id)->where('id', $memberId)->first();
        if (!$member) {
            throw new InvalidArgumentException('Team member not found.');
        }

        if (!empty($memberData['email'])) {
            $newEmail = strtolower(trim($memberData['email']));
            $emailExists = TeamMember::where('team_id', $team->id)
                ->where('email', $newEmail)
                ->where('id', '!=', $memberId)
                ->exists();

            if ($emailExists) {
                throw new InvalidArgumentException('A member with this email already exists in the team.');
            }

            $member->email = $newEmail;
        }

        $member->name = $memberData['name'] ?? $member->name;
        $member->role = $memberData['role'] ?? $member->role;
        $member->department = $memberData['department'] ?? $member->department;
        $member->status = $memberData['status'] ?? $member->status;
        if (array_key_exists('tasksAssigned', $memberData)) {
            $member->tasks_assigned = (int) $memberData['tasksAssigned'];
        }
        if (array_key_exists('tasksCompleted', $memberData)) {
            $member->tasks_completed = (int) $memberData['tasksCompleted'];
        }

        $member->save();
        return $team->fresh();
    }

    public function removeMember(Team $team, string $memberId): Team
    {
        $deleted = TeamMember::where('team_id', $team->id)
            ->where('id', $memberId)
            ->delete();

        if (!$deleted) {
            throw new InvalidArgumentException('Team member not found.');
        }

        return $team->fresh();
    }

    public function acceptPendingInvitationsForUser(User $user, ?string $invitationToken = null): void
    {
        $query = DB::table('team_invitations')
            ->where('email', strtolower($user->email))
            ->where('status', 'pending');

        if (!empty($invitationToken)) {
            $query->where('token', $invitationToken);
        }

        $invitations = $query->get();

        if (!empty($invitationToken) && $invitations->isEmpty()) {
            throw new InvalidArgumentException('Invalid or expired invitation token.');
        }

        foreach ($invitations as $invitation) {
            if (!empty($invitation->expires_at) && Carbon::parse($invitation->expires_at)->isPast()) {
                DB::table('team_invitations')
                    ->where('id', $invitation->id)
                    ->update([
                        'status' => 'expired',
                        'updated_at' => Carbon::now(),
                    ]);
                continue;
            }

            $team = Team::find($invitation->team_id);
            if (!$team) {
                DB::table('team_invitations')
                    ->where('id', $invitation->id)
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => Carbon::now(),
                    ]);
                continue;
            }

            $this->attachUserToTeam($team, $user, $invitation->name ?: $user->name);

            if (!empty($invitation->project_id)) {
                $project = Project::find($invitation->project_id);
                if ($project) {
                    $this->assignProject($team, $project);
                    $this->attachUserToProject($project, $user);
                }
            }

            DB::table('team_invitations')
                ->where('id', $invitation->id)
                ->update([
                    'status' => 'accepted',
                    'accepted_at' => Carbon::now(),
                    'accepted_user_id' => $user->id,
                    'updated_at' => Carbon::now(),
                ]);
        }
    }

    public function getProjects(Team $team)
    {
        return $team->projects()->get();
    }

    public function assignProject(Team $team, Project $project): Project
    {
        $team->projects()->syncWithoutDetaching([$project->id]);
        return $project->fresh();
    }

    public function unassignProject(Team $team, Project $project): Project
    {
        $isAssigned = $team->projects()->where('project_id', $project->id)->exists();
        if (!$isAssigned) {
            throw new InvalidArgumentException('Project is not assigned to this team.');
        }

        $team->projects()->detach($project->id);
        return $project->fresh();
    }

    private function attachUserToTeam(Team $team, User $user, ?string $name = null): Team
    {
        TeamMember::updateOrCreate(
            [
                'team_id' => $team->id,
                'email' => strtolower($user->email),
            ],
            [
                'user_id' => $user->id,
                'name' => $name ?: $user->name,
                'role' => 'member',
                'department' => null,
                'status' => 'active',
                'tasks_assigned' => 0,
                'tasks_completed' => 0,
                'invitation_status' => 'accepted',
            ]
        );

        return $team->fresh();
    }

    private function attachUserToProject(Project $project, User $user): Project
    {
        $members = $project->members ?? [];
        if (!in_array($user->email, $members, true)) {
            $members[] = $user->email;
            $project->update(['members' => $members]);
        }

        return $project->fresh();
    }
}
