@extends('layouts.app')

@section('content')
<div class="bg-[#f1eadc] min-h-screen flex justify-center px-4 py-10">
    <div class="max-w-6xl w-full bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-semibold mb-6 text-gray-700">Manage Employees</h2>

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
                        <button class="btn btn-primary" onclick="showEditForm({{ $employee->id }}, '{{ $employee->username }}')">
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
                
                <label for="editPassword">New Password (optional):</label>
                <input type="password" id="editPassword" name="password">

                <button type="submit" class="btn btn-success">Update</button>
            </form>
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
