{{-- This is the crucial change: only extend the layout if it's NOT a modal request --}}
@if(!request()->ajax())
    @extends('layouts.app')
    @section('content')
@endif

{{-- The rest of the file (form and script) will be rendered in both cases --}}
<style>
    /* You can add any specific styles for this form here if needed */
    /* All main styles are already on your employee list page */
</style>

{{-- For the full page view, this provides the background and centering --}}
<div class="coffee-bg min-h-screen flex items-center justify-center px-4">
    <div class="coffee-card w-full max-w-lg p-8">
        
        {{-- Show the title only on the full page view --}}
        @if(!request()->ajax())
        <h2 class="text-3xl font-semibold mb-6 coffee-text-primary text-center">Add Employee</h2>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block coffee-text-primary font-medium mb-1">First Name</label>
                <input type="text" name="first_name" class="w-full p-3 coffee-input rounded-lg" required>
            </div>

            <div>
                <label class="block coffee-text-primary font-medium mb-1">Last Name</label>
                <input type="text" name="last_name" class="w-full p-3 coffee-input rounded-lg" required>
            </div>
            
            <div>
                <label class="block coffee-text-primary font-medium mb-1">Phone Number</label>
                <input type="text" name="phone" id="phone" class="w-full p-3 coffee-input rounded-lg" value="+63 9" required>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="w-full coffee-btn-success py-3 rounded-lg font-semibold">
                    Add Employee
                </button>
                {{-- This button will be used by JS to close the modal --}}
                <button type="button" class="cancel-modal-btn w-full coffee-btn-danger py-3 rounded-lg font-semibold">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.value = '+63 9';
            
            phoneInput.addEventListener('input', function() {
                let numbers = this.value.replace(/[^\d+]/g, '');
                if (!numbers.startsWith('+639')) { this.value = '+63 9'; return; }
                numbers = numbers.substring(0, 13);
                let formatted = '+63 9';
                if (numbers.length > 4) formatted += numbers.substr(4, 2);
                if (numbers.length > 6) formatted += ' ' + numbers.substr(6, 3);
                if (numbers.length > 9) formatted += ' ' + numbers.substr(9, 4);
                this.value = formatted;
            });
            
            phoneInput.addEventListener('keydown', function(e) {
                if (this.selectionStart < 5 && (e.key === 'Backspace' || e.key === 'Delete')) {
                    e.preventDefault();
                }
            });
        }
    })();
</script>

@if(!request()->ajax())
    @endsection
@endif