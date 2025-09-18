@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <h1 class="h4 mb-4">Edit product</h1>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" method="POST">
                    @method('PUT')
                    @include('admin.products._form')
                </form>
            </div>
        </div>
    </div>
@endsection
