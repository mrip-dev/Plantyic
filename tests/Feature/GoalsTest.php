<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Goal;

class GoalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_goals_index_returns_success()
    {
        Goal::factory()->count(2)->create();
        $response = $this->getJson('/api/goals');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_goals_store_validates_input()
    {
        $response = $this->postJson('/api/goals', []);
        $response->assertStatus(422);
    }

    public function test_goals_can_be_created()
    {
        $payload = [
            'title' => 'Test Goal',
            'description' => 'Test description',
            'progress' => 10,
            'target' => 100,
            'category' => 'Personal',
            'dueDate' => '2026-03-01',
            'milestones' => ['Start', 'Finish'],
        ];
        $response = $this->postJson('/api/goals', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Goal']);
    }

    public function test_goals_can_be_shown()
    {
        $goal = Goal::factory()->create();
        $response = $this->getJson("/api/goals/{$goal->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $goal->title]);
    }

    public function test_goals_can_be_updated()
    {
        $goal = Goal::factory()->create();
        $payload = ['title' => 'Updated Goal'];
        $response = $this->putJson("/api/goals/{$goal->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Goal']);
    }

    public function test_goals_can_be_deleted()
    {
        $goal = Goal::factory()->create();
        $response = $this->deleteJson("/api/goals/{$goal->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('goals', ['id' => $goal->id]);
    }
}
