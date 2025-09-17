@extends('layouts.admin')

@section('content')
<div class="container py-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ $category->name }}</h1>
            <div class="text-gray-600">{{ $category->slug }}</div>
            <div class="mt-2 text-sm">Parent: {{ $category->parent ? $category->parent->name : '—' }}</div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-2 border rounded">Edit</a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
            </form>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold">Description</h3>
        <div class="mt-2 text-gray-800">{!! nl2br(e($category->description)) !!}</div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold">Children</h3>
        @if ($category->children->count())
            <ul class="mt-2 list-disc pl-6">
                @foreach($category->children as $child)
                    <li><a href="{{ route('categories.show', $child) }}" class="underline">{{ $child->name }}</a></li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">No child categories.</p>
        @endif
    </div>
</div>
@endsection
