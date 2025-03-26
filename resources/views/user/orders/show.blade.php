@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8f3ed] flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-2xl space-y-6">
        <!-- Order Header -->
        <div class="flex items-center justify-center gap-3 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#5c3d2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                <path d="M12 11h4"/>
                <path d="M12 16h4"/>
                <path d="M8 11h.01"/>
                <path d="M8 16h.01"/>
            </svg>
            <h1 class="text-3xl font-bold text-[#5c3d2e]">Order Details</h1>
        </div>

        <!-- Order Details Wrapper -->
        <div class="bg-[#f1eadc] rounded-xl p-6">
            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-[#e0d5c8]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c3d2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash">
                            <line x1="4" x2="20" y1="9" y2="9"/>
                            <line x1="4" x2="20" y1="15" y2="15"/>
                            <line x1="10" x2="8" y1="3" y2="21"/>
                            <line x1="16" x2="14" y1="3" y2="21"/>
                        </svg>
                        <div>
                            <p class="text-sm text-[#8b735b]">Order ID</p>
                            <p class="font-medium text-[#5c3d2e]">{{ $order->id }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c3d2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dollar-sign">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                            <path d="M12 18V6"/>
                        </svg>
                        <div>
                            <p class="text-sm text-[#8b735b]">Total Price</p>
                            <p class="font-medium text-[#5c3d2e]">₱{{ number_format($order->total_price, 2) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c3d2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet">
                            <path d="M19 7V4a1 1 0 0 0-1-1H4a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                            <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                        </svg>
                        <div>
                            <p class="text-sm text-[#8b735b]">Amount Received</p>
                            <p class="font-medium text-[#5c3d2e]">₱{{ number_format($order->amount_received, 2) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c3d2e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-coins">
                            <circle cx="8" cy="8" r="6"/>
                            <path d="M18.09 10.37A6 6 0 1 1 10.34 18"/>
                            <path d="M7 6h1v4"/>
                            <path d="m16.71 13.88.7.71-2.82 2.82"/>
                        </svg>
                        <div>
                            <p class="text-sm text-[#8b735b]">Change</p>
                            <p class="font-medium text-[#5c3d2e]">₱{{ number_format($order->change, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Ordered Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-[#e0d5c8] mt-4">
                <h2 class="text-xl font-bold text-[#5c3d2e]">Items Ordered</h2>
                <table class="w-full mt-3">
                    <thead class="bg-[#f3e9dd] text-[#5c3d2e]">
                        <tr>
                            <th class="p-3 text-left font-medium">Product</th>
                            <th class="p-3 text-center font-medium">Qty</th>
                            <th class="p-3 text-center font-medium">Price</th>
                            <th class="p-3 text-center font-medium">Size</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e0d5c8]">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-[#f9f5f0] transition-colors">
                                <td class="p-3 text-[#5c3d2e]">{{ $item->product->name }}</td>
                                <td class="p-3 text-center text-[#5c3d2e]">{{ $item->quantity }}</td>
                                <td class="p-3 text-center text-[#5c3d2e]">₱{{ number_format($item->price, 2) }}</td>
                                <td class="p-3 text-center text-[#5c3d2e]">{{ ucfirst($item->size) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
