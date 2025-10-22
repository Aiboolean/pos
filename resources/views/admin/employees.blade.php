@extends('layouts.app')

@section('content')
<style>
    /* Main Coffee Shop Theme Styles */
    .coffee-bg { background-color: #f5f1ea; }
    .coffee-card { background-color: white; border: 1px solid #e0d6c2; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 0.75rem; }
    .coffee-text-primary { color: #5c4d3c; }
    .coffee-text-secondary { color: #8c7b6b; }
    .coffee-border { border-color: #e0d6c2; }
    .coffee-btn-primary, .coffee-btn-success, .coffee-btn-warning, .coffee-btn-danger, .coffee-btn-secondary { transition: all 0.2s ease; border: none; }
    .coffee-btn-primary { background-color: #6f4e37; color: white; }
    .coffee-btn-primary:hover { background-color: #5c3d2a; }
    .coffee-btn-success { background-color: #8c7b6b; color: white; }
    .coffee-btn-success:hover { background-color: #6f4e37; }
    .coffee-btn-warning { background-color: #c4a76c; color: white; }
    .coffee-btn-warning:hover { background-color: #b08d4e; }
    .coffee-btn-danger { background-color: #c45e4c; color: white; }
    .coffee-btn-danger:hover { background-color: #a34a3a; }
    .coffee-btn-secondary { background-color: #e0d6c2; color: #5c4d3c; }
    .coffee-btn-secondary:hover { background-color: #d4c9b5; }
    .coffee-table-header { background-color: #f5f1ea; color: #5c4d3c; }
    .coffee-table-row:hover { background-color: #f9f7f3; }
    .coffee-input { border: 1px solid #e0d6c2; transition: all 0.2s ease; }
    .coffee-input:focus { outline: none; box-shadow: 0 0 0 2px #8c7b6b40; border-color: #8c7b6b; }
    .coffee-alert-danger { background-color: #f8d7da; border-left: 4px solid #c45e4c; color: #721c24; }
    .status-active { color: #6f8c6b; }
    .status-inactive { color: #c45e4c; }
</style>

<div class="min-h-screen coffee-bg p-6">
    <div class="coffee-card p-6 max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold coffee-text-primary flex items-center">
                <i data-lucide="users" class="w-6 h-6 mr-2 coffee-text-secondary"></i>
                Manage Employees
            </h2>
            {{-- This button now triggers the modal already on this page --}}
            <button id="addEmployeeBtn" class="coffee-btn-primary px-4 py-2 rounded-lg flex items-center">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                Add Employee
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="coffee-table-header">
                    <tr class="text-left">
                        <th class="p-3">ID</th>
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
                            <td class="p-3 coffee-text-primary">{{ $employee->id }}</td>
                            <td class="p-3 coffee-text-primary">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td class="p-3 coffee-text-primary">{{ $employee->username }}</td>
                            <td class="p-3 coffee-text-primary">{{ $employee->phone }}</td>
                            <td class="p-3"><span class="font-medium {{ $employee->is_active ? 'status-active' : 'status-inactive' }}">{{ $employee->is_active ? 'Active' : 'Disabled' }}</span></td>
                            <td class="p-3 flex space-x-2">
                                <button class="edit-employee-btn coffee-btn-secondary px-3 py-1 rounded-lg flex items-center"
                                    data-id="{{ $employee->id }}" data-first_name="{{ $employee->first_name }}" data-last_name="{{ $employee->last_name }}" data-username="{{ $employee->username }}" data-phone="{{ $employee->phone }}">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.employees.toggle', $employee->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="coffee-btn-warning px-3 py-1 rounded-lg flex items-center">
                                        <i data-lucide="{{ $employee->is_active ? 'power-off' : 'power' }}" class="w-4 h-4 mr-1"></i>{{ $employee->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-3 text-center coffee-text-secondary">NO DATA FOUND</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $employees->links() }}</div>
    </div>
</div>

{{-- ✅ FIX: Add Employee Modal with the full form now lives on this page --}}
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 p-4 flex items-center justify-center">
    <div class="coffee-card w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-lg">
        <div class="flex justify-between items-center p-6 border-b coffee-border">
            <h3 class="text-xl font-semibold coffee-text-primary">Add New Employee</h3>
            <button class="close-modal-btn text-3xl font-light leading-none coffee-text-secondary hover:text-red-600">&times;</button>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-5">
                @csrf
                <div><label class="block coffee-text-primary font-medium mb-1">First Name</label><input type="text" name="first_name" class="w-full p-3 coffee-input rounded-lg" required></div>
                <div><label class="block coffee-text-primary font-medium mb-1">Last Name</label><input type="text" name="last_name" class="w-full p-3 coffee-input rounded-lg" required></div>
                <div><label class="block coffee-text-primary font-medium mb-1">Phone Number</label><input type="text" name="phone" id="add_phone" class="w-full p-3 coffee-input rounded-lg" value="+63 9" required></div>
                <div class="flex space-x-3 pt-4"><button type="submit" class="w-full coffee-btn-success py-3 rounded-lg font-semibold">Add Employee</button><button type="button" class="cancel-modal-btn w-full coffee-btn-danger py-3 rounded-lg font-semibold">Cancel</button></div>
            </form>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 p-4 flex items-center justify-center">
    <div class="coffee-card w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-lg">
        <div class="flex justify-between items-center p-6 border-b coffee-border">
            <h3 class="text-xl font-semibold coffee-text-primary">Edit Employee</h3>
            <button class="close-modal-btn text-3xl font-light leading-none coffee-text-secondary hover:text-red-600">&times;</button>
        </div>
        <div class="p-6">
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                <div><label for="editFirstName" class="block font-medium mb-1 coffee-text-primary">First Name:</label><input type="text" id="editFirstName" name="first_name" required class="w-full p-3 coffee-input rounded-lg"></div>
                <div><label for="editLastName" class="block font-medium mb-1 coffee-text-primary">Last Name:</label><input type="text" id="editLastName" name="last_name" required class="w-full p-3 coffee-input rounded-lg"></div>
                <div><label for="editUsername" class="block font-medium mb-1 coffee-text-primary">Username:</label><input type="text" id="editUsername" name="username" required class="w-full p-3 coffee-input rounded-lg"></div>
                <div><label for="editPhone" class="block font-medium mb-1 coffee-text-primary">Phone:</label><input type="text" id="editPhone" name="phone" required class="w-full p-3 coffee-input rounded-lg"></div>
                <div class="flex space-x-3 pt-4"><button type="submit" class="flex-1 coffee-btn-success py-3 rounded-lg font-semibold">Update</button><button type="button" class="cancel-modal-btn flex-1 coffee-btn-danger py-3 rounded-lg font-semibold">Cancel</button></div>
            </form>
            <form id="resetPasswordForm" method="POST" class="mt-4">@csrf<button type="submit" class="w-full coffee-btn-warning py-3 rounded-lg font-semibold">Reset Password</button></form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        const addModal = document.getElementById('addModal');
        const addEmployeeBtn = document.getElementById('addEmployeeBtn');
        const editModal = document.getElementById('editModal');
        const editButtons = document.querySelectorAll('.edit-employee-btn');
        const allCloseButtons = document.querySelectorAll('.close-modal-btn');
        const allCancelButtons = document.querySelectorAll('.cancel-modal-btn');

        function openModal(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            addModal.classList.add('hidden');
            editModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // --- PHONE FORMATTING LOGIC ---
        function formatPhoneNumber(phoneInput) {
            phoneInput.oninput = function() {
                let numbers = this.value.replace(/[^\d+]/g, '');
                if (!numbers.startsWith('+639')) { this.value = '+63 9'; return; }
                numbers = numbers.substring(0, 13);
                let formatted = '+63 9';
                if (numbers.length > 4) formatted += numbers.substr(4, 2);
                if (numbers.length > 6) formatted += ' ' + numbers.substr(6, 3);
                if (numbers.length > 9) formatted += ' ' + numbers.substr(9, 4);
                this.value = formatted;
            };
            phoneInput.onkeydown = function(e) {
                if (this.selectionStart < 5 && (e.key === 'Backspace' || e.key === 'Delete')) {
                    e.preventDefault();
                }
            };
        }

        // --- ADD EMPLOYEE LOGIC ---
        if (addModal && addEmployeeBtn) {
            addEmployeeBtn.addEventListener('click', () => {
                // Apply formatting to the phone input in the ADD modal
                const addPhoneInput = document.getElementById('add_phone');
                if (addPhoneInput) {
                    formatPhoneNumber(addPhoneInput);
                }
                openModal(addModal);
            });
        }

        // --- EDIT EMPLOYEE LOGIC ---
        if (editModal && editButtons.length > 0) {
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const { id, first_name, last_name, username, phone } = this.dataset;
                    document.getElementById('editFirstName').value = first_name;
                    document.getElementById('editLastName').value = last_name;
                    document.getElementById('editUsername').value = username;
                    const phoneInput = document.getElementById('editPhone');
                    phoneInput.value = phone || '+63 9';
                    formatPhoneNumber(phoneInput); // Apply formatting on open
                    document.getElementById('editForm').action = `/admin/employees/${id}/update`;
                    document.getElementById('resetPasswordForm').action = `/admin/employees/${id}/reset-password`;
                    openModal(editModal);
                });
            });
        }

        // --- GENERAL CLOSE MODAL LOGIC ---
        allCloseButtons.forEach(btn => btn.addEventListener('click', closeModal));
        allCancelButtons.forEach(btn => btn.addEventListener('click', closeModal));
        addModal.addEventListener('click', e => { if (e.target === addModal) closeModal(); });
        editModal.addEventListener('click', e => { if (e.target === editModal) closeModal(); });
    });
</script>
@endpush
@endsection