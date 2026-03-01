<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;

class AdminStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_stats()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Passport::actingAs($admin);

        $response = $this->getJson('/api/stats');

        $response->assertStatus(200);
    }

    public function test_client_cannot_view_stats()
    {
        $client = User::factory()->create(['role' => 'client']);
        Passport::actingAs($client);

        $response = $this->getJson('/api/stats');

        $response->assertStatus(403);
    }
}