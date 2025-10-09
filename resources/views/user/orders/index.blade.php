@extends('layouts.app')

@section('content')
<style>
    /* Coffee Shop Theme CSS - Responsive */
   <style>
    /* Coffee Theme Base */
    .coffee-bg {
        background-color: #f5f1ea;
    }

    .coffee-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
        background-color: #f5f1ea;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .coffee-card {
        background-color: white;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-top: 1rem;
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
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
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
    
    /* Filter section styles */
    .filter-section {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .filter-label {
        color: #5c4d3c;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .filter-select {
        border: 1px solid #e0d6c2;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: white;
        color: #5c4d3c;
        min-width: 150px;
    }
    
    .period-display {
        background-color: #e0d6c2;
        color: #5c4d3c;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        margin-left: auto;
    }
    
    /* Responsive table container */
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Table styles */
    .orders-table {
        width: 100%;
        min-width: 600px;
        border-collapse: collapse;
    }

    .orders-table th,
    .orders-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e0d6c2;
    }

    /* Responsive text sizes */
    .responsive-text {
        font-size: clamp(0.875rem, 2vw, 1rem);
    }

    /* Header styles */
    .page-header {
        font-size: clamp(1.5rem, 4vw, 2rem);
        margin-bottom: 1.5rem;
    }

    /* Empty state styling */
    .empty-state {
        padding: 2rem;
        text-align: center;
    }

    /* Pagination responsive styles */
    .pagination-container {
        margin-top: 1.5rem;
    }

    .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
        min-width: 2.5rem;
        text-align: center;
    }

    /* Mobile-specific styles */
    @media (max-width: 640px) {
        .coffee-container {
            padding: 0.5rem;
        }

        .coffee-card {
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .orders-table th,
        .orders-table td {
            padding: 0.75rem 0.5rem;
        }

        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .page-header svg {
            margin-bottom: 0.5rem;
            margin-right: 0;
        }
        
        .filter-section {
            flex-direction: column;
            align-items: flex-end;
        }
        
        .period-display {
            margin-left: 0;
            width: 100%;
            text-align: center;
        }
    }
</style>


<div class="min-h-screen coffee-bg">
    <main class="p-6">
        <div class="coffee-container">
            <div class="coffee-card">
                <!-- Header with icon -->
                <div class="flex flex-col sm:flex-row items-center justify-between mb-6 page-header">
                    <div class="flex items-center justify-center sm:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list mr-0 sm:mr-3 mb-2 sm:mb-0">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <path d="M12 11h4"/>
                            <path d="M12 16h4"/>
                            <path d="M8 11h.01"/>
                            <path d="M8 16h.01"/>
                        </svg>
                        <h1 class="text-2xl sm:text-3xl font-bold coffee-text-primary text-center sm:text-left">My Orders</h1>
                    </div>

                    <!-- Filter Section - Fixed in the top right -->
                    <div class="filter-section">
                        <span class="filter-label">Filter by:</span>
                        <select id="timeFilter" class="filter-select" onchange="updateFilter()">
                            <option value="all" {{ request('filter') == 'all' || !request('filter') ? 'selected' : '' }}>All Orders</option>
                            <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        
                        @php
                            $filter = request('filter', 'all');
                            $periodText = '';
                            
                            if ($filter === 'daily') {
                                $periodText = 'Today: ' . now()->format('F j, Y');
                            } elseif ($filter === 'weekly') {
                                $startOfWeek = now()->startOfWeek(); // Sunday
                                $endOfWeek = now()->endOfWeek(); // Saturday
                                $periodText = 'This Week: ' . $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y');
                            } elseif ($filter === 'monthly') {
                                $periodText = 'This Month: ' . now()->format('F Y');
                            } elseif ($filter === 'yearly') {
                                $periodText = 'This Year: ' . now()->format('Y');
                            }
                        @endphp
                        
                        @if($filter !== 'all')
                            <div class="period-display">
                                {{ $periodText }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Responsive table container -->
                <div class="table-container">
                    <table class="orders-table">
                        <thead class="coffee-table-header">
                            <tr>
                                <th class="p-3 responsive-text">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash mr-2">
                                            <line x1="4" x2="20" y1="9" y2="9"/>
                                            <line x1="4" x2="20" y1="15" y2="15"/>
                                            <line x1="10" x2="8" y1="3" y2="21"/>
                                            <line x1="16" x2="14" y1="3" y2="21"/>
                                        </svg>
                                        ID
                                    </div>
                                </th>
                                <th class="p-3 responsive-text">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dollar-sign mr-2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                                            <path d="M12 18V6"/>
                                        </svg>
                                        Total
                                    </div>
                                </th>
                                <th class="p-3 responsive-text">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet mr-2">
                                            <path d="M19 7V4a1 1 0 0 0-1-1H4a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                                            <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                                        </svg>
                                        Received
                                    </div>
                                </th>
                                <th class="p-3 responsive-text">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-coins mr-2">
                                            <circle cx="8" cy="8" r="6"/>
                                            <path d="M18.09 10.37A6 6 0 1 1 10.34 18"/>
                                            <path d="M7 6h1v4"/>
                                            <path d="m16.71 13.88.7.71-2.82 2.82"/>
                                        </svg>
                                        Change
                                    </div>
                                </th>
                                <th class="p-3 responsive-text">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar mr-2">
                                            <path d="M8 2v4"/>
                                            <path d="M16 2v4"/>
                                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                                            <path d="M3 10h18"/>
                                        </svg>
                                        Date
                                    </div>
                                </th>
                                <th class="p-3 responsive-text text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isEmpty())
                                <tr>
                                    <td colspan="6" class="empty-state coffee-text-primary italic">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#a67c52" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-x mb-3">
                                                <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                                                <path d="m7.5 4.27 9 5.15"/>
                                                <polyline points="3.29 7 12 12 20.71 7"/>
                                                <line x1="12" x2="12" y1="22" y2="12"/>
                                                <path d="m17 13 5 5m-5 0 5-5"/>
                                            </svg>
                                            No orders found for the selected period.
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach($orders as $order)
                                    <tr class="hover:bg-[#f4e7da]/50 transition-colors duration-150">
                                        <td class="p-3 font-medium coffee-text-primary responsive-text">{{ $order->id }}</td>
                                        <td class="p-3 font-semibold text-[#8b5e3b] responsive-text">₱{{ number_format($order->total_price, 2) }}</td>
                                        <td class="p-3 font-semibold text-[#6d883e] responsive-text">₱{{ number_format($order->amount_received, 2) }}</td>
                                        <td class="p-3 font-semibold text-[#a94442] responsive-text">₱{{ number_format($order->change, 2) }}</td>
                                        <td class="p-3 coffee-text-primary responsive-text">{{ $order->created_at->format('M j, Y g:i A') }}</td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('user.orders.show', $order) }}" 
                                            class="inline-flex items-center coffee-btn-view">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye mr-2">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                <span class="hidden sm:inline">View</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Responsive pagination -->
                @if ($orders->hasPages())
                    <div class="pagination-container">
                        <div class="pagination">
                            {{ $orders->appends(['filter' => request('filter')])->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script>
    function updateFilter() {
        const filter = document.getElementById('timeFilter').value;
        const url = new URL(window.location.href);
        
        if (filter === 'all') {
            url.searchParams.delete('filter');
        } else {
            url.searchParams.set('filter', filter);
        }
        
        window.location.href = url.toString();
    }
</script>
@endsection