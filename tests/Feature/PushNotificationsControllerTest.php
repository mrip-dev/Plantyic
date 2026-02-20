<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushNotificationsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_push_notifications_endpoint_requires_auth()
    {
        $response = $this->getJson('/api/push-notifications');
        $response->assertStatus(401);
    }
}
