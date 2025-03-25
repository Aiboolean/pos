@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Admin Dashboard</h2>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Orders -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-lg bg-blue-50">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-lg bg-green-50">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Sales Today -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-lg bg-purple-50">
                <i data-lucide="activity" class="w-6 h-6 text-purple-500"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Sales</p>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($totalSalesToday, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Best Seller -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-lg bg-yellow-50">
                <i data-lucide="star" class="w-6 sm:w-8 h-6 sm:h-8 text-yellow-400 mr-3 sm:mr-4"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Best Seller</p>
                    @if ($bestSeller)
                        <p class="text-lg font-bold text-gray-800 truncate" title="{{ $bestSeller->product->name }} ({{ $bestSeller->total_quantity }} sold)">
                            {{ $bestSeller->product->name }} <span class="text-yellow-600">({{ $bestSeller->total_quantity }} sold)</span>
                        </p>
                    @else
                        <p class="text-lg font-bold text-gray-400">No sales yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Single Date Range Picker -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8 border-4 border-[#f1eadc]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Select Date Range</h3>
                <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                    <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                </button>
            </div>
            <input type="text" id="globalDateRangePicker" 
                   class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" 
                   placeholder="Select Date Range">
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg border-4 border-[#f1eadc]">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Chart</h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Category Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg border-4 border-[#f1eadc]">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Per Category</h3>
            <div class="mb-4">
                <select id="categoryDropdown" class="w-full p-2 border rounded-lg">
                    <option value="">Select a Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="h-64">
                <canvas id="categoryRevenueChart"></canvas>
            </div>
        </div>

        <!-- All Categories Revenue Chart -->
        <div class="col-span-full bg-white p-6 rounded-lg shadow-lg border-4 border-[#f1eadc]">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue by All Categories</h3>
            <div class="h-64">
                <canvas id="allCategoriesRevenueChart"></canvas>
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
    // Add cache-busting parameter to prevent browser caching
    const cacheBuster = new Date().getTime();
    fetch(`/admin/revenue-data?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}&_=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            updateChart(data);
        })
        .catch(error => {
            console.error('Error fetching revenue data:', error);
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
    // Add cache-busting parameter
    const cacheBuster = new Date().getTime();
    fetch(`/admin/category-revenue/${categoryId}?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}&_=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            updateCategoryChart(data);
        })
        .catch(error => {
            console.error('Error fetching category revenue data:', error);
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
    // Add cache-busting parameter
    const cacheBuster = new Date().getTime();
    fetch(`/admin/all-categories-revenue?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}&_=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            updateAllCategoriesChart(data);
        })
        .catch(error => {
            console.error('Error fetching all categories revenue data:', error);
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

// Function to refresh all charts
function refreshAllCharts() {
    const selectedDates = globalDatePicker.selectedDates;
    if (selectedDates.length === 2) {
        fetchRevenueData(selectedDates[0], selectedDates[1]);
        fetchAllCategoriesRevenueData(selectedDates[0], selectedDates[1]);
        
        // Update Category Revenue Chart if a category is selected
        const categoryId = document.getElementById('categoryDropdown').value;
        if (categoryId) {
            fetchCategoryRevenueData(categoryId, selectedDates[0], selectedDates[1]);
        }
    }
}

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

// Set up refresh interval (e.g., every 1 minutes)
const REFRESH_INTERVAL = 1 * 60 * 1000; // 1 minutes in milliseconds
setInterval(refreshAllCharts, REFRESH_INTERVAL);

// Initialize Lucide icons
lucide.createIcons();
</script>
@endsection