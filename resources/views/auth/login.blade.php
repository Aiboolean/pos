@extends('layouts.auth')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4">Login</h2>

    @if(session('error'))
        <p class="text-red-500">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="username" class="block font-medium">Username</label>
            <input type="text" name="username" required class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label for="password" class="block font-medium">Password</label>
            <input type="password" name="password" required class="w-full p-2 border rounded">
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Login
        </button>
    </form>
</div>
@endsection
