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
                    <p class="text-lg sm:text-xl font-bold text-purple-500">
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
        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Chart</h3>
            <!-- Date Range Picker -->
            <div class="mb-4">
                <input type="text" id="dateRangePicker" class="w-full p-2 border rounded-lg" placeholder="Select Date Range">
            </div>
            <!-- Chart Canvas with fixed height -->
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Category Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Per Category</h3>
            <!-- Date Range Picker for Category Revenue -->
            <div class="mb-4">
                <input type="text" id="categoryDateRangePicker" class="w-full p-2 border rounded-lg" placeholder="Select Date Range">
            </div>
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
    </div>
</div>

<script>
    // Initialize Flatpickr
    flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                fetchRevenueData(selectedDates[0], selectedDates[1]);
            }
        }
    });

    // Initialize Chart.js with responsive options
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

    // Function to update the chart
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

    // Fetch initial data for the chart (e.g., last 7 days)
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(endDate.getDate() - 7);

    fetchRevenueData(startDate, endDate);

    // Initialize Flatpickr for Category Revenue Date Range
    flatpickr("#categoryDateRangePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            const categoryId = document.getElementById('categoryDropdown').value;
            if (selectedDates.length === 2 && categoryId) {
                fetchCategoryRevenueData(categoryId, selectedDates[0], selectedDates[1]);
            }
        }
    });

    // Initialize Category Revenue Chart
    const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
    let categoryRevenueChart;

    // Function to fetch category revenue data
    function fetchCategoryRevenueData(categoryId, startDate, endDate) {
        // Log the request for debugging
        console.log(`Fetching category data for ID: ${categoryId}, from ${startDate.toISOString().split('T')[0]} to ${endDate.toISOString().split('T')[0]}`);
        
        fetch(`/admin/category-revenue/${categoryId}?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Category data received:", data);
                updateCategoryChart(data);
            })
            .catch(error => {
                console.error("Error fetching category data:", error);
            });
    }

    // Function to update the category chart
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
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 99, 132, 0.4)',
                        'rgba(54, 162, 235, 0.4)',
                        'rgba(255, 206, 86, 0.4)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
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

    // Add default values for the category chart
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-select the first category if available
        const categoryDropdown = document.getElementById('categoryDropdown');
        if (categoryDropdown && categoryDropdown.options.length > 1) {
            categoryDropdown.selectedIndex = 1;  // Select the first actual category (index 1, after the placeholder)
            
            // Set default date range (last 30 days)
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(endDate.getDate() - 30);
            
            // Initialize the date picker with these values
            const categoryDatePicker = document.querySelector("#categoryDateRangePicker");
            if (categoryDatePicker._flatpickr) {
                categoryDatePicker._flatpickr.setDate([startDate, endDate]);
            } else {
                setTimeout(() => {
                    // Manually trigger the chart data fetch
                    fetchCategoryRevenueData(categoryDropdown.value, startDate, endDate);
                }, 500);
            }
        }
    });

    // Fetch category revenue data when a category is selected
    document.getElementById('categoryDropdown').addEventListener('change', function() {
        const categoryId = this.value;
        const dateRange = document.querySelector("#categoryDateRangePicker")._flatpickr.selectedDates;
        
        if (categoryId && dateRange.length === 2) {
            fetchCategoryRevenueData(categoryId, dateRange[0], dateRange[1]);
        } else if (categoryId) {
            // If no date range is selected, use last 30 days
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(endDate.getDate() - 30);
            fetchCategoryRevenueData(categoryId, startDate, endDate);
        }
    });

    lucide.createIcons();
</script>
@endsection