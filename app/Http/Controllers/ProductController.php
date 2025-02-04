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
        'price' => 'required|numeric',
        'is_available' => 'required|boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validate image file
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public'); // Save image to storage
        $validatedData['image'] = $imagePath;
    }

    Product::create($validatedData);

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}


public function updateAvailability(Product $product)
{
    $product->update(['is_available' => !$product->is_available]);

    return redirect()->back()->with('success', 'Product availability updated.');
}


}
