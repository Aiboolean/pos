@extends('layouts.app')

@section('content')
<div class="bg-[#f1eadc] min-h-screen flex justify-center px-4 py-10">
    <div class="max-w-6xl w-full bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-semibold mb-6 text-gray-700">Manage Employees</h2>

<<<<<<< HEAD
                <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 py-2 px-4">
                Add Employees
                </a>
            
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td>{{ $employee->username }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>
                        <span class="{{ $employee->is_active ? 'text-success' : 'text-danger' }}">
                            {{ $employee->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.employees.toggle', $employee->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                {{ $employee->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <button class="btn btn-primary" onclick="showEditForm({{ $employee->id }}, '{{ $employee->username }}', '{{ $employee->phone }}')">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Edit Employee Modal -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h3>Edit Employee</h3>
        <form id="editForm" method="POST">
            @csrf
            <label for="editUsername">Username:</label>
            <input type="text" id="editUsername" name="username" required>
            
            <label for="editPhone">Phone:</label>
            <input type="text" id="editPhone" name="phone" required>

            <label for="editPassword">New Password (optional):</label>
            <input type="password" id="editPassword" name="password">

            <button type="submit" class="btn btn-success">Update</button>
        </form>
=======
        <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 bg-blue-500 text-white hover:bg-blue-600 py-2 px-4">
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
                    @foreach ($employees as $employee)
                        <tr class="border-t">
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
                                    onclick="showEditForm({{ $employee->id }}, '{{ $employee->username }}')">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Edit Employee Modal -->
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <span class="absolute top-4 right-4 text-gray-500 cursor-pointer text-xl" onclick="closeModal()">&times;</span>
                <h3 class="text-2xl font-semibold mb-4">Edit Employee</h3>
                <form id="editForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="editUsername" class="block font-medium mb-1">Username:</label>
                        <input type="text" id="editUsername" name="username" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label for="editPassword" class="block font-medium mb-1">New Password (optional):</label>
                        <input type="password" id="editPassword" name="password" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300">
                        Update
                    </button>
                </form>
            </div>
        </div>
>>>>>>> 823578e54d6308ea8e2a13f11c4825fc8d7205a4
    </div>
</div>
</div>

<script>
function showEditForm(id, username, phone) {
    document.getElementById('editUsername').value = username;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editForm').action = `/admin/employees/${id}/update`;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>

@endsection
