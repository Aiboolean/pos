@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>
    
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Category:</label>
            <select name="category_id" id="category_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" name="name" value="{{ $product->name }}" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Toggle for Multiple Sizes -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Has Multiple Sizes:</label>
            <input type="hidden" name="has_multiple_sizes" value="0"> 
            <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="mt-2" onchange="toggleSizeFields()" value="1" {{ $product->has_multiple_sizes ? 'checked' : '' }}>
        </div>

        <!-- Prices for Sizes (Hidden by Default) -->
        <div id="size-prices" class="{{ $product->has_multiple_sizes ? 'block' : 'hidden' }}">
            <label class="block text-sm font-medium text-gray-700">Prices:</label>
            <div class="space-y-2">
                <!-- Small Size -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Small:</label>
                    <input type="number" step="0.01" name="price_small" id="price_small" value="{{ $product->price_small }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="hidden" name="small_enabled" value="0"> <!-- Hidden field for unchecked state -->
                    <input type="checkbox" name="small_enabled" id="small_enabled" value="1" {{ $product->small_enabled ? 'checked' : '' }}>
                    <label for="small_enabled">Enable</label>
                </div>

                <!-- Medium Size -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Medium:</label>
                    <input type="number" step="0.01" name="price_medium" id="price_medium" value="{{ $product->price_medium }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="hidden" name="medium_enabled" value="0"> <!-- Hidden field for unchecked state -->
                    <input type="checkbox" name="medium_enabled" id="medium_enabled" value="1" {{ $product->medium_enabled ? 'checked' : '' }}>
                    <label for="medium_enabled">Enable</label>
                </div>

                <!-- Large Size -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Large:</label>
                    <input type="number" step="0.01" name="price_large" id="price_large" value="{{ $product->price_large }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="hidden" name="large_enabled" value="0"> <!-- Hidden field for unchecked state -->
                    <input type="checkbox" name="large_enabled" id="large_enabled" value="1" {{ $product->large_enabled ? 'checked' : '' }}>
                    <label for="large_enabled">Enable</label>
                </div>
            </div>
        </div>

        <!-- Single Price (Hidden by Default) -->
        <div id="single-price" class="{{ !$product->has_multiple_sizes ? 'block' : 'hidden' }}">
            <label class="block text-sm font-medium text-gray-700">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="{{ $product->price }}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Availability -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status:</label>
            <select name="is_available" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Image:</label>
            <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded-lg">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="mt-2 w-32 h-32 object-cover rounded">
            @endif
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mt-4">
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>

<!-- JavaScript to Toggle Size Fields -->
<script>
    function toggleSizeFields() {
        const hasMultipleSizes = document.getElementById('has_multiple_sizes').checked;
        const sizePricesDiv = document.getElementById('size-prices');
        const singlePriceDiv = document.getElementById('single-price');

        if (hasMultipleSizes) {
            sizePricesDiv.style.display = 'block';
            singlePriceDiv.style.display = 'none';
        } else {
            sizePricesDiv.style.display = 'none';
            singlePriceDiv.style.display = 'block';
        }
    }

    // Initialize the form based on the current state
    document.addEventListener('DOMContentLoaded', function () {
        toggleSizeFields();
    });
</script>
@endsection