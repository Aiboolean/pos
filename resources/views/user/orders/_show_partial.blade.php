<style>
    /* Coffee Shop Theme CSS */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-container { max-width: 1200px; margin: 0 auto; padding: 1rem; }
    .coffee-card { background-color: #fdf8f2; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; padding: 1.5rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-table-header { background-color: #f5f1ea; color: #5c4d3c; }
    .coffee-table-row:hover { background-color: #f9f7f3; }
    .info-card { background-color: white; border: 1px solid #e0d6c2; border-radius: 0.5rem; padding: 1.5rem; }
    .info-item { display: flex; align-items: center; gap: 0.75rem; }
    .info-label { color: #8c7b6b; font-size: 0.875rem; }
    .info-value { color: #5c4d3c; font-weight: 500; }
    
    @media (max-width: 768px) {
        .coffee-container { padding: 0.75rem; }
        .coffee-card { padding: 1rem; }
        .grid-cols-2 { grid-template-columns: 1fr; }
    }
</style>

<div class="coffee-card">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list">
            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>
        </svg>
        <h1 class="text-2xl sm:text-3xl font-bold coffee-text-primary text-center">Order Details</h1>
    </div>

    <div class="info-card mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                <div>
                    <p class="info-label">Order ID</p>
                    <p class="info-value">{{ $order->id }}</p>
                </div>
            </div>
            <div class="info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dollar-sign"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                <div>
                    <p class="info-label">Total Price</p>
                    <p class="info-value">₱{{ number_format($order->total_price, 2) }}</p>
                </div>
            </div>
            <div class="info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H4a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                <div>
                    <p class="info-label">Amount Received</p>
                    <p class="info-value">₱{{ number_format($order->amount_received, 2) }}</p>
                </div>
            </div>
            <div class="info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-coins"><circle cx="8" cy="8" r="6"/><path d="M18.09 10.37A6 6 0 1 1 10.34 18"/><path d="M7 6h1v4"/><path d="m16.71 13.88.7.71-2.82 2.82"/></svg>
                <div>
                    <p class="info-label">Change</p>
                    <p class="info-value">₱{{ number_format($order->change, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="info-card">
        <h2 class="text-xl font-bold coffee-text-primary mb-4">Items Ordered</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="coffee-table-header">
                    <tr>
                        <th class="p-3 text-left font-medium coffee-text-primary">Product</th>
                        <th class="p-3 text-center font-medium coffee-text-primary">Qty</th>
                        <th class="p-3 text-center font-medium coffee-text-primary">Price</th>
                        <th class="p-3 text-center font-medium coffee-text-primary">Size</th>
                    </tr>
                </thead>
                <tbody class="divide-y coffee-border">
                    @foreach($order->items as $item)
                        <tr class="coffee-table-row">
                            <td class="p-3 coffee-text-primary">{{ $item->product->name }}</td>
                            <td class="p-3 text-center coffee-text-primary">{{ $item->quantity }}</td>
                            <td class="p-3 text-center coffee-text-primary">₱{{ number_format($item->price, 2) }}</td>
                            <td class="p-3 text-center coffee-text-primary">{{ ucfirst($item->size) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>