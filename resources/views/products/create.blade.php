
<style>
    /* Coffee Shop Theme CSS */
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
     .coffee-btn-danger {
        background-color: #c45e4c;
        color: white;
        transition: all 0.2s ease;
    }
    .coffee-btn-danger:hover {
        background-color: #a34a3a;
    }
    .coffee-input {
        border: 1px solid #e0d6c2;
        background-color: white;
        color: #5c4d3c;
        transition: all 0.2s ease;
    }
    .coffee-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px #8c7b6b40;
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

<div class="w-full">
    {{-- ✅ FIX: Removed mx-auto as modal handles centering. Adjusted padding slightly (p-6) --}}
    <div class="max-w-5xl coffee-card overflow-hidden p-6 md:p-8">
        <div class="flex items-center mb-6"> {{-- Reduced mb slightly --}}
            {{-- Hide the back arrow when in the modal --}}
            @if(!request()->ajax())
            <a href="{{ route('admin.products') }}" class="mr-4 text-[#6f4e37] hover:text-[#5c3d2a] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            @endif
            
        </div>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8"> {{-- Adjusted gap slightly --}}
                {{-- Left Column --}}
                <div class="space-y-5"> {{-- Adjusted spacing slightly --}}
                    {{-- Category --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium coffee-text-primary flex items-center">
                            <i data-lucide="tag" class="w-4 h-4 mr-2"></i> Category
                        </label>
                        <select name="category_id" id="category_id" class="w-full px-4 py-2.5 coffee-input rounded-lg coffee-shadow focus:ring-2 focus:ring-[#8c7b6b]">
                            <option value="" selected disabled hidden>Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Name --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium coffee-text-primary flex items-center">
                            <i data-lucide="package-plus" class="w-4 h-4 mr-2"></i> Product Name
                        </label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 coffee-input rounded-lg coffee-shadow focus:ring-2 focus:ring-[#8c7b6b]">
                    </div>

                    {{-- Multiple Sizes Toggle --}}
                    <div class="flex items-center space-x-3 p-3 coffee-toggle-bg rounded-lg"> {{-- Reduced padding slightly --}}
                        <label class="block text-sm font-medium coffee-text-primary flex items-center">
                           <i data-lucide="ruler" class="w-4 h-4 mr-2"></i> Multiple Sizes
                        </label>
                        <input type="hidden" name="has_multiple_sizes" value="0">
                        <input type="checkbox" name="has_multiple_sizes" id="has_multiple_sizes" class="h-5 w-5 text-[#8c7b6b] focus:ring-[#8c7b6b] coffee-border rounded transition duration-200" value="1">
                    </div>

                    {{-- Size Prices --}}
                    <div id="size-prices" class="hidden space-y-3 coffee-toggle-bg p-4 rounded-lg coffee-border"> {{-- Adjusted spacing/padding --}}
                        <label class="block text-sm font-medium coffee-text-primary flex items-center"><i data-lucide="dollar-sign" class="w-4 h-4 mr-2"></i> Size Prices</label>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3"><label class="w-20 coffee-text-primary text-sm">Small</label><input type="number" step="0.01" name="price_small" id="price_small" class="flex-1 px-3 py-2 coffee-input rounded-lg text-sm"></div>
                            <div class="flex items-center space-x-3"><label class="w-20 coffee-text-primary text-sm">Medium</label><input type="number" step="0.01" name="price_medium" id="price_medium" class="flex-1 px-3 py-2 coffee-input rounded-lg text-sm"></div>
                            <div class="flex items-center space-x-3"><label class="w-20 coffee-text-primary text-sm">Large</label><input type="number" step="0.01" name="price_large" id="price_large" class="flex-1 px-3 py-2 coffee-input rounded-lg text-sm"></div>
                        </div>
                    </div>

                    {{-- Single Price --}}
                    <div id="single-price" class="space-y-1">
                        <label class="block text-sm font-medium coffee-text-primary"><i data-lucide="dollar-sign" class="w-4 h-4 mr-2 inline"></i> Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="w-full px-4 py-2.5 coffee-input rounded-lg">
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium coffee-text-primary"><i data-lucide="check-circle" class="w-4 h-4 mr-2 inline"></i> Status</label>
                        <select name="is_available" class="w-full px-4 py-2.5 coffee-input rounded-lg">
                            <option value="1">Available</option>
                            <option value="0">Not Available</option>
                        </select>
                    </div>

                    {{-- Image --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium coffee-text-primary"><i data-lucide="image" class="w-4 h-4 mr-2 inline"></i> Product Image</label>
                        <input type="file" name="image" accept="image/*" class="block w-full text-sm coffee-text-primary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-medium file:bg-[#e0d6c2] file:text-[#5c4d3c] hover:file:bg-[#d4c9b5] transition coffee-file-input">
                    </div>
                </div>
                
                {{-- Right Column - Ingredients --}}
                <div class="space-y-5"> {{-- Adjusted spacing --}}
                    <div class="form-group">
                        <h4 class="text-lg font-medium coffee-text-primary mb-3">Ingredients</h4>
                        <div id="ingredient-fields" class="space-y-4 max-h-80 overflow-y-auto pr-2"> {{-- Adjusted max-height --}}
                            {{-- Rows added by JS --}}
                        </div>
                        <button type="button" id="add-ingredient" class="coffee-btn-secondary mt-4 px-4 py-2 rounded-lg flex items-center justify-center w-full text-sm">
                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add Ingredient
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-6 mt-6 border-t border-[#d4c9b5]"> {{-- Adjusted padding/margin --}}
                <button type="submit" class="px-5 py-2.5 coffee-btn-success rounded-xl text-sm">Save Product</button>
                <button type="button" class="cancel-btn px-5 py-2.5 coffee-btn-secondary rounded-xl text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- This script is NOT wrapped in @if, so it gets sent with the AJAX response --}}
<script>
    (function() {
        function toggleSizeFields() {
            const hasMultipleSizes = document.getElementById('has_multiple_sizes');
            if (!hasMultipleSizes) return;
            const sizePricesDiv = document.getElementById('size-prices');
            const singlePriceDiv = document.getElementById('single-price');

            if (hasMultipleSizes.checked) {
                sizePricesDiv.classList.remove('hidden');
                singlePriceDiv.classList.add('hidden');
                if (document.getElementById('price')) document.getElementById('price').value = '';
            } else {
                sizePricesDiv.classList.add('hidden');
                singlePriceDiv.classList.remove('hidden');
                if (document.getElementById('price_small')) document.getElementById('price_small').value = '';
                if (document.getElementById('price_medium')) document.getElementById('price_medium').value = '';
                if (document.getElementById('price_large')) document.getElementById('price_large').value = '';
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

        const sizeToggle = document.getElementById('has_multiple_sizes');
        if (sizeToggle) {
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
                        <div><label class="block text-xs coffee-text-primary mb-1">Base Qty</label><input type="number" step="0.01" name="quantities[]" placeholder="0.00" class="w-full px-3 py-2 coffee-input rounded-lg base-quantity"></div>
                        <div><label class="block text-xs coffee-text-primary mb-1">Small</label><input type="number" step="0.01" name="small_multipliers[]" value="0.75" class="w-full px-3 py-2 coffee-input rounded-lg" readonly><div class="calculated-quantity text-xs coffee-text-secondary mt-1 small-quantity text-center">0.00</div></div>
                        <div><label class="block text-xs coffee-text-primary mb-1">Medium</label><input type="number" step="0.01" name="medium_multipliers[]" value="1.00" class="w-full px-3 py-2 coffee-input rounded-lg" readonly><div class="calculated-quantity text-xs coffee-text-secondary mt-1 medium-quantity text-center">0.00</div></div>
                        <div><label class="block text-xs coffee-text-primary mb-1">Large</label><input type="number" step="0.01" name="large_multipliers[]" value="1.50" class="w-full px-3 py-2 coffee-input rounded-lg" readonly><div class="calculated-quantity text-xs coffee-text-secondary mt-1 large-quantity text-center">0.00</div></div>
                        <div class="flex items-end justify-center"><span class="ingredient-unit text-sm coffee-text-primary font-medium">-</span></div>
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
        
        // --- INITIALIZE THE FORM STATE ON LOAD ---
        toggleSizeFields();

    })();
</script>

{{-- This closing tag is also conditional --}}
@if(!request()->ajax())
    @endsection
@endif