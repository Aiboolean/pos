@extends('layouts.app')

@section('content')
<div class="min-h-full bg-[#f5f1ea]">
    <main class="p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg border border-[#e0d6c2]">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-[#5c4d3c]">
                    <i data-lucide="tags" class="inline-block w-6 h-6 text-[#8c7b6b] mr-2"></i>
                   Categories
                </h1>
                
                <div class="bg-[#f5f1ea] p-2 rounded-lg">
                    <a href="{{ route('categories.create') }}" 
                       class="bg-[#e0d6c2] hover:bg-[#d4c9b5] text-black px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 mr-1 text-black"></i>
                        Add New Category
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-[#e8f5e9] border-l-4 border-[#4caf50] text-[#2e7d32] p-3 mb-6 rounded">
                    <div class="flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto bg-white rounded-lg border border-[#e0d6c2] shadow-sm">
                <table class="w-full">
                    <thead class="bg-[#f5f1ea] text-[#5c4d3c]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Category Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e0d6c2]">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-[#f9f7f3] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#5c4d3c]">{{ $category->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#5c4d3c]">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#5c4d3c]">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="text-[#6f4e37] hover:text-[#5c3d2a] flex items-center">
                                            <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-[#c45e4c] hover:text-[#a34a3a] flex items-center" 
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
    
    // Confirm delete with coffee-themed alert
    document.querySelectorAll('form[method="POST"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this coffee category?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection