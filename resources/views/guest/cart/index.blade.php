@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-4 text-uppercase fw-bold cart-title">Shopping cart</h1>

        @if (session('success'))
            <div class="alert alert-dark" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-secondary" role="alert">{{ session('error') }}</div>
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
                    <div class="card cart-card border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table cart-table mb-0 align-middle">
                                    <thead class="cart-thead">
                                        <tr>
                                            <th class="cart-th">Product</th>
                                            <th class="cart-th cart-th-narrow">Qty</th>
                                            <th class="cart-th">Unit</th>
                                            <th class="cart-th cart-th-center">Line total</th>
                                            <th class="cart-th cart-th-actions"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            @php
                                                $prod = $item['product'] ?? null;
                                                $variant = $item['variant'] ?? null;
                                                $img = $item['image'] ?? (optional($prod)->images->where('is_primary', true)->first() ?? optional($prod)->images->first());
                                                $imgUrl = $img ? asset('storage/' . ($img->path ?? '')) : null;

                                                $unitOriginal = number_format($item['unit_price_original'] ?? 0, 2);
                                                $unitDiscounted = number_format($item['unit_price_discounted'] ?? $item['unit_price_original'] ?? 0, 2);
                                                $lineOriginal = number_format($item['line_total_original'] ?? 0, 2);
                                                $lineDiscounted = number_format($item['line_total_discounted'] ?? 0, 2);
                                                $discountPercent = (float) ($item['discount_percent'] ?? 0);
                                            @endphp

                                            <tr class="cart-row">
                                                <td class="cart-td cart-td-product" style="min-width:260px;">
                                                    <div class="d-flex align-items-start gap-3">
                                                        <a href="{{ $prod ? route('products.show', $prod) : '#' }}" class="cart-thumb-link">
                                                            @if ($imgUrl)
                                                                <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? ($prod->title ?? '') }}" class="rounded cart-thumb">
                                                            @else
                                                                <div class="cart-thumb cart-noimage">No image</div>
                                                            @endif
                                                        </a>

                                                        <div class="cart-product-info">
                                                            <a href="{{ $prod ? route('products.show', $prod) : '#' }}" class="fw-semibold text-dark d-block cart-product-title">{{ $prod->title ?? 'Product' }}</a>

                                                            @if ($variant)
                                                                <div class="small text-muted cart-variant mt-1">{{ $variant->name ?? $variant->sku }}</div>
                                                            @endif

                                                            <div class="small text-muted cart-sku mt-1">{{ $prod->sku ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="cart-td cart-td-narrow">
                                                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center cart-qty-form">
                                                        @csrf
                                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                                        <input type="number" name="qty" min="0" value="{{ $item['qty'] }}" class="form-control form-control-sm cart-qty-input me-2" aria-label="Quantity">
                                                        <button type="submit" class="btn btn-sm btn-outline-dark cart-btn-update">Update</button>
                                                    </form>
                                                </td>

                                                <td class="cart-td">
                                                    @if ($discountPercent > 0)
                                                        <div class="cart-unit">
                                                            <div class="cart-unit-original">
                                                                <span class="cart-price-strike">{{ $unitOriginal }} €</span>
                                                                <small class="cart-discount-tag">-{{ (int) round($discountPercent) }}%</small>
                                                            </div>

                                                            <div class="cart-unit-discounted fw-medium">
                                                                {{ $unitDiscounted }} €
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="fw-medium cart-unit-price">{{ $unitOriginal }} €</div>
                                                    @endif
                                                </td>

                                                <td class="cart-td cart-td-center">
                                                    @if ($discountPercent > 0)
                                                        <div class="cart-line">
                                                            <div class="small text-muted">Was {{ $lineOriginal }} €</div>
                                                            <div class="fw-semibold cart-line-total">{{ $lineDiscounted }} €</div>
                                                        </div>
                                                    @else
                                                        <div class="fw-semibold cart-line-total">{{ $lineOriginal }} €</div>
                                                    @endif
                                                </td>

                                                <td class="cart-td cart-td-actions">
                                                    <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove item from cart?');">
                                                        @csrf
                                                        <input type="hidden" name="key" value="{{ $item['key'] }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-dark cart-btn-remove">Remove</button>
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

                <!-- Summary / aside -->
                <aside class="col-lg-4">
                    <div class="card cart-summary-card border-0">
                        <div class="card-body">
                            <div class="cart-subtotal-block mb-3">
                                <div class="text-muted small text-uppercase">Subtotal (before discounts)</div>
                                <div class="h6 mb-0 cart-subtotal-original">{{ number_format($subtotal ?? 0, 2) }} €</div>

                                @if (($total_discount ?? 0) > 0)
                                    <div class="text-muted small text-uppercase mt-2">Total discount</div>
                                    <div class="h5 mb-0 text-danger cart-total-discount">-{{ number_format($total_discount ?? 0, 2) }} €</div>
                                @endif

                                <hr class="my-3">

                                <div class="text-muted small text-uppercase">Total</div>
                                <div class="h4 mb-0 fw-semibold cart-grand-total">{{ number_format($grand_total ?? ($subtotal - ($total_discount ?? 0)), 2) }} €</div>
                            </div>

                            <div class="d-grid gap-2 mb-2">
                                <a href="{{ route('checkout.create') }}" class="btn btn-dark text-uppercase fw-bold cart-checkout-btn">Proceed to checkout</a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-dark cart-continue-btn">Continue shopping</a>
                            </div>

                            <div class="mt-3 small text-muted cart-note">
                                Prices and availability may change after checkout. By placing an order you agree to our terms.
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </div>
@endsection
