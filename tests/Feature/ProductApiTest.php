<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_view_products()
    {
        $client = User::factory()->create(['role' => 'client']);
        Passport::actingAs($client);

        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_provider_can_create_product()
    {
        $provider = User::factory()->create(['role' => 'provider']);
        Passport::actingAs($provider);

        $category = Category::factory()->create();

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id
        ]);

        $response->assertStatus(201);
    }

    public function test_provider_cannot_create_product_without_category()
    {
        $provider = User::factory()->create(['role' => 'provider']);
        Passport::actingAs($provider);

        $response = $this->postJson('/api/products', [
            'name' => 'Invalid',
            'price' => 10,
            'stock' => 5
        ]);

        $response->assertStatus(422);
    }

    public function test_provider_cannot_update_other_provider_product()
    {
        $provider1 = User::factory()->create(['role' => 'provider']);
        $provider2 = User::factory()->create(['role' => 'provider']);

        $product = Product::factory()->create(['user_id' => $provider1->id]);

        Passport::actingAs($provider2);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Hacked',
            'price' => 10,
            'stock' => 5
        ]);

        $response->assertStatus(403);
    }
}