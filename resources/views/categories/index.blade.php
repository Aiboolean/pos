@extends('layouts.app')

@section('content')
<style>
    /* Your CSS is correct. No changes are needed here. */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-btn-primary { background-color: #6f4e37; color: white; transition: all 0.2s ease; }
    .coffee-btn-primary:hover { background-color: #5c3d2a; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; transition: all 0.2s ease; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-btn-danger { background-color: #c45e4c; color: white; transition: all 0.2s ease; }
    .coffee-btn-danger:hover { background-color: #a34a3a; }
    .coffee-table-header { background-color: #f5f1ea; color: #5c4d3c; }
    .coffee-table-row:hover { background-color: #f9f7f3; }
    .coffee-alert-success { background-color: #e8f5e9; border-left: 4px solid #4caf50; color: #2e7d32; }
    .coffee-shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    .coffee-transition { transition: all 0.2s ease; }
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
                    {{-- ✅ VERIFIED: The ID is exactly 'addCategoryBtn' --}}
                    <button id="addCategoryBtn" class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center coffee-shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                        Add New Category
                    </button>
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
                                    <button data-edit-url="{{ route('categories.edit', $category) }}" 
        class="edit-category-btn coffee-btn-secondary px-3 py-1 rounded-lg flex items-center coffee-shadow-sm">
    <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
    Edit
</button>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="coffee-btn-danger px-3 py-1 rounded-lg flex items-center coffee-shadow-sm">
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

        {{-- ✅ VERIFIED: All modal IDs are correct --}}
        <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
            <div class="coffee-card w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
                <div class="flex justify-between items-center mb-4 pb-2 coffee-border border-b">
                    <h2 class="text-xl font-bold coffee-text-primary">Add New Category</h2>
                    <button id="closeCategoryModalBtn" class="text-3xl font-light leading-none coffee-text-secondary hover:text-red-600">&times;</button>
                </div>
                <div id="categoryModalBody">
                    <p class="text-center coffee-text-primary">Loading form...</p>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        const categoryModal = document.getElementById('categoryModal');
        const categoryModalBody = document.getElementById('categoryModalBody');
        const addCategoryBtn = document.getElementById('addCategoryBtn');
        const closeCategoryModalBtn = document.getElementById('closeCategoryModalBtn');

        if (categoryModal && addCategoryBtn) {
            const openModal = (title) => {
                // Update the modal title when opening
                categoryModal.querySelector('h2').textContent = title;
                categoryModal.style.display = 'flex';
            };
            const closeModal = () => categoryModal.style.display = 'none';

            // --- ADD CATEGORY LOGIC (No changes here) ---
            addCategoryBtn.addEventListener('click', () => {
                categoryModalBody.innerHTML = '<p class="text-center coffee-text-primary">Loading form...</p>';
                openModal('Add New Category'); // Set title

                fetch('{{ route("categories.create") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(response => response.text())
                .then(html => {
                    categoryModalBody.innerHTML = html;
                    lucide.createIcons();
                });
            });

            // --- NEW: EDIT CATEGORY LOGIC ---
            document.querySelectorAll('.edit-category-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const editUrl = button.dataset.editUrl;

                    categoryModalBody.innerHTML = '<p class="text-center coffee-text-primary">Loading form...</p>';
                    openModal('Edit Category'); // Set title to "Edit"

                    fetch(editUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(response => response.text())
                    .then(html => {
                        categoryModalBody.innerHTML = html;
                        lucide.createIcons();
                    });
                });
            });

            // --- CLOSE MODAL LOGIC (No changes here) ---
            closeCategoryModalBtn.addEventListener('click', closeModal);
            categoryModal.addEventListener('click', (event) => {
                if (event.target === categoryModal) {
                    closeModal();
                }
            });
        }

        // --- DELETE CONFIRMATION SCRIPT (No changes here) ---
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('This will permanently delete the category. Continue?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection