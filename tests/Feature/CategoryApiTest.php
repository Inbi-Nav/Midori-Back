<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Category;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_authenticated_user_can_view_categories()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_provider_can_create_category()
    {
        $provider = User::factory()->create(['role' => 'provider']);
        Passport::actingAs($provider);

        $response = $this->postJson('/api/categories', [
            'name' => 'New Category',
            'description' => 'Test description'
        ]);

        $response->assertStatus(201);
    }

    public function test_client_cannot_create_category()
    {
        $client = User::factory()->create(['role' => 'client']);
        Passport::actingAs($client);

        $response = $this->postJson('/api/categories', [
            'name' => 'New Category'
        ]);

        $response->assertStatus(403);
    }

    public function test_category_requires_name()
    {
        $provider = User::factory()->create(['role' => 'provider']);
        Passport::actingAs($provider);

        $response = $this->postJson('/api/categories', []);

        $response->assertStatus(422);
    }
}