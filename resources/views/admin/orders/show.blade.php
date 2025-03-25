@extends('layouts.app')

@section('content')
<!-- Full-screen center layout with background -->
<div class="flex justify-center items-center min-h-screen bg-[#f5f1ea] px-4 sm:px-0">
    <!-- Main container with shadow and responsiveness -->
    <div class="bg-[#f5f1ea] p-6 rounded-xl shadow-xl w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2">
        <div class="container bg-white p-6 rounded-xl shadow-lg border border-[#e0d6c2]">
            <!-- Header with icon and back button -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-black">
                        <i data-lucide="receipt" class="inline-block w-6 h-6 text-[#8c7b6b] mr-2"></i>
                        Order Details
                    </h1>
                </div>
                <a href="{{ route('admin.orders') }}" 
                   class="bg-[#6f4e37] hover:bg-[#5c3d2a] text-black px-4 py-2 rounded-lg font-medium transition-colors shadow-md inline-flex items-center">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    Back to Orders
                </a>
            </div>

            <!-- Order Information -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-[#f9f7f3] p-4 rounded-lg border border-[#e0d6c2]">
                    <p class="font-medium text-black mb-1">Order ID: <span class="font-normal">{{ $order->id }}</span></p>
                    <p class="font-medium text-black mb-1">Cashier: <span class="font-normal">{{ $order->user->username }}</span></p>
                </div>
                <div class="bg-[#f9f7f3] p-4 rounded-lg border border-[#e0d6c2]">
                    <p class="font-medium text-black mb-1">Total Price: <span class="font-normal">₱{{ number_format($order->total_price, 2) }}</span></p>
                    <p class="font-medium text-black mb-1">Amount Received: <span class="font-normal">₱{{ number_format($order->amount_received, 2) }}</span></p>
                    <p class="font-medium text-black mb-1">Change: <span class="font-normal">₱{{ number_format($order->change, 2) }}</span></p>
                </div>
            </div>

            <!-- Items Table -->
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <i data-lucide="list" class="w-5 h-5 text-[#8c7b6b] mr-2"></i>
                Items
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-[#e0d6c2] rounded-lg shadow-sm">
                    <thead class="bg-[#f5f1ea] text-black">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Product</th>
                            <th class="px-4 py-2 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Quantity</th>
                            <th class="px-4 py-2 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Price</th>
                            <th class="px-4 py-2 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Size</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e0d6c2]">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-[#f9f7f3] transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-black">{{ $item->product->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-black">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-black">₱{{ number_format($item->price, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-black">{{ $item->size }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/lucide.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endpush
@endsection