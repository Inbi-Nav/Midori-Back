<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_get_client_role()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'client'
        ]);
    }

    public function test_user_cannot_register_with_weak_password()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'weak@example.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'client'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_login_fails_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401);
    }
}