<?php

namespace App\Services;

use App\Models\Settings;

class SettingsService
{
    public function create(array $data): Settings
    {
        return Settings::create($data);
    }

    public function update(Settings $settings, array $data): Settings
    {
        $settings->update($data);
        return $settings;
    }

    public function delete(Settings $settings): void
    {
        $settings->delete();
    }
}
