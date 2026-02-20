<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_endpoint_requires_auth()
    {
        $response = $this->getJson('/api/auth/profile');
        $response->assertStatus(401);
    }
}
