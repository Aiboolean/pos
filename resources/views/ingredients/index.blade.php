@extends('layouts.app')

@section('content')
<style>
    /* Main Coffee Shop Theme Styles */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-btn-primary, .coffee-btn-success, .coffee-btn-warning, .coffee-btn-danger, .coffee-btn-secondary { transition: all 0.2s ease; border: none; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 500; display: inline-flex; align-items: center; justify-content: center; line-height: 1.25; }
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
    .coffee-input { border: 1px solid #e0d6c2; transition: all 0.2s ease; background-color: white; color: #5c4d3c; border-radius: 0.5rem; padding: 0.75rem 1rem; }
    .coffee-input:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }

    /* Custom Status Indicators */
    .status-ok-bg { background-color: #e8f5e9; } .status-ok-text { color: #2e7d32; }
    .status-low-bg { background-color: #fff8e1; } .status-low-text { color: #ffa000; }
    .status-critical-bg { background-color: #fbe9e7; } .status-critical-text { color: #c62828; }

    /* Report Result Styling */
    .report-card { background-color: white; border: 1px solid #e0d6c2; border-radius: 0.75rem; padding: 1.5rem; margin-top: 1.5rem; }
    .report-table th { background-color: #f5f1ea; color: #5c4d3c; padding: 10px; text-align: left; border: 1px solid #e0d6c2; font-size: 11px; font-weight: 600; text-transform: uppercase; }
    .report-table td { padding: 10px; border: 1px solid #e0d6c2; color: #5c4d3c; font-size: 12px; }
    .report-table tr:nth-child(even) { background-color: #f9f7f3; }
</style>

<div class="min-h-screen coffee-bg p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="coffee-card p-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold coffee-text-primary flex items-center">
                <i data-lucide="coffee" class="w-6 h-6 mr-2 coffee-text-secondary"></i>
                Ingredients Management
            </h1>
             <a href="{{ route('ingredients.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Add Ingredient
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- ✅ THEME APPLIED --}}
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

            {{-- ✅ THEME APPLIED --}}
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

        {{-- ✅ THEME APPLIED --}}
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
                        <tr class="coffee-table-row {{ $ingredient->status === 'low-stock' ? 'status-low-bg' : ($ingredient->status === 'out-of-stock' ? 'status-critical-bg' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium coffee-text-primary">{{ $ingredient->name }}</div>
                                <div class="text-sm coffee-text-secondary">{{ $ingredient->unit }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium coffee-text-primary">{{ $ingredient->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $ingredient->alert_threshold }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ingredient->status === 'out-of-stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-critical-bg status-critical-text">Out of Stock</span>
                                @elseif($ingredient->status === 'low-stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-low-bg status-low-text">Low Stock</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-ok-bg status-ok-text">In Stock</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-secondary">{{ $ingredient->last_updated }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-secondary usage-today">
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $ingredient->getUsageInPeriod(now()->startOfDay(), now()->endOfDay()) }}</div>
                                    <div class="text-xs">{{ $ingredient->unit }}</div>
                                </div>
                            </td>
                             {{-- ✅ THEME APPLIED & MODAL TRIGGER --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 mb-2">
                                    <form action="{{ route('ingredients.restock', $ingredient->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="number" name="restock_amount" step="0.01" min="0.01" placeholder="Qty" class="w-20 px-2 py-1 coffee-input rounded text-sm" required>
                                        <input type="text" name="reason" value="Restock" placeholder="Reason (Optional)" class="w-32 px-2 py-1 coffee-input rounded text-sm">
                                        <button type="submit" class="coffee-btn-success px-3 py-1 rounded text-sm flex items-center">
                                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i>Add
                                        </button>
                                    </form>
                                </div>
                                <div class="flex space-x-2">
                                    <button data-url="{{ route('ingredients.edit', $ingredient->id) }}" class="edit-ingredient-btn coffee-btn-secondary p-2 rounded-lg flex items-center" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" class="inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="coffee-btn-danger p-2 rounded-lg flex items-center" title="Delete">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate Usage Report
    document.getElementById('usageReportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("ingredients.usage-report") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            displayUsageReport(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Export PDF Report
document.getElementById('exportReport').addEventListener('click', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }
    
    // Show loading state
    const button = this;
    const originalText = button.innerHTML;
    button.innerHTML = '<i data-lucide="loader" class="w-5 h-5 mr-2 animate-spin"></i>Generating PDF...';
    button.disabled = true;
    
    window.location.href = `{{ route("ingredients.export-usage") }}?start_date=${startDate}&end_date=${endDate}`;
    
    // Reset button after 3 seconds
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        lucide.createIcons();
    }, 3000);
});

    function displayUsageReport(data) {
        const resultsDiv = document.getElementById('usageReportResults');
        resultsDiv.classList.remove('hidden');
        
        let html = `
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Usage Report: ${data.start_date} to ${data.end_date}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingredient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Used</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usage Rate/Day</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
        `;
        
        data.usage_data.forEach(item => {
            html += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.ingredient_name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.total_used}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.unit}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.usage_rate}</td>
                </tr>
            `;
        });
        
        html += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        
        resultsDiv.innerHTML = html;
    }

    // Set default dates (last 30 days)
    const endDate = new Date().toISOString().split('T')[0];
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
    document.getElementById('end_date').value = endDate;
});

lucide.createIcons();

// Stock History functionality
document.getElementById('stockHistoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("ingredients.stock-history") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        displayStockHistory(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Export Stock History PDF
document.getElementById('exportStockHistory').addEventListener('click', function() {
    const startDate = document.getElementById('stock_start_date').value;
    const endDate = document.getElementById('stock_end_date').value;
    
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }
    
    // Show loading state
    const button = this;
    const originalText = button.innerHTML;
    button.innerHTML = '<i data-lucide="loader" class="w-5 h-5 mr-2 animate-spin"></i>Generating PDF...';
    button.disabled = true;
    
    window.location.href = `{{ route("ingredients.export-stock-history") }}?start_date=${startDate}&end_date=${endDate}`;
    
    // Reset button after 3 seconds
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        lucide.createIcons();
    }, 3000);
});

function displayStockHistory(data) {
    const resultsDiv = document.getElementById('stockHistoryResults');
    resultsDiv.classList.remove('hidden');
    
    let html = `
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Stock History: ${data.start_date} to ${data.end_date}</h3>
            <p class="text-sm text-gray-600 mb-4">Total Movements: ${data.total_movements}</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingredient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Previous Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">New Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Change</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    data.stock_history.forEach(item => {
        const changeClass = item.change_amount < 0 ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold';
        const changeSign = item.change_amount > 0 ? '+' : '';
        
        html += `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.ingredient_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.change_type}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.previous_stock}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.new_stock}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm ${changeClass}">${changeSign}${item.change_amount}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.reason || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.user}</td>
            </tr>
        `;
    });
    
    if (data.stock_history.length === 0) {
        html += `
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                    No stock movements found for the selected period.
                </td>
            </tr>
        `;
    }
    
    html += `
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
}

// Set default dates for stock history (last 7 days)
const stockEndDate = new Date().toISOString().split('T')[0];
const stockStartDate = new Date();
stockStartDate.setDate(stockStartDate.getDate() - 7);
document.getElementById('stock_start_date').value = stockStartDate.toISOString().split('T')[0];
document.getElementById('stock_end_date').value = stockEndDate;
</script>
@endsection
