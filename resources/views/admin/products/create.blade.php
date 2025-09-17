@extends('layouts.admin')

@section('content')
<div class="container py-8">
    <h1>Create product</h1>

    <form action="{{ route('admin.products.store') }}" method="POST">
        @include('admin.products._form')
    </form>
</div>
@endsection
