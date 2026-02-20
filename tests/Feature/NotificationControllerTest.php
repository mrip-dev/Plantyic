<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
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
}
