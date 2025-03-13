@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">Manage Employees</h2>

    <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 bg-blue-500 text-white hover:bg-blue-600 px-4 py-2">
        Add Employee
    </a>

    <div class="overflow-x-auto mt-6">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Username</th>
                    <th class="px-4 py-2">Phone</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="px-4 py-3">{{ $employee->username }}</td>
                        <td class="px-4 py-3">{{ $employee->phone }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium {{ $employee->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $employee->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 flex space-x-2">
                            <form action="{{ route('admin.employees.toggle', $employee->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition">
                                    {{ $employee->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <button class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition"
                                onclick="showEditForm('{{ $employee->id }}', '{{ $employee->username }}', '{{ $employee->phone }}')">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">NO DATA FOUND</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
        <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-700" onclick="closeModal()">&times;</button>
        <h3 class="text-2xl font-semibold mb-4">Edit Employee</h3>
        
        <!-- Form for Updating Username and Phone -->
        <form id="editForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="editUsername" class="block font-medium mb-1">Username:</label>
                <input type="text" id="editUsername" name="username" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div class="mb-4">
                <label for="editPhone" class="block font-medium mb-1">Phone:</label>
                <input type="text" id="editPhone" name="phone" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300 mb-4">
                Update
            </button>
        </form>

        <!-- Form for Resetting Password -->
        <form id="resetPasswordForm" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                Reset Password
            </button>
        </form>
    </div>
</div>

<script>
function showEditForm(id, username, phone) {
    // Set values for the edit form
    document.getElementById('editUsername').value = username;
    document.getElementById('editPhone').value = phone;

    // Set the action for the edit form
    document.getElementById('editForm').action = `/admin/employees/${id}/update`;

    // Set the action for the reset password form
    document.getElementById('resetPasswordForm').action = `/admin/employees/${id}/reset-password`;

    // Show the modal
    document.getElementById('editModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
