@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg overflow-hidden p-8">
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.products') }}" class="mr-4 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left">
                    <path d="m12 19-7-7 7-7"/>
                    <path d="M19 12H5"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Add New Product</h1>
        </div>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Category -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag mr-2">
                        <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/>
                        <path d="M7 7h.01"/>
                    </svg>
                    Category
                </label>
                <select name="category_id" id="category_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="" selected disabled hidden>Select a Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Name -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-plus mr-2">
                        <path d="M16 16h6m-3-3v6M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                        <path d="m7.5 4.27 9 5.15"/>
                        <polyline points="3.29 7 12 12 20.71 7"/>
                        <line x1="12" x2="12" y1="22" y2="12"/>
                    </svg>
                    Product Name
                </label>
                <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            <!-- Toggle for Multiple Sizes -->
            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                <label class="block text-sm font-medium text-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ruler mr-2">
                        <path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/>
                        <path d="m14.5 12.5 2-2"/>
                        <path d="m11.5 9.5 2-2"/>
                        <path d="m8.5 6.5 2-2"/>
                        <path d="m17.5 15.5 2-2"/>
                    </svg>
                    Multiple Sizes
                </label>
                <input type="hidden" name="has_multiple_sizes" value="0">
                <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200" onchange="toggleSizeFields()" value="1">
            </div>

            <!-- Prices for Sizes (Hidden by Default) -->
            <div id="size-prices" class="hidden space-y-4 bg-gray-50 p-6 rounded-xl border border-gray-100">
                <label class="block text-sm font-medium text-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-currency-circle-dollar mr-2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                        <path d="M12 18V6"/>
                    </svg>
                    Size Prices
                </label>
                <div class="space-y-4">
                    <!-- Small -->
                    <div class="flex items-center space-x-3">
                        <label class="w-20 text-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-left mr-1">
                                <path d="m11 17-5-5 5-5"/>
                                <path d="m18 17-5-5 5-5"/>
                            </svg>
                            Small
                        </label>
                        <input type="number" step="0.01" name="price_small" id="price_small" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                    <!-- Medium -->
                    <div class="flex items-center space-x-3">
                        <label class="w-20 text-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus mr-1">
                                <path d="M5 12h14"/>
                            </svg>
                            Medium
                        </label>
                        <input type="number" step="0.01" name="price_medium" id="price_medium" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                    <!-- Large -->
                    <div class="flex items-center space-x-3">
                        <label class="w-20 text-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-right mr-1">
                                <path d="m6 17 5-5-5-5"/>
                                <path d="m13 17 5-5-5-5"/>
                            </svg>
                            Large
                        </label>
                        <input type="number" step="0.01" name="price_large" id="price_large" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                </div>
            </div>

            <!-- Single Price (Hidden by Default) -->
            <div id="single-price" class="hidden space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-currency-circle-dollar mr-2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                        <path d="M12 18V6"/>
                    </svg>
                    Price
                </label>
                <input type="number" step="0.01" name="price" id="price" class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
            </div>

            <!-- Availability -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check mr-2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    Status
                </label>
                <select name="is_available" class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="1">Available</option>
                    <option value="0">Not Available</option>
                </select>
            </div>

            <!-- Image -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image mr-2">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                    </svg>
                    Product Image
                </label>
                <div class="mt-1 flex items-center">
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2.5 file:px-4
                    file:rounded-xl file:border-0
                    file:text-sm file:font-medium
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100 transition duration-200">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-6">
            <button type="submit" class="px-5 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-1">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    Save Product
                </button>
                <a href="{{ route('admin.products') }}" class="px-5 py-2.5 border border-gray-200 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-1">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                    Cancel
                </a>
                
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to Toggle Size Fields -->
<script>
    function toggleSizeFields() {
        const hasMultipleSizes = document.getElementById('has_multiple_sizes').checked;
        const sizePricesDiv = document.getElementById('size-prices');
        const singlePriceDiv = document.getElementById('single-price');

        if (hasMultipleSizes) {
            sizePricesDiv.classList.remove('hidden');
            singlePriceDiv.classList.add('hidden');
            // Clear single price when switching to multiple sizes
            document.getElementById('price').value = '';
        } else {
            sizePricesDiv.classList.add('hidden');
            singlePriceDiv.classList.remove('hidden');
            // Clear size prices when switching to single price
            document.getElementById('price_small').value = '';
            document.getElementById('price_medium').value = '';
            document.getElementById('price_large').value = '';
        }
    }

    // Initialize the form based on the default state
    document.addEventListener('DOMContentLoaded', function () {
        // Start with single price visible by default
        document.getElementById('single-price').classList.remove('hidden');
        document.getElementById('size-prices').classList.add('hidden');
        toggleSizeFields();
    });
</script>
@endsection