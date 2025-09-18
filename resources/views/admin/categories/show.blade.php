@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="h4 mb-1">{{ $category->name }}</h1>
                    <div class="text-muted">{{ $category->slug }}</div>
                    <div class="mt-2 small text-muted">Parent: {{ $category->parent ? $category->parent->name : '—' }}</div>
                </div>

                <div class="d-flex gap-2 ms-3">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                        class="btn btn-outline-secondary btn-sm">Edit</a>

                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                        onsubmit="return confirm('Delete category?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h3 class="h6 mb-3">Description</h3>
                <div class="text-body">{!! nl2br(e($category->description)) !!}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="h6 mb-3">Children</h3>

                @if ($category->children->count())
                    <ul class="list-group list-group-flush">
                        @foreach ($category->children as $child)
                            <li class="list-group-item px-0">
                                <a href="{{ route('admin.categories.show', $child) }}"
                                    class="text-decoration-none">{{ $child->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No child categories.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
