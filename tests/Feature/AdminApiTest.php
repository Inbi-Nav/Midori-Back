<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data']);
    }

    public function test_client_cannot_access_admin_routes()
    {
        $client = User::factory()->create(['role' => 'client']);
        Passport::actingAs($client);

        $response = $this->getJson('/api/users');

        $response->assertStatus(403);
    }

    public function test_admin_cannot_approve_non_existing_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        $response = $this->patchJson('/api/users/999/approve-provider');

        $response->assertStatus(404);
    }
}