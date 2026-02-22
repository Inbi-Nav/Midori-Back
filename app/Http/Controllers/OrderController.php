<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {

        return response()-> json(Order::with('user')->get());
    }

    public function myOrders(Request $request) {

         return response()->json (
            $request->user()->orders()->with('items')->get()
         );
    }

    public function store(Request $request, OrderService $orderService) {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $orderService->createOrder($request->user(), $request->items);

        return response()->json($order, 201);
    }

    public function updateStatus(Request $request, Order $order) {
        $request->validate([
            'status' => 'required|string'
        ]);

        $newStatus = $request->status;

        $allowedTransitions = [
            'pending' => ['processing'],
            'processing' => ['shipped'],
            'shipped' => ['delivered'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$order->status] ?? [])) {
            return response()->json([
                'message' => 'Invalid status transition'
            ], 400);
        }

        $order->update(['status' => $newStatus]);

        return response()->json($order);
    }

    public function cancel(Request $request, Order $order, OrderService $orderService)
    {
        try {
            $order = $orderService->cancelOrder($order, $request->user());
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id) {
        $orders = Order::find($id);
        if ($orders) {
            $orders->delete();
            return response()-> json (['message' => 'pedido eliminado']);
        } else {
            return response()-> json (['message' => 'pedido no encontrado'], 404);
        }
    }

}
