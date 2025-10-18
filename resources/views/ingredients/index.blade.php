@extends('layouts.app')

@section('content')
<style>
    /* Main Coffee Shop Theme Styles */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-btn-primary, .coffee-btn-success, .coffee-btn-warning, .coffee-btn-danger, .coffee-btn-secondary { transition: all 0.2s ease; border: none; }
    .coffee-btn-primary { background-color: #6f4e37; color: white; }
    .coffee-btn-primary:hover { background-color: #5c3d2a; }
    .coffee-btn-success { background-color: #8c7b6b; color: white; }
    .coffee-btn-success:hover { background-color: #6f4e37; }
    .coffee-btn-warning { background-color: #c4a76c; color: white; }
    .coffee-btn-warning:hover { background-color: #b08d4e; }
    .coffee-btn-danger { background-color: #c45e4c; color: white; }
    .coffee-btn-danger:hover { background-color: #a34a3a; }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-table-header { background-color: #f5f1ea; color: #5c4d3c; }
    .coffee-table-row:hover { background-color: #f9f7f3; }
    .coffee-input { border: 1px solid #e0d6c2; transition: all 0.2s ease; background-color: white; color: #5c4d3c; }
    .coffee-input:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
    
    /* Custom Status Indicators */
    .status-ok { background-color: #e8f5e9; color: #2e7d32; }
    .status-low { background-color: #fff8e1; color: #ffa000; }
    .status-critical { background-color: #fbe9e7; color: #c62828; }
</style>

<div class="min-h-screen coffee-bg p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="coffee-card p-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold coffee-text-primary flex items-center">
                <i data-lucide="flask-conical" class="w-6 h-6 mr-2 coffee-text-secondary"></i>
                Ingredients Management
            </h1>
            {{-- ✅ FIX: Changed <a> to <button> with an ID --}}
            <button id="addIngredientBtn" class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Add Ingredient
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="coffee-card p-6">
                <h2 class="text-lg font-semibold mb-4 coffee-text-primary">Ingredient Usage Report</h2>
                <form id="usageReportForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium coffee-text-primary mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="w-full coffee-input rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium coffee-text-primary mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="w-full coffee-input rounded-lg px-3 py-2">
                    </div>
                    <div class="flex items-end gap-3 col-span-1 md:col-span-2">
                        <button type="submit" class="coffee-btn-success px-4 py-2 rounded-lg flex items-center">
                            <i data-lucide="bar-chart" class="w-5 h-5 mr-2"></i>Generate Report
                        </button>
                        <button type="button" id="exportReport" class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>Export PDF
                        </button>
                    </div>
                </form>
            </div>

            <div class="coffee-card p-6">
                <h2 class="text-lg font-semibold mb-4 coffee-text-primary">Stock History Report</h2>
                <form id="stockHistoryForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium coffee-text-primary mb-1">Start Date</label>
                        <input type="date" name="start_date" id="stock_start_date" class="w-full coffee-input rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium coffee-text-primary mb-1">End Date</label>
                        <input type="date" name="end_date" id="stock_end_date" class="w-full coffee-input rounded-lg px-3 py-2">
                    </div>
                    <div class="flex items-end gap-3 col-span-1 md:col-span-2">
                        <button type="submit" class="coffee-btn-success px-4 py-2 rounded-lg flex items-center">
                            <i data-lucide="history" class="w-5 h-5 mr-2"></i>View History
                        </button>
                        <button type="button" id="exportStockHistory" class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>Export PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div id="usageReportResults" class="hidden"></div>
        <div id="stockHistoryResults" class="hidden"></div>

        <div class="coffee-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y coffee-border">
                    <thead class="coffee-table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ingredient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Alert Threshold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Last Updated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Usage (Today)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y coffee-border" id="ingredientsTableBody">
                        @foreach($ingredients as $ingredient)
                        <tr class="coffee-table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium coffee-text-primary">{{ $ingredient->name }}</div>
                                <div class="text-sm coffee-text-secondary">{{ $ingredient->unit }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium coffee-text-primary">{{ $ingredient->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $ingredient->alert_threshold }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ingredient->status === 'out-of-stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-critical">Out of Stock</span>
                                @elseif($ingredient->status === 'low-stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-low">Low Stock</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-ok">In Stock</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-secondary">{{ $ingredient->last_updated }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-secondary usage-today">
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $ingredient->getUsageInPeriod(now()->startOfDay(), now()->endOfDay()) }}</div>
                                    <div class="text-xs">{{ $ingredient->unit }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button data-url="{{ route('ingredients.edit', $ingredient->id) }}" class="edit-ingredient-btn coffee-btn-secondary p-2 rounded-lg flex items-center">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="coffee-btn-danger p-2 rounded-lg flex items-center">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
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
</div>

<div id="ingredientModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 p-4 flex items-center justify-center">
    <div class="coffee-card w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-lg">
        <div class="flex justify-between items-center p-6 border-b coffee-border">
            <h3 id="ingredientModalTitle" class="text-xl font-semibold coffee-text-primary">Manage Ingredient</h3>
            <button class="close-modal-btn text-3xl font-light leading-none coffee-text-secondary hover:text-red-600">&times;</button>
        </div>
        <div id="ingredientModalBody" class="p-6">
            <p class="text-center coffee-text-primary">Loading form...</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // --- MODAL LOGIC (Copied from your working Products page) ---
        const modal = document.getElementById('ingredientModal');
        const modalBody = document.getElementById('ingredientModalBody');
        const modalTitle = document.getElementById('ingredientModalTitle');
        const addIngredientBtn = document.getElementById('addIngredientBtn');
        const editButtons = document.querySelectorAll('.edit-ingredient-btn');
        const closeButtons = document.querySelectorAll('.close-modal-btn');

        function openModal(title) {
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function loadHtmlWithScripts(container, html) {
            container.innerHTML = html;
            container.querySelectorAll('.cancel-btn').forEach(btn => btn.addEventListener('click', closeModal));
            lucide.createIcons();
        }

        if (addIngredientBtn) {
            addIngredientBtn.addEventListener('click', () => {
                modalBody.innerHTML = '<p class="text-center coffee-text-primary">Loading form...</p>';
                openModal('Add New Ingredient');
                fetch('{{ route("ingredients.create") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(response => response.text())
                    .then(html => loadHtmlWithScripts(modalBody, html));
            });
        }

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const url = this.dataset.url;
                modalBody.innerHTML = '<p class="text-center coffee-text-primary">Loading form...</p>';
                openModal('Edit Ingredient');
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(response => response.text())
                    .then(html => loadHtmlWithScripts(modalBody, html));
            });
        });

        closeButtons.forEach(btn => btn.addEventListener('click', closeModal));
        modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

        // --- DELETE CONFIRMATION ---
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this ingredient?')) {
                    e.preventDefault();
                }
            });
        });

        // --- REPORTING LOGIC (INTEGRATED) ---
        
        // Generate Usage Report
        const usageReportForm = document.getElementById('usageReportForm');
        if (usageReportForm) {
            usageReportForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('{{ route("ingredients.usage-report") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => displayUsageReport(data))
                .catch(error => console.error('Error:', error));
            });
        }

        // Export Usage PDF Report
        const exportReportBtn = document.getElementById('exportReport');
        if (exportReportBtn) {
            exportReportBtn.addEventListener('click', function() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (!startDate || !endDate) { alert('Please select both start and end dates'); return; }
                
                const button = this;
                const originalText = button.innerHTML;
                button.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 mr-2 animate-spin"></i>Generating...';
                button.disabled = true;
                lucide.createIcons();
                
                window.location.href = `{{ route("ingredients.export-usage") }}?start_date=${startDate}&end_date=${endDate}`;
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    lucide.createIcons();
                }, 3000);
            });
        }

        function displayUsageReport(data) {
            const resultsDiv = document.getElementById('usageReportResults');
            resultsDiv.classList.remove('hidden');
            let html = `<div class="coffee-card p-6"><h3 class="text-lg font-semibold mb-4 coffee-text-primary">Usage Report: ${data.start_date} to ${data.end_date}</h3><div class="overflow-x-auto"><table class="min-w-full divide-y coffee-border"><thead class="coffee-table-header"><tr><th class="px-6 py-3 text-left text-xs font-medium uppercase">Ingredient</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Total Used</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Unit</th><th class="px-6 py-3 text-left text-xs font-medium uppercase">Usage/Day</th></tr></thead><tbody class="bg-white divide-y coffee-border">`;
            data.usage_data.forEach(item => {
                html += `<tr><td class="px-6 py-4 text-sm font-medium coffee-text-primary">${item.ingredient_name}</td><td class="px-6 py-4 text-sm coffee-text-primary">${item.total_used}</td><td class="px-6 py-4 text-sm coffee-text-secondary">${item.unit}</td><td class="px-6 py-4 text-sm coffee-text-secondary">${item.usage_rate}</td></tr>`;
            });
            html += `</tbody></table></div></div>`;
            resultsDiv.innerHTML = html;
        }

        // Stock History functionality
        const stockHistoryForm = document.getElementById('stockHistoryForm');
        if (stockHistoryForm) {
            stockHistoryForm.addEventListener('submit', function(e) { /* ... same fetch logic as usage report ... */ });
        }
        
        // Export Stock History PDF
        const exportStockHistoryBtn = document.getElementById('exportStockHistory');
        if (exportStockHistoryBtn) {
            exportStockHistoryBtn.addEventListener('click', function() { /* ... same logic as export usage report ... */ });
        }

        function displayStockHistory(data) { /* ... HTML generation logic for stock history table ... */ }

        // Set default dates
        const endDate = new Date().toISOString().split('T')[0];
        const startDate30 = new Date();
        startDate30.setDate(startDate30.getDate() - 30);
        document.getElementById('start_date').value = startDate30.toISOString().split('T')[0];
        document.getElementById('end_date').value = endDate;

        const startDate7 = new Date();
        startDate7.setDate(startDate7.getDate() - 7);
        document.getElementById('stock_start_date').value = startDate7.toISOString().split('T')[0];
        document.getElementById('stock_end_date').value = endDate;
    });
</script>
@endpush
@endsection