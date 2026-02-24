<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function stats()
    {
        $stats = $this->adminService->getStats();
        return response()->json($stats);
    }

    public function orders()
    {
        $orders = Order::with('user') 
            ->latest() 
            ->get();
        
        return response()->json($orders);
    }

    public function showOrder($id)
    {
        $order = Order::with('user', 'items.product')
            ->findOrFail($id);
        
        return response()->json($order);
    }
}