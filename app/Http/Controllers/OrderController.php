<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

/**
 * @group Orders
 */
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

    public function store(StoreOrderRequest $request, OrderService $orderService) {
        
        try {

            $order = $orderService->createOrder(
                $request->user(),
                $request->validated()['items']
            );

            return (new OrderResource($order))
                ->response()
                ->setStatusCode(201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

     public function updateStatus(UpdateOrderStatusRequest $request, Order $order) {
        
        $user = $request->user();

        if ($user->role !== 'provider') {
            return response()->json([
                'message' => 'Only providers can update order status'
            ], 403);
        }

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return response()->json([
                'message' => 'Order cannot be modified'
            ], 400);
        }

        $order->update([
            'status' => $request->validated()['status']
        ]);

        return new OrderResource($order->load('items.product'));
    }
    

    public function cancel(Request $request, Order $order, OrderService $orderService) {

        try {
            $order = $orderService->cancelOrder($order, $request->user());
            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(Request $request, Order $order) {
        $user = $request->user();

        if ($user->role !== 'provider') {
            return response()->json([
                'message' => 'Not authorized'
            ], 403);
        }

        if (!in_array($order->status, ['delivered','cancelled'])) {
            return response()->json([
                'message' => 'Only finalized orders can be removed'
            ], 400);
        }

        $order->delete();

        return response()->json([
            'message' => 'Order deleted'
        ]);
    }
}