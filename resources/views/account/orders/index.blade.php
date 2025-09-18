
@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="mb-0">My orders</h4>
                <small class="text-muted">Past purchases and order status</small>
            </div>

            <div class="text-end">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Continue shopping</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($orders->count() === 0)
            <div class="card">
                <div class="card-body">
                    <p class="mb-2">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Shop now</a>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th>Order</th>
                                    <th>Placed</th>
                                    <th>Items</th>
                                    <th class="text-end">Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th style="width:180px;"></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="fw-semibold">#{{ $order->order_number }}</a>
                                            <div class="small text-muted mt-1">{{ $order->user->email ?? '' }}</div>
                                        </td>

                                        <td>
                                            <div class="small text-muted">
                                                {{ optional($order->placed_at)->toDayDateTimeString() ?? '-' }}</div>
                                        </td>

                                        <td>
                                            <div>{{ $order->items->count() }} &nbsp;<small
                                                    class="text-muted">line{{ $order->items->count() === 1 ? '' : 's' }}</small>
                                            </div>
                                        </td>

                                        <td class="text-end">
                                            <div class="fw-semibold">{{ number_format($order->grand_total ?? 0, 2) }} €
                                            </div>
                                            <div class="small text-muted">incl. tax</div>
                                        </td>

                                        <td>
                                            <span
                                                class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'refunded' ? 'secondary' : 'warning') }} text-capitalize">
                                                {{ $order->payment_status ?? 'unpaid' }}
                                            </span>
                                        </td>

                                        <td>
                                            @php
                                                $status = $order->status ?? 'pending';
                                                $color =
                                                    $status === 'pending'
                                                        ? 'warning'
                                                        : ($status === 'processing'
                                                            ? 'info'
                                                            : ($status === 'shipped'
                                                                ? 'primary'
                                                                : ($status === 'completed'
                                                                    ? 'success'
                                                                    : 'secondary')));
                                            @endphp
                                            <span
                                                class="badge bg-{{ $color }} text-capitalize">{{ $status }}</span>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of
                            {{ $orders->total() }} orders</div>
                        <div>
                            @if (method_exists($orders, 'links'))
                                {{ $orders->withQueryString()->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
