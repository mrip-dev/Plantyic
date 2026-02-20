<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_index_returns_success()
    {
        Task::factory()->count(2)->create();
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_tasks_store_validates_input()
    {
        $response = $this->postJson('/api/tasks', []);
        $response->assertStatus(422);
    }

    public function test_tasks_can_be_created()
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'Test description',
            'time' => '12:00',
            'duration' => 2,
            'priority' => 'medium',
            'tags' => ['api', 'test'],
            'status' => 'pending',
            'date' => '2026-02-20',
            'assignee' => 'John Doe',
            'project' => 'Demo Project',
        ];
        $response = $this->postJson('/api/tasks', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Task']);
    }

    public function test_tasks_can_be_shown()
    {
        $task = Task::factory()->create();
        $response = $this->getJson("/api/tasks/{$task->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $task->title]);
    }

    public function test_tasks_can_be_updated()
    {
        $task = Task::factory()->create();
        $payload = ['title' => 'Updated Title'];
        $response = $this->putJson("/api/tasks/{$task->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);
    }

    public function test_tasks_can_be_deleted()
    {
        $task = Task::factory()->create();
        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
