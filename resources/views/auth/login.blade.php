@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center h-screen bg-[#f1eadc]"> <!-- Added bg color here -->
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4">Login</h2>

        @if(session('error'))
            <p class="text-red-500">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4 relative group">
                <label for="username" class="block font-medium">Username</label>
                <div class="relative flex items-center">
                    <input type="text" name="username" required class="w-full p-2 border rounded">
                    <span class="absolute left-full ml-2 px-2 py-1 text-xs bg-gray-700 text-white rounded opacity-0 group-hover:opacity-100 transition-all duration-300 top-1/2 transform -translate-y-1/2 whitespace-nowrap">
                        Format: firstname.lastname
                    </span>
                </div>
            </div>

            <div class="mb-4 relative group">
                <label for="password" class="block font-medium">Password</label>
                <div class="relative flex items-center">
                    <input type="password" name="password" required class="w-full p-2 border rounded">
                    <span class="absolute left-full ml-2 px-2 py-1 text-xs bg-gray-700 text-white rounded opacity-0 group-hover:opacity-100 transition-all duration-300 top-1/2 transform -translate-y-1/2 whitespace-nowrap">
                        Format: firstname,lastname
                    </span>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
