<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> <!-- Alpine.js -->
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
<div x-data="{ open: true }" class="flex">
    <!-- Sidebar Container -->
    <div 
        class="bg-gray-600 text-white min-h-screen transition-all duration-300"
        :class="open ? 'w-64 p-4' : 'w-16 p-2'"
    >
        <button @click="open = !open" class="text-white p-2 focus:outline-none">
            <span x-show="open">← Collapse</span>
            <span x-show="!open" x-cloak>→</span>
        </button>
        
        <ul class="mt-4 space-y-2">
        <li>
            <a href="{{ route('user.orders') }}" class="block p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                :class="open ? 'text-left' : 'text-center'">
                My Orders
            </a>
        </li>

            <!-- Admin-only Links -->
            @if(Session::get('user_role') === 'Admin')
                <li>
                    <a href="{{ route('categories.index') }}" class="block p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Manage Categories
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products') }}" class="block p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Manage Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.employees') }}" class="block p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Manage Employees
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="block p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Sales
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ route('admin.credentials') }}" class="block p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                    :class="open ? 'text-left' : 'text-center'">
                    Update Credentials
                </a>
            </li>

            @if(Session::get('user_role') === 'Admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600 text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Admin Dashboard
                    </a>
                </li>
            @endif

            <!-- Authentication Links -->
            @if(!Session::has('admin_logged_in'))
                <li>
                    <a href="{{ route('login') }}" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Login
                    </a>
                </li>
            @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full p-2 bg-primary text-primary-foreground hover:bg-primary/90 rounded text-center"
                        :class="open ? 'text-left' : 'text-center'">
                        Logout
                    </button>
                </form>
            @endif
        </ul>
    </div>
</div>

        <!-- Main Content -->
        <div class="flex-1">
            <nav class="bg-gray-600 text-white p-4">
                <div class="container mx-auto">
                    <a href="/products" class="text-lg font-bold">CupsStreet</a>
                </div>
            </nav>

            <main class="p-6">
                @yield('content')
            </main>

            <footer class="bg-gray-800 text-white p-4 text-center mt-6">
                &copy; {{ date('Y') }} Coffee Shop POS
            </footer>
        </div>
    </div>
</body>
</html>
