<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/users/me', [UserController::class, 'me'])->middleware('auth:api');
    Route::put('/users/me', [UserController::class, 'updateMe'])->middleware('auth:api');
    Route::patch('/users/me/password', [UserController::class, 'changePassword'])->middleware('auth:api');

    Route::post('/orders', [OrderController::class, 'store'])
        ->middleware('role:client');

    Route::get('/orders/me', [OrderController::class, 'myOrders'])
        ->middleware('role:client');

    Route::post('/payments', [PaymentController::class, 'store'])
        ->middleware('role:client');

    
    Route::post('/users/request-provider', [UserController::class, 'requestProvider'])
        ->middleware('auth:api', 'role:client');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('role:provider');

    Route::put('/products/{id}', [ProductController::class, 'update'])
        ->middleware('role:provider');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])
        ->middleware('role:provider');

    Route::get('/orders', [OrderController::class, 'index'])
        ->middleware('role:provider');

    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:provider');

    Route::post('/categories', [CategoryController::class, 'store'])
        ->middleware('role:provider');

    Route::put('/categories/{id}', [CategoryController::class, 'update'])
        ->middleware('role:provider');

    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
        ->middleware('role:provider');


    Route::get('/users', [UserController::class, 'index'])
        ->middleware('role:admin');

    Route::get('/users/{id}', [UserController::class, 'show'])
        ->middleware('role:admin');

    Route::put('/users/{id}', [UserController::class, 'update'])
        ->middleware('role:admin');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])
        ->middleware('role:admin');

    Route::get('provider-request', [UserController::class, 'providerRequests'])
        ->middleware('auth:api', 'role:admin');

    Route::patch('/users/{id}/approve-provider', [UserController::class, 'approveProvider'])
        ->middleware('role:admin');

    Route::get('/stats', [AdminController::class, 'stats'])
        ->middleware('role:admin');
});

