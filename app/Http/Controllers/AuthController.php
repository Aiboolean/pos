<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $user = DB::table('users')->where('username', $request->username)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        // Check if the user is active
        if (!$user->is_active) {
            return back()->with('error', 'Your account is disabled.');
        }

        // Set session for login
        Session::put('admin_logged_in', true);
        Session::put('user_role', $user->role);
        Session::put('user_id', $user->id);
        Session::save();

        // Redirect based on role
        if ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('products.index');
        }
    }

    return back()->with('error', 'Invalid credentials');
}

public function logout()
{
    Session::forget('admin_logged_in');
    return redirect('/login')->with('success', 'You have been logged out.');
}


    public function showUpdateCredentials()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return view('admin.update_credentials');
    }

    public function updateCredentials(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        DB::table('users')->where('id', Session::get('user_id'))->update([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);

        return redirect('/admin')->with('success', 'Credentials updated successfully.');
    }

    public function dashboard()
{
    if (!Session::has('user_id') || Session::get('user_role') !== 'Admin') {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    // Fetch analytics data
    $totalOrders = Order::count();
    $totalRevenue = Order::sum('total_price');
    $totalSalesToday = Order::whereDate('created_at', now()->toDateString())
                            ->sum('total_price');
    $bestSeller = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                            ->groupBy('product_id')
                            ->orderByDesc('total_quantity')
                            ->with('product')
                            ->first();
//Fetch Categories and Products
    $categories = Category::with('products')->get();
    $products = Product::all();
    // Fetch Admin + Employees for Cashier dropdown
    $cashiers = DB::table('users')
                ->whereIn('role', ['Admin', 'Employee'])
                ->where('is_active', 1) // only active accounts
                ->get();
    // Pass analytics data to the view
    return view('admin.dashboard', [
        'totalOrders' => $totalOrders,
        'totalRevenue' => $totalRevenue,
        'totalSalesToday' => $totalSalesToday,
        'bestSeller' => $bestSeller,
        'categories' => $categories,
        'products' => $products,
        'cashiers' => $cashiers, // new
    ]);
}




public function showCreateEmployeeForm()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }
    
    // Generate the next 5-digit Employee ID
    $lastEmployee = DB::table('users')->latest('id')->first();
    $nextEmployeeId = $lastEmployee ? str_pad($lastEmployee->id + 1, 5, '0', STR_PAD_LEFT) : '00001';

    return view('admin.add_employee', ['employee_id' => $nextEmployeeId]);
}

public function storeEmployee(Request $request)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => [
            'required',
            'regex:/^\+63\s9\d{2}\s\d{3}\s\d{4}$/',
            'unique:users,phone'
        ],
    ], [
        'phone.regex' => 'Phone number must be in the format +63 9XX XXX XXXX.',
        'phone.unique' => 'The phone number is already registered.',
    ]);
    // Generate username
    $username = strtolower($request->first_name . '.' . $request->last_name);
    
    // Generate password from first and last name
    $password = strtolower($request->first_name . $request->last_name);

    // Get the next Employee ID (auto-incremented)
    $lastEmployee = DB::table('users')->latest('id')->first();
    $nextEmployeeId = $lastEmployee ? str_pad($lastEmployee->id + 1, 5, '0', STR_PAD_LEFT) : '00001';

    DB::table('users')->insert([
        'first_name' => strtoupper($request->first_name),
        'last_name' => strtoupper($request->last_name),
        'phone' => $request->phone,
        'username' => $username,
        'password' => Hash::make($password),
        'role' => 'Employee',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.employees')->with('success', "Employee added. Username: $username, Password: $password");
}

public function manageEmployees()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $employees = DB::table('users')->where('role', 'Employee')->paginate(10); // Added pagination
    return view('admin.employees', compact('employees'));
}

public function updateEmployee(Request $request, $id)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    // Validate the request data
    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'username' => 'required|unique:users,username,' . $id,
        'phone' => 'required|unique:users,phone,' . $id,
    ]);

    // Update the employee's details
    DB::table('users')->where('id', $id)->update([
        'first_name' => strtoupper($request->first_name),
        'last_name' => strtoupper($request->last_name),
        'username' => $request->username,
        'phone' => $request->phone,
    ]);

    return redirect()->route('admin.employees')->with('success', 'Employee updated successfully.');
}
public function toggleEmployeeStatus($id)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    $employee = DB::table('users')->where('id', $id)->first();
    if (!$employee) {
        return redirect()->back()->with('error', 'Employee not found.');
    }

    DB::table('users')->where('id', $id)->update([
        'is_active' => !$employee->is_active,
    ]);

    return redirect()->route('admin.employees')->with('success', 'Employee status updated.');
}
public function resetPassword($id)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'Unauthorized access.');
    }

    // Fetch the employee's details
    $employee = DB::table('users')->where('id', $id)->first();
    if (!$employee) {
        return redirect()->back()->with('error', 'Employee not found.');
    }

    // Generate the initial password
    $initialPassword = strtolower($employee->first_name . $employee->last_name);

    // Update the employee's password
    DB::table('users')->where('id', $id)->update([
        'password' => Hash::make($initialPassword),
    ]);

    return redirect()->route('admin.employees')->with('success', 'Password reset successfully. New password: ' . $initialPassword);
}

// Admin Dashboard chart data
public function getRevenueData(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ]);
    
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    // Make sure the end date includes the entire day
    $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
    
    // Fetch revenue data grouped by date - no caching
    $revenueData = Order::whereBetween('created_at', [$startDate, $endDate])
                        ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
    
    // Prepare data for the chart
    $labels = $revenueData->pluck('date');
    $revenue = $revenueData->pluck('revenue');
    
    // Include a timestamp to help with debugging
    return response()->json([
        'labels' => $labels,
        'revenue' => $revenue,
        'generated_at' => now()->toDateTimeString()
    ]);
}

public function getCategoryRevenue($categoryId, Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ]);
    
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    // Make sure the end date includes the entire day
    $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
    
    // Fetch revenue data for the selected category and date range - no caching
    $revenueData = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->where('products.category_id', $categoryId)
                            ->whereBetween('orders.created_at', [$startDate, $endDate])
                            ->selectRaw('products.name as product_name, SUM(order_items.price * order_items.quantity) as revenue')
                            ->groupBy('products.name')
                            ->get();
    
    // Prepare data for the chart
    $labels = $revenueData->pluck('product_name');
    $revenue = $revenueData->pluck('revenue');
    
    // Include a timestamp to help with debugging
    return response()->json([
        'labels' => $labels,
        'revenue' => $revenue,
        'generated_at' => now()->toDateTimeString()
    ]);
}

public function getAllCategoriesRevenue(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
    ]);
    
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    // Make sure the end date includes the entire day
    $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
    
    // Fetch revenue data for all categories - no caching
    $revenueData = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->whereBetween('orders.created_at', [$startDate, $endDate])
                            ->selectRaw('categories.name as category_name, SUM(order_items.price * order_items.quantity) as revenue')
                            ->groupBy('categories.name')
                            ->get();
    
    // Prepare data for the chart
    $labels = $revenueData->pluck('category_name');
    $revenue = $revenueData->pluck('revenue');
    
    // Include a timestamp to help with debugging
    return response()->json([
        'labels' => $labels,
        'revenue' => $revenue,
        'generated_at' => now()->toDateTimeString()
    ]);
}

}
