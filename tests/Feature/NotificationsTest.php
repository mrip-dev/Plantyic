<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Notification;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_index_returns_success()
    {
        $response = $this->getJson('/api/notifications');
        $response->assertStatus(200);
    }

    public function test_notifications_store_validates_input()
    {
        $response = $this->postJson('/api/notifications', []);
        $response->assertStatus(422);
    }

    public function test_notifications_store_creates_notification()
    {
        $payload = [
            'type' => 'info',
            'title' => 'Test Notification',
            'message' => 'This is a test notification.',
            'read' => false,
            'createdAt' => now()->toDateString(),
            'link' => 'https://example.com',
            'actor' => [
                'name' => 'John Doe',
                'avatar' => 'avatar.png',
            ],
        ];

        $response = $this->postJson('/api/notifications', $payload);
        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Test Notification');
        $this->assertDatabaseHas('notifications', [
            'title' => 'Test Notification',
        ]);
    }

    public function test_notifications_show_returns_notification()
    {
        $notification = Notification::factory()->create();
        $response = $this->getJson("/api/notifications/{$notification->id}");
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $notification->id);
    }

    public function test_notifications_update_modifies_notification()
    {
        $notification = Notification::factory()->create();
        $payload = [
            'type' => $notification->type,
            'title' => 'Updated Notification',
            'message' => $notification->message,
            'read' => true,
            'createdAt' => now()->toDateString(),
            'link' => $notification->link,
            'actor' => $notification->actor,
        ];
        $response = $this->putJson("/api/notifications/{$notification->id}", $payload);
        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Notification');
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'title' => 'Updated Notification',
        ]);
    }

    public function test_notifications_delete_removes_notification()
    {
        $notification = Notification::factory()->create();
        $response = $this->deleteJson("/api/notifications/{$notification->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => 'Notification deleted successfully']);
        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }
}
