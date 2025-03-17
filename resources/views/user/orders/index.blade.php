@extends('layouts.app')

@section('content')
<!-- Full-screen center layout with background -->
<div class="flex justify-center items-center min-h-screen bg-[#e7d7c1] px-4 sm:px-0">
    <!-- Main container with shadow and responsiveness -->
    <div class="bg-[#e7d7c1] p-6 rounded-lg shadow-xl w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2">
        <div class="container bg-[#fdf8f2] p-6 rounded-xl shadow-xl relative" style="min-height: 700px; padding-bottom: 60px;">
            <h1 class="text-3xl font-bold text-[#5a3825] mb-6 text-center">My Orders</h1>
            <div class="overflow-x-auto">
                <table class="w-full border border-[#d3a87c] rounded-lg shadow-md text-[#5a3825]">
                    <thead class="bg-[#c9a380] text-white">
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
                                <td colspan="5" class="border p-3 text-center text-[#a67c52] italic">No orders found.</td>
                            </tr>
                        @else
                            @foreach($orders as $order)
                                <tr class="border-t hover:bg-[#f4e7da] transition">
                                    <td class="border p-3">{{ $order->id }}</td>
                                    <td class="border p-3 font-medium text-[#8b5e3b]">₱{{ number_format($order->total_price, 2) }}</td>
                                    <td class="border p-3 font-medium text-[#6d883e]">₱{{ number_format($order->amount_received, 2) }}</td>
                                    <td class="border p-3 font-medium text-[#a94442]">₱{{ number_format($order->change, 2) }}</td>
                                    <td class="border p-3">
                                        <a href="{{ route('user.orders.show', $order) }}" 
                                        class="bg-[#a67c52] text-white px-4 py-2 rounded-lg font-medium transition hover:bg-[#8c5c34] block text-center sm:inline">
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
                <div class="absolute bottom-4 right-4 bg-[#fdf8f2] p-2 rounded-lg shadow-lg">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
