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

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Ingredients Management</h1>
            <a href="{{ route('ingredients.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Add Ingredient
            </a>
        </div>

        <!-- Ingredient Usage Report Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Ingredient Usage Report</h2>
            <form id="usageReportForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i data-lucide="bar-chart" class="w-5 h-5 mr-2"></i>
                        Generate Report
                    </button>
                </div>
                <div class="flex items-end">
                    <button type="button" id="exportReport" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                        Export PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Ingredients Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ingredient
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alert Threshold
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Updated
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usage (Today)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="ingredientsTableBody">
                        @foreach($ingredients as $ingredient)
                        <tr class="{{ $ingredient->status === 'low-stock' ? 'bg-yellow-50' : ($ingredient->status === 'out-of-stock' ? 'bg-red-50' : '') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ingredient->name }}</div>
                                <div class="text-sm text-gray-500">{{ $ingredient->unit }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ingredient->stock }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ingredient->alert_threshold }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ingredient->status === 'out-of-stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @elseif($ingredient->status === 'low-stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ingredient->last_updated }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 usage-today">
                                <div class="text-center">
                                    <div class="text-sm font-medium">{{ $ingredient->getUsageInPeriod(now()->startOfDay(), now()->endOfDay()) }}</div>
                                    <div class="text-xs text-gray-400">{{ $ingredient->unit }}</div>
                                </div>
                            </td>
                            <!-- Replace the Actions column in your table with this: -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <!-- Restock Form -->
                                <div class="flex items-center space-x-2 mb-2">
                                    <form action="{{ route('ingredients.restock', $ingredient->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="number" 
                                            name="restock_amount" 
                                            step="0.01" 
                                            min="0.01" 
                                            placeholder="Qty" 
                                            class="w-20 px-2 py-1 border rounded text-sm"
                                            required>
                                        <input type="text" 
                                            name="reason" 
                                            value="Restock" 
                                            class="w-32 px-2 py-1 border rounded text-sm">
                                        <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm flex items-center">
                                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                                            Add
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Edit and Delete Links -->
                                <div class="flex space-x-3">
                                    <a href="{{ route('ingredients.edit', $ingredient->id) }}" 
                                    class="text-blue-600 hover:text-blue-900 flex items-center">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 flex items-center"
                                                onclick="return confirm('Are you sure you want to delete this ingredient?')">
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

        <!-- Usage Report Results -->
        <div id="usageReportResults" class="mt-6 hidden">
            <!-- Results will be loaded here via AJAX -->
        </div>
                <!-- Stock History Report Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Stock History Report</h2>
            <form id="stockHistoryForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="stock_start_date" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="stock_end_date" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i data-lucide="history" class="w-5 h-5 mr-2"></i>
                        View History
                    </button>
                </div>
                <div class="flex items-end">
                    <button type="button" id="exportStockHistory" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                        Export PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Stock History Results -->
        <div id="stockHistoryResults" class="mt-6 hidden">
            <!-- Results will be loaded here via AJAX -->
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
