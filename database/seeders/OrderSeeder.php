<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('role', 'client')->first();
        $products = Product::take(3)->get();

        // Order 1 - Delivered
        $order1 = Order::create([
            'user_id' => $client->id,
            'status' => 'delivered',
            'total_amount' => 0,
        ]);

        $total1 = 0;

        foreach ($products as $product) {
            $quantity = 1;
            $subtotal = $product->price * $quantity;
            $total1 += $subtotal;

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        $order1->update(['total_amount' => $total1]);

        Payment::create([
            'order_id' => $order1->id,
            'amount' => $total1,
            'payment_method' => 'credit_card',
            'status' => 'paid',
        ]);


        $product = Product::first();
        
        $order2 = Order::create([
            'user_id' => $client->id,
            'status' => 'pending',
            'total_amount' => $product->price,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);


        $order3 = Order::create([
            'user_id' => $client->id,
            'status' => 'cancelled',
            'total_amount' => $product->price,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
    }
}