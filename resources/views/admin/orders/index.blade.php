@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="admin-ui">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0 admin-page-title text-uppercase fw-bold">Orders</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="card admin-card border-0 p-0 mb-4">
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead class="admin-thead">
                        <tr>
                            <th class="admin-th admin-th-narrow">#</th>
                            <th class="admin-th">Customer</th>
                            <th class="admin-th">Status</th>
                            <th class="admin-th">Placed at</th>
                            <th class="admin-th admin-th-center">Total</th>
                            <th class="admin-th admin-th-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="admin-td admin-td-narrow text-muted">{{ $order->id }}</td>

                                <td class="admin-td">
                                    <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                                    <div class="small text-muted">{{ $order->user->email ?? '-' }}</div>
                                </td>

                                <td class="admin-td">
                                    @php
                                        $status = $order->status;
                                        $statusLabel = ucfirst($status ?? 'unknown');
                                    @endphp

                                    @if ($status === 'pending')
                                        <span class="badge admin-badge-status admin-status-pending">Pending</span>
                                    @elseif($status === 'processing')
                                        <span class="badge admin-badge-status admin-status-processing">Processing</span>
                                    @elseif($status === 'shipped')
                                        <span class="badge admin-badge-status admin-status-shipped">Shipped</span>
                                    @elseif($status === 'completed')
                                        <span class="badge admin-badge-status admin-status-completed">Completed</span>
                                    @elseif($status === 'cancelled')
                                        <span class="badge admin-badge-status admin-status-cancelled">Cancelled</span>
                                    @elseif($status === 'refunded')
                                        <span class="badge admin-badge-status admin-status-refunded">Refunded</span>
                                    @else
                                        <span class="badge admin-badge-status">{{ $statusLabel }}</span>
                                    @endif
                                </td>

                                <td class="admin-td">
                                    <div class="small text-muted">{{ $order->placed_at?->format('Y-m-d H:i') }}</div>
                                </td>

                                <td class="admin-td admin-td-center fw-semibold">{{ number_format($order->total ?? 0, 2) }} €</td>

                                <td class="admin-td admin-td-actions text-end">
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary admin-action">View</a>

                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger admin-action">Delete</button>
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
</div>
@endsection
