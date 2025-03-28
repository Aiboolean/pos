@extends('layouts.app')

@section('content')
<style>
    /* Coffee Shop Theme Styles */
    .coffee-card {
        background-color: #fff;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .coffee-btn-primary {
        background-color: #6f4e37;
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    
    .coffee-btn-primary:hover {
        background-color: #5c3d2a;
        transform: translateY(-1px);
    }
    
    .coffee-btn-secondary {
        background-color: #e0d6c2;
        color: #5c4d3c;
        border: none;
        transition: all 0.3s ease;
    }
    
    .coffee-btn-secondary:hover {
        background-color: #d4c9b5;
    }
    
    .coffee-border {
        border-color: #e0d6c2;
    }
    
    .coffee-text {
        color: #5c4d3c;
    }
    
    .coffee-focus:focus {
        outline: none;
        ring: 2px;
        ring-color: #8c7b6b;
    }
</style>

<div class="max-w-lg mx-auto coffee-card rounded-xl p-6 mt-6">
    <h1 class="text-2xl font-bold mb-4 coffee-text">Edit Product</h1>
    
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Category -->
        <div>
            <label class="block text-sm font-medium coffee-text">Category:</label>
            <select name="category_id" id="category_id" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium coffee-text">Name:</label>
            <input type="text" name="name" value="{{ $product->name }}" required 
                   class="w-full p-2 coffee-border border rounded-lg coffee-focus">
        </div>

        <!-- Toggle for Multiple Sizes -->
        <div class="flex items-center">
            <label class="block text-sm font-medium coffee-text mr-2">Has Multiple Sizes:</label>
            <input type="hidden" name="has_multiple_sizes" value="0"> 
            <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" 
                   class="h-5 w-5 coffee-border rounded focus:ring-[#8c7b6b]" 
                   onchange="toggleSizeFields()" value="1" {{ $product->has_multiple_sizes ? 'checked' : '' }}>
        </div>

        <!-- Prices for Sizes (Hidden by Default) -->
        <div id="size-prices" class="{{ $product->has_multiple_sizes ? 'block' : 'hidden' }} space-y-3 mt-3">
            <label class="block text-sm font-medium coffee-text">Prices:</label>
            <div class="space-y-3">
                <!-- Small Size -->
                <div class="flex items-center space-x-3">
                    <label class="w-20 coffee-text">Small:</label>
                    <input type="number" step="0.01" name="price_small" id="price_small" 
                           value="{{ $product->price_small }}" 
                           class="flex-1 p-2 coffee-border border rounded-lg coffee-focus">
                    <div class="flex items-center">
                        <input type="hidden" name="small_enabled" value="0">
                        <input type="checkbox" name="small_enabled" id="small_enabled" 
                               class="h-5 w-5 coffee-border rounded focus:ring-[#8c7b6b]" 
                               value="1" {{ $product->small_enabled ? 'checked' : '' }}>
                        <label for="small_enabled" class="ml-2 text-sm coffee-text">Enable</label>
                    </div>
                </div>

                <!-- Medium Size -->
                <div class="flex items-center space-x-3">
                    <label class="w-20 coffee-text">Medium:</label>
                    <input type="number" step="0.01" name="price_medium" id="price_medium" 
                           value="{{ $product->price_medium }}" 
                           class="flex-1 p-2 coffee-border border rounded-lg coffee-focus">
                    <div class="flex items-center">
                        <input type="hidden" name="medium_enabled" value="0">
                        <input type="checkbox" name="medium_enabled" id="medium_enabled" 
                               class="h-5 w-5 coffee-border rounded focus:ring-[#8c7b6b]" 
                               value="1" {{ $product->medium_enabled ? 'checked' : '' }}>
                        <label for="medium_enabled" class="ml-2 text-sm coffee-text">Enable</label>
                    </div>
                </div>

                <!-- Large Size -->
                <div class="flex items-center space-x-3">
                    <label class="w-20 coffee-text">Large:</label>
                    <input type="number" step="0.01" name="price_large" id="price_large" 
                           value="{{ $product->price_large }}" 
                           class="flex-1 p-2 coffee-border border rounded-lg coffee-focus">
                    <div class="flex items-center">
                        <input type="hidden" name="large_enabled" value="0">
                        <input type="checkbox" name="large_enabled" id="large_enabled" 
                               class="h-5 w-5 coffee-border rounded focus:ring-[#8c7b6b]" 
                               value="1" {{ $product->large_enabled ? 'checked' : '' }}>
                        <label for="large_enabled" class="ml-2 text-sm coffee-text">Enable</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Price (Hidden by Default) -->
        <div id="single-price" class="{{ !$product->has_multiple_sizes ? 'block' : 'hidden' }}">
            <label class="block text-sm font-medium coffee-text">Price:</label>
            <input type="number" step="0.01" name="price" id="price" 
                   value="{{ $product->price }}" 
                   class="w-full p-2 coffee-border border rounded-lg coffee-focus">
        </div>

        <!-- Availability -->
        <div>
            <label class="block text-sm font-medium coffee-text">Status:</label>
            <select name="is_available" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium coffee-text">Image:</label>
            <input type="file" name="image" accept="image/*" 
                   class="w-full p-2 coffee-border border rounded-lg coffee-focus">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                     class="mt-2 w-32 h-32 object-cover rounded border coffee-border">
            @endif
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mt-6">
            <button type="submit" 
                    class="px-4 py-2 coffee-btn-primary rounded-lg shadow-sm">
                Update Product
            </button>
            <a href="{{ route('products.index') }}" 
               class="px-4 py-2 coffee-btn-secondary rounded-lg shadow-sm">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function toggleSizeFields() {
        const hasMultipleSizes = document.getElementById('has_multiple_sizes').checked;
        const sizePricesDiv = document.getElementById('size-prices');
        const singlePriceDiv = document.getElementById('single-price');

        if (hasMultipleSizes) {
            sizePricesDiv.classList.remove('hidden');
            singlePriceDiv.classList.add('hidden');
        } else {
            sizePricesDiv.classList.add('hidden');
            singlePriceDiv.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleSizeFields();
    });
</script>
@endsection