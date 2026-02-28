<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Database\Factories\OrderFactory;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;

class OrderItemController extends Controller {
    public function index()
    {
        return response()->json(OrderItem::with('product')->get());
    }
    public function show($id)
    {
        $item = OrderItem::with('product')->find($id);
        return new OrderItemResource($item);
    }
}