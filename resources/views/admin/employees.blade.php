@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-black">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold tracking-tight flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Employee Management
                </h2>
                <a href="{{ route('admin.employees.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-black rounded-lg transition-all shadow-sm backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Employee
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div class="p-6">
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Phone</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($employees as $employee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $employee->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $employee->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $employee->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.employees.toggle', $employee->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-black
                                                {{ $employee->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}
                                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all">
                                            {{ $employee->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                    <button onclick="showEditForm('{{ $employee->id }}', '{{ $employee->first_name }}', '{{ $employee->last_name }}', '{{ $employee->username }}', '{{ $employee->phone }}')"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-600">No employees found</p>
                                    <p class="text-sm text-gray-500">Add your first employee to get started</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-lg">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
        <button class="absolute top-3 right-3 text-gray-500 hover:text-gray-700" onclick="closeModal()">&times;</button>
        <h3 class="text-2xl font-semibold mb-4">Edit Employee</h3>

        @if ($errors->any())
            <div class="mb-4">
                <ul class="bg-red-500 text-white p-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form for Updating Username, Phone, First Name, and Last Name -->
        <form id="editForm" method="POST">
            @csrf

            <div class="mb-4">
                <label for="editFirstName" class="block font-medium mb-1">First Name:</label>
                <input type="text" id="editFirstName" name="first_name" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div class="mb-4">
                <label for="editLastName" class="block font-medium mb-1">Last Name:</label>
                <input type="text" id="editLastName" name="last_name" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
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
function showEditForm(id, firstName, lastName, username, phone) {
    // Set values for the edit form
    document.getElementById('editFirstName').value = firstName;
    document.getElementById('editLastName').value = lastName;
    document.getElementById('editUsername').value = username;
    document.getElementById('editPhone').value = phone;

    // Set the action for the edit form
    document.getElementById('editForm').action = `/admin/employees/${id}/update`;

    // Set the action for the reset password form
    document.getElementById('resetPasswordForm').action = `/admin/employees/${id}/reset-password`;

    // Show the modal
    document.getElementById('editModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection