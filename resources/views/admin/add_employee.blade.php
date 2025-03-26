@extends('layouts.app')

@section('content')
<div class="bg-[#f1eadc] min-h-screen flex items-center justify-center px-4">
    <div class="container max-w-lg mx-auto relative">
        <div class="bg-white p-8 shadow-lg rounded-xl">
            <!-- Close Button -->
            <button onclick="window.location.href='{{ route('admin.employees') }}'" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h2 class="text-3xl font-semibold mb-6 text-gray-700 text-center">Add Employee</h2>
            
            <!-- Display Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if(session('success'))
                <p class="bg-green-500 text-white p-3 rounded-lg text-center mb-4">{{ session('success') }}</p>
            @endif

            <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-600 font-medium mb-1">Employee ID</label>
                    <input type="text" name="employee_id" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none bg-gray-100" 
                        value="{{ $employee_id }}" readonly>
                </div>

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
                    <input type="text" name="phone" id="phone" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                           value="+63 9" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                    Add Employee
                </button>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    // Set initial value
    phoneInput.value = '+63 9';
    
    phoneInput.addEventListener('input', function() {
        // Remove all non-digit characters except leading +
        let numbers = this.value.replace(/[^\d+]/g, '');
        
        // Ensure it starts with +639
        if (!numbers.startsWith('+639')) {
            this.value = '+63 9';
            return;
        }
        
        // Limit to 12 digits after +63 (9XXXXXXXXX)
        numbers = numbers.substring(0, 13);
        
        // Format as +63 9XX XXX XXXX
        let formatted = '+63 9';
        if (numbers.length > 4) formatted += numbers.substr(4, 2);
        if (numbers.length > 6) formatted += ' ' + numbers.substr(6, 3);
        if (numbers.length > 9) formatted += ' ' + numbers.substr(9, 4);
        
        this.value = formatted;
    });
    
    // Prevent deleting the +63 9 prefix
    phoneInput.addEventListener('keydown', function(e) {
        if (this.selectionStart < 5 && (e.key === 'Backspace' || e.key === 'Delete')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection