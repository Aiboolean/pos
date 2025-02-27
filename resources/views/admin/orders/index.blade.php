@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Orders</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Amount Received</th>
                <th>Change</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->total_price }}</td>
                    <td>{{ $order->amount_received }}</td>
                    <td>{{ $order->change }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection