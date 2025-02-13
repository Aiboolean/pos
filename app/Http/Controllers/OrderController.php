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
        'items' => 'required|array',
        'items.*.id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric',
        'items.*.size' => 'required|string', // Add validation for size
    ]);

    $order = Order::create([
        'total_price' => $request->total_price
    ]);

    foreach ($request->items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'size' => $item['size'], // Add size to the order item
        ]);
    }

    return response()->json(['order_id' => $order->id], 201);
}
}
