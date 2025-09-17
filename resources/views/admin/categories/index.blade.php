@extends('layouts.admin')

@section('content')
<div class="container py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Create category</a>
    </div>

    @if(session('success')) <div class="mb-4 text-green-600">{{ session('success') }}</div> @endif

    <table class="min-w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">#</th>
                <th class="p-2 text-left">Name</th>
                <th class="p-2 text-left">Parent</th>
                <th class="p-2 text-left">Position</th>
                <th class="p-2 text-left">Active</th>
                <th class="p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            <tr class="border-t">
                <td class="p-2">{{ $cat->id }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.categories.show', $cat) }}" class="font-semibold">{{ $cat->name }}</a>
                    <div class="text-sm text-gray-600">{{ $cat->slug }}</div>
                </td>
                <td class="p-2">{{ $cat->parent ? $cat->parent->name : '-' }}</td>
                <td class="p-2">{{ $cat->position ?? '-' }}</td>
                <td class="p-2">{{ $cat->active ? 'Yes' : 'No' }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="px-2 py-1 border rounded">Edit</a>

                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                    </form>

                    <a href="{{ route('admin.categories.show', $cat) }}" class="px-2 py-1 border rounded">View</a>
                </td>
            </tr>
            @empty
            <tr><td class="p-4" colspan="6">No categories found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection
