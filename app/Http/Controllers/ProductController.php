<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->get();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all categories
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'has_multiple_sizes' => 'required|boolean',
        'price_small' => 'nullable|numeric', // Allow nullable for small price
        'price_medium' => 'nullable|numeric', // Allow nullable for medium price
        'price_large' => 'nullable|numeric', // Allow nullable for large price
        'price' => 'nullable|numeric', // Allow nullable for single price
        'is_available' => 'required|boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');
        $validatedData['image'] = $imagePath;
    }

    // If the product has multiple sizes, ensure size-based prices are provided
    if ($request->has_multiple_sizes) {
        $validatedData['price'] = null; // Clear single price
    } else {
        $validatedData['price_small'] = null;
        $validatedData['price_medium'] = null;
        $validatedData['price_large'] = null;
    }

    // Create the product
    Product::create($validatedData);

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}
    public function updateAvailability(Product $product)
    {
        $product->update(['is_available' => !$product->is_available]);
        return redirect()->back()->with('success', 'Product availability updated.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'has_multiple_sizes' => 'nullable|boolean',
            'price_small' => 'nullable|numeric',
            'price_medium' => 'nullable|numeric',
            'price_large' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // If the product has multiple sizes, ensure size-based prices are provided
        if ($request->has('has_multiple_sizes') && $request->has_multiple_sizes) {
            $validatedData['price'] = null; // Clear single price
        } else {
            $validatedData['price_small'] = null;
            $validatedData['price_medium'] = null;
            $validatedData['price_large'] = null;
        }
        

        // Update the product
        $product->update($validatedData);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    public function adminIndex(Request $request)
{
    $categories = Category::all();
    $query = Product::with('category'); // Ensure category relationship is loaded

    if ($request->has('category') && $request->category != '') {
        $query->where('category_id', $request->category);
    }

    $products = $query->get();

    return view('admin.products', compact('products', 'categories'));
}

}