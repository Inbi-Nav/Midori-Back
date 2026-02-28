<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_pay_their_order()
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::factory()->create(['stock' => 5]);

        $order = Order::factory()->create([
            'user_id' => $client->id,
            'total_amount' => 10
        ]);

        Passport::actingAs($client);

        $response = $this->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card'
        ]);

        $response->assertStatus(201);
    }

    public function test_client_cannot_pay_someone_else_order()
    {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);

        $order = Order::factory()->create([
            'user_id' => $client1->id
        ]);

        Passport::actingAs($client2);

        $response = $this->postJson('/api/payments', [
            'order_id' => $order->id,
            'payment_method' => 'credit_card'
        ]);

        $response->assertStatus(404);
    }

    public function test_provider_cannot_create_payment()
    {
        $provider = User::factory()->create(['role' => 'provider']);
        Passport::actingAs($provider);

        $response = $this->postJson('/api/payments', []);

        $response->assertStatus(403);
    }
}