<?php

namespace App\Services;

use App\Models\Integration;

class IntegrationService
{
    public function create(array $data): Integration
    {
        return Integration::create($data);
    }

    public function update(Integration $integration, array $data): Integration
    {
        $integration->update($data);
        return $integration;
    }

    public function delete(Integration $integration): void
    {
        $integration->delete();
    }
}
