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

    // Fetch Categories and Products
    $categories = Category::with('products')->get();
    $products = Product::all();
    
    // Fetch Admin + Employees for Cashier dropdown
    $cashiers = DB::table('users')
                ->whereIn('role', ['Admin', 'Employee'])
                ->where('is_active', 1) // only active accounts
                ->get();

    // NEW: Calculate product availability with pagination and category filtering
    $selectedCategory = request()->get('availability_category', 'all');
    
    $productsWithIngredients = Product::with(['ingredients', 'category'])
                                    ->when($selectedCategory !== 'all', function($query) use ($selectedCategory) {
                                        return $query->where('category_id', $selectedCategory);
                                    })
                                    ->get();

    $productAvailability = [];
    $lowStockProducts = 0;
    $outOfStockProducts = 0;

    foreach ($productsWithIngredients as $product) {
        $availability = $product->calculateAvailability();
        $minQuantity = PHP_INT_MAX;
        
        // Find the minimum available quantity across all sizes
        foreach ($availability as $size => $quantity) {
            $minQuantity = min($minQuantity, $quantity);
        }
        
        if ($minQuantity === PHP_INT_MAX) {
            $minQuantity = 0;
        }

        // Count low stock and out of stock products
        if ($minQuantity === 0) {
            $outOfStockProducts++;
        } elseif ($minQuantity <= 5) {
            $lowStockProducts++;
        }

        $productAvailability[] = [
            'name' => $product->name,
            'category_name' => $product->category->name ?? 'Uncategorized',
            'availability' => $availability,
            'min_quantity' => $minQuantity,
            'availability_type' => $product->has_multiple_sizes ? 'multiple' : 'single'
        ];
    }

    // Sort products by availability (lowest first) and paginate
    usort($productAvailability, function($a, $b) {
        return $a['min_quantity'] <=> $b['min_quantity'];
    });

    // Manual pagination for 6 items per page
    $currentPage = request()->get('availability_page', 1);
    $perPage = 6;
    $offset = ($currentPage - 1) * $perPage;
    $paginatedAvailability = array_slice($productAvailability, $offset, $perPage);
    $totalPages = ceil(count($productAvailability) / $perPage);

    // Build pagination URLs with category filter preserved
    $paginationBaseUrl = '?' . http_build_query([
        'availability_category' => $selectedCategory
    ]);

    // Pass analytics data to the view
    return view('admin.dashboard', [
        'totalOrders' => $totalOrders,
        'totalRevenue' => $totalRevenue,
        'totalSalesToday' => $totalSalesToday,
        'bestSeller' => $bestSeller,
        'categories' => $categories,
        'products' => $products,
        'cashiers' => $cashiers,
        'productAvailability' => $paginatedAvailability,
        'lowStockProducts' => $lowStockProducts,
        'outOfStockProducts' => $outOfStockProducts,
        'availabilityCurrentPage' => $currentPage,
        'availabilityTotalPages' => $totalPages,
        'availabilityTotalProducts' => count($productAvailability),
        'availabilitySelectedCategory' => $selectedCategory, // NEW
        'paginationBaseUrl' => $paginationBaseUrl // NEW
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

    // Custom validation for duplicate names
    $existingEmployee = DB::table('users')
        ->where('first_name', strtoupper($request->first_name))
        ->where('last_name', strtoupper($request->last_name))
        ->first();
        
    if ($existingEmployee) {
        return redirect()->route('admin.employees')
            ->withErrors(['first_name' => 'An employee with this first and last name already exists.'])
            ->withInput();
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

    // Custom validation for duplicate names (excluding current employee)
    $existingEmployee = DB::table('users')
        ->where('first_name', strtoupper($request->first_name))
        ->where('last_name', strtoupper($request->last_name))
        ->where('id', '!=', $id)
        ->first();
        
    if ($existingEmployee) {
        return redirect()->route('admin.employees')
            ->withErrors(['first_name' => 'Another employee with this first and last name already exists.'])
            ->withInput()
            ->with('edit_errors', true);
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

    // Admin Dashboard chart data with year-based filtering
    public function getRevenueData(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'period' => 'sometimes|in:daily,weekly,monthly,yearly'
        ]);
        
        $year = $request->input('year');
        $period = $request->input('period', 'monthly');
        
        $query = Order::whereYear('created_at', $year);
        
        // Group by different periods
        switch ($period) {
            case 'yearly':
                // Show multiple years (selected year and previous 4 years)
                $query = Order::whereYear('created_at', '>=', $year - 4)
                            ->whereYear('created_at', '<=', $year);
                $revenueData = $query->selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue')
                                    ->groupBy('year')
                                    ->orderBy('year')
                                    ->get();
                
                $labels = $revenueData->pluck('year');
                break;
                
            case 'weekly':
                $revenueData = $query->selectRaw('WEEK(created_at) as week, SUM(total_price) as revenue')
                                    ->groupBy('week')
                                    ->orderBy('week')
                                    ->get();
                
                $labels = $revenueData->map(function($item) use ($year) {
                    return "Week {$item->week}, {$year}";
                });
                break;
                
            case 'daily':
                $revenueData = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
                                    ->groupBy('date')
                                    ->orderBy('date')
                                    ->get();
                
                $labels = $revenueData->map(function($item) {
                    return Carbon::parse($item->date)->format('M j');
                });
                break;
                
            case 'monthly':
            default:
                $revenueData = $query->selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
                                    ->groupBy('month')
                                    ->orderBy('month')
                                    ->get();
                
                $labels = $revenueData->map(function($item) {
                    return Carbon::create()->month($item->month)->format('F');
                });
                break;
        }
        
        $revenue = $revenueData->pluck('revenue');
        
        return response()->json([
            'labels' => $labels,
            'revenue' => $revenue,
            'period' => $period,
            'year' => $year,
            'generated_at' => now()->toDateTimeString()
        ]);
    }

    public function getCategoryRevenue($categoryId, Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'period' => 'sometimes|in:daily,weekly,monthly,yearly'
        ]);
        
        $year = $request->input('year');
        $period = $request->input('period', 'monthly');
        
        $query = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('products.category_id', $categoryId)
                        ->whereYear('orders.created_at', $year);
        
        // For category product breakdown, we can also apply period filtering
        switch ($period) {
            case 'yearly':
                $query = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                ->where('products.category_id', $categoryId)
                                ->whereYear('orders.created_at', '>=', $year - 4)
                                ->whereYear('orders.created_at', '<=', $year);
                break;
                
            case 'monthly':
                // For monthly view, we can show monthly breakdown per product
                $revenueData = $query->selectRaw('products.name as product_name, MONTH(orders.created_at) as month, SUM(order_items.price * order_items.quantity) as revenue')
                                    ->groupBy('products.name', 'month')
                                    ->orderBy('month')
                                    ->get();
                
                // You might want to adjust this based on how you want to display monthly category data
                $labels = $revenueData->pluck('product_name');
                $revenue = $revenueData->pluck('revenue');
                break;
                
            default:
                // For daily, weekly, and default - show product breakdown
                $revenueData = $query->selectRaw('products.name as product_name, SUM(order_items.price * order_items.quantity) as revenue')
                                    ->groupBy('products.name')
                                    ->get();
                
                $labels = $revenueData->pluck('product_name');
                $revenue = $revenueData->pluck('revenue');
                break;
        }
        
        return response()->json([
            'labels' => $labels,
            'revenue' => $revenue,
            'period' => $period,
            'year' => $year,
            'generated_at' => now()->toDateTimeString()
        ]);
    }

    public function getAllCategoriesRevenue(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'period' => 'sometimes|in:daily,weekly,monthly,yearly'
        ]);
        
        $year = $request->input('year');
        $period = $request->input('period', 'monthly');
        
        $query = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->join('categories', 'products.category_id', '=', 'categories.id')
                        ->whereYear('orders.created_at', $year);
        
        // Apply period filtering to categories chart as well
        switch ($period) {
            case 'yearly':
                $query = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
                                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                ->join('categories', 'products.category_id', '=', 'categories.id')
                                ->whereYear('orders.created_at', '>=', $year - 4)
                                ->whereYear('orders.created_at', '<=', $year);
                
                $revenueData = $query->selectRaw('categories.name as category_name, YEAR(orders.created_at) as year, SUM(order_items.price * order_items.quantity) as revenue')
                                    ->groupBy('categories.name', 'year')
                                    ->orderBy('year')
                                    ->get();
                
                $labels = $revenueData->pluck('category_name');
                break;
                
            case 'monthly':
                $revenueData = $query->selectRaw('categories.name as category_name, MONTH(orders.created_at) as month, SUM(order_items.price * order_items.quantity) as revenue')
                                    ->groupBy('categories.name', 'month')
                                    ->orderBy('month')
                                    ->get();
                
                $labels = $revenueData->pluck('category_name');
                break;
                
            default:
                $revenueData = $query->selectRaw('categories.name as category_name, SUM(order_items.price * order_items.quantity) as revenue')
                                    ->groupBy('categories.name')
                                    ->get();
                
                $labels = $revenueData->pluck('category_name');
                break;
        }
        
        $revenue = $revenueData->pluck('revenue');
        
        return response()->json([
            'labels' => $labels,
            'revenue' => $revenue,
            'period' => $period,
            'year' => $year,
            'generated_at' => now()->toDateTimeString()
        ]);
    }
}
