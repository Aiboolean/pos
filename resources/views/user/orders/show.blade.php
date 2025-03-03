@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Order Details</h1>
    <div>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Total Price:</strong> {{ $order->total_price }}</p>
        <p><strong>Amount Received:</strong> {{ $order->amount_received }}</p>
        <p><strong>Change:</strong> {{ $order->change }}</p>
    </div>
    <h2>Items</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Size</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->size }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection