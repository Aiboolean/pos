@extends('layouts.app')

@section('content')
<style>
    /* Custom Coffee Shop Theme */
    .coffee-btn-primary {
        background-color: #6f4e37;
        color: #fff;
        border: 1px solid #5c3d2a;
        transition: all 0.3s ease;
    }
    .coffee-btn-primary:hover {
        background-color: #5c3d2a;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .coffee-btn-success {
        background-color: #8c7b6b;
        color: #fff;
        border: 1px solid #6f4e37;
        transition: all 0.3s ease;
    }
    .coffee-btn-success:hover {
        background-color: #6f4e37;
    }

    .coffee-btn-danger {
        background-color: #c45e4c;
        color: #fff;
        border: 1px solid #a34a3a;
        transition: all 0.3s ease;
    }
    .coffee-btn-danger:hover {
        background-color: #a34a3a;
    }

    .coffee-btn-secondary {
        background-color: #e0d6c2;
        color: #5c4d3c;
        border: 1px solid #d4c9b5;
        transition: all 0.3s ease;
    }
    .coffee-btn-secondary:hover {
        background-color: #d4c9b5;
    }

    .coffee-bg {
        background-color: #f5f1ea;
    }

    .coffee-card {
        background-color: #fff;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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

    .available {
        color: #6f8c6b;
    }

    .unavailable {
        color: #c45e4c;
    }

    .coffee-table-header {
        background-color: #f5f1ea;
        color: #5c4d3c;
    }

    .coffee-table-row:hover {
        background-color: #f9f7f3;
    }
</style>

<div class="min-h-full coffee-bg">
    <main class="p-6">
        <div class="coffee-card p-6 rounded-lg shadow-lg">
            <div class="flex items-center mb-6">
                <h1 class="text-2xl font-bold coffee-text-primary">
                    <i data-lucide="coffee" class="inline-block w-6 h-6 coffee-text-secondary mr-2"></i>
                    Products Management
                </h1>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <a href="{{ route('products.create') }}" 
                   class="coffee-btn-success px-4 py-2 rounded-lg flex items-center shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                    Add New Product
                </a>
                
                <form method="GET" action="{{ route('admin.products') }}" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative">
                        <select name="category" id="category" 
                                class="pl-10 pr-4 py-2 coffee-border border rounded-lg shadow-sm bg-white coffee-text-primary focus:ring-2 focus:ring-[#8c7b6b] focus:border-[#8c7b6b] transition w-full sm:w-64">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" 
                            class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center shadow-sm">
                        <i data-lucide="search" class="w-4 h-4 mr-1"></i>
                        Filter
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto pb-16">
                <table class="w-full coffee-border border rounded-lg">
                    <thead>
                        <tr class="coffee-table-header">
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Category</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Availability</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y coffee-border">
                        @foreach($products as $product)
                        <tr class="coffee-table-row transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium coffee-text-primary">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $product->category->name ?? 'No Category' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="font-medium {{ $product->is_available ? 'available' : 'unavailable' }}">
                                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">
                                <div class="flex space-x-3">
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="coffee-btn-secondary px-3 py-1 rounded-lg flex items-center shadow-sm">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="coffee-btn-danger px-3 py-1 rounded-lg flex items-center shadow-sm"
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