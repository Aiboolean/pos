@extends('layouts.app')

@section('content')
<style>
    /* Coffee Shop Theme CSS */
    .coffee-bg {
        background-color: #f5f1ea; /* Warm cream background */
    }
    
    .coffee-card {
        background-color: white;
        border: 1px solid #e0d6c2; /* Light beige border */
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 0.75rem;
    }
    
    .coffee-text-primary {
        color: #5c4d3c; /* Dark brown text */
    }
    
    .coffee-text-secondary {
        color: #8c7b6b; /* Medium brown text */
    }
    
    .coffee-border {
        border-color: #e0d6c2;
    }
    
    .coffee-btn-primary {
        background-color: #6f4e37; /* Dark brown */
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-primary:hover {
        background-color: #5c3d2a; /* Darker brown */
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .coffee-btn-secondary {
        background-color: #e0d6c2; /* Light beige */
        color: #5c4d3c;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-secondary:hover {
        background-color: #d4c9b5; /* Slightly darker beige */
    }
    
    .coffee-btn-danger {
        background-color: #c45e4c; /* Rust red */
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-danger:hover {
        background-color: #a34a3a; /* Darker rust */
    }
    
    .coffee-table-header {
        background-color: #f5f1ea;
        color: #5c4d3c;
    }
    
    .coffee-table-row:hover {
        background-color: #f9f7f3;
    }
    
    .coffee-alert-success {
        background-color: #e8f5e9;
        border-left: 4px solid #4caf50;
        color: #2e7d32;
    }
    
    .coffee-shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
    }
    
    .coffee-transition {
        transition: all 0.2s ease;
    }
</style>

<div class="min-h-full coffee-bg">
    <main class="p-6">
        <div class="coffee-card p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold coffee-text-primary">
                    <i data-lucide="tags" class="inline-block w-6 h-6 coffee-text-secondary mr-2"></i>
                    Manage Categories
                </h1>
                
                <div class="bg-[#f5f1ea] p-2 rounded-lg">
                    <a href="{{ route('categories.create') }}" 
                       class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center coffee-shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                        Add New Category
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="coffee-alert-success p-3 mb-6 rounded">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto bg-white rounded-lg coffee-border border coffee-shadow-sm">
                <table class="w-full">
                    <thead class="coffee-table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Category Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y coffee-border">
                        @foreach ($categories as $category)
                        <tr class="coffee-table-row coffee-transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $category->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium coffee-text-primary">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">
                                <div class="flex space-x-4">
                                    <a href="{{ route('categories.edit', $category) }}" 
                                       class="coffee-btn-secondary px-3 py-1 rounded-lg flex items-center coffee-shadow-sm">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="coffee-btn-danger px-3 py-1 rounded-lg flex items-center coffee-shadow-sm"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
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
        </div>
    </main>
</div>

@push('scripts')
<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Enhanced delete confirmation
    document.querySelectorAll('form[method="POST"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('This will permanently delete the category and all associated products. Continue?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection