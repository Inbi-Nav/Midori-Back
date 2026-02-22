<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder($user, array $items)
    {
        return DB::transaction(function () use ($user, $items) {

            $total = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            foreach ($items as $item) {

                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $order->update(['total_amount' => $total]);

            return $order->load('items.product');
        });
    }

    public function cancelOrder(Order $order, $user) {
        
        if ($order->user_id !== $user->id) {
            throw new \Exception("No autorizado");
        }

        if ($order->status !== 'pending') {
            throw new \Exception("Solo se pueden cancelar pedidos pendientes");
        }

        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);

        return $order->load('items.product');
    }
}
?>