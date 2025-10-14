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

    .coffee-btn-late {
        background-color: #8b5d3c;
        color: white;
        transition: all 0.2s ease;
    }

    .coffee-btn-late:hover {
        background-color: #734a2e;
    }
    
    .coffee-btn-pdf {
        background-color: #8b5d3c;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-pdf:hover {
        background-color: #734a2e;
    }
    
    .coffee-btn-filter {
        background-color: #8b5d3c;
        color: white;
    }
    
    .coffee-empty-state {
        color: #a67c52;
    }
    
    .coffee-pagination {
        background-color: white;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .coffee-input {
        border: 1px solid #d9c7b3;
        background-color: white;
    }
    
    .coffee-input:focus {
        border-color: #a67c52;
        box-shadow: 0 0 0 2px rgba(166, 124, 82, 0.2);
        outline: none;
    }
    
    /* Modal Styles - SIMPLE AND WORKING */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .modal-content-simple {
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header-simple {
        padding: 20px 24px 0 24px;
        border-bottom: none;
    }
    
    .modal-title-simple {
        font-size: 18px;
        font-weight: 600;
        color: #5c4d3c;
        margin-bottom: 20px;
    }
    
    .modal-body-simple {
        padding: 0 24px;
    }
    
    .modal-footer-simple {
        padding: 20px 24px;
        border-top: 1px solid #e0d6c2;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .form-label-simple {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #5c4d3c;
        margin-bottom: 8px;
    }
    
    .form-input-simple {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d9c7b3;
        border-radius: 6px;
        font-size: 14px;
        color: #5c4d3c;
        background-color: white;
        margin-bottom: 16px;
    }
    
    .form-input-simple:focus {
        outline: none;
        border-color: #8b5d3c;
        box-shadow: 0 0 0 2px rgba(139, 93, 60, 0.1);
    }
    
    .btn-update-simple {
        background-color: #8b5d3c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .btn-update-simple:hover {
        background-color: #734a2e;
    }
    
    .btn-cancel-simple {
        background-color: #f5f1ea;
        color: #5c4d3c;
        border: 1px solid #d9c7b3;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .btn-cancel-simple:hover {
        background-color: #e0d6c2;
    }
</style>

<div class="min-h-screen coffee-bg flex justify-center items-center px-4 sm:px-0 py-8">
    <div class="coffee-container p-6 w-full max-w-6xl">
        <div class="coffee-card p-6 relative" style="min-height: 700px; padding-bottom: 60px;">

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#5c4d3c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag mr-2">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <h1 class="text-2xl font-bold coffee-text-primary">Orders Management</h1>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <!-- PDF Export Button -->
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

                    <!-- Add Late Transaction Button - SIMPLE CLICK HANDLER -->
                    <button type="button" onclick="openModal()" class="coffee-btn-late px-4 py-2 rounded-lg font-medium flex items-center justify-center shadow-sm hover:shadow-md transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 6v6h4"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                        Add Late Transaction
                    </button>
                </div>
            </div>

            <!-- Rest of your content remains the same -->
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

            <!-- Orders Table -->
            <div class="overflow-x-auto rounded-lg shadow-sm border border-[#d9c7b3]">
                <table class="w-full">
                    <thead class="bg-[#f0e6d8]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Change</th>
                            <th class="px-6 py-3 text-left text-xs font-medium coffee-text-primary uppercase tracking-wider">Payment Method</th> <!-- ADD THIS LINE -->
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
                            <!-- ========== ADDED PAYMENT METHOD COLUMN START ========== -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($order->payment_method === 'gcash')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        GCash
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Cash
                                    </span>
                                @endif
                            </td>
                            <!-- ========== ADDED PAYMENT METHOD COLUMN END ========== -->
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
                            <td colspan="7" class="px-6 py-8 text-center"><!-- CHANGED FROM 6 TO 7 -->
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

<!-- 🔹 LATE TRANSACTION MODAL -->
<div id="lateTransactionModal" class="modal-backdrop" style="display: none;">
    <div class="modal-content-simple">
        <!-- Modal Header -->
        <div class="modal-header-simple">
            <h3 class="modal-title-simple">Add Late Transaction</h3>
        </div>

        <!-- Modal Body -->
        <div class="modal-body-simple">
            <form id="lateTransactionForm" action="{{ route('orders.storeLate') }}" method="POST">
                @csrf

                <!-- Cashier -->
                <div class="mb-4">
                    <label for="cashier_id" class="form-label-simple">Cashier</label>
                    <select name="cashier_id" id="cashier_id" class="form-input-simple" required>
                        @foreach($cashiers as $cashier)
                            <option value="{{ $cashier->id }}" 
                                {{ $admin && $cashier->id === $admin->id ? 'selected' : '' }}>
                                {{ $cashier->first_name }} {{ $cashier->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date & Time -->
                <div class="mb-4">
                    <label for="transaction_time" class="form-label-simple">Date & Time</label>
                    <input type="text"
                           name="transaction_time"
                           id="transaction_time"
                           class="form-input-simple"
                           placeholder="Select date & time"
                           autocomplete="off"
                           required>
                </div>

                <!-- Category Filter -->
                <div class="mb-4">
                    <label for="categorySelect" class="form-label-simple">Category</label>
                    <select id="categorySelect" class="form-input-simple">
                        <option value="all">-- All Categories --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Products Section -->
                <div class="mb-4">
                    <label class="form-label-simple">Products</label>
                    <div class="d-flex mb-2">
                        <select id="productSelect" class="form-input-simple flex-grow-1">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                @if($product->is_available)
                                    <option value="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->category_id }}"
                                        data-price="{{ $product->price }}"
                                        data-has-sizes="{{ $product->has_multiple_sizes ? 'true' : 'false' }}"
                                        data-small-enabled="{{ $product->small_enabled ? 'true' : 'false' }}"
                                        data-medium-enabled="{{ $product->medium_enabled ? 'true' : 'false' }}"
                                        data-large-enabled="{{ $product->large_enabled ? 'true' : 'false' }}"
                                        data-price-small="{{ $product->price_small }}"
                                        data-price-medium="{{ $product->price_medium }}"
                                        data-price-large="{{ $product->price_large }}">
                                        {{ $product->name }} — 
                                        @if($product->has_multiple_sizes)
                                            Sizes: 
                                            @if($product->small_enabled)S @endif
                                            @if($product->medium_enabled)M @endif
                                            @if($product->large_enabled)L @endif
                                        @else
                                            ₱{{ number_format($product->price,2) }}
                                        @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" id="addProductBtn" class="btn-update-simple ml-2">Add</button>
                    </div>

                    <table id="productTable" class="table-simple w-100">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Payment Method -->
                <div class="mb-4">
                    <label for="payment_method" class="form-label-simple">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-input-simple" required>
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                    </select>
                </div>

                <!-- Total Price -->
                <div class="mb-4">
                    <label for="total_price" class="form-label-simple">Total Price</label>
                    <input type="number" step="0.01" name="total_price" id="total_price" class="form-input-simple" readonly placeholder="0.00">
                </div>

                <!-- Amount Received -->
                <div class="mb-4">
                    <label for="amount_received" class="form-label-simple">Amount Received</label>
                    <input type="number" step="0.01" name="amount_received" id="amount_received" class="form-input-simple" placeholder="0.00">
                </div>

                <!-- Change -->
                <div class="mb-4">
                    <label for="change" class="form-label-simple">Change</label>
                    <input type="number" step="0.01" name="change" id="change" class="form-input-simple" readonly placeholder="0.00">
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer-simple">
            <button type="button" class="btn-cancel-simple" onclick="closeModal()">Cancel</button>
            <button type="submit" form="lateTransactionForm" class="btn-update-simple">Save Transaction</button>
        </div>
    </div>
</div>

<!-- JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('lateTransactionModal');
    const productSelect = document.getElementById("productSelect");
    const addProductBtn = document.getElementById("addProductBtn");
    const productTableBody = document.querySelector("#productTable tbody");
    const totalPriceInput = document.getElementById("total_price");
    const amountReceivedInput = document.getElementById("amount_received");
    const changeInput = document.getElementById("change");

    let selectedProducts = [];

    // Category filter
    categorySelect.addEventListener("change", () => {
        const categoryId = categorySelect.value;
        Array.from(productSelect.options).forEach(opt => {
            if(opt.value === "") return; // keep default
            if(categoryId === "all" || opt.dataset.category == categoryId) {
                opt.style.display = "";
            } else {
                opt.style.display = "none";
            }
        });
        productSelect.value = "";
    });


    function updateTable() {
        productTableBody.innerHTML = "";
        let total = 0;

        selectedProducts.forEach((p, index) => {
            p.subtotal = p.price * p.quantity;
            total += p.subtotal;

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${p.name}</td>
                <td>
                    ${p.hasSizes ? `
                        <select class="sizeSelect">
                            ${p.enabledSizes.map(s => `<option value="${s}" ${s===p.size?'selected':''}>${s}</option>`).join('')}
                        </select>` : '-'}
                </td>
                <td>₱${p.price.toFixed(2)}</td>
                <td><input type="number" class="quantityInput" min="1" max="100" value="${p.quantity}"></td>
                <td>₱${p.subtotal.toFixed(2)}</td>
                <td><button type="button" class="btn-cancel-simple removeBtn">Remove</button></td>
            `;
            productTableBody.appendChild(tr);

            if (p.hasSizes) {
                tr.querySelector(".sizeSelect").addEventListener("change", e => {
                    const newSize = e.target.value;
                    p.size = newSize;
                    p.price = p.sizePrices[newSize];
                    updateTable();
                });
            }

            tr.querySelector(".quantityInput").addEventListener("input", e => {
                let val = parseInt(e.target.value);
                if (val < 1) val = 1;
                if (val > 100) val = 100;
                e.target.value = val;
                p.quantity = val;
                updateTable();
            });

            tr.querySelector(".removeBtn").addEventListener("click", () => {
                selectedProducts.splice(index, 1);
                updateTable();
            });
        });

        totalPriceInput.value = total.toFixed(2);
        calculateChange();
    }

    function calculateChange() {
        let total = parseFloat(totalPriceInput.value) || 0;
        let received = parseFloat(amountReceivedInput.value) || 0;
        let change = received - total;
        changeInput.value = change >= 0 ? change.toFixed(2) : 0;
    }

    amountReceivedInput.addEventListener("input", calculateChange);

    addProductBtn.addEventListener("click", () => {
        const option = productSelect.selectedOptions[0];
        if (!option || !option.value) return;

        const id = option.value;
        const name = option.dataset.name;
        const hasSizes = option.dataset.hasSizes === "true";
        const enabledSizes = [];
        const sizePrices = {};

        if (hasSizes) {
            if (option.dataset.smallEnabled === "true") { enabledSizes.push("Small"); sizePrices["Small"] = parseFloat(option.dataset.priceSmall); }
            if (option.dataset.mediumEnabled === "true") { enabledSizes.push("Medium"); sizePrices["Medium"] = parseFloat(option.dataset.priceMedium); }
            if (option.dataset.largeEnabled === "true") { enabledSizes.push("Large"); sizePrices["Large"] = parseFloat(option.dataset.priceLarge); }
        }

        const price = hasSizes ? sizePrices[enabledSizes[0]] : parseFloat(option.dataset.price);

        if (!selectedProducts.some(p => p.id == id && p.size === (hasSizes ? enabledSizes[0] : null))) {
            selectedProducts.push({
                id, name, hasSizes, enabledSizes, sizePrices,
                size: hasSizes ? enabledSizes[0] : null,
                price, quantity: 1, subtotal: price
            });
        }

        updateTable();
    });

    // Flatpickr for date/time
    flatpickr("#transaction_time", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y h:i K",
        time_24hr: false,
        minuteIncrement: 1,
        maxDate: "today",
        allowInput: true
    });

    // Submit: create hidden inputs for Laravel
    document.getElementById("lateTransactionForm").addEventListener("submit", function (e) {
        document.querySelectorAll('.product-hidden-input').forEach(el => el.remove());

         // ===================== CHECK IF AMOUNT RECEIVED IS ENOUGH =====================
        const total = parseFloat(totalPriceInput.value) || 0;
        const received = parseFloat(amountReceivedInput.value) || 0;

        // Remove existing error message
        const existingError = document.getElementById("amountError");
        if (existingError) existingError.remove();

        if (received < total) {
            e.preventDefault(); // stop form submission

            // Show inline error below the Amount Received field
            const errorMsg = document.createElement("div");
            errorMsg.id = "amountError";
            errorMsg.style.color = "red";
            errorMsg.style.marginTop = "0.3rem";
            errorMsg.textContent = "Amount received is not enough to cover the total price.";
            amountReceivedInput.parentNode.appendChild(errorMsg);

            amountReceivedInput.focus(); // focus input for correction
            return; // exit the listener
    }

        selectedProducts.forEach((p, idx) => {
            const inputId = document.createElement("input");
            inputId.type = "hidden"; inputId.name = `products[${idx}][id]`; inputId.value = p.id; inputId.classList.add("product-hidden-input");
            const inputSize = document.createElement("input");
            inputSize.type = "hidden"; inputSize.name = `products[${idx}][size]`; inputSize.value = p.size || ''; inputSize.classList.add("product-hidden-input");
            const inputQty = document.createElement("input");
            inputQty.type = "hidden"; inputQty.name = `products[${idx}][quantity]`; inputQty.value = p.quantity; inputQty.classList.add("product-hidden-input");

            this.appendChild(inputId);
            this.appendChild(inputSize);
            this.appendChild(inputQty);
        });
    });

}); // DOMContentLoaded

// Modal open/close
function openModal() { document.getElementById('lateTransactionModal').style.display = 'flex'; }
function closeModal() { document.getElementById('lateTransactionModal').style.display = 'none'; }
document.getElementById('lateTransactionModal').addEventListener('click', e => { if (e.target===e.currentTarget) closeModal(); });
document.addEventListener('keydown', e => { if (e.key==='Escape') closeModal(); });
</script>

<style>
.modal-backdrop {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4); z-index:1000;
}
.modal-content-simple { background:#fff; border-radius:0.75rem; padding:1.5rem; max-width:800px; width:95%; }
.table-simple { width:100%; border-collapse: collapse; margin-top:0.5rem; }
.table-simple th, .table-simple td { border:1px solid #ddd; padding:0.5rem; text-align:center; }
.table-simple input { width:60px; }
.btn-update-simple { padding:0.3rem 0.8rem; margin-left:0.3rem; cursor:pointer; }
.btn-cancel-simple { padding:0.3rem 0.8rem; cursor:pointer; }
</style>



@push('styles')
    <!-- Lucide icons -->
    <link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/lucide.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <!-- Lucide icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script> lucide.createIcons(); </script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@endsection