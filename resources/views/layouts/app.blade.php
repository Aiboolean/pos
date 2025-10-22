<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .sidebar-transition {
            transition: width 0.3s ease;
        }
        .dropdown-transition {
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .rotate-chevron {
            transition: transform 0.3s ease;
        }
        .menu-item-transition {
            transition: all 0.3s ease;
        }
        .text-transition {
            transition: all 0.3s ease;
        }
        [x-cloak] { display: none !important; }
        .main-content-wrapper {
            flex: 1;
            display: flex;
            min-height: 0;
            overflow: hidden;
        }
        .sidebar-and-content {
            flex: 1;
            display: flex;
            overflow: hidden;
        }
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }
        footer {
            flex-shrink: 0;
            background-color: #1f2937;
            color: white;
            padding: 1rem;
            text-align: bottom;
        }
        .dropdown-container {
            position: relative;
        }
        .dropdown-menu {
            position: absolute;
            left: 100%;
            top: 0;
            min-width: 200px;
            z-index: 50;
            margin-left: 4px;
        }
                /* notif */
        .notification-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        .notification-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .notification-scroll::-webkit-scrollbar-track {
            background: #f7fafc;
            border-radius: 3px;
        }

        .notification-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .notification-scroll::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>
</head>
<body class="bg-[#E6DDC6]">

        <!-- Navbar -->
    <nav class="bg-[#f1eadc] text-black p-4 flex items-center justify-between shadow-md border-b-2 border-black">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('storage/images/cupstreet_logo.jpg') }}" alt="Logo" class="h-8">
            <a href="/products" class="text-lg font-bold">Cups Street</a>
        </div>

        <!-- Notification and User Dropdown -->
        <div class="flex items-center space-x-4">
            <!-- Low Stock Notification -->
            @php
                // Direct query in the blade file - much simpler!
                $lowStockIngredients = App\Models\Ingredient::whereRaw('stock <= alert_threshold')
                    ->where('alert_threshold', '>', 0)
                    ->get();
                $lowStockCount = $lowStockIngredients->count();
            @endphp
            
            <div x-data="{ notificationOpen: false }" class="relative">
                <!-- Notification Bell -->
                <button @click="notificationOpen = !notificationOpen" 
                        class="p-2 rounded-full bg-gray-700 text-white focus:outline-none hover:bg-gray-600 transition-all duration-300 relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    
                    <!-- Notification Badge -->
                    @if($lowStockCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center animate-pulse">
                        {{ $lowStockCount }}
                    </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
<div x-show="notificationOpen" @click.away="notificationOpen = false" 
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="absolute right-0 mt-2 w-96 bg-white border rounded-lg shadow-lg z-50 max-h-96 overflow-hidden">
    
    <!-- Notification Header -->
    <div class="bg-gray-50 px-4 py-3 border-b">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Low Stock Alerts</h3>
            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                {{ $lowStockCount }} alert(s)
            </span>
        </div>
    </div>

    <!-- Notification Content -->
    <div class="max-h-64 overflow-y-auto notification-scroll">
        @if($lowStockCount > 0)
            @foreach($lowStockIngredients as $ingredient)
            @php
                // Get products that use this ingredient and calculate their availability
                $relatedProducts = $ingredient->products->map(function($product) {
                    $availability = $product->calculateAvailability();
                    return [
                        'product' => $product,
                        'availability' => $availability
                    ];
                })->filter(function($item) {
                    // Only show products that can still be made (at least 1 in some size)
                    foreach ($item['availability'] as $size => $count) {
                        if ($count > 0) return true;
                    }
                    return false;
                });
            @endphp
            
            <div class="border-b border-gray-100 last:border-b-0">
                <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $ingredient->name }}
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                Current stock: 
                                <span class="font-semibold {{ $ingredient->stock == 0 ? 'text-red-600' : 'text-orange-600' }}">
                                    {{ $ingredient->stock }} {{ $ingredient->unit }}
                                </span>
                            </p>
                            <p class="text-xs text-gray-500">
                                Alert threshold: {{ $ingredient->alert_threshold }} {{ $ingredient->unit }}
                            </p>
                            
                            <!-- Products that can be made -->
                            @if($relatedProducts->count() > 0)
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <p class="text-xs font-medium text-gray-700 mb-1">Can make:</p>
                                <div class="space-y-1">
                                    @foreach($relatedProducts as $item)
                                    <div class="text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium truncate flex-1 mr-2">
                                                {{ $item['product']->name }}
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($item['availability'] as $size => $count)
                                                @if($count > 0)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-green-100 text-green-800">
                                                    {{ ucfirst($size) }}: {{ $count }}
                                                </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <p class="text-xs text-red-500 italic">
                                    Cannot make any products with current stock
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <!-- No alerts state -->
            <div class="px-4 py-8 text-center">
                <div class="flex justify-center mb-3">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-500"></i>
                </div>
                <p class="text-gray-600 text-sm">All ingredients are well stocked!</p>
                <p class="text-gray-500 text-xs mt-1">No low stock alerts</p>
            </div>
        @endif
    </div>

    <!-- Notification Footer -->
    @if($lowStockCount > 0)
    <div class="bg-gray-50 px-4 py-3 border-t">
        <a href="{{ route('ingredients.index') }}" 
        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200">
            Manage Ingredients
        </a>
    </div>
    @endif
</div>
            </div>

            <!-- User Dropdown (your existing code) -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="p-2 rounded-full bg-gray-700 text-white focus:outline-none hover:bg-gray-600 transition-all duration-300">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                </button>

                <div x-show="open" @click.away="open = false" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-md z-50">
                    <a href="{{ route('admin.credentials') }}" 
                    class="flex items-center px-4 py-2 text-black hover:bg-gray-200 transition-all duration-300">
                        <i data-lucide="key-round" class="mr-2 w-5 h-5"></i> 
                        <span>Update Credentials</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-4 py-2 text-black hover:bg-gray-200 transition-all duration-300">
                            <i data-lucide="log-out" class="mr-2 w-5 h-5"></i> 
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <!-- Sidebar and Content Container -->
        <div class="sidebar-and-content">
            <!-- Sidebar -->
            <div x-data="{ open: window.innerWidth > 640, manageOpen: false }" class="sidebar-transition flex-shrink-0"
                :class="open ? 'w-64' : 'w-20'">
                <div class="bg-[#F1EADC] text-black h-full shadow-lg flex flex-col px-3 py-4">
                    
                    <!-- Sidebar Toggle Button -->
                    <button @click="open = !open"
                        class="flex items-center p-3 rounded mb-4 bg-gray-700 text-white w-full hover:bg-gray-600 transition-all duration-300">
                        <i data-lucide="menu" class="w-6 h-6 flex-shrink-0"></i>
                        <span x-show="open" 
                              x-transition:enter="transition ease-out duration-300"
                              x-transition:enter-start="opacity-0 transform translate-x-4"
                              x-transition:enter-end="opacity-100 transform translate-x-0"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-start="opacity-100 transform translate-x-0"
                              x-transition:leave-end="opacity-0 transform -translate-x-4" 
                              class="ml-3"></span>
                    </button>

                    <!-- Sidebar Menu -->
                    <ul class="space-y-2 flex-grow">
                        @if(Session::get('user_role') === 'Admin')
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center p-3 rounded menu-item-transition
                               {{ request()->routeIs('admin.dashboard') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                                <i data-lucide="layout-dashboard" class="w-6 h-6 flex-shrink-0"></i>
                                <span x-show="open" 
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 transform translate-x-4"
                                      x-transition:enter-end="opacity-100 transform translate-x-0"
                                      x-transition:leave="transition ease-in duration-200"
                                      x-transition:leave-start="opacity-100 transform translate-x-0"
                                      x-transition:leave-end="opacity-0 transform -translate-x-4" 
                                      class="ml-3 text-transition">Admin Dashboard</span>
                            </a>
                        </li>
                        @endif

                        <li>
                            <a href="/products" 
                               class="flex items-center p-3 rounded menu-item-transition
                               {{ request()->is('products') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                                <i data-lucide="shopping-cart" class="w-6 h-6 flex-shrink-0"></i>
                                <span x-show="open" 
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 transform translate-x-4"
                                      x-transition:enter-end="opacity-100 transform translate-x-0"
                                      x-transition:leave="transition ease-in duration-200"
                                      x-transition:leave-start="opacity-100 transform translate-x-0"
                                      x-transition:leave-end="opacity-0 transform -translate-x-4" 
                                      class="ml-3 text-transition">POS</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('user.orders') }}" 
                               class="flex items-center p-3 rounded menu-item-transition
                               {{ request()->routeIs('user.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                                <i data-lucide="book" class="w-6 h-6 flex-shrink-0"></i>
                                <span x-show="open" 
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 transform translate-x-4"
                                      x-transition:enter-end="opacity-100 transform translate-x-0"
                                      x-transition:leave="transition ease-in duration-200"
                                      x-transition:leave-start="opacity-100 transform translate-x-0"
                                      x-transition:leave-end="opacity-0 transform -translate-x-4" 
                                      class="ml-3 text-transition">Transactions</span>
                            </a>
                        </li>

                        @if(Session::get('user_role') === 'Admin')
                        <li class="dropdown-container" x-data="{ manageOpen: false }">
                            <!-- Manage button -->
                            <button @click="manageOpen = !manageOpen" 
                                    class="flex items-center w-full p-3 rounded menu-item-transition
                                    {{ request()->routeIs('categories.index') || request()->routeIs('admin.products') || request()->routeIs('admin.employees') || request()->routeIs('admin.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                                <i data-lucide="settings" class="w-6 h-6 flex-shrink-0"></i>
                                <span x-show="open" 
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 transform translate-x-4"
                                      x-transition:enter-end="opacity-100 transform translate-x-0"
                                      x-transition:leave="transition ease-in duration-200"
                                      x-transition:leave-start="opacity-100 transform translate-x-0"
                                      x-transition:leave-end="opacity-0 transform -translate-x-4" 
                                      class="ml-3 text-transition">Manage</span>
                                <i data-lucide="chevron-down" 
                                   class="w-6 h-6 ml-auto rotate-chevron flex-shrink-0"
                                   :class="manageOpen ? 'rotate-180' : ''"></i>
                            </button>
                            
                            <!-- Dropdown List - Shows to the right when sidebar is collapsed -->
                            <div x-show="manageOpen" @click.away="manageOpen = false"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-x-0"
                                x-transition:leave-end="opacity-0 transform translate-x-4"
                                :class="!open ? 'dropdown-menu' : ''"
                                class="bg-gray-200 rounded-lg shadow-md space-y-2 p-2 w-full">
                                <ul>
                                    <li>
                                        <a href="{{ route('categories.index') }}" 
                                           class="flex items-center p-2 rounded hover:bg-gray-300 transition-all duration-300
                                           {{ request()->routeIs('categories.index') ? 'bg-green-500 text-white' : '' }}">
                                            <i data-lucide="grid" class="w-6 h-6 mr-2 flex-shrink-0"></i>
                                            <span class="text-transition">Categories</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.products') }}" 
                                           class="flex items-center p-2 rounded hover:bg-gray-300 transition-all duration-300
                                           {{ request()->routeIs('admin.products') ? 'bg-green-500 text-white' : '' }}">
                                            <i data-lucide="package" class="w-6 h-6 mr-2 flex-shrink-0"></i>
                                            <span class="text-transition">Products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('ingredients.index') }}" 
                                        class="flex items-center p-2 rounded hover:bg-gray-300 transition-all duration-300
                                        {{ request()->routeIs('ingredients.*') ? 'bg-green-500 text-white' : '' }}">
                                            <i data-lucide="warehouse" class="w-6 h-6 mr-2 flex-shrink-0"></i>
                                            <span class="text-transition">Ingredients</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.employees') }}" 
                                           class="flex items-center p-2 rounded hover:bg-gray-300 transition-all duration-300
                                           {{ request()->routeIs('admin.employees') ? 'bg-green-500 text-white' : '' }}">
                                            <i data-lucide="users" class="w-6 h-6 mr-2 flex-shrink-0"></i>
                                            <span class="text-transition">Employees</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.orders') }}" 
                                           class="flex items-center p-2 rounded hover:bg-gray-300 transition-all duration-300
                                           {{ request()->routeIs('admin.orders') ? 'bg-green-500 text-white' : '' }}">
                                            <i data-lucide="dollar-sign" class="w-6 h-6 mr-2 flex-shrink-0"></i>
                                            <span class="text-transition">Sales</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="main-content transition-all duration-300">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer - Fixed at bottom -->
    <footer class="bg-gray-800 text-white p-4 text-center w-full">
        <div class="container mx-auto px-4">
            <p class="text-sm md:text-base transition-all duration-300">&copy; {{ date('Y') }} Coffee Shop POS</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        
        // Close manage dropdown when clicking outside
        document.addEventListener('alpine:init', () => {
            Alpine.debounce = (func, wait) => {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    const later = function() {
                        timeout = null;
                        func.apply(context, args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            };
        });
    </script>
    @stack('scripts')
</body>
</html>