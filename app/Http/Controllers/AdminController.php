<?php

namespace App\Http\Controllers;

use App\Services\AdminService;

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
}