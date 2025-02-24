@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Category</h1>

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <label class="block mb-2">Category Name:</label>
        <input type="text" name="name" value="{{ $category->name }}" class="w-full p-2 border rounded" required>
        
        @error('name')
            <p class="text-red-500">{{ $message }}</p>
        @enderror

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Update</button>
    </form>
</div>
@endsection
