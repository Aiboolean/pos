@extends('layouts.app')

@section('content')
<div class="min-h-full bg-[#f5f1ea]">
    <main class="p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg border border-[#e0d6c2]">
            <div class="flex items-center mb-6">
                <h1 class="text-2xl font-bold text-[#5c4d3c]">
                    <i data-lucide="coffee" class="inline-block w-6 h-6 text-[#8c7b6b] mr-2"></i>
                     Products Management
                </h1>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <a href="{{ route('products.create') }}" 
       class="bg-[#6f4e37] hover:bg-[#5c3d2a] text-black px-4 py-2 rounded-lg flex items-center transition-colors shadow-md">
        <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
        Add New Product
    </a>
    
    <form method="GET" action="{{ route('admin.products') }}" class="flex flex-col sm:flex-row items-center gap-3">
        
        <div class="relative">
            
            <select name="category" id="category" 
                    class="pl-10 pr-4 py-2 border border-[#e0d6c2] rounded-lg shadow-sm bg-white text-[#000000] focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition w-full sm:w-64">
                    
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            
        </div>
        <button type="submit" 
                class="bg-[#6f4e37] hover:bg-[#5c3d2a] text-black px-4 py-2 rounded-lg flex items-center transition-colors shadow-md">
            <i data-lucide="search" class="w-4 h-4 mr-1"></i>
            Filter
        </button>
    </form>
</div>

            <div class="overflow-x-auto pb-16">
                <table class="w-full border border-[#e0d6c2] rounded-lg">
                    <thead class="bg-[#f5f1ea] text-[#5c4d3c]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Category</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Availability</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e0d6c2]">
                        @foreach($products as $product)
                        <tr class="hover:bg-[#f9f7f3] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#5c4d3c]">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#5c4d3c]">{{ $product->category->name ?? 'No Category' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="font-medium {{ $product->is_available ? 'text-[#4caf50]' : 'text-[#c45e4c]' }}">
                                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#5c4d3c]">
                                <div class="flex space-x-3">
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="bg-[#e0d6c2] hover:bg-[#d4c9b5] text-[#5c4d3c] px-3 py-1 rounded-lg flex items-center shadow-sm">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-[#c45e4c] hover:bg-[#a34a3a] text-black px-3 py-1 rounded-lg flex items-center shadow-sm"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->appends(['category' => request('category')])->links() }}
            </div>
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