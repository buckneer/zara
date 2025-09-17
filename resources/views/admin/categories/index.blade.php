@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create category</a>
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
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Parent</th>
                    <th scope="col">Position</th>
                    <th scope="col">Active</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>

                    <td>
                        <a href="{{ route('admin.categories.show', $cat) }}" class="fw-semibold text-decoration-none">
                            {{ $cat->name }}
                        </a>
                        <div class="small text-muted">{{ $cat->slug }}</div>
                    </td>

                    <td>{{ $cat->parent ? $cat->parent->name : '-' }}</td>
                    <td>{{ $cat->position ?? '-' }}</td>
                    <td>{{ $cat->active ? 'Yes' : 'No' }}</td>

                    <td>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>

                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger me-1">Delete</button>
                        </form>

                        <a href="{{ route('admin.categories.show', $cat) }}" class="btn btn-sm btn-outline-primary">View</a>
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

    <div class="mt-3">
        {{ $categories->links() }}
    </div>
</div>
@endsection