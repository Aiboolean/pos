@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Edit Product</h2>
    
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block font-medium">Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}" required class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label for="category" class="block font-medium">Category</label>
            <input type="text" name="category" value="{{ $product->category }}" required class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Product Prices</label>
            @php $prices = json_decode($product->prices, true); @endphp
            @if(in_array($product->category, ['Hot Coffee', 'Cold Coffee', 'Frappe Coffee', 'Fruit Tea', 'Iced Tea', 'Milktea Classic', 'Milktea Premium', 'Non-Coffee', 'Yakult Series']))
                <input type="number" name="price_small" placeholder="Small Price" value="{{ $prices['small'] ?? '' }}" class="w-full border p-2 rounded mb-2">
                <input type="number" name="price_medium" placeholder="Medium Price" value="{{ $prices['medium'] ?? '' }}" class="w-full border p-2 rounded mb-2">
                <input type="number" name="price_large" placeholder="Large Price" value="{{ $prices['large'] ?? '' }}" class="w-full border p-2 rounded">
            @else
                <input type="number" name="price" placeholder="Single Price" value="{{ $prices['single'] ?? '' }}" class="w-full border p-2 rounded">
            @endif
        </div>

        <div class="mb-4">
            <label for="is_available" class="block font-medium">Availability</label>
            <select name="is_available" class="w-full border p-2 rounded">
                <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Unavailable</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="image" class="block font-medium">Product Image</label>
            <input type="file" name="image" class="w-full border p-2 rounded">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="w-32 h-32 mt-2">
            @endif
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Product</button>
            <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
