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
        /* Smooth transitions for sidebar and content */
        .sidebar {
            transition: width 0.3s ease-in-out;
        }
        .sidebar-content {
            white-space: nowrap;
            overflow: hidden;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#E6DDC6] flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-[#f1eadc] text-black p-4 flex items-center justify-between shadow-md border-b-2 border-black">
        <div class="flex items-center space-x-2">
        <img src="{{ asset('storage/images/cupstreetlogo.png') }}" alt="Logo" class="h-8">
            <a href="/products" class="text-lg font-bold">CupsStreet</a>
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="p-2 rounded-full bg-gray-700 text-white focus:outline-none">
                <i data-lucide="settings"></i>
            </button>

            <div x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-md z-50">
                <a href="{{ route('admin.credentials') }}" class="flex items-center px-4 py-2 text-black hover:bg-gray-200 transition">
                    <i data-lucide="key-round" class="mr-2"></i> Update Credentials
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-black hover:bg-gray-200 transition">
                        <i data-lucide="log-out" class="mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Content -->
    <div class="flex flex-col-reverse sm:flex-row flex-1">
    <!-- Sidebar -->
    <div x-data="{ open: window.innerWidth > 640, manageOpen: false }" class="transition-all duration-300"
        :class="open ? 'sm:w-64' : 'w-16'">
        <div class="bg-[#F1EADC] text-black min-h-screen transition-all duration-300 shadow-lg flex flex-col px-3 py-4">
            
            <!-- Sidebar Toggle Button -->
            <button @click="open = !open"
                class="flex items-center p-3 rounded transition-all duration-200 mb-4 bg-gray-700 text-white w-full">
                <i data-lucide="menu" class="w-6 h-6"></i>
                <span x-show="open" x-transition.opacity class="ml-3">Collapse</span>
            </button>

            <!-- Sidebar Menu -->
            <ul class="space-y-2 flex-grow">
                @if(Session::get('user_role') === 'Admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                        class="flex items-center p-3 rounded transition-all duration-200 
                        {{ request()->routeIs('admin.dashboard') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                        <span x-show="open" x-transition.opacity class="ml-3">Admin Dashboard</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="/products" class="flex items-center p-3 rounded transition-all duration-200 
                        {{ request()->is('products') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <span x-show="open" x-transition.opacity class="ml-3">POS</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('user.orders') }}" class="flex items-center p-3 rounded transition-all duration-200 
                        {{ request()->routeIs('user.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                        <i data-lucide="book" class="w-6 h-6"></i>
                        <span x-show="open" x-transition.opacity class="ml-3">Transactions</span>
                    </a>
                </li>

                @if(Session::get('user_role') === 'Admin')
                <li class="relative">
                    <!-- Always keep Manage button visible -->
                    <button @click="manageOpen = !manageOpen" class="flex items-center w-full p-3 rounded transition-all duration-200 
                            {{ request()->routeIs('categories.index') || request()->routeIs('admin.products') || request()->routeIs('admin.employees') || request()->routeIs('admin.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                        <i data-lucide="settings" class="w-6 h-6"></i>
                        <span x-show="open" x-transition.opacity class="ml-3">Manage</span>

                        <!-- Dropdown Arrow (Always Visible) -->
                        <i data-lucide="chevron-down" class="w-6 h-6 ml-auto transition-transform duration-200"
                            :class="manageOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Dropdown List (Hidden when sidebar is collapsed) -->
                    <ul x-show="manageOpen" class="bg-gray-200 mt-1 rounded-lg shadow-md overflow-hidden space-y-2 p-2">
                            <li>
                                <a href="{{ route('categories.index') }}" class="flex items-center p-2 rounded 
                                    {{ request()->routeIs('categories.index') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="grid" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open" x-cloak>Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products') }}" class="flex items-center p-2 rounded 
                                    {{ request()->routeIs('admin.products') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="package" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open" x-cloak>Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.employees') }}" class="flex items-center p-2 rounded 
                                    {{ request()->routeIs('admin.employees') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="users" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open" x-cloak>Employees</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders') }}" class="flex items-center p-2 rounded 
                                    {{ request()->routeIs('admin.orders') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="dollar-sign" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open" x-cloak>Sales</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                
                @endif
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="p-6 flex-1 transition-all duration-300">
        @yield('content')
    </main>
</div>


    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 text-center mt-auto w-full">
        <div class="container mx-auto px-4">
            <p class="text-sm md:text-base">&copy; {{ date('Y') }} Coffee Shop POS</p>
        </div>
    </footer>


    <script>
        lucide.createIcons();
    </script>
</body>
</html>