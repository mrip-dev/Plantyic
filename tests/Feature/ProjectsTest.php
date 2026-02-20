<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_index_returns_success()
    {
        Project::factory()->count(2)->create();
        $response = $this->getJson('/api/projects');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_projects_store_validates_input()
    {
        $response = $this->postJson('/api/projects', []);
        $response->assertStatus(422);
    }

    public function test_projects_can_be_created()
    {
        $payload = [
            'name' => 'Demo Project',
            'description' => 'Project description',
            'completed' => false,
            'members' => ['Alice', 'Bob'],
            'status' => 'active',
            'dueDate' => '2026-03-01',
            'createdAt' => '2026-02-20',
        ];
        $response = $this->postJson('/api/projects', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Demo Project']);
    }

    public function test_projects_can_be_shown()
    {
        $project = Project::factory()->create();
        $response = $this->getJson("/api/projects/{$project->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $project->name]);
    }

    public function test_projects_can_be_updated()
    {
        $project = Project::factory()->create();
        $payload = ['name' => 'Updated Project'];
        $response = $this->putJson("/api/projects/{$project->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Project']);
    }

    public function test_projects_can_be_deleted()
    {
        $project = Project::factory()->create();
        $response = $this->deleteJson("/api/projects/{$project->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
