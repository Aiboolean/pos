
<style>
    /* Your existing coffee shop theme styles */
    .coffee-card { background-color: #fff; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .coffee-btn-primary { background-color: #6f4e37; color: white; border: none; transition: all 0.3s ease; }
    .coffee-btn-primary:hover { background-color: #5c3d2a; transform: translateY(-1px); }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; border: none; transition: all 0.3s ease; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-text { color: #5c4d3c; }
    .coffee-focus:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
</style>

<div class="max-w-lg mx-auto coffee-card rounded-xl p-6">
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium coffee-text">Category:</label>
            <select name="category_id" id="category_id" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium coffee-text">Name:</label>
            <input type="text" name="name" value="{{ $product->name }}" required class="w-full p-2 coffee-border border rounded-lg coffee-focus">
        </div>

        <div class="flex items-center">
            <label class="block text-sm font-medium coffee-text mr-2">Has Multiple Sizes:</label>
            <input type="hidden" name="has_multiple_sizes" value="0"> 
            <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="h-5 w-5 coffee-border rounded focus:ring-[#8c7b6b]" value="1" {{ $product->has_multiple_sizes ? 'checked' : '' }}>
        </div>

        <div id="size-prices" class="{{ $product->has_multiple_sizes ? 'block' : 'hidden' }} space-y-3 mt-3">
            {{-- Your size price inputs... --}}
        </div>

        <div id="single-price" class="{{ !$product->has_multiple_sizes ? 'block' : 'hidden' }}">
            {{-- Your single price input... --}}
        </div>

        <div>
            <label class="block text-sm font-medium coffee-text">Status:</label>
            <select name="is_available" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium coffee-text">Image:</label>
            <input type="file" name="image" accept="image/*" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="mt-2 w-32 h-32 object-cover rounded border coffee-border">
            @endif
        </div>

        <div class="flex justify-end space-x-3 mt-6">
    <button type="submit" class="px-4 py-2 coffee-btn-primary rounded-lg shadow-sm">
        Update Product
    </button>
    <a href="{{ route('admin.products') }}" class="px-5 py-2.5 coffee-btn-secondary rounded-xl coffee-shadow text-sm font-medium flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-1">
            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
        </svg>
        Cancel
    </a>
</div>
    </form>
</div>

<script>
    (function() {
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
        // Run on load
        toggleSizeFields();
        // Add listener
        const sizeToggle = document.getElementById('has_multiple_sizes');
        if(sizeToggle) {
            sizeToggle.addEventListener('change', toggleSizeFields);
        }
    })();
</script>