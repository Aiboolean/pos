<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['products' => Product::all()]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'price_small' => 'nullable|numeric',
        'price_medium' => 'nullable|numeric',
        'price_large' => 'nullable|numeric',
        'is_available' => 'required|boolean',
        'category' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');
        $validatedData['image'] = $imagePath;
    }

    // Store prices for sizes
    $validatedData['prices'] = json_encode([
        'small' => $request->price_small,
        'medium' => $request->price_medium,
        'large' => $request->price_large,
    ]);

    Product::create($validatedData);

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}

public function updateAvailability(Product $product)
{
    $product->update(['is_available' => !$product->is_available]);

    return redirect()->back()->with('success', 'Product availability updated.');
}


}
