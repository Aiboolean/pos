@extends('layouts.app')

@section('content')
<div class="bg-[#f1eadc] min-h-screen flex items-center justify-center px-4">
    <div class="container max-w-lg mx-auto">
        <div class="bg-white p-8 shadow-lg rounded-xl">
            <h2 class="text-3xl font-semibold mb-6 text-gray-700 text-center">Add Employee</h2>

            @if(session('success'))
                <p class="bg-green-500 text-white p-3 rounded-lg text-center mb-4">{{ session('success') }}</p>
            @endif

            <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-600 font-medium mb-1">First Name</label>
                    <input type="text" name="first_name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-600 font-medium mb-1">Last Name</label>
                    <input type="text" name="last_name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-gray-600 font-medium mb-1">Phone Number</label>
                    <input type="text" name="phone" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                    Add Employee
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
