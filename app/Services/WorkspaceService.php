<?php

namespace App\Services;

use App\Models\Workspace;

class WorkspaceService
{
    public function create(array $data): Workspace
    {
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
