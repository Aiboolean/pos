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
            <input type="text" name="phone" id="phone" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Add Employee</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const phoneInput = document.getElementById("phone");

    phoneInput.addEventListener("input", function (e) {
        let value = phoneInput.value.replace(/\D/g, ""); // Remove non-numeric characters
        if (value.startsWith("63")) value = value.substring(2); // Remove '63' if typed by mistake

        if (value.length > 10) value = value.substring(0, 10); // Limit to 10 digits

        let formattedNumber = "+63 ";
        if (value.length > 0) formattedNumber += value.substring(0, 3);
        if (value.length > 3) formattedNumber += " " + value.substring(3, 6);
        if (value.length > 6) formattedNumber += " " + value.substring(6, 10);

        phoneInput.value = formattedNumber;
    });

    phoneInput.addEventListener("keydown", function (e) {
        // Prevent backspacing into the "+63 " prefix
        if (phoneInput.selectionStart <= 4 && (e.key === "Backspace" || e.key === "Delete")) {
            e.preventDefault();
        }

        // Auto-jump when space is pressed at specific points
        if (e.key === " " && (phoneInput.value.length === 7 || phoneInput.value.length === 11)) {
            e.preventDefault();
            phoneInput.value += " ";
        }
    });

    // Set default value
    phoneInput.value = "+63 ";
});
</script>
@endsection