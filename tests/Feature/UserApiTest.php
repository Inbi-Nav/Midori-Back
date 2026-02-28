<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_resource_is_secured()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/users/me');

        $response->assertStatus(200)
                 ->assertJsonMissing(['password'])
                 ->assertJsonMissing(['remember_token']);
    }

    public function test_admin_can_see_sensitive_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'client']);

        Passport::actingAs($admin);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertJsonStructure([
            'data' => ['created_at', 'updated_at']
        ]);
    }
}