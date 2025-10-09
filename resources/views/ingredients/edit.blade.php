@extends('layouts.app')

@section('content')
<style>
.coffee-bg {
    background-color: #f5f1ea;
}

.coffee-card {
    background-color: white;
    border: 1px solid #e0d6c2;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-radius: 0.75rem;
}

.coffee-text-primary {
    color: #5c4d3c;
}

.coffee-text-secondary {
    color: #8c7b6b;
}

.coffee-border {
    border-color: #e0d6c2;
}

.coffee-btn-primary {
    background-color: #6f4e37;
    color: white;
    transition: all 0.2s ease;
}

.coffee-btn-primary:hover {
    background-color: #5c3d2a;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.coffee-btn-success {
    background-color: #8c7b6b;
    color: white;
    transition: all 0.2s ease;
}

.coffee-btn-success:hover {
    background-color: #6f4e37;
}

.coffee-btn-secondary {
    background-color: #e0d6c2;
    color: #5c4d3c;
    transition: all 0.2s ease;
}

.coffee-btn-secondary:hover {
    background-color: #d4c9b5;
}

.coffee-input {
    border: 1px solid #e0d6c2;
    background-color: white;
    color: #5c4d3c;
    transition: all 0.2s ease;
}

.coffee-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px #8c7b6b;
    border-color: #8c7b6b;
}

.coffee-shadow {
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
}

.coffee-toggle-bg {
    background-color: #f5f1ea;
}

.coffee-file-input {
    border-color: #e0d6c2;
}

.coffee-file-input:hover {
    background-color: #f5f1ea;
}
</style>
<div class="container mx-auto px-4 py-6 coffee-bg">
    <div class="coffee-card p-6">
        <h1 class="text-2xl font-bold mb-6 coffee-text-primary">Edit Ingredient</h1>
        
        <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block font-medium mb-2 coffee-text-primary">Name</label>
                <input type="text" name="name" id="name" 
                       class="w-full px-4 py-2 rounded-lg coffee-input"
                       value="{{ old('name', $ingredient->name) }}" required>
            </div>
            
            <div class="mb-4">
                <label for="unit" class="block font-medium mb-2 coffee-text-primary">Unit</label>
                <select name="unit" id="unit" 
                        class="w-full px-4 py-2 rounded-lg coffee-input"
                        required>
                    <option value="g" {{ $ingredient->unit == 'g' ? 'selected' : '' }}>Grams (g)</option>
                    <option value="ml" {{ $ingredient->unit == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                    <option value="oz" {{ $ingredient->unit == 'oz' ? 'selected' : '' }}>Ounces (oz)</option>
                    <option value="pcs" {{ $ingredient->unit == 'pcs' ? 'selected' : '' }}>Pieces</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="stock" class="block font-medium mb-2 coffee-text-primary">Current Stock</label>
                <input type="number" step="0.01" name="stock" id="stock" 
                       class="w-full px-4 py-2 rounded-lg coffee-input"
                       value="{{ old('stock', $ingredient->stock) }}" required>
            </div>
            
            <div class="mb-6">
                <label for="alert_threshold" class="block font-medium mb-2 coffee-text-primary">Low Stock Alert</label>
                <input type="number" step="0.01" name="alert_threshold" id="alert_threshold" 
                       class="w-full px-4 py-2 rounded-lg coffee-input"
                       value="{{ old('alert_threshold', $ingredient->alert_threshold) }}">
                <p class="coffee-text-secondary text-sm mt-1">
                    System will warn when stock falls below this level
                </p>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="submit" 
                        class="px-4 py-2 rounded-lg coffee-btn-primary">
                    Update Ingredient
                </button>
                <a href="{{ route('ingredients.index') }}" 
                   class="px-4 py-2 rounded-lg coffee-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    
</div>

@endsection
