@extends('layouts.app')

@section('content')
<h1 class="text-2xl">Manage Products</h1>

<table>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>
                <img src="{{ asset('storage/' . $product->image) }}" width="50">
            </td>
            <td>
                <form method="POST" action="{{ route('products.updateAvailability', $product->id) }}">
                    @csrf
                    @method('PATCH')
                    <select name="is_available" onchange="this.form.submit()">
                        <option value="1" {{ $product->is_available ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Not Available</option>
                    </select>
                </form>
            </td>
        </tr>
    @endforeach
</table>
@endsection
