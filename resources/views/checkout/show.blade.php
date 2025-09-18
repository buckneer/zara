{{-- resources/views/account/orders.show.blade.php --}}
@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <h3>Order confirmation</h3>
        <p class="text-muted">Thanks! Your order has been placed.</p>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Order #{{ $order->order_number }}</h6>
                        <div>Status: <strong class="text-capitalize">{{ $order->status }}</strong></div>
                        <div>Placed: {{ $order->placed_at->toDayDateTimeString() }}</div>
                    </div>

                    <div class="col-md-6 text-end">
                        <div>Total: <strong>{{ number_format($order->grand_total, 2) }} €</strong></div>
                        <div>Payment: <strong class="text-capitalize">{{ $order->payment_status }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Shipping address</div>
                    <div class="card-body">
                        <strong>{{ $order->shippingAddress->name ?? '' }}</strong><br>
                        {{ $order->shippingAddress->line1 }}@if ($order->shippingAddress->line2)
                            , {{ $order->shippingAddress->line2 }}
                        @endif
                        <br>
                        {{ $order->shippingAddress->city }}@if ($order->shippingAddress->state)
                            , {{ $order->shippingAddress->state }}
                        @endif
                        <br>
                        {{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->country }}<br>
                        @if ($order->shippingAddress->phone)
                            Phone: {{ $order->shippingAddress->phone }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Billing address</div>
                    <div class="card-body">
                        <strong>{{ $order->billingAddress->name ?? '' }}</strong><br>
                        {{ $order->billingAddress->line1 }}@if ($order->billingAddress->line2)
                            , {{ $order->billingAddress->line2 }}
                        @endif
                        <br>
                        {{ $order->billingAddress->city }}@if ($order->billingAddress->state)
                            , {{ $order->billingAddress->state }}
                        @endif
                        <br>
                        {{ $order->billingAddress->postal_code }} {{ $order->billingAddress->country }}<br>
                        @if ($order->billingAddress->phone)
                            Phone: {{ $order->billingAddress->phone }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Items</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th width="80">Qty</th>
                            <th width="120">Unit</th>
                            <th width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                <td>{{ number_format($item->total_price, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-end">
                    <div style="width:320px;">
                        <div class="d-flex justify-content-between">
                            <div>Subtotal</div>
                            <div>{{ number_format($order->subtotal, 2) }} €</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Shipping</div>
                            <div>{{ number_format($order->shipping_total, 2) }} €</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Tax</div>
                            <div>{{ number_format($order->tax_total, 2) }} €</div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <div>Total</div>
                            <div>{{ number_format($order->grand_total, 2) }} €</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue shopping</a>
        </div>
    </div>
@endsection
