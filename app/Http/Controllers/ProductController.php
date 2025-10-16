<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        // Check if the admin is logged in
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'You must log in first.');
        }

        $categories = Category::all();
        $products = Product::with('category')->get(); 

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        // Check if the admin is logged in
        if (!Session::has('admin_logged_in')) {
            return redirect('/login')->with('error', 'You must log in first.');
        }

        $categories = Category::all(); // Fetch all categories
        $ingredients = Ingredient::all(); // fetch all ingredients
        // Pass both categories and ingredients to the view
        return view('products.create', compact('categories', 'ingredients'));
        
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'has_multiple_sizes' => 'required|boolean',
            'price_small' => 'nullable|numeric',
            'price_medium' => 'nullable|numeric',
            'price_large' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'small_enabled' => 'nullable|boolean',
            'medium_enabled' => 'nullable|boolean',
            'large_enabled' => 'nullable|boolean',
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
            $validatedData['price'] = null;
        } else {
            $validatedData['price_small'] = null;
            $validatedData['price_medium'] = null;
            $validatedData['price_large'] = null;
        }

        // Create the product
        // ========== NEW INVENTORY LOGIC STARTS HERE ==========
        $product = Product::create($validatedData);

        // Attach ingredients with size multipliers
        if ($request->has('ingredients')) {
            $ingredientsData = [];
            foreach ($request->ingredients as $index => $ingredientId) {
                if (!empty($ingredientId) && !empty($request->quantities[$index])) {
                    $ingredientsData[$ingredientId] = [
                        'quantity' => $request->quantities[$index],
                        'small_multiplier' => $request->small_multipliers[$index] ?? 0.75,
                        'medium_multiplier' => $request->medium_multipliers[$index] ?? 1.00,
                        'large_multiplier' => $request->large_multipliers[$index] ?? 1.50
                    ];
                }
            }
            $product->ingredients()->sync($ingredientsData);
        }

        return redirect()->route('admin.products')->with('success', 'Product added successfully.');
    }

    public function updateAvailability(Product $product)
    {
        $product->update(['is_available' => !$product->is_available]);
        return redirect()->back()->with('success', 'Product availability updated.');
    }

   public function edit(Product $product)
{
    $categories = Category::all();
    $ingredients = Ingredient::all(); // Make sure ingredients are available

    // ✅ This is the smart logic
    if (request()->ajax()) {
        // If it's a modal request, return the PARTIAL view.
        return view('products._edit', compact('product', 'categories', 'ingredients'));
    }

    // Otherwise, for a direct URL visit, return the FULL page view.
    return view('products.edit', compact('product', 'categories', 'ingredients'));
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
            'small_enabled' => 'nullable|boolean',
            'medium_enabled' => 'nullable|boolean',
            'large_enabled' => 'nullable|boolean',
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
            $validatedData['price'] = null;
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
        $query = Product::with('category');

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(10); // Paginate products, 10 per page

        return view('admin.products', compact('products', 'categories'));
    }
}