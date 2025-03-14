@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full">
    <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
    <p>Welcome to the admin panel. System analytics will be displayed here in the future.</p>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700">Total Orders</h3>
            <p class="text-2xl font-bold text-blue-500">{{ $totalOrders }}</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700">Total Revenue</h3>
            <p class="text-2xl font-bold text-green-500">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>

        <!-- Total Sales Today -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700">Total Sales Today</h3>
            <p class="text-2xl font-bold text-purple-500">₱{{ number_format($totalSalesToday, 2) }}</p>
        </div>

        <!-- Best Seller -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700">Best Seller</h3>
            @if ($bestSeller)
                <p class="text-xl font-bold text-purple-500">
                    {{ $bestSeller->product->name }} ({{ $bestSeller->total_quantity }} sold)
                </p>
            @else
                <p class="text-xl font-bold text-purple-500">No sales yet</p>
            @endif
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Chart</h3>
        <!-- Date Range Picker -->
        <div class="mb-4">
            <input type="text" id="dateRangePicker" class="w-full p-2 border rounded-lg" placeholder="Select Date Range">
        </div>
        <!-- Chart Canvas -->
        <canvas id="revenueChart" width="400" height="200"></canvas>
    </div>

    <!-- Sales per Product -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Sales per Product</h3>
        <!-- Product Dropdown -->
        <div class="mb-4">
            <select id="productDropdown" class="w-full p-2 border rounded-lg">
                <option value="">Select a Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <!-- Sales Data -->
        <div id="productSalesData" class="hidden">
            <p class="text-lg"><strong>Total Revenue:</strong> ₱<span id="productRevenue">0.00</span></p>
            <p class="text-lg"><strong>Times Ordered:</strong> <span id="productOrders">0</span></p>
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

    // Initialize Chart.js
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

    // Fetch sales data for the selected product
    document.getElementById('productDropdown').addEventListener('change', function() {
        const productId = this.value;
        if (productId) {
            fetch(`/admin/product-sales/${productId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('productSalesData').classList.remove('hidden');
                    document.getElementById('productRevenue').textContent = data.totalRevenue.toFixed(2);
                    document.getElementById('productOrders').textContent = data.timesOrdered;
                });
        } else {
            document.getElementById('productSalesData').classList.add('hidden');
        }
    });
</script>
@endsection