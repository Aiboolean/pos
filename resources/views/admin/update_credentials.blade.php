@extends('layouts.app')

@section('content')
<div class="bg-[#f1eadc] min-h-screen flex justify-center items-center px-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-96">
        <h2 class="text-3xl font-semibold mb-6 text-gray-700 text-center">Update Credentials</h2>

        @if(session('success'))
            <p class="bg-green-500 text-white p-3 rounded-lg text-center mb-4">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.update') }}" class="space-y-5">
            @csrf
            <div>
                <label for="username" class="block text-gray-600 font-medium mb-1">New Username</label>
                <input type="text" name="username" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-gray-600 font-medium mb-1">New Password</label>
                <input type="password" name="password" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="w-1/2 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                    Update
                </button>
                <a href="{{ url()->previous() }}" class="w-1/2 bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-lg font-semibold text-center transition duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
