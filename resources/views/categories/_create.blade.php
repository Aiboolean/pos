{{-- File: resources/views/categories/_create.blade.php --}}

<form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
    @csrf

    <div class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium coffee-text-primary mb-1 flex items-center">
                <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                Category Name
            </label>
            <input type="text" id="name" name="name" required
                   class="w-full p-3 coffee-input rounded-lg coffee-shadow focus:ring-2 focus:ring-[#8c7b6b]"
                   placeholder="e.g., Espresso Drinks">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <button type="submit" 
                class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center coffee-shadow">
            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
            Create Category
        </button>
    </div>
</form>