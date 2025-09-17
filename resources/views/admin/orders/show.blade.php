@extends('layouts.admin')

@section('title', 'Order #'.$order->id)

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Order #{{ $order->id }}</h1>

    <div class="row g-4">
        {{-- Order details (main) --}}
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="h6 mb-3">Items</h2>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th class="text-end">Line total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->title ?? '—' }}</td>
                                        <td>{{ $item->variant->name ?? '-' }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }} €</td>
                                        <td class="text-end fw-semibold">{{ number_format($item->unit_price * $item->qty, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="col-12 col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="h6">Customer</h5>
                    <p class="mb-0">{{ $order->user->name ?? 'Guest' }}</p>
                    <p class="small text-muted mb-0">{{ $order->user->email ?? '' }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="h6">Status</h5>

                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-2">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <select name="status" class="form-select">
                                @foreach(['pending','processing','shipped','completed','cancelled','refunded'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="h6">Placed at</h5>
                    <p class="mb-0">{{ $order->placed_at?->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="h6">Total</h5>
                    <p class="fs-5 fw-bold mb-0">{{ number_format($order->total ?? 0, 2) }} €</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
