@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Add New Ingredient</h1>
        
        <form action="{{ route('ingredients.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                <input type="text" name="name" id="name" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       required>
            </div>
            
            <div class="mb-4">
                <label for="unit" class="block text-gray-700 font-medium mb-2">Unit</label>
                <select name="unit" id="unit" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                    <option value="">Select Unit</option>
                    <option value="g">Grams (g)</option>
                    <option value="ml">Milliliters (ml)</option>
                    <option value="oz">Ounces (oz)</option>
                    <option value="pcs">Pieces</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="stock" class="block text-gray-700 font-medium mb-2">Initial Stock</label>
                <input type="number" step="0.01" name="stock" id="stock" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       required>
            </div>
            
            <div class="mb-6">
                <label for="alert_threshold" class="block text-gray-700 font-medium mb-2">Low Stock Alert (optional)</label>
                <input type="number" step="0.01" name="alert_threshold" id="alert_threshold" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="text-gray-500 text-sm mt-1">System will warn when stock falls below this level</p>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('ingredients.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    Save Ingredient
                </button>
            </div>
        </form>
    </div>
</div>
@endsection