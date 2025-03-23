@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
    

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mt-4 sm:mt-6">
        <!-- Total Orders -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg flex items-center">
            <i data-lucide="shopping-bag" class="w-6 sm:w-8 h-6 sm:h-8 text-blue-500 mr-3 sm:mr-4"></i>
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Total Orders</h3>
                <p class="text-xl sm:text-2xl font-bold text-blue-500">{{ $totalOrders }}</p>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg flex items-center">
            <i data-lucide="dollar-sign" class="w-6 sm:w-8 h-6 sm:h-8 text-green-500 mr-3 sm:mr-4"></i>
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Total Revenue</h3>
                <p class="text-xl sm:text-2xl font-bold text-green-500">₱{{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>

        <!-- Total Sales Today -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg flex items-center">
            <i data-lucide="activity" class="w-6 sm:w-8 h-6 sm:h-8 text-purple-500 mr-3 sm:mr-4"></i>
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Total Sales Today</h3>
                <p class="text-xl sm:text-2xl font-bold text-purple-500">₱{{ number_format($totalSalesToday, 2) }}</p>
            </div>
        </div>

        <!-- Best Seller -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg flex items-center">
            <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-500 mr-3 sm:mr-4"></i>
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700">Best Seller</h3>
                @if ($bestSeller)
                    <p class="text-lg sm:text-xl font-bold text-yellow-500">
                        {{ $bestSeller->product->name }} ({{ $bestSeller->total_quantity }} sold)
                    </p>
                @else
                    <p class="text-lg sm:text-xl font-bold text-purple-500">No sales yet</p>
                @endif
            </div>
        </div>
    </div>


    <!-- Charts Section - Side by Side -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Single Date Range Picker -->
        <div class="col-span-full bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Select Date Range</h3>
            <input type="text" id="globalDateRangePicker" class="w-full p-2 border rounded-lg" placeholder="Select Date Range">
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Chart</h3>
            <!-- Chart Canvas with fixed height -->
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Category Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Per Category</h3>
            <!-- Category Dropdown -->
            <div class="mb-4">
                <select id="categoryDropdown" class="w-full p-2 border rounded-lg">
                    <option value="">Select a Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Chart Canvas with fixed height -->
            <div class="h-64">
                <canvas id="categoryRevenueChart"></canvas>
            </div>
        </div>

        <!-- All Categories Revenue Chart -->
        <div class="col-span-full bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue by All Categories</h3>
            <!-- Chart Canvas with fixed height -->
            <div class="h-64">
                <canvas id="allCategoriesRevenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Flatpickr for Global Date Range
const globalDatePicker = flatpickr("#globalDateRangePicker", {
    mode: "range",
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
            const startDate = selectedDates[0];
            const endDate = selectedDates[1];

            // Update all charts with the selected date range
            fetchRevenueData(startDate, endDate); // Update Revenue Chart
            fetchAllCategoriesRevenueData(startDate, endDate); // Update All Categories Revenue Chart

            // Update Category Revenue Chart if a category is selected
            const categoryId = document.getElementById('categoryDropdown').value;
            if (categoryId) {
                fetchCategoryRevenueData(categoryId, startDate, endDate);
            }
        }
    }
});

// Initialize Chart.js for Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
let revenueChart;

// Function to fetch revenue data
function fetchRevenueData(startDate, endDate) {
    fetch(`/admin/revenue-data?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`)
        .then(response => response.json())
        .then(data => {
            updateChart(data);
        });
}

// Function to update the Revenue Chart
function updateChart(data) {
    if (revenueChart) {
        revenueChart.destroy();
    }

    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Revenue',
                data: data.revenue,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Initialize Category Revenue Chart
const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
let categoryRevenueChart;

// Function to fetch category revenue data
function fetchCategoryRevenueData(categoryId, startDate, endDate) {
    fetch(`/admin/category-revenue/${categoryId}?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`)
        .then(response => response.json())
        .then(data => {
            updateCategoryChart(data);
        });
}

// Function to update the Category Revenue Chart
function updateCategoryChart(data) {
    if (categoryRevenueChart) {
        categoryRevenueChart.destroy();
    }

    categoryRevenueChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Revenue by Product',
                data: data.revenue,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ₱${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Initialize All Categories Revenue Chart
const allCategoriesCtx = document.getElementById('allCategoriesRevenueChart').getContext('2d');
let allCategoriesRevenueChart;

// Function to fetch revenue data for all categories
function fetchAllCategoriesRevenueData(startDate, endDate) {
    fetch(`/admin/all-categories-revenue?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`)
        .then(response => response.json())
        .then(data => {
            updateAllCategoriesChart(data);
        });
}

// Function to update the All Categories Revenue Chart
function updateAllCategoriesChart(data) {
    if (allCategoriesRevenueChart) {
        allCategoriesRevenueChart.destroy();
    }

    allCategoriesRevenueChart = new Chart(allCategoriesCtx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Revenue by Category',
                data: data.revenue,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Fetch initial data for all charts (e.g., last 30 days)
const endDate = new Date();
const startDate = new Date();
startDate.setDate(endDate.getDate() - 30);

// Set initial date range in the global date picker
globalDatePicker.setDate([startDate, endDate]);

// Fetch initial data for all charts
fetchRevenueData(startDate, endDate); // Revenue Chart
fetchAllCategoriesRevenueData(startDate, endDate); // All Categories Revenue Chart

// Fetch category revenue data when a category is selected
document.getElementById('categoryDropdown').addEventListener('change', function() {
    const categoryId = this.value;
    const selectedDates = globalDatePicker.selectedDates;
    if (categoryId && selectedDates.length === 2) {
        fetchCategoryRevenueData(categoryId, selectedDates[0], selectedDates[1]);
    }
});

    lucide.createIcons();
</script>
@endsection