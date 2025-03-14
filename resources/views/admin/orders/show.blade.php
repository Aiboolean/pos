@extends('layouts.app')

@section('content')
<!-- Added outer div for styling and centering -->
<div class="flex justify-center items-center min-h-screen bg-[#f1eadc]">
    <!-- Added new div with background color and rounded corners -->
    <div class="bg-[#f1eadc] p-6 rounded-lg shadow-lg">
        <div class="container bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">Order Details</h1>
            <div class="mb-4">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Cashier:</strong> {{ $order->user->usernane }}</p>
                <p><strong>Total Price:</strong> {{ $order->total_price }}</p>
                <p><strong>Amount Received:</strong> {{ $order->amount_received }}</p>
                <p><strong>Change:</strong> {{ $order->change }}</p>
            </div>
            <h2 class="text-xl font-bold mb-2">Items</h2>
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Product</th>
                        <th class="border p-2">Quantity</th>
                        <th class="border p-2">Price</th>
                        <th class="border p-2">Size</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border">
                            <td class="border p-2">{{ $item->product->name }}</td>
                            <td class="border p-2">{{ $item->quantity }}</td>
                            <td class="border p-2">{{ $item->price }}</td>
                            <td class="border p-2">{{ $item->size }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
