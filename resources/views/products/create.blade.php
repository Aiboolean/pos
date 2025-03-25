@extends('layouts.app')

@section('content')
<div class="min-h-full bg-[#f5f1ea]">
    <main class="p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg border border-[#e0d6c2]">
            <div class="flex items-center mb-6">
                <a href="{{ route('admin.products') }}" class="mr-4 text-[#6f4e37] hover:text-[#5c3d2a]">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-2xl font-bold text-[#5c4d3c]">
                    Add Coffee Product
                </h1>
            </div>

            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-[#5c4d3c] mb-1">
                        <i data-lucide="tags" class="inline-block w-4 h-4 mr-1"></i>
                        Category:
                    </label>
                    <select name="category_id" id="category_id" class="w-full p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                        <option value="" selected disabled hidden>Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-[#5c4d3c] mb-1">
                        <i data-lucide="coffee" class="inline-block w-4 h-4 mr-1"></i>
                        Product Name:
                    </label>
                    <input type="text" name="name" required class="w-full p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                </div>

                <!-- Toggle for Multiple Sizes -->
                <div class="flex items-center">
                    <label class="block text-sm font-medium text-[#5c4d3c] mr-3">
                        <i data-lucide="ruler" class="inline-block w-4 h-4 mr-1"></i>
                        Has Multiple Sizes:
                    </label>
                    <input type="hidden" name="has_multiple_sizes" value="0">
                    <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="h-5 w-5 text-[#6f4e37] focus:ring-[#8c7b6b] border-[#e0d6c2] rounded" onchange="toggleSizeFields()" value="1">
                </div>

                <!-- Prices for Sizes (Hidden by Default) -->
                <div id="size-prices" class="hidden space-y-3">
                    <label class="block text-sm font-medium text-[#5c4d3c]">
                        <i data-lucide="dollar-sign" class="inline-block w-4 h-4 mr-1"></i>
                        Size Prices:
                    </label>
                    <!-- Small -->
                    <div class="flex items-center space-x-3">
                        <label class="w-20 text-[#5c4d3c]">Small:</label>
                        <input type="number" step="0.01" name="price_small" id="price_small" class="flex-1 p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                    </div>
                    <!-- Medium -->
                    <div class="flex items-center space-x-3">
                        <label class="w-20 text-[#5c4d3c]">Medium:</label>
                        <input type="number" step="0.01" name="price_medium" id="price_medium" class="flex-1 p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                    </div>
                    <!-- Large -->
                    <div class="flex items-center space-x-3">
                        <label class="w-20 text-[#5c4d3c]">Large:</label>
                        <input type="number" step="0.01" name="price_large" id="price_large" class="flex-1 p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                    </div>
                </div>

                <!-- Single Price (Hidden by Default) -->
                <div id="single-price" class="hidden">
                    <label class="block text-sm font-medium text-[#5c4d3c] mb-1">
                        <i data-lucide="dollar-sign" class="inline-block w-4 h-4 mr-1"></i>
                        Price:
                    </label>
                    <input type="number" step="0.01" name="price" id="price" class="w-full p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                </div>

                <!-- Availability -->
                <div>
                    <label class="block text-sm font-medium text-[#5c4d3c] mb-1">
                        <i data-lucide="check-circle" class="inline-block w-4 h-4 mr-1"></i>
                        Status:
                    </label>
                    <select name="is_available" class="w-full p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                        <option value="1">Available</option>
                        <option value="0">Not Available</option>
                    </select>
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-[#5c4d3c] mb-1">
                        <i data-lucide="image" class="inline-block w-4 h-4 mr-1"></i>
                        Product Image:
                    </label>
                    <input type="file" name="image" accept="image/*" class="w-full p-2 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-[#e0d6c2] hover:bg-[#d4c9b5] text-black rounded-lg flex items-center transition-colors shadow-md border border-[#d4c9b5]">
                        <i data-lucide="x" class="w-4 h-4 mr-1 text-black"></i>
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-[#4caf50] hover:bg-[#3d8b40] text-black rounded-lg flex items-center transition-colors shadow-md">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-1 text-black"></i>
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/lucide.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Initialize Lucide icons
    lucide.createIcons();

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

    // Initialize the form based on the default state
    document.addEventListener('DOMContentLoaded', function () {
        toggleSizeFields();
    });
</script>
@endpush
@endsection