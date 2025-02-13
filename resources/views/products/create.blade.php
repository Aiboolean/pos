@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>
    
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Category:</label>
            <select name="category" id="category" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Hot Coffee">Hot Coffee</option>
                <option value="Cold Coffee">Cold Coffee</option>
                <option value="Frappe Coffee">Frappe Coffee</option>
                <option value="Fruit Tea">Fruit Tea</option>
                <option value="Iced Tea">Iced Tea</option>
                <option value="Milktea Classic">Milktea Classic</option>
                <option value="Milktea Premium">Milktea Premium</option>
                <option value="Non-Coffee">Non-Coffee</option>
                <option value="Yakult Series">Yakult Series</option>
                <option value="Add Ons">Add Ons</option>
                <option value="Rice Meals">Rice Meals</option>
                <option value="Snacks">Snacks</option>
                <option value="Fries">Fries</option>
                <option value="Chips and Cup Noodles">Chips and Cup Noodles</option>
                <option value="Croffle">Croffle</option>
                <option value="Pastry">Pastry</option>
            </select>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" name="name" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Prices for Sizes (Hidden by Default) -->
        <div id="size-prices">
            <label class="block text-sm font-medium text-gray-700">Prices:</label>
            <div class="space-y-2">
                <!-- Small -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Small:</label>
                    <input type="number" step="0.01" name="price_small" id="price_small" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>
                <!-- Medium -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Medium:</label>
                    <input type="number" step="0.01" name="price_medium" id="price_medium" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                </div>
                <!-- Large -->
                <div class="flex items-center space-x-2">
                    <label class="w-20">Large:</label>
                    <input type="number" step="0.01" name="price_large" id="price_large" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
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
            <label class="block text-sm font-medium text-gray-700">Available:</label>
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
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
        </div>
    </form>
</div>

<!-- JavaScript to Handle Size Inputs and Single Price Based on Category -->
<script>
    document.getElementById('category').addEventListener('change', function () {
        const category = this.value;
        const sizePricesDiv = document.getElementById('size-prices');
        const singlePriceDiv = document.getElementById('single-price');
        const priceSmall = document.getElementById('price_small');
        const priceMedium = document.getElementById('price_medium');
        const priceLarge = document.getElementById('price_large');
        const priceInput = document.getElementById('price');

        // Reset all fields
        sizePricesDiv.style.display = 'none';
        singlePriceDiv.style.display = 'none';
        priceSmall.disabled = true;
        priceMedium.disabled = true;
        priceLarge.disabled = true;
        priceSmall.value = '';
        priceMedium.value = '';
        priceLarge.value = '';
        priceInput.value = '';

        // Categories with sizes
        const sizeCategories = [
            'Hot Coffee', 'Cold Coffee', 'Frappe Coffee', 'Fruit Tea', 'Iced Tea',
            'Milktea Classic', 'Milktea Premium', 'Non-Coffee', 'Yakult Series'
        ];

        if (sizeCategories.includes(category)) {
            // Show size-based prices
            sizePricesDiv.style.display = 'block';
            singlePriceDiv.style.display = 'none';

            // Enable fields based on category
            switch (category) {
                case 'Hot Coffee':
                    priceSmall.disabled = false;
                    priceMedium.disabled = false;
                    break;
                case 'Cold Coffee':
                    priceSmall.disabled = false;
                    priceMedium.disabled = false;
                    priceLarge.disabled = false;
                    break;
                case 'Frappe Coffee':
                    priceMedium.disabled = false;
                    priceLarge.disabled = false;
                    break;
                case 'Fruit Tea':
                    priceLarge.disabled = false;
                    break;
                case 'Iced Tea':
                    priceMedium.disabled = false;
                    priceLarge.disabled = false;
                    break;
                case 'Milktea Classic':
                    priceSmall.disabled = false;
                    priceMedium.disabled = false;
                    priceLarge.disabled = false;
                    break;
                case 'Milktea Premium':
                    priceMedium.disabled = false;
                    priceLarge.disabled = false;
                    break;
                case 'Non-Coffee':
                    priceSmall.disabled = false;
                    priceMedium.disabled = false;
                    priceLarge.disabled = false;
                    break;
                case 'Yakult Series':
                    priceLarge.disabled = false;
                    break;
            }
        } else {
            // Show single price input
            sizePricesDiv.style.display = 'none';
            singlePriceDiv.style.display = 'block';
        }
    });
</script>
@endsection