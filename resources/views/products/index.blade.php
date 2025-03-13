@extends('layouts.app')

@section('content')
    <div class="flex h-screen">
    <!-- Order Summary (Now on the Left Side) -->
    <div class="w-2/6 bg-gray-100 p-4">
        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
        <div id="cart-items" class="space-y-2"></div>
        <hr class="my-4">
        <p class="text-lg font-semibold">Total: ₱<span id="total-price">0.00</span></p>
        <button id="checkout" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 w-full">
            Proceed to Payment
        </button>
    </div>

    <!-- Main Content (Now on the Right Side) -->
    <div class="w-4/6 p-6">
        <!-- Category Filter -->
        <div class="mb-4">
            <label for="categoryFilter" class="block text-sm font-medium text-gray-700">Filter by Category</label>
            <select id="categoryFilter" class="p-2 border rounded-lg">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
                <option value="unavailable">Unavailable Products</option>
            </select>
        </div>

        <!-- Scrollable Product Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 overflow-y-auto" style="max-height: 100vh;" id="productGrid">
            @foreach($products as $product)
                @if($product->is_available)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden p-4 product-item" data-category="{{ $product->category_id }}" data-available="true">
                        <!-- Product Details -->
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded">
                        <h2 class="text-lg font-semibold mt-2">{{ $product->name }}</h2>
                        <p class="text-gray-500">Category: {{ $product->category->name }}</p>
                        <p class="text-sm font-semibold text-green-500">Available</p>

                        <!-- Size Selection -->
                        <div class="mt-2">
                            <label for="size-{{ $product->id }}" class="block text-sm font-medium text-gray-700">Size</label>
                            <select id="size-{{ $product->id }}" class="w-full p-2 border rounded-lg">
                                @if($product->has_multiple_sizes)
                                    @if($product->price_small && $product->small_enabled)
                                        <option value="small" data-price="{{ $product->price_small }}">Small - ₱{{ $product->price_small }}</option>
                                    @endif
                                    @if($product->price_medium && $product->medium_enabled)
                                        <option value="medium" data-price="{{ $product->price_medium }}">Medium - ₱{{ $product->price_medium }}</option>
                                    @endif
                                    @if($product->price_large && $product->large_enabled)
                                        <option value="large" data-price="{{ $product->price_large }}">Large - ₱{{ $product->price_large }}</option>
                                    @endif
                                @else
                                    <option value="single" data-price="{{ $product->price }}">Single - ₱{{ $product->price }}</option>
                                @endif
                            </select>
                        </div>

                        <!-- Quantity Adjustment -->
                        <div class="mt-2">
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <div class="flex items-center">
                                <button class="px-2 py-1 bg-gray-300 rounded-l-lg" onclick="adjustQuantity('{{ $product->id }}', -1)">-</button>
                                <input type="number" id="quantity-{{ $product->id }}" min="1" value="1" class="w-16 text-center border-t border-b">
                                <button class="px-2 py-1 bg-gray-300 rounded-r-lg" onclick="adjustQuantity('{{ $product->id }}', 1)">+</button>
                            </div>
                        </div>

                        <!-- Toggle Availability Button -->
                        <form action="{{ route('products.toggleAvailability', $product->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 rounded-lg w-full transition-all text-white bg-red-500 hover:bg-red-600">
                                Mark as Not Available
                            </button>
                        </form>

                        <!-- Add to Order Button -->
                        <button class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 w-full add-to-order"
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->name }}"
                                data-has-multiple-sizes="{{ $product->has_multiple_sizes }}"
                                data-price-small="{{ $product->price_small }}"
                                data-price-medium="{{ $product->price_medium }}"
                                data-price-large="{{ $product->price_large }}"
                                data-price="{{ $product->price }}">
                            Add to Order
                        </button>
                    </div>
                @endif
            @endforeach

            <!-- Hidden Unavailable Products -->
            @foreach($products as $product)
                @if(!$product->is_available)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden p-4 product-item hidden" data-category="{{ $product->category_id }}" data-available="false">
                        <!-- Product Details -->
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded">
                        <h2 class="text-lg font-semibold mt-2">{{ $product->name }}</h2>
                        <p class="text-gray-500">Category: {{ $product->category->name }}</p>
                        <p class="text-sm font-semibold text-red-500">Not Available</p>

                        <!-- Toggle Availability Button -->
                        <form action="{{ route('products.toggleAvailability', $product->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 rounded-lg w-full transition-all text-white bg-green-500 hover:bg-green-600">
                                Mark as Available
                            </button>
                        </form>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Confirm Order</h2>
        <p class="text-lg">Total: ₱<span id="modal-total-price">0.00</span></p>
        
        <div class="mt-4">
            <label for="amountReceived" class="block text-sm font-medium text-gray-700">Amount Received</label>
            <input type="number" id="amountReceived" class="w-full p-2 border rounded-lg" placeholder="Enter amount received">
        </div>

        <div class="mt-4">
            <p class="text-lg">Change: ₱<span id="changeAmount">0.00</span></p>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <button id="cancelOrder" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
            <button id="confirmOrder" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Confirm</button>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Order Receipt</h2>
        <p class="text-lg">Order ID: <span id="receipt-order-id"></span></p>
        <p class="text-lg">Total: ₱<span id="receipt-total-price"></span></p>
        <p class="text-lg">VAT (12%): ₱<span id="receipt-vat"></span></p> <!-- Added VAT Display -->
        <p class="text-lg">Amount Received: ₱<span id="receipt-amount-received"></span></p>
        <p class="text-lg">Change: ₱<span id="receipt-change"></span></p>
        <hr class="my-4">
        <h3 class="text-lg font-bold">Items:</h3>
        <ul id="receipt-items"></ul>
        <div class="mt-6 flex justify-end space-x-4">
            <button onclick="printReceipt()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Print Receipt</button>
            <button onclick="closeReceiptModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Close</button>
        </div>
    </div>
</div>

<script>
    function adjustQuantity(productId, change) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        let quantity = parseInt(quantityInput.value);
        quantity += change;
        if (quantity < 1) quantity = 1;
        quantityInput.value = quantity;
    }

    document.addEventListener("DOMContentLoaded", function () {
        let cart = [];
        const cartItemsContainer = document.getElementById("cart-items");
        const totalPriceElement = document.getElementById("total-price");

        function updateCartUI() {
            cartItemsContainer.innerHTML = "";
            let total = 0;

            cart.forEach((item, index) => {
                total += item.price * item.quantity;

                let cartItem = document.createElement("div");
                cartItem.classList.add("p-2", "bg-white", "rounded", "shadow");

                cartItem.innerHTML = `
                    <div class="flex justify-between items-center">
                        <span>${item.name} (${item.size || 'Single'}, ${item.quantity})</span>
                        <span>₱${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                    <button class="text-red-500 text-sm mt-1" onclick="removeFromCart(${index})">Remove</button>
                `;

                cartItemsContainer.appendChild(cartItem);
            });

            totalPriceElement.innerText = total.toFixed(2);
        }

        function addToCart(productId, name, size, price, quantity) {
            let existingProduct = cart.find(item => item.id === productId && item.size === size);
            if (existingProduct) {
                existingProduct.quantity += quantity;
            } else {
                cart.push({ id: productId, name, size, price, quantity });
            }
            updateCartUI();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        window.addToCart = addToCart;
        window.removeFromCart = removeFromCart;

        document.querySelectorAll(".add-to-order").forEach(button => {
            button.addEventListener("click", function () {
                const productId = this.dataset.id;
                const productName = this.dataset.name;
                const hasMultipleSizes = this.dataset.hasMultipleSizes === '1';
                const sizeElement = document.getElementById(`size-${productId}`);
                const quantity = parseInt(document.getElementById(`quantity-${productId}`).value);

                let size = 'single';
                let price = 0;

                if (hasMultipleSizes && sizeElement) {
                    size = sizeElement.value;
                    price = parseFloat(document.querySelector(`#size-${productId} option:checked`).dataset.price);
                } else {
                    size = 'single';
                    price = parseFloat(this.dataset.price);
                }

                addToCart(productId, productName, size, price, quantity);
            });
        });

        document.getElementById("checkout").addEventListener("click", function () {
            if (cart.length === 0) {
                alert("Your cart is empty!");
                return;
            }

            const modal = document.getElementById("confirmationModal");
            const modalTotalPrice = document.getElementById("modal-total-price");
            const changeAmount = document.getElementById("changeAmount");
            const amountReceivedInput = document.getElementById("amountReceived");

            modalTotalPrice.innerText = totalPriceElement.innerText;
            amountReceivedInput.value = "";
            changeAmount.innerText = "0.00";
            modal.classList.remove("hidden");

            amountReceivedInput.addEventListener("input", function () {
                const amountReceived = parseFloat(amountReceivedInput.value);
                const totalPrice = parseFloat(totalPriceElement.innerText);

                if (!isNaN(amountReceived) && amountReceived >= totalPrice) {
                    const change = amountReceived - totalPrice;
                    changeAmount.innerText = change.toFixed(2);
                } else {
                    changeAmount.innerText = "0.00";
                }
            });

            document.getElementById("cancelOrder").addEventListener("click", function () {
                modal.classList.add("hidden");
            });

            document.getElementById("confirmOrder").addEventListener("click", function () {
                const amountReceived = parseFloat(amountReceivedInput.value);
                const totalPrice = parseFloat(totalPriceElement.innerText);

                if (isNaN(amountReceived) || amountReceived < totalPrice) {
                    alert("Please enter a valid amount that covers the total price.");
                    return;
                }

                modal.classList.add("hidden");

                fetch("{{ route('orders.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({ 
                        items: cart,
                        total_price: totalPriceElement.innerText,
                        amount_received: amountReceived,
                        change: (amountReceived - totalPrice).toFixed(2)
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    cart = [];
                    updateCartUI();
                    openReceiptModal(data.order, data.items);
                })
                .catch(error => console.error("Error:", error));
            });
        });

        function openReceiptModal(order, items) {
            const receiptModal = document.getElementById("receiptModal");
            const receiptOrderId = document.getElementById("receipt-order-id");
            const receiptTotalPrice = document.getElementById("receipt-total-price");
            const receiptVat = document.getElementById("receipt-vat"); // VAT Element
            const receiptAmountReceived = document.getElementById("receipt-amount-received");
            const receiptChange = document.getElementById("receipt-change");
            const receiptItems = document.getElementById("receipt-items");

            // Calculate VAT (12%)
            const totalPrice = parseFloat(order.total_price);
            const vat = (totalPrice * 0.12).toFixed(2); // 12% VAT

            receiptOrderId.innerText = order.id;
            receiptTotalPrice.innerText = totalPrice.toFixed(2);
            receiptVat.innerText = vat; // Display VAT
            receiptAmountReceived.innerText = order.amount_received || "0.00";
            receiptChange.innerText = order.change || "0.00";
            receiptItems.innerHTML = items.map(item => `
                <li>${item.name} (${item.size || 'Single'}, ${item.quantity}) - ₱${item.price * item.quantity}</li>
            `).join("");

            receiptModal.classList.remove("hidden");
        }

        function printReceipt() {
            const receiptContent = document.getElementById("receiptModal").innerHTML;
            const printWindow = window.open("", "_blank");
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Order Receipt</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            h2, h3 { color: #333; }
                            hr { border: 1px solid #ddd; }
                            button { display: none; }
                        </style>
                    </head>
                    <body>
                        ${receiptContent}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

       

        // Category Filter
    document.getElementById('categoryFilter').addEventListener('change', function () {
        const selectedCategory = this.value;
        const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
            const category = item.dataset.category;
            const isAvailable = item.dataset.available === "true";

            if (selectedCategory === "unavailable") {
                // Show only unavailable products
                item.style.display = isAvailable ? 'none' : 'block';
            } else if (selectedCategory === "") {
                // Show all available products
                item.style.display = isAvailable ? 'block' : 'none';
            } else {
                // Show products matching the selected category and availability
                item.style.display = (category === selectedCategory && isAvailable) ? 'block' : 'none';
            }
        });
    });

    });
    function closeReceiptModal() {
            const receiptModal = document.getElementById("receiptModal");
            receiptModal.classList.add("hidden");
        }
</script>
@endsection