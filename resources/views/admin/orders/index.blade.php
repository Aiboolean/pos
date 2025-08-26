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

<div class="min-h-screen coffee-bg flex justify-center items-center px-4 sm:px-0 py-8">
    <div class="coffee-container p-6 w-full max-w-6xl"> <!-- Increased max width for better layout -->
        <div class="coffee-card p-6 relative" style="min-height: 700px; padding-bottom: 60px;">
            
            <!-- Header Section with Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag mr-2">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <h1 class="text-2xl font-bold coffee-text-primary">Orders Management</h1>
                </div>

                <!-- PDF Export Button - Moved to top right -->
                <form method="GET" action="{{ route('admin.orders.report.pdf') }}" class="w-full sm:w-auto">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit" class="coffee-btn-pdf px-4 py-2 rounded-lg font-medium flex items-center justify-center shadow-sm hover:shadow-md transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                            <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/>
                        </svg>
                        Export PDF
                    </button>
                </form>
            </div>

            <!-- Filter Card -->
            <div class="bg-[#f8f3e9] border border-[#d9c7b3] rounded-xl p-4 mb-6 shadow-sm">
                <h2 class="text-lg font-semibold coffee-text-primary mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                    </svg>
                    Filter Orders
                </h2>
                
                <form method="GET" action="{{ route('admin.orders') }}" class="flex flex-col lg:flex-row gap-4 items-end">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        <div class="flex flex-col">
                            <label for="start_date" class="text-sm font-medium coffee-text-secondary mb-2">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                                   class="coffee-input rounded-lg px-4 py-2 border border-[#d9c7b3] focus:ring-2 focus:ring-[#a67c52] focus:border-transparent">
                        </div>
                        
                        <div class="flex flex-col">
                            <label for="end_date" class="text-sm font-medium coffee-text-secondary mb-2">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                                   class="coffee-input rounded-lg px-4 py-2 border border-[#d9c7b3] focus:ring-2 focus:ring-[#a67c52] focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex gap-2 w-full lg:w-auto">
                        <button type="submit" class="coffee-btn-filter px-6 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors hover:bg-[#8b5d3c]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.orders') }}" class="px-6 py-2 rounded-lg font-medium border border-[#d9c7b3] text-[#5c4d3c] hover:bg-[#f0e6d8] transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18"/>
                                <path d="M19 12H5"/>
                                <path d="M6 18h12"/>
                            </svg>
                            Clear
                        </a>
                    </div>
                </form>

                <!-- Active Filters Badge -->
                @if(request('start_date') || request('end_date'))
                <div class="mt-4 pt-3 border-t border-[#d9c7b3]">
                    <span class="text-sm coffee-text-secondary mr-2">Active filters:</span>
                    @if(request('start_date'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#e6d7c1] text-[#5c4d3c] mr-2">
                        From: {{ request('start_date') }}
                    </span>
                    @endif
                    @if(request('end_date'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#e6d7c1] text-[#5c4d3c]">
                        To: {{ request('end_date') }}
                    </span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Results Summary -->
            @if($orders->total() > 0)
            <div class="mb-4 flex justify-between items-center">
                <p class="text-sm coffee-text-secondary">
                    Showing {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </p>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-[#f0e6d8] text-[#5c4d3c]">
                    {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                </span>
            </div>
            @endif

            <!-- Table Container -->
            <div class="overflow-x-auto rounded-lg shadow-sm border border-[#d9c7b3]">
                <table class="w-full">
                    <thead class="bg-[#f0e6d8]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Change</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e6d7c1]">
                        @forelse($orders as $order)
                        <tr class="hover:bg-[#faf7f2] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium coffee-text-primary">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm coffee-text-primary">{{ $order->user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                ₱{{ number_format($order->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                ₱{{ number_format($order->amount_received, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                ₱{{ number_format($order->change, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="coffee-btn-view px-3 py-1 rounded-lg text-sm font-medium shadow-sm inline-flex items-center hover:shadow-md transition-shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye mr-1">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#a67c52" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package mb-4 opacity-60">
                                        <path d="M16.5 9.4 7.5 4.21"/>
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                        <path d="m3.27 6.96 8.73 5.05 8.85-5.06"/>
                                        <path d="M12 22.08V12"/>
                                    </svg>
                                    <p class="text-lg font-medium coffee-text-primary mb-2">No orders found</p>
                                    <p class="text-sm coffee-text-secondary">Try adjusting your filters or check back later</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="coffee-pagination p-3 rounded-lg">
                    {{ $orders->appends(request()->except('page'))->links() }}
                </div>
            </div>
            @endif
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