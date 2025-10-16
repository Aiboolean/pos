{{-- This conditional statement is the key change --}}
@if(!request()->ajax())
    @extends('layouts.app')
    @section('content')
@endif

{{-- The entire style block and form will now be sent in both cases --}}
<style>
    /* Coffee Shop Theme CSS */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-btn-primary { background-color: #6f4e37; color: white; transition: all 0.2s ease; }
    .coffee-btn-primary:hover { background-color: #5c3d2a; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .coffee-btn-success { background-color: #8c7b6b; color: white; transition: all 0.2s ease; }
    .coffee-btn-success:hover { background-color: #6f4e37; }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-btn-danger { background-color: #c45e4c; color: white; transition: all 0.2s ease; }
    .coffee-btn-danger:hover { background-color: #a34a3a; }
    .coffee-input { border: 1px solid #e0d6c2; background-color: white; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-input:focus { outline: none; ring: 2px; ring-color: #8c7b6b; border-color: #8c7b6b; }
    .coffee-error { color: #c45e4c; }
    .coffee-shadow { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    .coffee-textarea { min-height: 100px; resize: vertical; }
</style>

<div class="min-h-full coffee-bg">
    <main class="p-6">
        <div class="coffee-card p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('categories.index') }}" class="mr-4 text-[#6f4e37] hover:text-[#5c3d2a] transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-2xl font-bold coffee-text-primary">
                    Create New Coffee Category
                </h1>
            </div>

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
                               placeholder="Espresso Drinks, Brewed Coffee, etc.">
                        @error('name')
                            <p class="mt-1 text-sm coffee-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="submit" class="coffee-btn-success px-4 py-2 rounded-lg flex items-center coffee-shadow">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                        Create Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="coffee-btn-secondary px-4 py-2 rounded-lg flex items-center coffee-shadow">
                        <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

{{-- This part also needs to be inside the conditional block --}}
@if(!request()->ajax())
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/lucide.css">
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    @endpush

    @endsection
@endif