@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Payments</h1>

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
                        <th scope="col">Order</th>
                        <th scope="col">Method</th>
                        <th scope="col">Status</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Paid At</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-decoration-none">
                                    #{{ $payment->order_id }}
                                </a>
                            </td>
                            <td>{{ $payment->method ?? '-' }}</td>
                            <td>
                                @if($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($payment->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($payment->status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @elseif($payment->status === 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @else
                                    <span class="badge bg-light text-dark">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ number_format($payment->amount ?? 0, 2) }} €</td>
                            <td class="text-muted">{{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $payments->links() }}
    </div>
</div>
@endsection
