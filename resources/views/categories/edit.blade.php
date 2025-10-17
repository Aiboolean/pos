

{{-- The rest of the file (styles and form) will be rendered for both the page and the modal --}}
<style>
    /* Your existing styles for the edit form */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-btn-success { background-color: #8c7b6b; color: white; transition: all 0.2s ease; }
    .coffee-btn-success:hover { background-color: #6f4e37; }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-input { border: 1px solid #e0d6c2; background-color: white; color: #5c4d3c; }
    .coffee-input:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
    .coffee-error { color: #c45e4c; }
    .coffee-shadow { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
</style>

<div class="min-h-full coffee-bg">
    <main class="p-6">
        <div class="coffee-card p-6">
            <div class="flex items-center mb-6">
                {{-- Hide the back arrow inside the modal, as it doesn't make sense there --}}
                @if(!request()->ajax())
                <a href="{{ route('categories.index') }}" class="mr-4 text-[#6f4e37] hover:text-[#5c3d2a] transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                @endif
                <h1 class="text-2xl font-bold coffee-text-primary">
                    Edit Category
                </h1>
            </div>

            <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium coffee-text-primary mb-1 flex items-center">
                            <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                            Category Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                               class="w-full p-3 coffee-input rounded-lg coffee-shadow focus:ring-2 focus:ring-[#8c7b6b]">
                        @error('name')
                            <p class="mt-1 text-sm coffee-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" 
                            class="coffee-btn-success px-4 py-2 rounded-lg flex items-center coffee-shadow">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                        Update Category
                    </button>
                    {{-- This button now closes the modal via JavaScript --}}
                    
                </div>
            </form>
        </div>
    </main>
</div>

{{-- This part also needs to be inside the conditional block --}}
@if(!request()->ajax())
    @push('scripts')
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>
    @endpush
    @endsection
@endif