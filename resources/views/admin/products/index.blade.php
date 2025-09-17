@extends('layouts.admin')

@section('content')
<div class="container py-8">
    <div class="flex justify-between items-center">
        <h1>Products</h1>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Create product</a>
    </div>

    @if(session('success')) <div class="mt-2 text-green-600">{{ session('success') }}</div> @endif

    <table class="min-w-full mt-4">
        <thead>
            <tr>
                <th>Title</th><th>SKU</th><th>Price</th><th>Active</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->title }}</td>
                <td>{{ $p->sku }}</td>
                <td>{{ number_format($p->price,2) }}</td>
                <td>{{ $p->active ? 'Yes' : 'No' }}</td>
                <td class="flex gap-2">
                    <a href="{{ route('admin.products.edit', $p) }}" class="px-2 py-1 border rounded">Edit</a>

                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete product?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                    </form>

                    <a href="{{ route('products.show', $p) }}" class="px-2 py-1 border rounded">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
