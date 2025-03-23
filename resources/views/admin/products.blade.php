@extends('layouts.app')

@section('content')
<div class="bg-[#f1eadc] min-h-screen flex justify-center px-4 py-10">
    <div class="max-w-6xl w-full bg-white p-8 rounded-xl shadow-lg relative">
        <h2 class="text-3xl font-semibold mb-6 text-gray-700">Product Management</h2>
        
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
            <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-5 py-2 rounded-lg font-medium transition hover:bg-blue-600">
                Add New Product
            </a>
            
            <form method="GET" action="{{ route('admin.products') }}" class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
                <label for="category" class="text-sm font-medium text-gray-700">Filter by Category:</label>
                <select name="category" id="category" class="border p-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-lg font-medium transition hover:bg-blue-600">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table Wrapper with More Bottom Padding to Avoid Overlap -->
        <div class="overflow-x-auto pb-24">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Availability</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->category->name ?? 'No Category' }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium {{ $product->is_available ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex space-x-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination (Inside Container, No Overlap, Fixed in Bottom Right) -->
        <div class="absolute bottom-4 right-4 bg-white p-2 rounded-lg shadow-lg">
            {{ $products->appends(['category' => request('category')])->links() }}
        </div>
    </div>
</div>
@endsection  
