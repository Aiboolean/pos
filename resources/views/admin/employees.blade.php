@extends('layouts.app')

@section('content')
<style>
    /* Coffee Shop Theme CSS */
    .coffee-bg {
        background-color: #f5f1ea;
    }
    
    .coffee-card {
        background-color: white;
        border: 1px solid #e0d6c2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 0.75rem;
    }
    
    .coffee-text-primary {
        color: #5c4d3c;
    }
    
    .coffee-text-secondary {
        color: #8c7b6b;
    }
    
    .coffee-border {
        border-color: #e0d6c2;
    }
    
    .coffee-btn-primary {
        background-color: #6f4e37;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-primary:hover {
        background-color: #5c3d2a;
    }
    
    .coffee-btn-success {
        background-color: #8c7b6b;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-success:hover {
        background-color: #6f4e37;
    }
    
    .coffee-btn-warning {
        background-color: #c4a76c;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-warning:hover {
        background-color: #b08d4e;
    }
    
    .coffee-btn-danger {
        background-color: #c45e4c;
        color: white;
        transition: all 0.2s ease;
    }
    
    .coffee-btn-danger:hover {
        background-color: #a34a3a;
    }
    
    .coffee-table-header {
        background-color: #f5f1ea;
        color: #5c4d3c;
    }
    
    .coffee-table-row:hover {
        background-color: #f9f7f3;
    }
    
    .coffee-input {
        border: 1px solid #e0d6c2;
        transition: all 0.2s ease;
    }
    
    .coffee-input:focus {
        outline: none;
        ring: 2px;
        ring-color: #8c7b6b;
        border-color: #8c7b6b;
    }
    
    .coffee-modal {
        background-color: rgba(0,0,0,0.5);
    }
    
    .coffee-modal-content {
        background-color: white;
        border: 1px solid #e0d6c2;
        width: 500px;
        max-width: 95%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        color: #8c7b6b;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .close-btn:hover {
        color: #5c3d2a;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-container {
            overflow-x: auto;
        }
        
        .coffee-modal-content {
            width: 95%;
            padding: 1rem;
        }
    }
</style>

<div class="min-h-screen coffee-bg p-6">
    <div class="coffee-card p-6 max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold coffee-text-primary">Manage Employees</h2>
            <a href="{{ route('admin.employees.create') }}" class="coffee-btn-primary px-4 py-2 rounded-lg">
                Add Employee
            </a>
        </div>

        <div class="table-container">
            <table class="w-full">
                <thead class="coffee-table-header">
                    <tr class="text-left">
                        <th class="p-3">Employee ID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Username</th>
                        <th class="p-3">Phone</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr class="border-t coffee-border coffee-table-row">
                            <td class="p-3 coffee-text-primary">{{ $employee->employee_id }}</td>
                            <td class="p-3 coffee-text-primary">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td class="p-3 coffee-text-primary">{{ $employee->username }}</td>
                            <td class="p-3 coffee-text-primary">{{ $employee->phone }}</td>
                            <td class="p-3">
                                <span class="font-medium {{ $employee->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $employee->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="p-3 flex space-x-2">
                            <button class="coffee-btn-success px-3 py-1 rounded-lg"
                                    onclick="showEditForm('{{ $employee->id }}', '{{ $employee->first_name }}', '{{ $employee->last_name }}', '{{ $employee->username }}', '{{ $employee->phone }}')">
                                    Edit
                                </button>
                                <form action="{{ route('admin.employees.toggle', $employee->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="coffee-btn-warning px-3 py-1 rounded-lg">
                                        {{ $employee->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-3 text-center coffee-text-secondary">NO DATA FOUND</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center coffee-modal hidden z-50">
    <div class="coffee-modal-content p-6 rounded-lg relative">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3 class="text-xl font-semibold mb-4 coffee-text-primary">Edit Employee</h3>

        @if ($errors->any())
            <div class="mb-4 bg-[#f8d7da] text-[#721c24] p-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="editForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="editFirstName" class="block font-medium mb-1 coffee-text-primary">First Name:</label>
                <input type="text" id="editFirstName" name="first_name" required class="w-full p-3 coffee-input rounded-lg">
            </div>
            <div class="mb-4">
                <label for="editLastName" class="block font-medium mb-1 coffee-text-primary">Last Name:</label>
                <input type="text" id="editLastName" name="last_name" required class="w-full p-3 coffee-input rounded-lg">
            </div>
            <div class="mb-4">
                <label for="editUsername" class="block font-medium mb-1 coffee-text-primary">Username:</label>
                <input type="text" id="editUsername" name="username" required class="w-full p-3 coffee-input rounded-lg">
            </div>
            <div class="mb-4">
                <label for="editPhone" class="block font-medium mb-1 coffee-text-primary">Phone:</label>
                <input type="text" id="editPhone" name="phone" required class="w-full p-3 coffee-input rounded-lg">
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 coffee-btn-success py-3 rounded-lg font-semibold">
                    Update
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 coffee-btn-danger py-3 rounded-lg font-semibold">
                    Cancel
                </button>
            </div>
        </form>

        <form id="resetPasswordForm" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full coffee-btn-warning py-3 rounded-lg font-semibold">
                Reset Password
            </button>
        </form>
    </div>
</div>

<script>
function showEditForm(id, firstName, lastName, username, phone) {
    document.getElementById('editFirstName').value = firstName;
    document.getElementById('editLastName').value = lastName;
    document.getElementById('editUsername').value = username;

    const phoneInput = document.getElementById('editPhone');
    phoneInput.value = phone || '+63 9';
    
    phoneInput.addEventListener('input', function() {
        let numbers = this.value.replace(/[^\d+]/g, '');
        if (!numbers.startsWith('+639')) {
            this.value = '+63 9';
            return;
        }
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

    document.getElementById('editForm').action = `/admin/employees/${id}/update`;
    document.getElementById('resetPasswordForm').action = `/admin/employees/${id}/reset-password`;
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endsection