@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <div class="w-3/3 p-6">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>

        <!-- Analytics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
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
    </div>
</div>
@endsection
