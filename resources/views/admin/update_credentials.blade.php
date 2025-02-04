@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4">Update Credentials</h2>

        @if(session('success'))
            <p class="text-green-500">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.update') }}">
            @csrf
            <div class="mb-4">
                <label for="username" class="block font-medium">New Username</label>
                <input type="text" name="username" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="password" class="block font-medium">New Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">
                Update Credentials
            </button>
        </form>
    </div>
</div>
@endsection
