@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">Add Employee</h2>

    @if(session('success'))
        <p class="bg-green-500 text-white p-2 rounded">{{ session('success') }}</p>
    @endif

    <form action="{{ route('admin.employees.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold">First Name</label>
            <input type="text" name="first_name" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Last Name</label>
            <input type="text" name="last_name" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Phone Number</label>
            <input type="text" name="phone" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Add Employee</button>
    </form>
</div>
@endsection
