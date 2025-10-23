<style>
    /* Your existing coffee shop theme styles */
    .coffee-card { background-color: #fff; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .coffee-btn-primary { background-color: #6f4e37; color: white; border: none; transition: all 0.3s ease; }
    .coffee-btn-primary:hover { background-color: #5c3d2a; transform: translateY(-1px); }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; border: none; transition: all 0.3s ease; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-btn-success { background-color: #8c7b6b; color: white; transition: all 0.2s ease; }
    .coffee-btn-success:hover { background-color: #6f4e37; }
    .coffee-btn-danger { background-color: #c45e4c; color: white; transition: all 0.2s ease; }
    .coffee-btn-danger:hover { background-color: #a34a3a; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-text { color: #5c4d3c; }
    .coffee-focus:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
    .coffee-input { border: 1px solid #e0d6c2; background-color: white; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-input:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
    .coffee-shadow { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    .coffee-toggle-bg { background-color: #f5f1ea; }
</style>

<div class="max-w-5xl mx-auto coffee-card rounded-xl p-6">
    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium coffee-text mb-1">Category:</label>
                    <select name="category_id" id="category_id" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium coffee-text mb-1">Name:</label>
                    <input type="text" name="name" value="{{ $product->name }}" required class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                </div>

                <div class="flex items-center space-x-3 p-4 coffee-toggle-bg rounded-xl">
                    <label class="block text-sm font-medium coffee-text">Has Multiple Sizes:</label>
                    <input type="hidden" name="has_multiple_sizes" value="0">
                    <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="h-5 w-5 coffee-border rounded focus:ring-[#8c7b6b]" value="1" {{ $product->has_multiple_sizes ? 'checked' : '' }}>
                </div>

                <div id="size-prices" class="{{ $product->has_multiple_sizes ? 'block' : 'hidden' }} space-y-4 coffee-toggle-bg p-6 rounded-xl coffee-border">
                    <label class="block text-sm font-medium coffee-text">Size Prices</label>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <label class="w-20 coffee-text">Small</label>
                            <input type="number" step="0.01" name="price_small" id="price_small" value="{{ $product->price_small }}" class="flex-1 px-4 py-2 coffee-input rounded-lg">
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="w-20 coffee-text">Medium</label>
                            <input type="number" step="0.01" name="price_medium" id="price_medium" value="{{ $product->price_medium }}" class="flex-1 px-4 py-2 coffee-input rounded-lg">
                        </div>
                        <div class="flex items-center space-x-3">
                            <label class="w-20 coffee-text">Large</label>
                            <input type="number" step="0.01" name="price_large" id="price_large" value="{{ $product->price_large }}" class="flex-1 px-4 py-2 coffee-input rounded-lg">
                        </div>
                    </div>
                </div>

                <div id="single-price" class="{{ !$product->has_multiple_sizes ? 'block' : 'hidden' }} space-y-2">
                    <label class="block text-sm font-medium coffee-text">Price:</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ $product->price }}" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium coffee-text">Status:</label>
                    <select name="is_available" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                        <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium coffee-text">Image:</label>
                    <input type="file" name="image" accept="image/*" class="w-full p-2 coffee-border border rounded-lg coffee-focus">
                    @if($product->image)
                        <div class="mt-2">
                            <p class="text-sm coffee-text mb-1">Current Image:</p>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded border coffee-border">
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="form-group">
                    <h4 class="text-lg font-medium coffee-text mb-3">Ingredients</h4>
                    <div id="ingredient-fields" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($product->ingredients as $index => $ingredient)
                        <div class="ingredient-row p-4 coffee-toggle-bg rounded-xl">
                            <div class="flex items-center mb-3">
                                <select name="ingredients[]" class="form-control mr-2 flex-1 coffee-input ingredient-select rounded-lg">
                                    <option value="">Select Ingredient</option>
                                    @foreach($ingredients as $i)
                                    <option value="{{ $i->id }}" 
                                        data-unit="{{ $i->unit }}"
                                        {{ $ingredient->id == $i->id ? 'selected' : '' }}>
                                        {{ $i->name }} ({{ $i->unit }})
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" class="remove-ingredient coffee-btn-danger px-3 py-2 rounded-lg flex items-center">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs coffee-text mb-1">Base Qty</label>
                                    <input type="number" step="0.01" name="quantities[]" value="{{ $ingredient->pivot->quantity }}" placeholder="0.00" class="w-full px-3 py-2 coffee-input rounded-lg base-quantity">
                                </div>
                                <div>
                                    <label class="block text-xs coffee-text mb-1">Small</label>
                                    <input type="number" step="0.01" name="small_multipliers[]" value="{{ $ingredient->pivot->small_multiplier ?? 0.75 }}" class="w-full px-3 py-2 coffee-input rounded-lg" readonly>
                                    <div class="calculated-quantity text-xs coffee-text mt-1 small-quantity text-center">
                                        {{ number_format(($ingredient->pivot->quantity ?? 0) * ($ingredient->pivot->small_multiplier ?? 0.75), 2) }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs coffee-text mb-1">Medium</label>
                                    <input type="number" step="0.01" name="medium_multipliers[]" value="{{ $ingredient->pivot->medium_multiplier ?? 1.00 }}" class="w-full px-3 py-2 coffee-input rounded-lg" readonly>
                                    <div class="calculated-quantity text-xs coffee-text mt-1 medium-quantity text-center">
                                        {{ number_format(($ingredient->pivot->quantity ?? 0) * ($ingredient->pivot->medium_multiplier ?? 1.00), 2) }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs coffee-text mb-1">Large</label>
                                    <input type="number" step="0.01" name="large_multipliers[]" value="{{ $ingredient->pivot->large_multiplier ?? 1.50 }}" class="w-full px-3 py-2 coffee-input rounded-lg" readonly>
                                    <div class="calculated-quantity text-xs coffee-text mt-1 large-quantity text-center">
                                        {{ number_format(($ingredient->pivot->quantity ?? 0) * ($ingredient->pivot->large_multiplier ?? 1.50), 2) }}
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-ingredient" class="coffee-btn-secondary mt-4 px-4 py-2 rounded-lg flex items-center justify-center w-full">
                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add Ingredient
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <button type="submit" class="px-4 py-2 coffee-btn-success rounded-lg shadow-sm">
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
            const hasMultipleSizes = document.getElementById('has_multiple_sizes');
            const sizePricesDiv = document.getElementById('size-prices');
            const singlePriceDiv = document.getElementById('single-price');
            if (hasMultipleSizes.checked) {
                sizePricesDiv.classList.remove('hidden');
                singlePriceDiv.classList.add('hidden');
            } else {
                sizePricesDiv.classList.add('hidden');
                singlePriceDiv.classList.remove('hidden');
            }
        }

        function calculateSizeQuantities(input) {
            const row = input.closest('.ingredient-row');
            if (!row) return;
            const baseQty = parseFloat(input.value) || 0;
            
            row.querySelector('.small-quantity').textContent = (baseQty * 0.75).toFixed(2);
            row.querySelector('.medium-quantity').textContent = (baseQty * 1.00).toFixed(2);
            row.querySelector('.large-quantity').textContent = (baseQty * 1.50).toFixed(2);
        }

        function updateIngredientUnit(select) {
            const row = select.closest('.ingredient-row');
            if (!row) return;
            const selectedOption = select.options[select.selectedIndex];
            const unit = selectedOption.getAttribute('data-unit') || '-';
            row.querySelector('.ingredient-unit').textContent = unit;
        }

        // Run on load
        toggleSizeFields();
        
        // Add listener
        const sizeToggle = document.getElementById('has_multiple_sizes');
        if(sizeToggle) {
            sizeToggle.addEventListener('change', toggleSizeFields);
        }

        const addIngredientBtn = document.getElementById('add-ingredient');
        if (addIngredientBtn) {
            addIngredientBtn.addEventListener('click', function() {
                const container = document.getElementById('ingredient-fields');
                const newField = `
                <div class="ingredient-row p-4 coffee-toggle-bg rounded-xl">
                    <div class="flex items-center mb-3">
                        <select name="ingredients[]" class="form-control mr-2 flex-1 coffee-input ingredient-select rounded-lg">
                            <option value="">Select Ingredient</option>
                            @foreach($ingredients as $i)
                            <option value="{{ $i->id }}" data-unit="{{ $i->unit }}">{{ $i->name }} ({{ $i->unit }})</option>
                            @endforeach
                        </select>
                        <button type="button" class="remove-ingredient coffee-btn-danger px-3 py-2 rounded-lg flex items-center">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="block text-xs coffee-text mb-1">Base Qty</label><input type="number" step="0.01" name="quantities[]" placeholder="0.00" class="w-full px-3 py-2 coffee-input rounded-lg base-quantity"></div>
                        <div><label class="block text-xs coffee-text mb-1">Small</label><input type="number" step="0.01" name="small_multipliers[]" value="0.75" class="w-full px-3 py-2 coffee-input rounded-lg" readonly><div class="calculated-quantity text-xs coffee-text mt-1 small-quantity text-center">0.00</div></div>
                        <div><label class="block text-xs coffee-text mb-1">Medium</label><input type="number" step="0.01" name="medium_multipliers[]" value="1.00" class="w-full px-3 py-2 coffee-input rounded-lg" readonly><div class="calculated-quantity text-xs coffee-text mt-1 medium-quantity text-center">0.00</div></div>
                        <div><label class="block text-xs coffee-text mb-1">Large</label><input type="number" step="0.01" name="large_multipliers[]" value="1.50" class="w-full px-3 py-2 coffee-input rounded-lg" readonly><div class="calculated-quantity text-xs coffee-text mt-1 large-quantity text-center">0.00</div></div>
            
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', newField);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        const ingredientFields = document.getElementById('ingredient-fields');
        if (ingredientFields) {
            ingredientFields.addEventListener('click', function(e) {
                if (e.target.closest('.remove-ingredient')) {
                    e.target.closest('.ingredient-row').remove();
                }
            });
            ingredientFields.addEventListener('input', function(e) {
                if (e.target.classList.contains('base-quantity')) {
                    calculateSizeQuantities(e.target);
                }
            });
            ingredientFields.addEventListener('change', function(e) {
                if (e.target.classList.contains('ingredient-select')) {
                    updateIngredientUnit(e.target);
                }
            });
        }
        
        // Initialize unit display for existing ingredients
        document.querySelectorAll('.ingredient-select').forEach(select => {
            if (select.value) {
                updateIngredientUnit(select);
            }
        });
    })();
</script>