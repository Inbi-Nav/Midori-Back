<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return PaymentResource::collection(Payment::all());
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return new PaymentResource($payment);
    }

    public function store(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->calculateTotal(),
            'payment_method' => $request->payment_method,
            'status' => 'paid',
        ]);

        $order->status = 'paid';
        $order->total_amount = $payment->amount;
        $order->save();

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->all());

        return new PaymentResource($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted']);
    }
}