<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 



class OrderController extends Controller
{
    public function store(Request $request)
    {
        // ... YOUR EXISTING store METHOD (NO CHANGES) ...
        $request->validate([
            'total_price' => 'required|numeric',
            'amount_received' => 'required|numeric',
            'change' => 'required|numeric',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.size' => 'required|string',
        ]);

        // Start database transaction for atomic operations
        DB::beginTransaction();

        try {
        // Create the order
        $order = Order::create([
            'total_price' => $request->total_price,
            'amount_received' => $request->amount_received,
            'change' => $request->change,
            'user_id' => Session::get('user_id'),
        ]);

        // Add order items and process inventory
        $orderItems = [];
        foreach ($request->items as $item) {
            $product = Product::with('ingredients')->find($item['id']);
            
            // ========== INVENTORY DEDUCTION START ==========
            foreach ($product->ingredients as $ingredient) {
                // Check if product has multiple sizes
                if ($product->has_multiple_sizes) {
                    // Use size multipliers for multi-size products
                    $multiplier = match($item['size']) {
                        'small' => $ingredient->pivot->small_multiplier,
                        'medium' => $ingredient->pivot->medium_multiplier,
                        'large' => $ingredient->pivot->large_multiplier,
                        default => 1.00 // Fallback
                    };
                } else {
                    // Use base quantity for single-size products
                    $multiplier = 1.00;
                }
                
                $quantityNeeded = $ingredient->pivot->quantity * $multiplier * $item['quantity'];
                
                // Check stock availability
                if ($ingredient->stock < $quantityNeeded) {
                    throw new \Exception("Not enough stock for {$ingredient->name}. Available: {$ingredient->stock}, Needed: {$quantityNeeded}");
                }
                
                // Deduct from inventory
                $ingredient->decrement('stock', $quantityNeeded);
            }
            // ========== INVENTORY DEDUCTION END ==========
            
            // Create order item
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'user_id' => Session::get('user_id'),
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'size' => $product->has_multiple_sizes ? $item['size'] : 'standard',
            ]);
            
            $orderItems[] = [
                'name' => $product->name,
                'size' => $product->has_multiple_sizes ? $item['size'] : 'standard',
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }

        DB::commit();

        return response()->json([
            'order' => $order,
            'items' => $orderItems,
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'Order processing failed: ' . $e->getMessage()
        ], 400);
    }
}

    // MODIFIED METHOD: Added filtering functionality
   public function adminIndex(Request $request)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }

    // Start building the query with relationships and descending order
    $query = Order::with('user', 'items.product')->orderBy('created_at', 'desc');

    // ===== NEW DATE RANGE FILTERING LOGIC =====
    // Filter by start date
    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->input('start_date'));
    }

    // Filter by end date
    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->input('end_date'));
    }
    // ===== END FILTERING LOGIC =====

    // Execute the query and paginate the results
    $orders = $query->paginate(10);

    // Pass the request object to the view to pre-fill the filter form inputs
    return view('admin.orders.index', compact('orders', 'request'));
}

    public function adminShow(Order $order)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'You must log in first.');
        }

        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function userOrders(Request $request)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }

    // Fetch orders for the logged-in user in descending order (latest first) with pagination
    $userId = Session::get('user_id');
    
    // Start with your original query
    $query = Order::with('items.product')
                ->where('user_id', $userId);
    
    // ADDED: Time-based filtering (preserves all existing functionality)
    $filter = $request->query('filter', 'all');
    switch ($filter) {
        case 'daily':
            $query->whereDate('created_at', today());
            break;
        case 'weekly':
            // Sunday to Saturday week
            $query->whereBetween('created_at', [
                now()->startOfWeek(), // Sunday
                now()->endOfWeek()    // Saturday
            ]);
            break;
        case 'monthly':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;
        case 'yearly':
            $query->whereYear('created_at', now()->year);
            break;
        // No 'default' case needed - when filter is 'all' or anything else,
        // it will just use your original query without additional filters
    }
    
    // YOUR ORIGINAL CODE - completely unchanged
    $orders = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('user.orders.index', compact('orders'));
}
   public function userOrderShow(Order $order)
{
    // --- Your security checks ---
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }
    $userId = Session::get('user_id');
    if ($order->user_id !== $userId) {
        if (request()->ajax()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
        return redirect('/orders')->with('error', 'Unauthorized access.');
    }
    // --- End of security checks ---

    $order->load('items.product');

    // ✅ This is the key logic we are putting back
    if (request()->ajax()) {
        // For modal requests, return the partial view
        return view('user.orders._show_partial', compact('order'));
    }

    // For direct page visits, return the full page view
    return view('user.orders.show', compact('order'));
}
    // NEW METHOD: Handles generating the PDF report
    public function generatePDFReport(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'You must log in first.');
        }

        // 1. Fetch orders with filters
        $query = Order::with('user', 'items.product');
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        $filteredOrders = $query->get();

        // 2. Calculate Product Sales Data - COMPLETELY REWRITTEN
        // First, get all order IDs that match our date filter
        $filteredOrderIds = Order::when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->input('start_date'));
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->input('end_date'));
            })
            ->pluck('id');

        // Now get product performance for those order IDs
        $productPerformance = OrderItem::with('product')
            ->whereIn('order_id', $filteredOrderIds)
            ->select('product_id', 
                    DB::raw('SUM(quantity) as total_quantity'), 
                    DB::raw('SUM(price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->get();

        // 3. Calculate report statistics
        $totalSales = $filteredOrders->sum('total_price');
        $totalOrders = $filteredOrders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // 4. Load a PDF view, pass the data
        $pdf = PDF::loadView('admin.reports.pdf', [
            'orders' => $filteredOrders,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'productPerformance' => $productPerformance,
            'filters' => $request->all()
        ]);

        // 5. Generate a filename
        $fileName = 'sales-report-' . now()->format('Y-m-d_H-i') . '.pdf';

        // 6. Download the PDF
        return $pdf->download($fileName);
    }
}