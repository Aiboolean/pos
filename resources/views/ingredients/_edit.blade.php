{{-- File: resources/views/ingredients/_edit.blade.php --}}

<form method="POST" action="{{ route('ingredients.update', $ingredient->id) }}" class="space-y-4">
    @csrf
    @method('PUT')
    <div>
        <label for="name" class="block font-medium mb-1 coffee-text-primary">Ingredient Name</label>
        <input type="text" id="name" name="name" value="{{ $ingredient->name }}" required class="w-full p-3 coffee-input rounded-lg">
    </div>
    <div>
        <label for="unit" class="block font-medium mb-1 coffee-text-primary">Unit of Measurement</label>
        <input type="text" id="unit" name="unit" value="{{ $ingredient->unit }}" required class="w-full p-3 coffee-input rounded-lg">
    </div>
    <div>
        <label for="stock" class="block font-medium mb-1 coffee-text-primary">Current Stock</label>
        <input type="number" step="0.01" id="stock" name="stock" value="{{ $ingredient->stock }}" required class="w-full p-3 coffee-input rounded-lg">
    </div>
    <div>
        <label for="alert_threshold" class="block font-medium mb-1 coffee-text-primary">Low Stock Alert Threshold</label>
        <input type="number" step="0.01" id="alert_threshold" name="alert_threshold" value="{{ $ingredient->alert_threshold }}" required class="w-full p-3 coffee-input rounded-lg">
    </div>
    <div class="flex space-x-3 pt-4">
        <button type="submit" class="w-full coffee-btn-success py-3 rounded-lg font-semibold">
            Update Ingredient
        </button>
        <button type="button" class="cancel-btn w-full coffee-btn-danger py-3 rounded-lg font-semibold">
            Cancel
        </button>
    </div>
</form>