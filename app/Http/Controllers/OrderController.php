<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
{
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
                $quantityNeeded = $ingredient->pivot->quantity * $item['quantity'];
                
                // Check stock availability
                if ($ingredient->stock < $quantityNeeded) {
                    throw new \Exception("Not enough stock for {$ingredient->name}. Available: {$ingredient->stock}, Needed: {$quantityNeeded}");
                }
                
                // Deduct from inventory
                $ingredient->decrement('stock', $quantityNeeded);
            }
            // ========== INVENTORY DEDUCTION END ==========
            
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'user_id' => Session::get('user_id'),
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'size' => $item['size'],
            ]);
            
            $orderItems[] = [
                'name' => $product->name,
                'size' => $item['size'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }

        // Commit transaction if all operations succeed
        DB::commit();

        return response()->json([
            'order' => $order,
            'items' => $orderItems,
        ], 201);

    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();
        return response()->json([
            'error' => 'Order processing failed: ' . $e->getMessage()
        ], 400);
    }
}
public function adminIndex()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }

    // Fetch orders in descending order (latest first) with pagination
    $orders = Order::with('user', 'items.product')
                ->orderBy('created_at', 'desc')
                ->paginate(10); // Adjust the number of items per page as needed

    return view('admin.orders.index', compact('orders'));
}

public function adminShow(Order $order)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }

    $order->load('user', 'items.product');
    return view('admin.orders.show', compact('order'));
}
public function userOrders()
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }

    // Fetch orders for the logged-in user in descending order (latest first) with pagination
    $userId = Session::get('user_id');
    $orders = Order::with('items.product')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->paginate(10); // Adjust the number of items per page as needed

    return view('user.orders.index', compact('orders'));
}
public function userOrderShow(Order $order)
{
    if (!Session::has('admin_logged_in')) {
        return redirect('/login')->with('error', 'You must log in first.');
    }

    // Ensure the order belongs to the logged-in user
    $userId = Session::get('user_id');
    if ($order->user_id !== $userId) {
        return redirect('/orders')->with('error', 'Unauthorized access.');
    }

    $order->load('items.product');
    return view('user.orders.show', compact('order'));
}


}