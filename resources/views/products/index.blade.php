@extends('layouts.app')

@section('content')
<div class="flex h-screen">

    <!-- Sidebar -->
    <div class="w-1/6 bg-gray-100 p-4">
        <h2 class="text-xl font-bold mb-4">Menu</h2>
        <ul class="space-y-2">
            <li><a href="#" class="block p-2 bg-gray-200 rounded hover:bg-gray-300">Home</a></li>
            <li><a href="#" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Orders
            </a></li>
            <li><a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Add New Product
            </a></li>
            @if(!Session::has('admin_logged_in'))
                <a href="{{ route('login') }}" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Login</a>
            @else
                @if(Session::get('user_role') === 'Admin')
                    <a href="{{ route('admin.dashboard') }}" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">Admin Dashboard</a>
                @endif
            @endif
            <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Logout
            </button>
        </form>



        </ul>
    </div>

    <div class="w-2/3 p-6">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden p-4">
    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded">
    
    <h2 class="text-lg font-semibold mt-2">{{ $product->name }}</h2>
    <p class="text-gray-500">Category: {{ $product->category }}</p>
    
    <!-- Availability Status -->
    <p class="text-sm font-semibold {{ $product->is_available ? 'text-green-500' : 'text-red-500' }}">
        {{ $product->is_available ? 'Available' : 'Not Available' }}
    </p>

    <!-- Size Selection -->
    <div class="mt-2">
        <label for="size-{{ $product->id }}" class="block text-sm font-medium text-gray-700">Size</label>
        <select id="size-{{ $product->id }}" class="w-full p-2 border rounded-lg">
            @php
                $prices = $product->prices ? json_decode($product->prices, true) : [];
            @endphp
            @if(isset($prices['small']) && $prices['small'])
                <option value="small" data-price="{{ $prices['small'] }}">Small - ${{ $prices['small'] }}</option>
            @endif
            @if(isset($prices['medium']) && $prices['medium'])
                <option value="medium" data-price="{{ $prices['medium'] }}">Medium - ${{ $prices['medium'] }}</option>
            @endif
            @if(isset($prices['large']) && $prices['large'])
                <option value="large" data-price="{{ $prices['large'] }}">Large - ${{ $prices['large'] }}</option>
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

    <!-- Add to Order Button -->
    <button class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 w-full add-to-order"
            data-id="{{ $product->id }}" data-name="{{ $product->name }}">
        Add to Order
    </button>
</div>
        @endforeach
    </div>
</div>



    <!-- Order Summary -->
    <!-- Order Summary -->
<div class="w-1/6 bg-gray-100 p-4">
    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
    <div id="cart-items" class="space-y-2"></div>
    <hr class="my-4">
    <p class="text-lg font-semibold">Total: $<span id="total-price">0.00</span></p>
    <button id="checkout" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 w-full">
        Proceed to Payment
    </button>
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
                    <span>${item.name} (${item.size}, ${item.quantity})</span>
                    <span>$${(item.price * item.quantity).toFixed(2)}</span>
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
            const size = document.getElementById(`size-${productId}`).value;
            const price = parseFloat(document.querySelector(`#size-${productId} option:checked`).dataset.price);
            const quantity = parseInt(document.getElementById(`quantity-${productId}`).value);

            addToCart(productId, productName, size, price, quantity);
        });
    });

    document.getElementById("checkout").addEventListener("click", function () {
        if (cart.length === 0) {
            alert("Your cart is empty!");
            return;
        }

        fetch("{{ route('orders.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ 
                items: cart,
                total_price: totalPriceElement.innerText
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert("Order placed successfully!");
            cart = [];
            updateCartUI();
        })
        .catch(error => console.error("Error:", error));
    });
});
</script>

@endsection
