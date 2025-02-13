@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>
    
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Category:</label>
            <select name="category" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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

        <!-- Prices for Sizes -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Prices:</label>
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <label class="w-20">Small:</label>
                    <input type="number" step="0.01" name="price_small" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-2">
                    <label class="w-20">Medium:</label>
                    <input type="number" step="0.01" name="price_medium" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-2">
                    <label class="w-20">Large:</label>
                    <input type="number" step="0.01" name="price_large" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
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
@endsection