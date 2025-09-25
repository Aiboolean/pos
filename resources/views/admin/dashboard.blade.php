@extends('layouts.app')

@section('content')
<style>
    /* ===== Base Styles ===== */
    .coffee-bg {
        background-color: #f5f1ea;
    }

    .coffee-card {
        background-color: white;
        border: 1px solid #e0d6c2;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
    }

    .coffee-icon-bg {
        background-color: #f9f7f3;
    }

    /* ===== Responsive Cards ===== */
    .coffee-analytics-card {
        transition: all 0.2s ease;
    }

    .coffee-analytics-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    /* ===== Charts ===== */
    .revenue-chart-container {
        width: 100%;
        height: clamp(300px, 40vh, 400px); /* Responsive height */
    }

    .category-chart-container {
        width: 100%;
        height: clamp(250px, 35vh, 350px); /* Responsive height */
    }

    /* ===== Excel-Style Date Picker ===== */
    .compact-controls {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    #globalDateRangePicker {
        width: clamp(150px, 180px, 200px) !important; /* Responsive width */
        padding: 6px 10px;
        font-size: clamp(12px, 13px, 14px); /* Responsive text */
    }

    #refreshChartsBtn {
        padding: 6px 10px;
        font-size: clamp(12px, 13px, 14px); /* Matches date picker */
    }

    /* ===== Mobile Optimizations ===== */
    @media (max-width: 768px) {
        /* Stack analytics cards on mobile */
        .analytics-grid {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }

        /* Smaller icons on mobile */
        .coffee-icon-bg {
            padding: 10px !important;
        }

        /* Compact chart headers */
        .chart-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 8px !important;
        }

        /* Full-width date picker on mobile */
        .compact-controls {
            width: 100%;
        }

        #globalDateRangePicker {
            width: 100% !important;
        }
    }

    /* ===== Tablet Adjustments ===== */
    @media (min-width: 769px) and (max-width: 1024px) {
        /* 2-column grid for tablets */
        .analytics-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }

        /* Slightly smaller charts */
        .revenue-chart-container {
            height: 350px;
        }

        .category-chart-container {
            height: 300px;
        }
    }
</style>

<div class="min-h-screen coffee-bg p-4 sm:p-6">
    <!-- ===== Header ===== -->
    <div class="flex justify-between items-center mb-4 sm:mb-6">
        <h2 class="text-xl sm:text-2xl font-bold coffee-text-primary flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="mr-2" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Admin Dashboard
        </h2>
    </div>
    
    <!-- ===== Analytics Cards (Responsive Grid) ===== -->
    <div class="analytics-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <!-- Total Orders - Now Clickable -->
        <a href="{{ route('user.orders') }}" class="coffee-card p-4 transition-all duration-200 coffee-analytics-card hover:shadow-md hover:border-coffee-300">
            <div class="flex items-center space-x-3">
                <div class="p-2 sm:p-3 rounded-lg coffee-icon-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6f4e37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium coffee-text-secondary">Total Orders</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold coffee-text-primary">{{ $totalOrders }}</p>
                </div>
            </div>
        </a>

        <!-- Total Revenue -->
        <div class="coffee-card p-4 transition-all duration-200 coffee-analytics-card">
            <div class="flex items-center space-x-3">
                <div class="p-2 sm:p-3 rounded-lg coffee-icon-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6f4e37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" x2="12" y1="2" y2="22"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium coffee-text-secondary">Total Revenue</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold coffee-text-primary">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="coffee-card p-4 transition-all duration-200 coffee-analytics-card">
            <div class="flex items-center space-x-3">
                <div class="p-2 sm:p-3 rounded-lg coffee-icon-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6f4e37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium coffee-text-secondary">Today's Sales</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold coffee-text-primary">₱{{ number_format($totalSalesToday, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Best Seller -->
        <div class="coffee-card p-4 transition-all duration-200 coffee-analytics-card">
            <div class="flex items-center space-x-3">
                <div class="p-2 sm:p-3 rounded-lg coffee-icon-bg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6f4e37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium coffee-text-secondary">Best Seller</p>
                    @if ($bestSeller)
                        <p class="text-sm sm:text-base font-bold coffee-text-primary truncate" title="{{ $bestSeller->product->name }} ({{ $bestSeller->total_quantity }} sold)">
                            {{ $bestSeller->product->name }} <span class="text-yellow-600">({{ $bestSeller->total_quantity }} sold)</span>
                        </p>
                    @else
                        <p class="text-sm sm:text-base font-bold coffee-text-secondary">No sales yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Main Content ===== -->
    <div class="space-y-4 sm:space-y-6">
        <!-- Revenue Chart -->
        <div class="coffee-card p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 chart-header">
                <h3 class="text-base sm:text-lg font-semibold coffee-text-primary flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="mr-2" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"/>
                        <path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    Revenue Chart
                </h3>
                <!-- Excel-Style Compact Controls with Period Filter -->
                <div class="compact-controls w-full sm:w-auto">
                    <select id="periodFilter" class="border coffee-border rounded-lg p-2 text-sm">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <input type="text" id="globalDateRangePicker" class="border coffee-border rounded-lg w-full">
                    <button id="refreshChartsBtn" class="coffee-btn-primary rounded-lg font-medium inline-flex items-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                            <path d="M16 16h5v5"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
            <div class="revenue-chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Category Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- Revenue Per Category -->
            <div class="coffee-card p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                    <h3 class="text-base sm:text-lg font-semibold coffee-text-primary flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="mr-2" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
                            <path d="M22 12A10 10 0 0 0 12 2v10z"/>
                        </svg>
                        Product Revenue Per Category
                    </h3>
                    <select id="categoryDropdown" class="w-full sm:w-48 p-2 border coffee-border rounded-lg text-sm">
                        <option value="">Select a Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="category-chart-container">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
            </div>

            <!-- All Categories Revenue -->
            <div class="coffee-card p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold coffee-text-primary mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="mr-2" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" x2="18" y1="20" y2="10"/>
                        <line x1="12" x2="12" y1="20" y2="4"/>
                        <line x1="6" x2="6" y1="20" y2="14"/>
                    </svg>
                    Revenue by All Categories
                </h3>
                <div class="category-chart-container">
                    <canvas id="allCategoriesRevenueChart"></canvas>
                </div>
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
            refreshAllCharts();
        }
    }
});

// Initialize Chart.js for Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
let revenueChart;

// Global variables to track current period and dates
let currentPeriod = 'daily';
let currentStartDate, currentEndDate;

// Function to fetch revenue data with period filtering
function fetchRevenueData(startDate, endDate, period = 'daily') {
    const cacheBuster = new Date().getTime();
    fetch(`/admin/revenue-data?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}&period=${period}&_=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            updateChart(data, startDate, endDate, period);
        })
        .catch(error => {
            console.error('Error fetching revenue data:', error);
        });
}

// Enhanced chart update function with period support
function updateChart(data, startDate, endDate, period) {
    if (revenueChart) {
        revenueChart.destroy();
    }

    revenueChart = new Chart(ctx, {
        type: period === 'daily' ? 'line' : 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: `${period.charAt(0).toUpperCase() + period.slice(1)} Revenue`,
                data: data.revenue,
                backgroundColor: period === 'daily' ? 'rgba(166, 123, 91, 0.2)' : '#A67B5B',
                borderColor: '#A67B5B',
                borderWidth: period === 'daily' ? 3 : 1,
                tension: 0.1,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#A67B5B',
                pointBorderWidth: 2,
                pointRadius: period === 'daily' ? 5 : 0,
                pointHoverRadius: period === 'daily' ? 8 : 4,
                fill: period === 'daily'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e0d6c2'
                    },
                    ticks: {
                        color: '#5c4d3c',
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        color: '#e0d6c2'
                    },
                    ticks: {
                        color: '#5c4d3c',
                        maxRotation: period === 'daily' ? 45 : 0,
                        minRotation: period === 'daily' ? 45 : 0
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#5c4d3c'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Revenue: ₱${context.raw.toLocaleString()}`;
                        },
                        title: function(context) {
                            return context[0].label;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Initialize other charts
const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
let categoryRevenueChart;

function fetchCategoryRevenueData(categoryId, startDate, endDate, period = 'daily') {
    const cacheBuster = new Date().getTime();
    fetch(`/admin/category-revenue/${categoryId}?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}&period=${period}&_=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            updateCategoryChart(data, period);
        })
        .catch(error => {
            console.error('Error fetching category revenue data:', error);
        });
}

function updateCategoryChart(data, period) {
    if (categoryRevenueChart) {
        categoryRevenueChart.destroy();
    }

    categoryRevenueChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                label: `Revenue by Product (${period})`,
                data: data.revenue,
                backgroundColor: [
                    '#A67B5B', '#C9A87C', '#E3C16F', '#8D6E63', 
                    '#D4B483', '#BC8A5F', '#E6C39A', '#9C7E56'
                ],
                borderColor: '#f5f1ea',
                borderWidth: 1.5,
                hoverBackgroundColor: [
                    '#956A4F', '#B5976B', '#D3B15F', '#7D5E54',
                    '#C4A472', '#AA754D', '#D6B288', '#8B6D4A'
                ],
                hoverBorderWidth: 2
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
                        },
                        color: '#5c4d3c'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ₱${value.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

const allCategoriesCtx = document.getElementById('allCategoriesRevenueChart').getContext('2d');
let allCategoriesRevenueChart;

function fetchAllCategoriesRevenueData(startDate, endDate, period = 'daily') {
    const cacheBuster = new Date().getTime();
    fetch(`/admin/all-categories-revenue?start_date=${startDate.toISOString().split('T')[0]}&end_date=${endDate.toISOString().split('T')[0]}&period=${period}&_=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            updateAllCategoriesChart(data, period);
        })
        .catch(error => {
            console.error('Error fetching all categories revenue data:', error);
        });
}

function updateAllCategoriesChart(data, period) {
    if (allCategoriesRevenueChart) {
        allCategoriesRevenueChart.destroy();
    }

    allCategoriesRevenueChart = new Chart(allCategoriesCtx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: `Revenue by Category (${period})`,
                data: data.revenue,
                backgroundColor: [
                    '#A67B5B', '#C9A87C', '#E3C16F', '#8D6E63', '#D4B483', '#BC8A5F'
                ],
                borderColor: '#f5f1ea',
                borderWidth: 1.5,
                hoverBackgroundColor: [
                    '#956A4F', '#B5976B', '#D3B15F', '#7D5E54', '#C4A472', '#AA754D'
                ],
                hoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e0d6c2'
                    },
                    ticks: {
                        color: '#5c4d3c',
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        color: '#e0d6c2'
                    },
                    ticks: {
                        color: '#5c4d3c'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#5c4d3c'
                    }
                }
            }
        }
    });
}

// Function to refresh all charts
function refreshAllCharts() {
    const selectedDates = globalDatePicker.selectedDates;
    const period = document.getElementById('periodFilter').value;
    
    if (selectedDates.length === 2) {
        currentPeriod = period;
        currentStartDate = selectedDates[0];
        currentEndDate = selectedDates[1];
        
        fetchRevenueData(selectedDates[0], selectedDates[1], period);
        fetchAllCategoriesRevenueData(selectedDates[0], selectedDates[1], period);
        
        const categoryId = document.getElementById('categoryDropdown').value;
        if (categoryId) {
            fetchCategoryRevenueData(categoryId, selectedDates[0], selectedDates[1], period);
        }
    }
}

// Set initial date range (last 30 days)
const endDate = new Date();
const startDate = new Date();
startDate.setDate(endDate.getDate() - 30);
globalDatePicker.setDate([startDate, endDate]);

// Store initial dates
currentStartDate = startDate;
currentEndDate = endDate;

// Fetch initial data
fetchRevenueData(startDate, endDate, 'daily');
fetchAllCategoriesRevenueData(startDate, endDate, 'daily');

// Set up refresh interval (5 minutes)
const REFRESH_INTERVAL = 5 * 60 * 1000;
setInterval(refreshAllCharts, REFRESH_INTERVAL);

// Add event listeners
document.getElementById('refreshChartsBtn').addEventListener('click', refreshAllCharts);
document.getElementById('periodFilter').addEventListener('change', refreshAllCharts);

// Fetch category revenue data when a category is selected
document.getElementById('categoryDropdown').addEventListener('change', function() {
    const categoryId = this.value;
    const selectedDates = globalDatePicker.selectedDates;
    const period = document.getElementById('periodFilter').value;
    
    if (categoryId && selectedDates.length === 2) {
        fetchCategoryRevenueData(categoryId, selectedDates[0], selectedDates[1], period);
    }
});

// Initialize Lucide icons
lucide.createIcons();
</script>
@endsection