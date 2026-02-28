<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return OrderResource::collection(
                Order::with('items.product')->latest()->get()
            );
        }

        if ($user->role === 'provider') {
            $orders = Order::whereHas('items.product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('items.product')->get();

            return OrderResource::collection($orders);
        }

        return response()->json(['message' => 'Not authorized'], 403);
    }

    public function myOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }

    public function store(Request $request, OrderService $orderService) {

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {

            $order = $orderService->createOrder($request->user(), $request->items);

            return (new OrderResource($order))
                ->response()
                ->setStatusCode(201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:processing,shipped,delivered'
        ]);

        $user = $request->user();

        if ($user->role !== 'provider') {
            return response()->json(['message' => 'Only providers can update order status'], 403);
        }

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return response()->json(['message' => 'Order cannot be modified'], 400);
        }

        $order->update(['status' => $request->status]);

        return new OrderResource($order->load('items.product'));
    }

    public function cancel(Request $request, Order $order, OrderService $orderService)
    {
        $order = $orderService->cancelOrder($order, $request->user());
        return new OrderResource($order->load('items.product'));
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}