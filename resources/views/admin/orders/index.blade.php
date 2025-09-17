@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Orders</h1>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Status</th>
                        <th scope="col">Placed at</th>
                        <th scope="col">Total</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge bg-info text-dark">Processing</span>
                                @elseif($order->status === 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge bg-secondary">Cancelled</span>
                                @elseif($order->status === 'refunded')
                                    <span class="badge bg-dark">Refunded</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted">
                                    {{ $order->placed_at?->format('Y-m-d H:i') }}
                                </div>
                            </td>
                            <td class="fw-semibold">{{ number_format($order->total ?? 0, 2) }} €</td>
                            <td class="text-end">
                                <div class="d-inline-flex">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary me-2">View</a>

                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
