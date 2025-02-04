@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-1/6 bg-gray-100 p-4">
        <h2 class="text-xl font-bold mb-4">Admin</h2>
        <ul class="space-y-2">
            <li><a href="#" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Orders
            </a></li>
            <li><a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Add New Product
            </a></li>

            @if(Session::has('admin_logged_in'))
            <li>
            <a href="{{ route('admin.credentials') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Update Credentials
            </a>
            </li>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Logout
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">
            Login
        </a>
    @endif



        </ul>
    </div>
    <div class="w-2/3 p-6">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
        <p>Welcome to the admin panel. System analytics will be displayed here in the future.</p>
    </div>
    



</div>

@endsection
