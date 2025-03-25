@extends('layouts.app')

@section('content')
<!-- Full-screen center layout with background -->
<div class="flex justify-center items-center min-h-screen bg-[#f5f1ea] px-4 sm:px-0">
    <!-- Main container with shadow and responsiveness -->
    <div class="bg-[#f5f1ea] p-6 rounded-xl shadow-xl w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2">
        <div class="container bg-white p-6 rounded-xl shadow-lg border border-[#e0d6c2] relative" style="min-height: 700px; padding-bottom: 60px;">
            <!-- Header with icon -->
            <div class="flex items-center mb-6">
                <h1 class="text-2xl font-bold text-[#5c4d3c]">
                    <i data-lucide="shopping-bag" class="inline-block w-6 h-6 text-[#8c7b6b] mr-2"></i>
                    Orders Management
                </h1>
            </div>

            <!-- Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full border border-[#e0d6c2] rounded-lg shadow-sm">
                    <thead class="bg-[#f5f1ea] text-[#5c4d3c]">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">User</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Total Price</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Amount Received</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Change</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider border-b border-[#e0d6c2]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e0d6c2]">
                        @forelse($orders as $order)
                        <tr class="hover:bg-[#f9f7f3] transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#5c4d3c]">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#5c4d3c]">{{ $order->user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                ₱{{ number_format($order->total_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                ₱{{ number_format($order->amount_received, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                ₱{{ number_format($order->change, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#5c4d3c]">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="bg-[#6f4e37] hover:bg-[#5c3d2a] text-black px-4 py-2 rounded-lg font-medium transition-colors shadow-md inline-flex items-center">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-[#8c7b6b]">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i data-lucide="package" class="w-12 h-12 text-[#a67c52] mb-2"></i>
                                    <p class="text-lg font-medium text-[#5c4d3c]">No orders found</p>
                                    <p class="text-sm text-[#8c7b6b]">There are currently no orders to display</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Fixed Pagination Links -->
            <div class="absolute bottom-4 right-4 bg-white p-2 rounded-lg shadow-lg border border-[#e0d6c2]">
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