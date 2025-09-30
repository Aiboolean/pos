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
    </style>
</head>
<body class="bg-[#E6DDC6]">

    <!-- Navbar -->
    <nav class="bg-[#f1eadc] text-black p-4 flex items-center justify-between shadow-md border-b-2 border-black">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('storage/images/cupstreet_logo.jpg') }}" alt="Logo" class="h-8">
            <a href="/products" class="text-lg font-bold">Cups Street</a>
        </div>

        <!-- User Dropdown -->
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
                              class="ml-3">Collapse</span>
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
</body>
</html>