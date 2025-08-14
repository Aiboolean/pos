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
    
    .coffee-table-header {
        background-color: #f5f1ea;
        color: #5c4d3c;
    }
    
    .coffee-table-row:hover {
        background-color: #f9f7f3;
    }
    
    .coffee-btn-view {
        background-color: #6f4e37;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-view:hover {
        background-color: #5c3d2a;
    }
    
    .coffee-empty-state {
        color: #a67c52;
    }
    
    .coffee-pagination {
        background-color: white;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="min-h-screen coffee-bg flex justify-center items-center px-4 sm:px-6 md:px-8 py-8">
    <div class="coffee-container p-4 sm:p-6 w-full max-w-7xl">
        <div class="coffee-card p-4 sm:p-6 relative" style="min-height: 700px; padding-bottom: 60px;">
            <!-- Header with icon -->
            <div class="flex items-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag mr-2">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                    <path d="M3 6h18"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <h1 class="text-xl sm:text-2xl font-bold coffee-text-primary">Sales</h1>
            </div>

            <!-- Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="min-w-full coffee-border rounded-lg shadow-sm">
                    <thead class="coffee-table-header">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium uppercase tracking-wider border-b coffee-border">ID</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium uppercase tracking-wider border-b coffee-border">User</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium uppercase tracking-wider border-b coffee-border">Total Price</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium uppercase tracking-wider border-b coffee-border">Amount Received</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium uppercase tracking-wider border-b coffee-border">Change</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium uppercase tracking-wider border-b coffee-border">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y coffee-border">
                        @forelse($orders as $order)
                        <tr class="coffee-table-row">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium coffee-text-primary">{{ $order->id }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm coffee-text-primary">{{ $order->user->username }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-blue-600">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-green-600">₱{{ number_format($order->amount_received, 2) }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-red-600">₱{{ number_format($order->change, 2) }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm coffee-text-primary">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="coffee-btn-view px-3 sm:px-4 py-2 rounded-lg font-medium shadow-sm inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="lucide lucide-eye mr-1">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 sm:px-6 py-4 text-center coffee-empty-state">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                         viewBox="0 0 24 24" fill="none" stroke="#a67c52" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package mb-2">
                                        <path d="M16.5 9.4 7.5 4.21"/>
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                        <path d="m3.27 6.96 8.73 5.05 8.85-5.06"/>
                                        <path d="M12 22.08V12"/>
                                    </svg>
                                    <p class="text-lg font-medium coffee-text-primary">No orders found</p>
                                    <p class="text-sm coffee-text-secondary">There are currently no orders to display</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Fixed Pagination Links -->
            <div class="absolute bottom-4 right-4 coffee-pagination p-2 rounded-lg">
                {{ $orders->links() }}
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