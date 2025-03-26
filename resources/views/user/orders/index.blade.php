@extends('layouts.app')

@section('content')
<!-- Full-screen center layout with warm background -->
<div class="flex justify-center items-center min-h-screen bg-[#e7d7c1] px-4 sm:px-0 py-8">
    <!-- Main container with elegant shadow and responsiveness -->
    <div class="bg-[#fdf8f2] p-6 rounded-xl shadow-xl w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2 border border-[#d3a87c]/30">
        <!-- Header with icon -->
        <div class="flex items-center justify-center mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#5a3825" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list mr-3">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                <path d="M12 11h4"/>
                <path d="M12 16h4"/>
                <path d="M8 11h.01"/>
                <path d="M8 16h.01"/>
            </svg>
            <div class="flex items-center justify-center mb-1">
            <h1 class="text-3xl font-bold text-[#5a3825]">My Orders</h1>
            </div>
        </div>

        <!-- Orders table container -->
        <div class="overflow-x-auto rounded-xl border border-[#d3a87c]/50 shadow-md mb-6 bg-white">
            <table class="w-full">
                <thead class="bg-[#c9a380] text-white">
                    <tr>
                        <th class="p-4 text-left font-semibold">
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
                        <th class="p-4 text-left font-semibold">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dollar-sign mr-2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/>
                                    <path d="M12 18V6"/>
                                </svg>
                                Total
                            </div>
                        </th>
                        <th class="p-4 text-left font-semibold">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet mr-2">
                                    <path d="M19 7V4a1 1 0 0 0-1-1H4a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                                    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                                </svg>
                                Received
                            </div>
                        </th>
                        <th class="p-4 text-left font-semibold">
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
                        <th class="p-4 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="5" class="p-6 text-center text-[#a67c52] italic">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#a67c52" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-x mb-3">
                                        <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                                        <path d="m7.5 4.27 9 5.15"/>
                                        <polyline points="3.29 7 12 12 20.71 7"/>
                                        <line x1="12" x2="12" y1="22" y2="12"/>
                                        <path d="m17 13 5 5m-5 0 5-5"/>
                                    </svg>
                                    No orders found.
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($orders as $order)
                            <tr class="border-t border-[#d3a87c]/30 hover:bg-[#f4e7da]/50 transition-colors duration-150">
                                <td class="p-4 font-medium text-[#5a3825]">{{ $order->id }}</td>
                                <td class="p-4 font-semibold text-[#8b5e3b]">₱{{ number_format($order->total_price, 2) }}</td>
                                <td class="p-4 font-semibold text-[#6d883e]">₱{{ number_format($order->amount_received, 2) }}</td>
                                <td class="p-4 font-semibold text-[#a94442]">₱{{ number_format($order->change, 2) }}</td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('user.orders.show', $order) }}" 
                                    class="inline-flex items-center bg-[#e0c9a6] hover:bg-[#d3b78c] text-[#5a3825] px-4 py-2 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye mr-2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination with improved styling -->
        @if ($orders->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="bg-[#fdf8f2] p-3 rounded-lg shadow-inner border border-[#d3a87c]/20">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom pagination styling to match our theme */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    .page-item.active .page-link {
        background-color: #c9a380;
        border-color: #c9a380;
        color: white;
    }
    .page-link {
        color: #5a3825;
        border: 1px solid #d3a87c;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.2s;
    }
    .page-link:hover {
        background-color: #f4e7da;
        border-color: #c9a380;
    }
    .page-item.disabled .page-link {
        color: #a67c52;
        border-color: #e0c9a6;
        background-color: #fdf8f2;
    }
</style>
@endsection