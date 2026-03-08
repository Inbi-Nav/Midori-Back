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

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users/me', [UserController::class, 'me']);
    Route::put('/users/me', [UserController::class, 'updateMe']);
    Route::patch('/users/me/password', [UserController::class, 'changePassword']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    
    Route::middleware('role:client')->group(function () {

        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/me', [OrderController::class, 'myOrders']);
        Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
        Route::post('/payments', [PaymentController::class, 'store']);
        Route::post('/users/request-provider', [UserController::class, 'requestProvider']);
    });



    Route::middleware('role:provider')->group(function () {

        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    });


    Route::middleware('role:admin')->group(function () {

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        Route::get('/provider-request', [UserController::class, 'providerRequests']);
        Route::patch('/users/{user}/approve-provider', [UserController::class, 'approveProvider']);
        Route::patch('/users/{user}/decline-provider', [UserController::class, 'declineProvider']);

        Route::get('/admin/orders', [AdminController::class, 'orders']);
        Route::get('/admin/products', [AdminController::class, 'products']);
        Route::get('/stats', [AdminController::class, 'stats']);
    });
});