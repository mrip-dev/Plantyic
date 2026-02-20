<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Workspace;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_index_returns_success()
    {
        $response = $this->getJson('/api/workspace');
        $response->assertStatus(200);
    }

    public function test_workspace_store_validates_input()
    {
        $response = $this->postJson('/api/workspace', []);
        $response->assertStatus(422);
    }

    public function test_workspace_store_creates_workspace()
    {
        $payload = [
            'name' => 'Test Workspace',
            'description' => 'A test workspace',
            'icon' => 'icon.png',
            'color' => 'blue',
            'plan' => 'pro',
        ];

        $response = $this->postJson('/api/workspace', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Workspace');
        $this->assertDatabaseHas('workspaces', [
            'name' => 'Test Workspace',
        ]);
    }

    public function test_workspace_show_returns_workspace()
    {
        $workspace = Workspace::factory()->create();
        $response = $this->getJson("/api/workspace/{$workspace->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $workspace->id);
    }

    public function test_workspace_update_modifies_workspace()
    {
        $workspace = Workspace::factory()->create();
        $payload = [
            'name' => 'Updated Workspace',
            'description' => $workspace->description,
            'icon' => $workspace->icon,
            'color' => $workspace->color,
            'plan' => $workspace->plan,
        ];
        $response = $this->putJson("/api/workspace/{$workspace->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Workspace');
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'name' => 'Updated Workspace',
        ]);
    }

    public function test_workspace_delete_removes_workspace()
    {
        $workspace = Workspace::factory()->create();
        $response = $this->deleteJson("/api/workspace/{$workspace->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Workspace deleted successfully']);
        $this->assertDatabaseMissing('workspaces', [
            'id' => $workspace->id,
        ]);
    }
}
