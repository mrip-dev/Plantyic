<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationMemberController extends Controller
{
    public function getUserOrganizations(Request $request, int $userId): JsonResponse
    {

        $targetUser = User::query()->select(['id', 'default_organization_id'])->findOrFail($userId);

        $organizations = Organization::query()
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhereHas('members', fn ($memberQuery) => $memberQuery->where('users.id', $userId));
            })
            ->withCount(['members', 'workspaces'])
            ->with([
                'organizationMembers' => fn ($query) => $query
                    ->where('user_id', $userId)
                    ->select(['id', 'organization_id', 'user_id', 'role', 'joined_at'])
                    ->limit(1),
            ])
            ->orderByDesc('created_at')
            ->get();

        $data = $organizations->map(function (Organization $organization) use ($userId, $targetUser) {
            $isOwner = (int) $organization->user_id === $userId;
            $memberRecord = $organization->organizationMembers->first();

            return [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'description' => $organization->description,
                '_count' => [
                    'members' => $organization->members_count,
                    'workspaces' => $organization->workspaces_count,
                ],
                'userRole' => $isOwner ? 'OWNER' : ($memberRecord->role ?? 'MEMBER'),
                'joinedAt' => $memberRecord->joined_at ?? $organization->created_at,
                'isOwner' => $isOwner,
                'isDefault' => (int) $targetUser->default_organization_id === (int) $organization->id,
            ];
        })->values();


         return $this->apiResponse($data, 'Organization list fetched successfully');
    }
}
