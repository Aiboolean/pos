@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Product Management</h2>
    
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add New Product</a>
        
        <form method="GET" action="{{ route('admin.products') }}" class="flex items-center">
            <label for="category" class="mr-2">Filter by Category:</label>
            <select name="category" id="category" class="border p-2 rounded">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Filter</button>
        </form>
    </div>

    
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Name</th>
                <th class="p-2 border">Category</th>
                <th class="p-2 border">Availability</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border">
                <td class="p-2 border">{{ $product->name }}</td>
                <td class="p-2 border">{{ $product->category->name ?? 'No Category' }}</td>
                <td class="p-2 border">
                    {{ $product->is_available ? 'Available' : 'Unavailable' }}
                </td>
                <td class="p-2 border flex space-x-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
