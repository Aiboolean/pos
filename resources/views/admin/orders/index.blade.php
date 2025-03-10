@extends('layouts.app')

@section('content')
<!-- Added outer div for styling and centering -->
<div class="flex justify-center items-center min-h-screen bg-[#f1eadc]">
    <!-- Added new div with background color and rounded corners -->
    <div class="bg-[#f1eadc] p-6 rounded-lg shadow-lg">
        <div class="container bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Orders</h1>
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Cashier</th>
                        <th class="border p-2">Total Price</th>
                        <th class="border p-2">Amount Received</th>
                        <th class="border p-2">Change</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border">
                            <td class="border p-2">{{ $order->id }}</td>
                            <td class="border p-2">{{ $order->user->name }}</td>
                            <td class="border p-2">{{ $order->total_price }}</td>
                            <td class="border p-2">{{ $order->amount_received }}</td>
                            <td class="border p-2">{{ $order->change }}</td>
                            <td class="border p-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="bg-blue-500 text-white px-3 py-1 rounded">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection