<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Integration;

class IntegrationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_integrations_index_returns_success()
    {
        $response = $this->getJson('/api/integrations');
        $response->assertStatus(200);
    }

    public function test_integrations_store_validates_input()
    {
        $response = $this->postJson('/api/integrations', []);
        $response->assertStatus(422);
    }

    public function test_integrations_store_creates_integration()
    {
        $payload = [
            'name' => 'Test Integration',
            'description' => 'A test integration',
            'icon' => 'icon.png',
            'connected' => true,
            'scopes' => ['read', 'write'],
            'lastSync' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/integrations', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Integration');
        $this->assertDatabaseHas('integrations', [
            'name' => 'Test Integration',
        ]);
    }

    public function test_integrations_show_returns_integration()
    {
        $integration = Integration::factory()->create();
        $response = $this->getJson("/api/integrations/{$integration->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $integration->id);
    }

    public function test_integrations_update_modifies_integration()
    {
        $integration = Integration::factory()->create();
        $payload = [
            'name' => 'Updated Integration',
            'description' => $integration->description,
            'icon' => $integration->icon,
            'connected' => false,
            'scopes' => $integration->scopes,
            'lastSync' => now()->toDateString(),
        ];
        $response = $this->putJson("/api/integrations/{$integration->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Integration');
        $this->assertDatabaseHas('integrations', [
            'id' => $integration->id,
            'name' => 'Updated Integration',
        ]);
    }

    public function test_integrations_delete_removes_integration()
    {
        $integration = Integration::factory()->create();
        $response = $this->deleteJson("/api/integrations/{$integration->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Integration deleted successfully']);
        $this->assertDatabaseMissing('integrations', [
            'id' => $integration->id,
        ]);
    }
}
