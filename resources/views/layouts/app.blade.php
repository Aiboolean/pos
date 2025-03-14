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
        <div x-data="{ open: true, manageOpen: false }" class="flex">
            <div class="bg-[#F1EADC] text-black min-h-screen transition-all duration-300 shadow-lg"
                 :class="open ? 'w-64 p-4' : 'w-16 p-2'">
                <!-- Toggle Sidebar Button -->
                <button @click="open = !open" class="mb-4 p-2 rounded bg-gray-700 text-white w-full flex items-center justify-center hover:bg-gray-600">
                    <i data-lucide="menu"></i>
                </button>

                <ul class="mt-4 space-y-2">
                    @if(Session::get('user_role') === 'Admin')
                        <!-- Admin Dashboard -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center p-2 text-primary-foreground rounded 
                                      {{ request()->routeIs('admin.dashboard') ? 'bg-green-500' : 'bg-primary' }}">
                                <i data-lucide="layout-dashboard" class="mr-2"></i>
                                <span x-show="open">Admin Dashboard</span>
                            </a>
                        </li>
                    @endif

                    <!-- POS -->
                    <li>
                        <a href="/products" class="flex items-center p-2 rounded transition-all duration-200 
                                  {{ request()->is('products') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                            <i data-lucide="shopping-cart" class="mr-2"></i>
                            <span x-show="open">POS</span>
                        </a>
                    </li>

                    <!-- Transactions -->
                    <li>
                        <a href="{{ route('user.orders') }}" class="flex items-center p-2 rounded transition-all duration-200 
                                  {{ request()->routeIs('user.orders') ? 'bg-green-500 text-white' : 'bg-gray-600 hover:bg-gray-500 text-primary-foreground' }}">
                            <i data-lucide="book" class="mr-2"></i>
                            <span x-show="open">Transactions</span>
                        </a>
                    </li>
                    @if(Session::get('user_role') === 'Admin')
                    <li>
                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center p-2 text-primary-foreground rounded 
                                  {{ request()->routeIs('categories.index') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="grid" class="mr-2"></i>
                            <span x-show="open">Manage Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products') }}" 
                           class="flex items-center p-2 text-primary-foreground rounded 
                                  {{ request()->routeIs('admin.products') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="package" class="mr-2"></i>
                            <span x-show="open">Manage Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.employees') }}" 
                           class="flex items-center p-2 text-primary-foreground rounded 
                                  {{ request()->routeIs('admin.employees') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="users" class="mr-2"></i>
                            <span x-show="open">Manage Employees</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders') }}" 
                           class="flex items-center p-2 text-primary-foreground rounded 
                                  {{ request()->routeIs('admin.orders') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="dollar-sign" class="mr-2"></i>
                            <span x-show="open">Sales</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('admin.credentials') }}" 
                           class="flex items-center p-2 text-primary-foreground rounded 
                                  {{ request()->routeIs('admin.credentials') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="key" class="mr-2"></i>
                            <span x-show="open">Update Credentials</span>
                        </a>
                    </li>
                    @if(!Session::has('admin_logged_in'))
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center p-2 bg-blue-500 text-white rounded">
                                <i data-lucide="log-in" class="mr-2"></i>
                                <span x-show="open">Login</span>
                            </a>
                        </li>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center p-2 bg-primary text-primary-foreground rounded w-full">
                                <i data-lucide="log-out" class="mr-2"></i>
                                <span x-show="open">Logout</span>
                            </button>
                        </form>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <nav class="bg-[#f1eadc] text-black p-4">
                <div class="container mx-auto flex items-center space-x-2">
                    <img src="{{ asset('storage/product_images/logocups1.png') }}" alt="Logo" class="h-8 mr-2">
                    <a href="/products" class="text-lg font-bold">CupsStreet</a>
                </div>
            </nav>
            <div class="w-full border-b-4 border-black"></div>
            <main class="p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 text-center mt-auto shadow-inner">
        &copy; {{ date('Y') }} Coffee Shop POS
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>