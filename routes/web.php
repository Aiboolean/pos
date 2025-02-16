<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

// ----------------------
// 🔹 Redirect Home to Login
// ----------------------
Route::get('/', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }
    return redirect('/products');
});

// ----------------------
// 🔹 Product Routes (CRUD Operations) - Requires Login
// ----------------------
Route::resource('products', ProductController::class);

// ----------------------
// 🔹 Order Processing - Requires Login
// ----------------------
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// ----------------------
// 🔹 Admin Authentication Routes
// ----------------------
 Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Show login form
 Route::post('/login', [AuthController::class, 'login']); // Handle login logic
 Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Handle logout

// ----------------------
// 🔹 Admin Dashboard - Requires Login
// ----------------------
Route::get('/admin', function () {
    if (!Session::has('admin_logged_in') || Session::get('user_role') !== 'Admin') {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    return app(AuthController::class)->dashboard();
})->name('admin.dashboard');

// ----------------------
// 🔹 Product Availability Management - Requires Login
// ----------------------
Route::patch('/products/{product}/availability', [ProductController::class, 'updateAvailability'])->name('products.updateAvailability');
Route::post('/products/{product}/toggle-availability', [ProductController::class, 'updateAvailability'])->name('products.toggleAvailability');

// ----------------------
// 🔹 Admin Product Management - Requires Login
// ----------------------
Route::get('/admin/products', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }
    return app(ProductController::class)->adminIndex();
})->name('admin.products');

// ----------------------
// 🔹 Update Admin Credentials - Requires Login
// ----------------------
Route::get('/admin/update', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    return app(AuthController::class)->showUpdateCredentials();
})->name('admin.update');

Route::post('/admin/update', [AuthController::class, 'updateCredentials']);
Route::get('/admin/credentials', [AuthController::class, 'showUpdateCredentials'])->name('admin.credentials');

// ----------------------
// 🔹 Employee Management (Only Admin) - Requires Login
// ----------------------
Route::get('/admin/employees/create', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    return app(AuthController::class)->showCreateEmployeeForm();
})->name('admin.employees.create');

Route::post('/admin/employees/store', [AuthController::class, 'storeEmployee'])->name('admin.employees.store');

