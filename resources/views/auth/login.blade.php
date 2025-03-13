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
            <input type="text" name="username" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4 relative">
            <label for="password" class="block font-medium">Password</label>
            <div class="relative flex items-center">
                <input type="password" id="password" name="password" required 
                    class="w-full px-3 py-2 border rounded pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <!-- Centered Eye Icon -->
                <span class="absolute right-3 cursor-pointer flex items-center" onclick="togglePassword()">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 transition-all duration-300 ease-in-out hover:text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M2 12s4.5-7.5 10-7.5 10 7.5 10 7.5-4.5 7.5-10 7.5S2 12 2 12Z"></path>
                    </svg>
                </span>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
            Login
        </button>
    </form>
</div>

<!-- JavaScript for Show/Hide Password -->
<script>
    function togglePassword() {
        let passwordInput = document.getElementById("password");
        let eyeIcon = document.getElementById("eye-icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.add("text-blue-500"); // Highlight icon when active
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("text-blue-500"); // Reset color
        }
    }
</script>
@endsection
