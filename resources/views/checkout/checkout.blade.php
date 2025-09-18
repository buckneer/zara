@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <h3 class="text-uppercase fw-bold" style="letter-spacing:.04em;">Checkout</h3>
        <p class="text-muted">Review your order and complete the purchase.</p>

        @if (session('error'))
            <div class="alert alert-secondary">{{ session('error') }}</div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="zara-form">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card zara-card mb-3">
                        <div class="card-header zara-card-header">Shipping address</div>
                        <div class="card-body">
                            @if ($addresses->count() === 0)
                                <div class="alert alert-secondary">
                                    You have no saved addresses. <a href="{{ route('addresses.create') }}">Create one</a>.
                                </div>
                            @else
                                <div class="list-group zara-list-group">
                                    @foreach ($addresses as $address)
                                        <label class="list-group-item zara-list-item d-flex gap-3 align-items-start">
                                            <input class="form-check-input me-2" type="radio" name="shipping_address_id"
                                                value="{{ $address->id }}" {{ $loop->first ? 'checked' : '' }}>
                                            <div>
                                                <strong
                                                    class="d-block">{{ $address->name ?: ($address->company ?: 'Address') }}</strong>
                                                <div class="small text-muted">
                                                    {{ $address->line1 }}@if ($address->line2)
                                                        , {{ $address->line2 }}
                                                    @endif,
                                                    {{ $address->city }}@if ($address->state)
                                                        , {{ $address->state }}
                                                    @endif
                                                    @if ($address->postal_code)
                                                        - {{ $address->postal_code }}
                                                    @endif,
                                                    {{ $address->country }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('addresses.create') }}" class="btn btn-outline-dark btn-sm">Add new
                                    address</a>
                            </div>
                        </div>
                    </div>

                    <div class="card zara-card mb-3">
                        <div class="card-header zara-card-header">Billing address</div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="same_as_shipping"
                                    name="same_as_shipping" value="1" checked>
                                <label class="form-check-label" for="same_as_shipping">Same as shipping address</label>
                            </div>

                            <div id="billing-select" style="display:none;">
                                <div class="mb-2 small text-muted">Choose billing address</div>
                                @foreach ($addresses as $address)
                                    <label class="list-group-item zara-list-item">
                                        <input class="form-check-input me-2" type="radio" name="billing_address_id"
                                            value="{{ $address->id }}">
                                        <strong
                                            class="d-block">{{ $address->name ?: ($address->company ?: 'Address') }}</strong>
                                        <div class="small text-muted">
                                            {{ $address->line1 }}@if ($address->line2)
                                                , {{ $address->line2 }}
                                            @endif,
                                            {{ $address->city }}@if ($address->state)
                                                , {{ $address->state }}
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card zara-card mb-3">
                        <div class="card-header zara-card-header">Shipping & payment</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-uppercase small fw-bold">Shipping method</label>
                                <select name="shipping_method" class="form-select">
                                    <option value="standard">Standard (2-5 days)</option>
                                    <option value="express">Express (1-2 days)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase small fw-bold">Payment method</label>
                                <div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pay_cod"
                                            value="cod" checked>
                                        <label class="form-check-label" for="pay_cod">Cash on delivery</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pay_card"
                                            value="card">
                                        <label class="form-check-label" for="pay_card">Pay by card (simulated)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase small fw-bold">Order notes (optional)</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <aside class="col-lg-4">
                    <div class="card zara-card">
                        <div class="card-header zara-card-header">Order summary</div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-3">
                                @foreach ($cart->items as $item)
                                    <li class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <div class="fw-semibold">{{ $item->product->title ?? 'Product' }}</div>
                                            <div class="small text-muted">{{ $item->variant->name ?? '' }}</div>
                                            <div class="small text-muted">Qty: {{ $item->qty }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div>
                                                {{ number_format(($item->unit_price ?? ($item->product->price ?? 0)) * $item->qty, 2) }}
                                                €</div>
                                            <div class="small text-muted">
                                                {{ number_format($item->unit_price ?? ($item->product->price ?? 0), 2) }} € /
                                                unit</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <div class="small text-muted">Subtotal</div>
                                <div>{{ number_format($subtotal, 2) }} €</div>
                            </div>

                            @php
                                $shippingDisplay = $subtotal >= 100 ? 'Free' : '5.00';
                                $taxDisplay = number_format($subtotal * 0.2, 2);
                                $grand = number_format($subtotal + ($subtotal >= 100 ? 0 : 5) + $subtotal * 0.2, 2);
                            @endphp

                            <div class="d-flex justify-content-between">
                                <div class="small text-muted">Shipping</div>
                                <div>{{ $shippingDisplay === 'Free' ? 'Free' : number_format($shippingDisplay, 2) . ' €' }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div class="small text-muted">Tax (20%)</div>
                                <div>{{ $taxDisplay }} €</div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold">
                                <div>Total</div>
                                <div>{{ $grand }} €</div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-dark w-100 text-uppercase fw-bold">Place
                                    order</button>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </form>
    </div>

    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('same_as_shipping');
            const billing = document.getElementById('billing-select');

            function toggleBilling() {
                if (!checkbox) return;
                billing.style.display = checkbox.checked ? 'none' : 'block';
            }

            if (checkbox) {
                checkbox.addEventListener('change', toggleBilling);
                toggleBilling();
            }
        });
    </script>
@endsection
