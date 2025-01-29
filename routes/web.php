<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Redirect home to the products page
Route::get('/', function () {
    return redirect('/products');
});

// Product routes (CRUD operations)
Route::resource('products', ProductController::class);

// Order processing route
Route::post('orders', [OrderController::class, 'store']);
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
