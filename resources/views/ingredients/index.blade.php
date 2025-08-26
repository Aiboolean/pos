@extends('layouts.app')

@section('content')
<style>
    /* Coffee Shop Theme CSS */
    .coffee-bg {
        background-color: #f5f1ea;
    }
    
    .coffee-card {
        background-color: white;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 0.75rem;
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
    
    .coffee-btn-primary {
        background-color: #6f4e37;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-primary:hover {
        background-color: #5c3d2a;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .coffee-btn-success {
        background-color: #8c7b6b;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-success:hover {
        background-color: #6f4e37;
    }
    
    .coffee-btn-secondary {
        background-color: #e0d6c2;
        color: #5c4d3c;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-secondary:hover {
        background-color: #d4c9b5;
    }
    
    .coffee-input {
        border: 1px solid #e0d6c2;
        background-color: white;
        color: #5c4d3c;
        transition: all 0.2s ease;
    }
    
    .coffee-input:focus {
        outline: none;
        ring: 2px;
        ring-color: #8c7b6b;
        border-color: #8c7b6b;
    }
    
    .coffee-shadow {
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
    }
    
    .coffee-toggle-bg {
        background-color: #f5f1ea;
    }
    
    .coffee-file-input {
        border-color: #e0d6c2;
    }
    
    .coffee-file-input:hover {
        background-color: #f5f1ea;
    }
</style>

<div class="container mx-auto px-4 py-6 coffee-bg">
    <div class="coffee-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold coffee-text-primary">Ingredients</h1>
            <a href="{{ route('ingredients.create') }}" 
               class="px-4 py-2 rounded-lg coffee-btn-primary">
                Add New Ingredient
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full coffee-card">
                <thead>
                    <tr class="coffee-bg">
                        <th class="py-3 px-4 text-left coffee-text-secondary">Name</th>
                        <th class="py-3 px-4 text-left coffee-text-secondary">Stock</th>
                        <th class="py-3 px-4 text-left coffee-text-secondary">Unit</th>
                        <th class="py-3 px-4 text-left coffee-text-secondary">Low Stock Alert</th>
                        <th class="py-3 px-4 text-left coffee-text-secondary">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingredients as $ingredient)
                    <tr class="coffee-border border-t">
                        <td class="py-3 px-4 coffee-text-primary">{{ $ingredient->name }}</td>
                        <td class="py-3 px-4 coffee-text-primary">{{ $ingredient->stock }}</td>
                        <td class="py-3 px-4 coffee-text-primary">{{ $ingredient->unit }}</td>
                        <td class="py-3 px-4 coffee-text-primary">{{ $ingredient->alert_threshold ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('ingredients.edit', $ingredient->id) }}" 
                                   class="px-3 py-1 rounded coffee-btn-success">
                                    Edit
                                </a>
                                <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 rounded coffee-btn-secondary"
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
