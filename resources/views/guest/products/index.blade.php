@extends('layouts.guest')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Products</h1>

        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Create product</a>
            @endif
        @endauth
    </div>

    @if($products->isEmpty())
        <p>No products.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                @include('guest.products._card', ['product'=>$product])
            @endforeach
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    @endif
</div>
@endsection
