@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Categories</h1>

    <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add New Category</a>

    @if(session('success'))
        <div class="bg-green-200 text-green-700 p-2 mt-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full mt-4 border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">ID</th>
                <th class="border p-2">Category Name</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td class="border p-2">{{ $category->id }}</td>
                    <td class="border p-2">{{ $category->name }}</td>
                    <td class="border p-2">
                        <a href="{{ route('categories.edit', $category) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
