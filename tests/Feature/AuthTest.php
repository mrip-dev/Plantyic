<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_requires_fields()
    {
        $response = $this->postJson('/api/auth/register/customer', []);
        $response->assertStatus(422);
    }

    public function test_login_fails_with_wrong_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'wrong@example.com',
            'password' => 'invalid',
        ]);
        $response->assertStatus(401);
    }
}
