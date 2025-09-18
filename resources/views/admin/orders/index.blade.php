@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0 text-uppercase fw-bold" style="letter-spacing:.04em;">Orders</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 p-0 mb-4">
            <div class="table-responsive">
                <table class="table zara-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="zara-th zara-th-narrow">#</th>
                            <th class="zara-th">Customer</th>
                            <th class="zara-th">Status</th>
                            <th class="zara-th">Placed at</th>
                            <th class="zara-th zara-th-center">Total</th>
                            <th class="zara-th zara-th-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr class="zara-row">
                                <td class="zara-td zara-td-narrow text-muted">{{ $order->id }}</td>

                                <td class="zara-td">
                                    <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                                    <div class="small text-muted">{{ $order->user->email ?? '-' }}</div>
                                </td>

                                <td class="zara-td">
                                    @php
                                        $status = $order->status;
                                        // normalize to safe string
                                        $statusLabel = ucfirst($status ?? 'unknown');
                                    @endphp

                                    @if ($status === 'pending')
                                        <span class="badge zara-badge-status zara-status-pending">Pending</span>
                                    @elseif($status === 'processing')
                                        <span class="badge zara-badge-status zara-status-processing">Processing</span>
                                    @elseif($status === 'shipped')
                                        <span class="badge zara-badge-status zara-status-shipped">Shipped</span>
                                    @elseif($status === 'completed')
                                        <span class="badge zara-badge-status zara-status-completed">Completed</span>
                                    @elseif($status === 'cancelled')
                                        <span class="badge zara-badge-status zara-status-cancelled">Cancelled</span>
                                    @elseif($status === 'refunded')
                                        <span class="badge zara-badge-status zara-status-refunded">Refunded</span>
                                    @else
                                        <span class="badge zara-badge-status">{{ $statusLabel }}</span>
                                    @endif
                                </td>

                                <td class="zara-td">
                                    <div class="small text-muted">{{ $order->placed_at?->format('Y-m-d H:i') }}</div>
                                </td>

                                <td class="zara-td zara-td-center fw-semibold">{{ number_format($order->total ?? 0, 2) }} €
                                </td>

                                <td class="zara-td zara-td-actions">
                                    <div class="zara-actions">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary zara-action">View</a>

                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger zara-action">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
