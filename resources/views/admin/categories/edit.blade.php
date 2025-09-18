@extends('layouts.admin')

@section('content')
    <div class="container py-8">
        <h1 class="text-2xl font-bold mb-4">Edit Category</h1>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </div>
@endsection
