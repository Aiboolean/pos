@extends('layouts.app')

@section('content')
<div class="min-h-full bg-[#f5f1ea]">
    <main class="p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg border border-[#e0d6c2]">
            <div class="flex items-center mb-6">
                <a href="{{ route('categories.index') }}" class="mr-4 text-[#6f4e37] hover:text-[#5c3d2a]">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-2xl font-bold text-[#5c4d3c]">
                    Create New Coffee Category
                </h1>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-4">
                    <!-- Category Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#5c4d3c] mb-1">
                            <i data-lucide="tag" class="inline-block w-4 h-4 mr-1"></i>
                            Category Name
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition"
                               placeholder="Espresso Drinks, Brewed Coffee, etc.">
                        @error('name')
                            <p class="mt-1 text-sm text-[#c45e4c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Field (Optional) -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-[#5c4d3c] mb-1">
                            <i data-lucide="file-text" class="inline-block w-4 h-4 mr-1"></i>
                            Description (Optional)
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full p-3 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#5c4d3c] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition"
                                  placeholder="Brief description of this coffee category"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
    <a href="{{ route('categories.index') }}" 
       class="px-4 py-2 bg-[#e0d6c2] hover:bg-[#d4c9b5] text-black rounded-lg flex items-center transition-colors shadow-md border border-[#d4c9b5]">
        <i data-lucide="x" class="w-4 h-4 mr-1 text-black"></i>
        Cancel
    </a>
    <button type="submit" 
            class="px-4 py-2 bg-[#4caf50] hover:bg-[#3d8b40] text-black rounded-lg flex items-center transition-colors shadow-md">
        <i data-lucide="check-circle" class="w-4 h-4 mr-1 text-black"></i>
        Create Category
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
</script>
@endpush
@endsection