<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IngredientController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

// 🔹 Redirect Home to Login
Route::get('/', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }
    return redirect('/products');
});

// 🔹 Product Routes (CRUD Operations) - Requires Login
Route::resource('products', ProductController::class);

// 🔹 Order Processing - Requires Login
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// 🔹 Admin Authentication Routes
 Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Show login form
 Route::post('/login', [AuthController::class, 'login']); // Handle login logic
 Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Handle logout


// 🔹 Admin Dashboard - Requires Login
Route::get('/admin', function () {
    if (!Session::has('admin_logged_in') || Session::get('user_role') !== 'Admin') {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    return app(AuthController::class)->dashboard();
})->name('admin.dashboard');

// 🔹 Product Availability Management - Requires Login
Route::patch('/products/{product}/availability', [ProductController::class, 'updateAvailability'])->name('products.updateAvailability');
Route::post('/products/{product}/toggle-availability', [ProductController::class, 'updateAvailability'])->name('products.toggleAvailability');

// 🔹 Admin Product Management - Requires Login
Route::get('/admin/products', function (Illuminate\Http\Request $request) {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }
    return app(ProductController::class)->adminIndex($request);
})->name('admin.products');

// 🔹 Update Admin Credentials - Requires Login
Route::get('/admin/update', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    return app(AuthController::class)->showUpdateCredentials();
})->name('admin.update');

Route::post('/admin/update', [AuthController::class, 'updateCredentials']);
Route::get('/admin/credentials', [AuthController::class, 'showUpdateCredentials'])->name('admin.credentials');

// 🔹 Employee Management (Only Admin) - Requires Login
Route::get('/admin/employees/create', function () {
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    return app(AuthController::class)->showCreateEmployeeForm();
})->name('admin.employees.create');

Route::post('/admin/employees/store', [AuthController::class, 'storeEmployee'])->name('admin.employees.store');

//update product information
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products');

// Category routes
Route::resource('categories', CategoryController::class)->except(['show']);


// 🔹 Admin Employee Management - Requires Login
Route::get('/admin/employees', [AuthController::class, 'manageEmployees'])->name('admin.employees');
Route::post('/admin/employees/{id}/update', [AuthController::class, 'updateEmployee'])->name('admin.employees.update');
Route::post('/admin/employees/{id}/toggle', [AuthController::class, 'toggleEmployeeStatus'])->name('admin.employees.toggle');

Route::post('/admin/employees/{id}/reset-password', [AuthController::class, 'resetPassword'])->name('admin.employees.resetPassword');


// 🔹 Admin Order Management - Requires Login
Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders');
Route::get('/admin/orders/{order}', [OrderController::class, 'adminShow'])->name('admin.orders.show');

// 🔹 User Order History - Requires Login
Route::get('/orders', [OrderController::class, 'userOrders'])->name('user.orders');

Route::get('/orders/{order}', [OrderController::class, 'userOrderShow'])->name('user.orders.show');

// inredient route
Route::resource('ingredients', IngredientController::class)->except(['show']);



// API endpoint for chart

Route::get('/admin/revenue-data', [AuthController::class, 'getRevenueData']);


Route::get('/admin/category-revenue/{categoryId}', [AuthController::class, 'getCategoryRevenue']);

Route::get('/admin/all-categories-revenue', [AuthController::class, 'getAllCategoriesRevenue']);