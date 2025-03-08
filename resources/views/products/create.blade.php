@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>
    
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Category:</label>
            <select name="category_id" id="category_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" name="name" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Toggle for Multiple Sizes -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Has Multiple Sizes:</label>
            <input type="hidden" name="has_multiple_sizes" value="0"> <!-- Always submit a value -->
            <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="mt-2" onchange="toggleSizeFields()" value="1">
        </div>

        <!-- Prices for Sizes (Hidden by Default) -->
        <div id="size-prices" class="hidden">
            <label class="block text-sm font-medium text-gray-700">Prices:</label>
            <div class="space-y-2">
                <!-- Small -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Small:</label>
                    <input type="number" step="0.01" name="price_small" id="price_small" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- Medium -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Medium:</label>
                    <input type="number" step="0.01" name="price_medium" id="price_medium" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- Large -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Large:</label>
                    <input type="number" step="0.01" name="price_large" id="price_large" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Single Price (Hidden by Default) -->
        <div id="single-price" class="hidden">
            <label class="block text-sm font-medium text-gray-700">Price:</label>
            <input type="number" step="0.01" name="price" id="price" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Availability -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status:</label>
            <select name="is_available" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Image:</label>
            <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded-lg">
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mt-4">
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
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

    // Initialize the form based on the default state
    document.addEventListener('DOMContentLoaded', function () {
        toggleSizeFields();
    });
</script>
@endsection