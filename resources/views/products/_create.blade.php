{{-- This is the crucial change: only extend the layout if it's NOT a modal request --}}
@if(!request()->ajax())
    @extends('layouts.app')
    @section('content')
@endif

{{-- The rest of the file (styles, HTML, and scripts) will be rendered in BOTH cases --}}
<style>
    /* Your existing CSS for the create form */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-btn-success { background-color: #8c7b6b; color: white; transition: all 0.2s ease; }
    .coffee-btn-success:hover { background-color: #6f4e37; }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-input { border: 1px solid #e0d6c2; background-color: white; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-input:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
    .coffee-shadow { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    .coffee-toggle-bg { background-color: #f5f1ea; }
    .coffee-file-input { border-color: #e0d6c2; }
    .coffee-file-input:hover { background-color: #f5f1ea; }
</style>

<div class="min-h-screen coffee-bg py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto coffee-card overflow-hidden p-8">
        <div class="flex items-center mb-8">
            {{-- Hide the back arrow when in the modal --}}
            @if(!request()->ajax())
            <a href="{{ route('admin.products') }}" class="mr-4 text-[#6f4e37] hover:text-[#5c3d2a] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            @endif
            <h1 class="text-2xl font-bold coffee-text-primary">Add New Product</h1>
        </div>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    {{-- All your form fields like Category, Name, Prices, Image, etc. --}}
                </div>
                
                <div class="space-y-6">
                    {{-- Your ingredients section --}}
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-8 mt-6 border-t border-[#d4c9b5]">
                <button type="submit" class="px-5 py-2.5 coffee-btn-success rounded-xl coffee-shadow text-sm font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-1"><path d="M20 6 9 17l-5-5"/></svg>
                    Save Product
                </button>
                <button type="button" class="cancel-btn px-5 py-2.5 coffee-btn-secondary rounded-xl coffee-shadow text-sm font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-1"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- This script is NOT wrapped, so it gets sent with the AJAX response and executed by your helper function --}}
<script>
    // All of your existing JavaScript for this form (toggleSizeFields, add-ingredient, etc.)
    // MUST be placed here.
    function toggleSizeFields() { /* ... */ }
    function calculateSizeQuantities(input) { /* ... */ }
    function updateIngredientUnit(select) { /* ... */ }

    document.getElementById('add-ingredient').addEventListener('click', function() { /* ... */ });
    document.addEventListener('click', function(e) { /* ... for removing ingredients ... */ });

    // Initialize fields on load
    document.getElementById('single-price').classList.remove('hidden');
    document.getElementById('size-prices').classList.add('hidden');
    toggleSizeFields();
</script>

{{-- This closing tag is also conditional --}}
@if(!request()->ajax())
    @endsection
@endif