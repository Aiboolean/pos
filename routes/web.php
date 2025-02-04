<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Session;
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


//login
// Admin Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Dashboard Route
Route::get('/admin', [AuthController::class, 'dashboard'])->name('admin.dashboard');


// Update Credentials (Only accessible if logged in)
Route::get('/admin/update', [AuthController::class, 'showUpdateCredentials'])->name('admin.update');
Route::post('/admin/update', [AuthController::class, 'updateCredentials']);
Route::get('/admin/credentials', [AuthController::class, 'showUpdateCredentials'])->name('admin.credentials');
