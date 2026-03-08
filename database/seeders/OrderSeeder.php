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
        $client = User::where('email', 'Sara@gmail.com')->first();

        if (!$client) {
            $this->command->error('Client user not found.');
            return;
        }

        $products = Product::whereIn('name', [
            'Ceramic Mug',
            'Bamboo Lunch Box',
            'Glass Water Bottle',
            'Matcha Ceremonial Set',
        ])->get();

        if ($products->count() < 4) {
            $this->command->error('Not enough products found.');
            return;
        }


        $order1 = Order::create([
            'user_id' => $client->id,
            'status' => 'delivered',
            'total_amount' => 0,
        ]);

        $total1 = 0;

        foreach ($products->take(2) as $product) {

            $quantity = rand(1, 2);
            $subtotal = $product->price * $quantity;
            $total1 += $subtotal;

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        $order1->update([
            'total_amount' => $total1
        ]);

        Payment::create([
            'order_id' => $order1->id,
            'amount' => $total1,
            'payment_method' => 'credit_card',
            'status' => 'paid',
        ]);


        $product = $products[2];

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

        $product = $products[3];

        $order3 = Order::create([
            'user_id' => $client->id,
            'status' => 'cancelled',
            'total_amount' => $product->price * 2,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);

        $this->command->info('Orders seeded successfully.');
    }
}