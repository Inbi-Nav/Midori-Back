<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return PaymentResource::collection(Payment::all());
    }

    public function show(Payment $payment)
    {
        return new PaymentResource($payment);
    }

    public function store(StorePaymentRequest $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount' => $order->calculateTotal(),
            'payment_method' => $request->payment_method,
            'status' => 'paid',
        ]);

        $order->update([
            'status' => 'processing',
            'total_amount' => $payment->amount,
        ]);

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return new PaymentResource($payment);
    }

    public function destroy(Payment $payment, Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully'
        ]);
    }
}