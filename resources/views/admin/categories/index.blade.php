@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0 text-uppercase fw-bold" style="letter-spacing:.04em;">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-dark text-uppercase fw-bold px-3">Create category</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="card border-0 p-0 mb-4">
        <div class="table-responsive">
            <table class="table zara-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="zara-th zara-th-narrow">#</th>
                        <th scope="col" class="zara-th">Name</th>
                        <th scope="col" class="zara-th">Parent</th>
                        <th scope="col" class="zara-th zara-th-center">Position</th>
                        <th scope="col" class="zara-th zara-th-center">Active</th>
                        <th scope="col" class="zara-th zara-th-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $cat)
                    <tr class="zara-row">
                        <td class="zara-td zara-td-narrow text-muted">{{ $cat->id }}</td>

                        <td class="zara-td">
                            <a href="{{ route('admin.categories.show', $cat) }}" class="d-block fw-semibold text-decoration-none text-dark">
                                {{ $cat->name }}
                            </a>
                            <div class="small text-muted">{{ $cat->slug }}</div>
                        </td>

                        <td class="zara-td">{{ $cat->parent ? $cat->parent->name : '-' }}</td>
                        <td class="zara-td zara-td-center">{{ $cat->position ?? '-' }}</td>

                        <td class="zara-td zara-td-center">
                            @if($cat->active)
                                <span class="badge zara-badge-active">Yes</span>
                            @else
                                <span class="badge zara-badge-inactive">No</span>
                            @endif
                        </td>

                        <td class="zara-td zara-td-actions">
                            <div class="zara-actions">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-secondary zara-action">Edit</a>

                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger zara-action">Delete</button>
                                </form>

                                <a href="{{ route('admin.categories.show', $cat) }}" class="btn btn-sm btn-outline-primary zara-action">View</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $categories->links() }}
    </div>
</div>
@endsection
