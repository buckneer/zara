@extends('layouts.admin')

@section('content')
<div class="admin-ui">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0 admin-page-title">Products</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create product</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card admin-card mb-4">
            <div class="table-responsive">
                <table class="table admin-table table-striped table-hover align-middle">
                    <thead class="admin-thead table-light">
                        <tr>
                            <th scope="col" class="admin-th">Title</th>
                            <th scope="col" class="admin-th">SKU</th>
                            <th scope="col" class="admin-th admin-th-center">Price</th>
                            <th scope="col" class="admin-th admin-th-center">Active</th>
                            <th scope="col" class="admin-th admin-th-actions text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td class="admin-td">
                                    <span class="admin-item-title">{{ $p->title }}</span>
                                </td>

                                <td class="admin-td">{{ $p->sku }}</td>

                                <td class="admin-td admin-td-center">{{ number_format($p->price ?? 0, 2) }} €</td>

                                <td class="admin-td admin-td-center">
                                    @if ($p->active)
                                        <span class="badge admin-badge admin-badge-active">Yes</span>
                                    @else
                                        <span class="badge admin-badge admin-badge-inactive">No</span>
                                    @endif
                                </td>

                                <td class="admin-td admin-td-actions text-end">
                                    <div class="admin-actions d-inline-flex gap-2">
                                        <a href="{{ route('admin.products.edit', $p) }}"
                                            class="btn btn-sm btn-outline-secondary admin-action">Edit</a>

                                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger admin-action">Delete</button>
                                        </form>

                                        <a href="{{ route('products.show', $p) }}"
                                            class="btn btn-sm btn-outline-primary admin-action">View</a>
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
            </div> <!-- /.table-responsive -->

            <div class="card-footer admin-card-footer d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div> <!-- /.card -->
    </div>
</div>
@endsection
