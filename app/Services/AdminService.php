<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class AdminService
{
    public function getStats()
    {
        return [
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),

            'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                ->where('status', 'delivered')
                ->sum('total_amount'),

            'total_orders' => Order::count(),

            'pending_orders' => Order::where('status', 'pending')->count(),

            'total_users' => User::count(),

            'total_products' => Product::count(),
        ];
    }
}