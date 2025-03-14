<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#E6DDC6] flex flex-col min-h-screen">
    <div class="flex flex-1">
        <!-- Sidebar -->
        <div x-data="{ open: true }" class="flex">
            <!-- Sidebar Container -->
            <div class="bg-[#F1EADC] text-black min-h-screen transition-all duration-300"
                 :class="open ? 'w-64 p-4' : 'w-16 p-2'">
                <!-- Collapse Button -->
                <button @click="open = !open" class="mb-4 p-2 rounded bg-gray-700 text-white w-full flex items-center justify-center">
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
                        <a href="/products" class="flex items-center p-2 text-primary-foreground rounded text-center 
                                  {{ request()->is('products') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="shopping-cart" class="mr-2"></i>
                            <span x-show="open">POS</span>
                        </a>
                    </li>

                    <!-- Transactions -->
                    <li>
                        <a href="{{ route('user.orders') }}" class="flex items-center p-2 text-primary-foreground rounded text-center 
                                  {{ request()->is('orders') ? 'bg-green-500' : 'bg-primary' }}">
                            <i data-lucide="book" class="mr-2"></i>
                            <span x-show="open">Transactions</span>
                        </a>
                    </li>

                    @if(Session::get('user_role') === 'Admin')
                        <!-- Admin Dropdown -->
                        <li x-data="{ adminOpen: false }">
                            <button @click="adminOpen = !adminOpen" class="flex items-center p-2 text-primary-foreground rounded w-full 
                                      {{ request()->routeIs(['categories.index', 'admin.products', 'admin.employees', 'admin.orders']) ? 'bg-green-500' : 'bg-primary' }}">
                                <i data-lucide="settings" class="mr-2"></i>
                                <span x-show="open">Admin</span>
                                <i data-lucide="chevron-down" class="ml-auto" x-show="open"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <ul x-show="adminOpen" class="pl-4 mt-2 space-y-2">
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
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->
<nav class="bg-[#f1eadc] text-black p-4">
    <div class="container mx-auto flex items-center justify-between">
        <!-- Logo and Name -->
        <div class="flex items-center space-x-2">
            <img src="{{ asset('storage/product_images/logocups1.png') }}" alt="Logo" class="h-8 mr-2">
            <a href="/products" class="text-lg font-bold">CupsStreet</a>
        </div>

        <!-- Update Credentials and Logout Links -->
        <div class="flex items-center space-x-4">
            @if(Session::has('admin_logged_in'))
                <a href="{{ route('admin.credentials') }}" 
                   class="flex items-center p-2 text-black rounded hover:bg-gray-200 transition duration-300">
                    <i data-lucide="key" class="mr-2"></i>
                    <span>Account</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center p-2 text-black rounded hover:bg-gray-200 transition duration-300">
                        <i data-lucide="log-out" class="mr-2"></i>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center p-2 text-black rounded hover:bg-gray-200 transition duration-300">
                    <i data-lucide="log-in" class="mr-2"></i>
                    <span>Login</span>
                </a>
            @endif
        </div>
    </div>
</nav>
            <div class="w-full border-b-4 border-black"></div>
            <main class="p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white p-4 text-center mt-auto">
        &copy; {{ date('Y') }} Coffee Shop POS
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>