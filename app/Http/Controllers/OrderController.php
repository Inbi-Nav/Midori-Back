<?php

namespace App\Http\Controllers;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;

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

        $allowedTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped'],
            'shipped' => ['delivered'],
        ];

        $newStatus = $request->status;

        if (!in_array($newStatus, $allowedTransitions[$order->status] ?? [])) {
            return response()->json([
                'message' => 'Cambio de estado no permitido'
            ], 400);
        }

        $order->update(['status' => $newStatus]);

        return response()->json($order);
    }

    public function cancel(Order $order, Request $request, OrderService $orderService) {
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
