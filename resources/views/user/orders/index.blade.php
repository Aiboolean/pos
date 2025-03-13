@extends('layouts.app')

@section('content')
<!-- Full-screen center layout with background -->
<div class="flex justify-center items-center min-h-screen bg-[#f1eadc] px-4 sm:px-0">
    <!-- Main container with shadow and responsiveness -->
    <div class="bg-[#f1eadc] p-6 rounded-lg shadow-lg w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2">
        <div class="container bg-white p-6 rounded-xl shadow-lg relative" style="min-height: 700px; padding-bottom: 60px;">
            <h1 class="text-2xl font-bold text-gray-700 mb-6">My Orders</h1>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-lg shadow-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border p-3">ID</th>
                            <th class="border p-3">Total Price</th>
                            <th class="border p-3">Amount Received</th>
                            <th class="border p-3">Change</th>
                            <th class="border p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($orders->isEmpty())
                            <tr>
                                <td colspan="5" class="border p-3 text-center text-gray-500">No orders found.</td>
                            </tr>
                        @else
                            @foreach($orders as $order)
                                <tr class="border-t hover:bg-gray-50 transition">
                                    <td class="border p-3">{{ $order->id }}</td>
                                    <td class="border p-3 font-medium text-blue-600">{{ number_format($order->total_price, 2) }}</td>
                                    <td class="border p-3 font-medium text-green-600">{{ number_format($order->amount_received, 2) }}</td>
                                    <td class="border p-3 font-medium text-red-600">{{ number_format($order->change, 2) }}</td>
                                    <td class="border p-3">
                                        <a href="{{ route('user.orders.show', $order) }}" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded-lg font-medium transition hover:bg-blue-600 block text-center sm:inline">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Fixed Pagination Links -->
            @if ($orders->hasPages())
                <div class="absolute bottom-4 right-4 bg-white p-2 rounded-lg shadow-lg">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection