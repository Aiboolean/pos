@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Ingredients</h1>
            <a href="{{ route('ingredients.create') }}" 
               class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                Add New Ingredient
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 text-left">Name</th>
                        <th class="py-3 px-4 text-left">Stock</th>
                        <th class="py-3 px-4 text-left">Unit</th>
                        <th class="py-3 px-4 text-left">Low Stock Alert</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingredients as $ingredient)
                    <tr class="border-t">
                        <td class="py-3 px-4">{{ $ingredient->name }}</td>
                        <td class="py-3 px-4">{{ $ingredient->stock }}</td>
                        <td class="py-3 px-4">{{ $ingredient->unit }}</td>
                        <td class="py-3 px-4">{{ $ingredient->alert_threshold ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('ingredients.edit', $ingredient->id) }}" 
                                   class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    Edit
                                </a>
                                <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                                            onclick="return confirm('Are you sure?')">
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
    </div>
</div>
@endsection