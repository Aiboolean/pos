@extends('layouts.app')

@section('content')
<style>
    /* Coffee Shop Theme CSS */
    .coffee-bg {
        background-color: #f5f1ea;
    }
    
    .coffee-container {
        background-color: #f5f1ea;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
    
    .coffee-info-box {
        background-color: #f9f7f3;
        border: 1px solid #e0d6c2;
    }
    
    .coffee-btn-primary {
        background-color: #6f4e37;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-primary:hover {
        background-color: #5c3d2a;
    }
    
    .coffee-table-header {
        background-color: #f5f1ea;
        color: #5c4d3c;
    }
    
    .coffee-table-row:hover {
        background-color: #f9f7f3;
    }
</style>

<div class="min-h-screen coffee-bg flex justify-center items-center px-4 sm:px-0 py-8">
    <div class="coffee-container p-6 w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2">
        <div class="coffee-card p-6">
            <!-- Header with icon and back button -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt mr-2">
                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1Z"/>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                        <path d="M12 17.5v-11"/>
                    </svg>
                    <h1 class="text-2xl font-bold coffee-text-primary">Order Details</h1>
                </div>
                <a href="{{ route('admin.orders') }}" 
                   class="coffee-btn-primary px-4 py-2 rounded-lg font-medium shadow-sm inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left mr-1">
                        <path d="m12 19-7-7 7-7"/>
                        <path d="M19 12H5"/>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <!-- Order Information -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="coffee-info-box p-4 rounded-lg">
                    <p class="font-medium coffee-text-primary mb-1">Order ID: <span class="font-normal">{{ $order->id }}</span></p>
                    <p class="font-medium coffee-text-primary mb-1">Cashier: <span class="font-normal">{{ $order->user->username }}</span></p>
                    <p class="font-medium coffee-text-primary mb-1">Transaction Date: 
                        <span class="font-normal">{{ $order->created_at->format('F j, Y h:i A') }}</span>
                    </p>
                </div>
                <div class="coffee-info-box p-4 rounded-lg">
                    <p class="font-medium coffee-text-primary mb-1">Total Price: <span class="font-normal">₱{{ number_format($order->total_price, 2) }}</span></p>
                    <p class="font-medium coffee-text-primary mb-1">Amount Received: <span class="font-normal">₱{{ number_format($order->amount_received, 2) }}</span></p>
                    <p class="font-medium coffee-text-primary mb-1">Change: <span class="font-normal">₱{{ number_format($order->change, 2) }}</span></p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list mr-2">
                    <line x1="8" x2="21" y1="6" y2="6"/>
                    <line x1="8" x2="21" y1="12" y2="12"/>
                    <line x1="8" x2="21" y1="18" y2="18"/>
                    <line x1="3" x2="3.01" y1="6" y2="6"/>
                    <line x1="3" x2="3.01" y1="12" y2="12"/>
                    <line x1="3" x2="3.01" y1="18" y2="18"/>
                </svg>
                <h2 class="text-xl font-bold coffee-text-primary">Items</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full coffee-border rounded-lg shadow-sm">
                    <thead class="coffee-table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Product</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Quantity</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Price</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b coffee-border">Size</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y coffee-border">
                        @foreach($order->items as $item)
                        <tr class="coffee-table-row">
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $item->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">₱{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $item->size }}</td>
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