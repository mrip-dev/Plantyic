<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Team;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_index_returns_success()
    {
        $response = $this->getJson('/api/team');
        $response->assertStatus(200);
    }

    public function test_team_store_validates_input()
    {
        $response = $this->postJson('/api/team', []);
        $response->assertStatus(422);
    }

    public function test_team_store_creates_team()
    {
        $payload = [
            'name' => 'Test Team',
            'members' => [
                [
                    'id' => '1',
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'avatar' => 'avatar.png',
                    'role' => 'Developer',
                    'department' => 'Engineering',
                    'status' => 'active',
                    'tasksAssigned' => 5,
                    'tasksCompleted' => 3,
                ]
            ],
            'color' => 'blue',
        ];

        $response = $this->postJson('/api/team', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Test Team');
        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
        ]);
    }

    public function test_team_show_returns_team()
    {
        $team = Team::factory()->create();
        $response = $this->getJson("/api/team/{$team->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $team->id);
    }

    public function test_team_update_modifies_team()
    {
        $team = Team::factory()->create();
        $payload = [
            'name' => 'Updated Team',
            'members' => $team->members,
            'color' => 'red',
        ];
        $response = $this->putJson("/api/team/{$team->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Team');
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'Updated Team',
        ]);
    }

    public function test_team_delete_removes_team()
    {
        $team = Team::factory()->create();
        $response = $this->deleteJson("/api/team/{$team->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Team deleted successfully']);
        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
        ]);
    }
}
