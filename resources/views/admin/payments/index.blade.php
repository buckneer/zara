@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="admin-ui">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0 admin-page-title text-uppercase fw-bold">Payments</h1>
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
                            <th class="admin-th">Order</th>
                            <th class="admin-th">Method</th>
                            <th class="admin-th">Status</th>
                            <th class="admin-th admin-th-center">Amount</th>
                            <th class="admin-th">Paid At</th>
                            <th class="admin-th admin-th-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="admin-td admin-td-narrow text-muted">{{ $payment->id }}</td>

                                <td class="admin-td">
                                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="fw-semibold text-decoration-none">
                                        #{{ $payment->order_id }}
                                    </a>
                                </td>

                                <td class="admin-td">{{ $payment->method ?? '-' }}</td>

                                <td class="admin-td">
                                    @php $status = $payment->status; @endphp
                                    @if ($status === 'pending')
                                        <span class="badge admin-badge-status admin-status-pending">Pending</span>
                                    @elseif($status === 'completed')
                                        <span class="badge admin-badge-status admin-status-completed">Completed</span>
                                    @elseif($status === 'failed')
                                        <span class="badge admin-badge-status admin-status-cancelled">Failed</span>
                                    @elseif($status === 'refunded')
                                        <span class="badge admin-badge-status admin-status-refunded">Refunded</span>
                                    @else
                                        <span class="badge admin-badge-status">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>

                                <td class="admin-td admin-td-center fw-semibold">{{ number_format($payment->amount ?? 0, 2) }} €</td>

                                <td class="admin-td text-muted">{{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}</td>

                                <td class="admin-td admin-td-actions text-end">
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary admin-action">View</a>
                                    </div>
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
</div>
@endsection
