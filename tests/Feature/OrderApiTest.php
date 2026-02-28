<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_order()
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::factory()->create(['stock'=> 5]);

        Passport::actingAs($client);

        $response = $this->postJson('/api/orders', [
            'items'=> [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ]);

        $response->assertStatus(201);
    }

    public function test_cannot_create_order_with_insufficient_stock()
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::factory()->create(['stock' => 1]);

        Passport::actingAs($client);

        $response = $this->postJson('/api/orders', [
            'items'=> [
                [
                    'product_id' => $product->id,
                    'quantity' => 5
                ]
            ]
        ]);

        $response->assertStatus(400);
    }

    public function test_client_can_view_only_their_orders()
    {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);

        Order::factory()->create(['user_id' => $client1->id]);
        Order::factory()->create(['user_id' => $client2->id]);

        Passport::actingAs($client1);

        $response = $this->getJson('/api/orders/me');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_client_cannot_access_provider_orders_endpoint()
    {
        $client = User::factory()->create(['role' => 'client']);
        Passport::actingAs($client);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(403);
    }
}