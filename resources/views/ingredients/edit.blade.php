@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Ingredient</h1>
        
        <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                <input type="text" name="name" id="name" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('name', $ingredient->name) }}" required>
            </div>
            
            <div class="mb-4">
                <label for="unit" class="block text-gray-700 font-medium mb-2">Unit</label>
                <select name="unit" id="unit" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                    <option value="g" {{ $ingredient->unit == 'g' ? 'selected' : '' }}>Grams (g)</option>
                    <option value="ml" {{ $ingredient->unit == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                    <option value="oz" {{ $ingredient->unit == 'oz' ? 'selected' : '' }}>Ounces (oz)</option>
                    <option value="pcs" {{ $ingredient->unit == 'pcs' ? 'selected' : '' }}>Pieces</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="stock" class="block text-gray-700 font-medium mb-2">Current Stock</label>
                <input type="number" step="0.01" name="stock" id="stock" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('stock', $ingredient->stock) }}" required>
            </div>
            
            <div class="mb-6">
                <label for="alert_threshold" class="block text-gray-700 font-medium mb-2">Low Stock Alert</label>
                <input type="number" step="0.01" name="alert_threshold" id="alert_threshold" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       value="{{ old('alert_threshold', $ingredient->alert_threshold) }}">
                <p class="text-gray-500 text-sm mt-1">System will warn when stock falls below this level</p>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('ingredients.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    Update Ingredient
                </button>
            </div>
        </form>
    </div>
</div>
@endsection