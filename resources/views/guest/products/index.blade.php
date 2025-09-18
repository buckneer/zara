@extends('layouts.guest')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Products</h1>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Create product</a>
                @endif
            @endauth
        </div>

        @if ($products->isEmpty())
            <p class="text-muted">No products.</p>
        @else
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-12 col-sm-6 col-md-4">
                        {{-- make sure this path matches where your card partial lives:
                        I provided a Bootstrap card at resources/views/products/_card.blade.php,
                        so we include 'products._card' here. --}}
                        @include('guest.products._card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
