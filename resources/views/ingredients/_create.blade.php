

{{-- The form will be rendered in both cases (full page and modal) --}}
<div class="container mx-auto px-4 py-6 coffee-bg">
    <div class="coffee-card p-6">
        @if(!request()->ajax())
        <h1 class="text-2xl font-bold mb-6 coffee-text-primary">Add New Ingredient</h1>
        @endif
        
        <form action="{{ route('ingredients.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block font-medium mb-2 coffee-text-primary">Name</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 rounded-lg coffee-input" required>
            </div>
            
            <div class="mb-4">
                <label for="unit" class="block font-medium mb-2 coffee-text-primary">Unit</label>
                <select name="unit" id="unit" class="w-full px-4 py-2 rounded-lg coffee-input" required>
                    <option value="">Select Unit</option>
                    <option value="g">Grams (g)</option>
                    <option value="ml">Milliliters (ml)</option>
                    <option value="oz">Ounces (oz)</option>
                    <option value="pcs">Pieces</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="stock" class="block font-medium mb-2 coffee-text-primary">Initial Stock</label>
                <input type="number" step="0.01" name="stock" id="stock" class="w-full px-4 py-2 rounded-lg coffee-input" required>
            </div>
            
            <div class="mb-6">
                <label for="alert_threshold" class="block font-medium mb-2 coffee-text-primary">Low Stock Alert (optional)</label>
                <input type="number" step="0.01" name="alert_threshold" id="alert_threshold" class="w-full px-4 py-2 rounded-lg coffee-input">
                <p class="coffee-text-secondary text-sm mt-1">System will warn when stock falls below this level</p>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="px-4 py-2 rounded-lg coffee-btn-primary">Save Ingredient</button>
                {{-- ✅ FIX: Changed <a> to <button> with "cancel-btn" class --}}
                <button type="button" class="px-4 py-2 rounded-lg coffee-btn-secondary cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>

@if(!request()->ajax())
    @endsection
@endif