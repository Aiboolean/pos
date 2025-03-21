@extends('layouts.app')

@section('content')
<div class="content-wrapper d-flex justify-content-center align-items-center min-vh-100">
    <div class="container d-flex flex-column align-items-center text-center">

        <!-- Order Details Section -->
        <div class="order-details-container d-flex justify-content-center">
            <div class="card shadow-lg p-4 mb-4 bg-light-coffee text-dark-brown rounded-4" style="max-width: 600px;">
                <h1 class="mb-4 text-brown fw-bold">☕ Order Details ☕</h1>
                <div class="detail-item"><strong>Order ID:</strong> {{ $order->id }}</div>
                <div class="detail-item"><strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }}</div>
                <div class="detail-item"><strong>Amount Received:</strong> ₱{{ number_format($order->amount_received, 2) }}</div>
                <div class="detail-item"><strong>Change:</strong> ₱{{ number_format($order->change, 2) }}</div>
            </div>
        </div>

        <!-- Items Ordered Section -->
        <div class="items-ordered-container d-flex justify-content-center">
            <div class="card shadow-lg p-4 mb-4 bg-light-coffee text-dark-brown rounded-4" style="max-width: 600px; width: 100%;">
                <h2 class="mb-3 text-brown text-center fw-bold" style="font-size: 2rem;"> Items Ordered</h2>
                <div class="table-responsive d-flex justify-content-center">
                    <table class="table table-hover table-bordered custom-table mx-auto" style="max-width: 500px; border-radius: 12px; overflow: hidden;">
                        <thead class="bg-white text-light fw-bold">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr style="background-color: #f1eadc;">
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>{{ ucfirst($item->size) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8e1c4;
        font-family: 'Poppins', sans-serif;
    }
    .content-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .order-details-container, .items-ordered-container {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .bg-light-coffee {
        background-color: #f3d9b1 !important;
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .bg-medium-coffee {
        background-color: #d2a679 !important;
        border-radius: 12px;
    }
    .text-brown {
        color: #5c3d2e;
    }
    .text-dark-brown {
        color: #3d2b1f;
    }
    .detail-item {
        padding: 10px;
        border-bottom: 1px solid #c4a484;
        font-size: 1.1rem;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .custom-table {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    .custom-table th, .custom-table td {
        padding: 12px;
        text-align: center;
    }
    .custom-table thead {
        border-bottom: 3px solid black;
    }
    
    .custom-table tbody tr:hover {
        background-color: #e8dac4;  
    }
</style>
@endsection
