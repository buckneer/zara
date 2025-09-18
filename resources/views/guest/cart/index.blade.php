
@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-4 text-uppercase fw-bold" style="letter-spacing:.04em;">Shopping cart</h1>

        @if (session('success'))
            <div class="alert alert-dark" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-secondary" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (empty($items) || count($items) === 0)
            <div class="card">
                <div class="card-body">
                    <p class="mb-3">Your cart is empty.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-dark text-uppercase fw-bold">Browse products</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="card border-0 p-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table zara-table mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th class="zara-th">Product</th>
                                            <th class="zara-th zara-th-narrow">Qty</th>
                                            <th class="zara-th">Unit</th>
                                            <th class="zara-th zara-th-center">Line total</th>
                                            <th class="zara-th zara-th-actions"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            @php
                                                $prod = $item['product'] ?? null;
                                                $variant = $item['variant'] ?? null;
                                                $img =
                                                    optional($prod)->images->where('is_primary', true)->first() ??
                                                    optional($prod)->images->first();
                                                $imgUrl = $img ? asset('storage/' . $img->path) : null;
                                            @endphp

                                            <tr class="zara-row">
                                                <td class="zara-td" style="min-width:260px;">
                                                    <div class="d-flex align-items-start gap-3">
                                                        <a href="{{ $prod ? route('products.show', $prod) : '#' }}"
                                                            class="me-2">
                                                            @if ($imgUrl)
                                                                <img src="{{ $imgUrl }}"
                                                                    alt="{{ $img->alt ?? ($prod->title ?? '') }}"
                                                                    class="rounded zara-thumb"
                                                                    style="width:80px;height:80px;object-fit:cover;">
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                    style="width:80px;height:80px;font-size:12px;color:#6c757d;">
                                                                    No image</div>
                                                            @endif
                                                        </a>

                                                        <div>
                                                            <a href="{{ $prod ? route('products.show', $prod) : '#' }}"
                                                                class="fw-semibold text-dark d-block">{{ $prod->title ?? 'Product' }}</a>
                                                            @if ($variant)
                                                                <div class="small text-muted mt-1">
                                                                    {{ $variant->name ?? $variant->sku }}</div>
                                                            @endif
                                                            <div class="small text-muted mt-1">{{ $prod->sku ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="zara-td zara-td-narrow">
                                                    <form action="{{ route('cart.update') }}" method="POST"
                                                        class="d-flex align-items-center">
                                                        @csrf
                                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                                        <input type="number" name="qty" min="0"
                                                            value="{{ $item['qty'] }}"
                                                            class="form-control form-control-sm me-2" style="width:90px;">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-dark">Update</button>
                                                    </form>
                                                </td>

                                                <td class="zara-td">
                                                    <div class="fw-medium">{{ number_format($item['unit_price'] ?? 0, 2) }}
                                                        €</div>
                                                </td>

                                                <td class="zara-td zara-td-center">
                                                    <div class="fw-semibold">
                                                        {{ number_format($item['line_total'] ?? 0, 2) }} €</div>
                                                </td>

                                                <td class="zara-td zara-td-actions">
                                                    <form action="{{ route('cart.remove') }}" method="POST"
                                                        onsubmit="return confirm('Remove item from cart?');">
                                                        @csrf
                                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-dark zara-action">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>

                
                <aside class="col-lg-4">
                    <div class="card border-0 p-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted small text-uppercase">Subtotal</div>
                                <div class="h4 mb-0 fw-semibold">{{ number_format($subtotal ?? 0, 2) }} €</div>
                            </div>

                            <div class="d-grid gap-2 mb-2">
                                <a href="{{ route('checkout.create') }}"
                                    class="btn btn-dark text-uppercase fw-bold">Proceed to checkout</a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-dark">Continue shopping</a>
                            </div>

                            <div class="mt-3 small text-muted">
                                Prices and availability may change after checkout. By placing an order you agree to our
                                terms.
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </div>
@endsection
