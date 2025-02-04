@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h1 class="text-2xl font-bold mb-4">Add Product</h1>
    
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    
    <div>
        <label class="block text-sm font-medium text-gray-700">Name:</label>
        <input type="text" name="name" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Price:</label>
        <input type="number" step="0.01" name="price" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Available:</label>
        <select name="is_available" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="1">Available</option>
            <option value="0">Not Available</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Image:</label>
        <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded-lg">
    </div>

    <div class="flex justify-between mt-4">
        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
    </div>
</form>

</div>
@endsection

