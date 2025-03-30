@extends('layouts.auth')

@section('content')
<style>
    .auth-background {
        background-image: url("{{ asset('images/cupstreet login background.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-card {
        background-color: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
</style>

<div class="auth-background">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-96 login-card">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Login</h2>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-5">
                <label for="username" class="block font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div class="mb-6 relative">
                <label for="password" class="block font-medium text-gray-700 mb-2">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" name="password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    
                    <span class="absolute right-3 cursor-pointer flex items-center" onclick="togglePassword()">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 transition-all duration-300 hover:text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M2 12s4.5-7.5 10-7.5 10 7.5 10 7.5-4.5 7.5-10 7.5S2 12 2 12Z"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">
                Login
            </button>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        let passwordInput = document.getElementById("password");
        let eyeIcon = document.getElementById("eye-icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.add("text-blue-500");
            eyeIcon.classList.remove("text-gray-500");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("text-blue-500");
            eyeIcon.classList.add("text-gray-500");
        }
    }
</script>
@endsection