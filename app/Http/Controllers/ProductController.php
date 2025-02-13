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
            'price' => 'nullable|numeric', // Single price for non-size categories
            'is_available' => 'required|boolean',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $imagePath;
        }
    
        // Handle prices based on category
        if (in_array($request->category, ['Hot Coffee', 'Cold Coffee', 'Frappe Coffee', 'Fruit Tea', 'Iced Tea', 'Milktea Classic', 'Milktea Premium', 'Non-Coffee', 'Yakult Series'])) {
            // Size-based prices
            $validatedData['prices'] = json_encode([
                'small' => $request->price_small ?? null,
                'medium' => $request->price_medium ?? null,
                'large' => $request->price_large ?? null,
            ]);
        } else {
            // Single price for non-size categories
            $validatedData['prices'] = json_encode([
                'single' => $request->price ?? null,
            ]);
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
