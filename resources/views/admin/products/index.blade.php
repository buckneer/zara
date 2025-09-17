@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create product</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">SKU</th>
                    <th scope="col">Price</th>
                    <th scope="col">Active</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $p)
                <tr>
                    <td>{{ $p->title }}</td>
                    <td>{{ $p->sku }}</td>
                    <td>{{ number_format($p->price ?? 0, 2) }}</td>
                    <td>
                        @if($p->active)
                        <span class="badge bg-success">Yes</span>
                        @else
                        <span class="badge bg-secondary">No</span>
                        @endif
                    </td>

                    <td class="text-end">
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>

                            <a href="{{ route('products.show', $p) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection