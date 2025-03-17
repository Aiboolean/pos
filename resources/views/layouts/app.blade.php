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
</head>
<body class="bg-[#E6DDC6] flex flex-col min-h-screen">
    <div class="flex flex-1">
        <!-- Sidebar -->
        <div x-data="{ open: localStorage.getItem('sidebar') === 'true', manageOpen: false }" 
             class="flex" 
             x-init="$watch('open', value => localStorage.setItem('sidebar', value))">
            
            <div class="bg-[#F1EADC] text-black min-h-screen transition-all duration-300 shadow-lg"
                 :class="open ? 'w-64 p-4' : 'w-16 p-2'">
                
                <!-- Toggle Sidebar Button -->
                <button @click="open = !open" 
                        class="mb-4 p-2 rounded bg-gray-700 text-white w-full flex items-center justify-center hover:bg-gray-600">
                    <i data-lucide="menu" class="w-6 h-6 flex items-center justify-center"></i>
                </button>

                <ul class="mt-4 space-y-2">
                    @if(Session::get('user_role') === 'Admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center p-2 rounded transition-all duration-200 
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                            <i data-lucide="layout-dashboard" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                            <span x-show="open">Admin Dashboard</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="/products" class="flex items-center p-2 rounded transition-all duration-200 
                                  {{ request()->is('products') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                            <i data-lucide="shopping-cart" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                            <span x-show="open">POS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.orders') }}" class="flex items-center p-2 rounded transition-all duration-200 
                                  {{ request()->routeIs('user.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                            <i data-lucide="book" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                            <span x-show="open">Transactions</span>
                        </a>
                    </li>
                    
                    @if(Session::get('user_role') === 'Admin')
                    <li class="relative">
                        <!-- Manage Button (Dropdown Trigger) -->
                        <button @click="manageOpen = !manageOpen" 
                                class="flex items-center justify-between w-full p-2 rounded transition-all duration-200 
                                {{ request()->routeIs('categories.index') || request()->routeIs('admin.products') || request()->routeIs('admin.employees') || request()->routeIs('admin.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                            <div class="flex items-center">
                                <i data-lucide="settings" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                <span x-show="open">Manage</span>
                            </div>
                            <i data-lucide="chevron-down" class="transition-transform duration-200 flex items-center justify-center" :class="manageOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <ul x-show="manageOpen" x-collapse class="bg-gray-200 mt-1 rounded-lg shadow-md overflow-hidden space-y-2 p-2">
                            <li>
                                <a href="{{ route('categories.index') }}" class="flex items-center p-2 rounded transition-all duration-200 
                                    {{ request()->routeIs('categories.index') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="grid" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open">Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products') }}" class="flex items-center p-2 rounded transition-all duration-200 
                                    {{ request()->routeIs('admin.products') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="package" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open">Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.employees') }}" class="flex items-center p-2 rounded transition-all duration-200 
                                    {{ request()->routeIs('admin.employees') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="users" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open">Employees</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders') }}" class="flex items-center p-2 rounded transition-all duration-200 
                                    {{ request()->routeIs('admin.orders') ? 'bg-green-500 text-white' : 'hover:bg-gray-300' }}">
                                    <i data-lucide="dollar-sign" class="mr-2 w-6 h-6 flex items-center justify-center shrink-0"></i>
                                    <span x-show="open">Sales</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <nav class="bg-[#f1eadc] text-black p-4 flex justify-between items-center shadow-md">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('storage/product_images/logocups1.png') }}" alt="Logo" class="h-8 mr-2">
                    <a href="/products" class="text-lg font-bold">CupsStreet</a>
                </div>
            

            <!-- User Dropdown (Gear Icon) -->
            <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="p-2 rounded-full bg-gray-700 text-white focus:outline-none flex items-center space-x-1">
                        <i data-lucide="settings" class="text-xl"></i> <!-- New settings icon -->
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" 
                        class="absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-md z-50">
                        
                        <!-- Update Credentials -->
                        <a href="{{ route('admin.credentials') }}" 
                        class="flex items-center px-4 py-2 text-black hover:bg-gray-200 transition">
                            <i data-lucide="key-round" class="mr-2 text-lg"></i> <!-- New key icon -->
                            <span>Update Credentials</span>
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-black hover:bg-gray-200 transition">
                                <i data-lucide="log-out" class="mr-2 text-lg"></i> <!-- Improved logout icon -->
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            <div class="w-full border-b-4 border-black"></div>
            <main class="p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- <footer class="bg-gray-800 text-white p-4 text-center mt-auto shadow-inner">
        &copy; {{ date('Y') }} Coffee Shop POS
    </footer> -->

    <script>
        lucide.createIcons();
    </script>
</body>
</html>