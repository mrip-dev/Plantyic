<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Settings;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_index_returns_success()
    {
        $response = $this->getJson('/api/settings');
        $response->assertStatus(200);
    }

    public function test_settings_store_validates_input()
    {
        $response = $this->postJson('/api/settings', []);
        $response->assertStatus(422);
    }

    public function test_settings_store_creates_settings()
    {
        $payload = [
            'theme' => 'light',
            'primaryColor' => 'blue',
            'fontFamily' => 'Arial',
            'displayFont' => 'Arial',
            'borderRadius' => 'md',
            'sidebarCompact' => false,
            'animationsEnabled' => true,
            'fontSize' => 'md',
            'language' => 'en',
            'dateFormat' => 'Y-m-d',
            'timeFormat' => 'H:i',
            'weekStartsOn' => 'monday',
            'emailNotifications' => true,
            'pushNotifications' => true,
            'taskReminders' => false,
            'weeklyDigest' => false,
            'teamUpdates' => true,
            'soundEnabled' => false,
            'sidebarOnRight' => false,
            'showBottomBar' => true,
        ];

        $response = $this->postJson('/api/settings', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.theme', 'light');
        $this->assertDatabaseHas('settings', [
            'theme' => 'light',
        ]);
    }

    public function test_settings_show_returns_settings()
    {
        $settings = Settings::factory()->create();
        $response = $this->getJson("/api/settings/{$settings->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $settings->id);
    }

    public function test_settings_update_modifies_settings()
    {
        $settings = Settings::factory()->create();
        $payload = [
            'theme' => 'dark',
            'primaryColor' => $settings->primaryColor,
            'fontFamily' => $settings->fontFamily,
            'displayFont' => $settings->displayFont,
            'borderRadius' => $settings->borderRadius,
            'sidebarCompact' => $settings->sidebarCompact,
            'animationsEnabled' => $settings->animationsEnabled,
            'fontSize' => $settings->fontSize,
            'language' => $settings->language,
            'dateFormat' => $settings->dateFormat,
            'timeFormat' => $settings->timeFormat,
            'weekStartsOn' => $settings->weekStartsOn,
            'emailNotifications' => $settings->emailNotifications,
            'pushNotifications' => $settings->pushNotifications,
            'taskReminders' => $settings->taskReminders,
            'weeklyDigest' => $settings->weeklyDigest,
            'teamUpdates' => $settings->teamUpdates,
            'soundEnabled' => $settings->soundEnabled,
            'sidebarOnRight' => $settings->sidebarOnRight,
            'showBottomBar' => $settings->showBottomBar,
        ];
        $response = $this->putJson("/api/settings/{$settings->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonPath('data.theme', 'dark');
        $this->assertDatabaseHas('settings', [
            'id' => $settings->id,
            'theme' => 'dark',
        ]);
    }

    public function test_settings_delete_removes_settings()
    {
        $settings = Settings::factory()->create();
        $response = $this->deleteJson("/api/settings/{$settings->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Settings deleted successfully']);
        $this->assertDatabaseMissing('settings', [
            'id' => $settings->id,
        ]);
    }
}
