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

    public function test_client_can_cancel_pending_order() {

        $client = User::factory()->create(['role' => 'client']);
        $order = Order::factory()->create([
            'user_id' => $client->id,
            'status' => 'pending'
        ]);

        Passport::actingAs($client);

        $response = $this->patchJson("/api/orders/{$order->id}/cancel");

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled'
        ]);
    }

        public function test_client_cannot_cancel_delivered_order() {
        $client = User::factory()->create(['role' => 'client']);
        $order = Order::factory()->create([
            'user_id' => $client->id,
            'status' => 'delivered'
        ]);

        Passport::actingAs($client);

        $response = $this->patchJson("/api/orders/{$order->id}/cancel");
        
        $response->assertStatus(400);
    }
    
    public function test_client_cannot_cancel_someone_elses_order() {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);

        $order = Order::factory()->create([
            'user_id' => $client1->id,
            'status' => 'pending'
        ]);

        Passport::actingAs($client2);

        $response = $this->patchJson("/api/orders/{$order->id}/cancel");
        
        $response->assertStatus(400);
    }

    public function test_provider_can_update_order_status() {
        $provider = User::factory()->create(['role' => 'provider']);
        $order = Order::factory()->create(['status' => 'pending']);

        Passport::actingAs($provider);

        $response = $this->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'processing'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing'
        ]);
    }

        public function test_provider_cannot_modify_delivered_order()
    {
        $provider = User::factory()->create(['role' => 'provider']);
        $order = Order::factory()->create(['status' => 'delivered']);

        Passport::actingAs($provider);

        $response = $this->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'processing'
        ]);

        $response->assertStatus(400);
    }


    public function test_client_cannot_update_order_status() {
        
        $client = User::factory()->create(['role' => 'client']);
        $order = Order::factory()->create(['status' => 'pending']);

        Passport::actingAs($client);

        $response = $this->patchJson("/api/orders/{$order->id}/status", [
            'status' => 'processing'
        ]);

        $response->assertStatus(403);
    }
}