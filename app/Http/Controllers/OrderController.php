<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

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

    // Create the order
    $order = Order::create([
        'total_price' => $request->total_price,
        'amount_received' => $request->amount_received,
        'change' => $request->change,
    ]);

    // Add order items
    $orderItems = [];
    foreach ($request->items as $item) {
        $product = Product::find($item['id']); // Fetch the product
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'size' => $item['size'],
        ]);
        $orderItems[] = [
            'name' => $product->name, // Include product name
            'size' => $item['size'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ];
    }

    // Return the order and items
    return response()->json([
        'order' => $order,
        'items' => $orderItems,
    ], 201);
}
}
