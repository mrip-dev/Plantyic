<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function create(array $data): Project
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Project::generateSlug($data['name'], $data['workspace_id'] ?? null);
        }

        return Project::create($data);
    }

    public function update(Project $project, array $data): Project
    {
        if (
            isset($data['name']) &&
            $data['name'] !== $project->name &&
            empty($data['slug'])
        ) {
            $data['slug'] = Project::generateSlug($data['name'], $data['workspace_id'] ?? $project->workspace_id);
        }

        $project->update($data);
        return $project;
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }
}
