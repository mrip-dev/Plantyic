<?php

namespace App\Services;

use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class WorkspaceService
{
    public function create(array $data): Workspace
    {

        // If no explicit owner provided, assign the currently authenticated user (if any)
        if (empty($data['user_id']) && Auth::guard('api')->check()) {
            $data['user_id'] = Auth::guard('api')->id();

        }

        return Workspace::create($data);
    }

    public function update(Workspace $workspace, array $data): Workspace
    {

        $workspace->update($data);
        return $workspace;
    }

    public function delete(Workspace $workspace): void
    {
        $workspace->delete();
    }
}
