@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0 text-uppercase fw-bold" style="letter-spacing:.04em;">Payments</h1>
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
                            <th class="zara-th">Order</th>
                            <th class="zara-th">Method</th>
                            <th class="zara-th">Status</th>
                            <th class="zara-th zara-th-center">Amount</th>
                            <th class="zara-th">Paid At</th>
                            <th class="zara-th zara-th-actions">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="zara-row">
                                <td class="zara-td zara-td-narrow text-muted">{{ $payment->id }}</td>

                                <td class="zara-td">
                                    <a href="{{ route('admin.orders.show', $payment->order_id) }}"
                                        class="fw-semibold text-decoration-none">
                                        #{{ $payment->order_id }}
                                    </a>
                                </td>

                                <td class="zara-td">{{ $payment->method ?? '-' }}</td>

                                <td class="zara-td">
                                    @php $status = $payment->status; @endphp
                                    @if ($status === 'pending')
                                        <span class="badge zara-badge-status zara-status-pending">Pending</span>
                                    @elseif($status === 'completed')
                                        <span class="badge zara-badge-status zara-status-completed">Completed</span>
                                    @elseif($status === 'failed')
                                        <span class="badge zara-badge-status zara-status-cancelled">Failed</span>
                                    @elseif($status === 'refunded')
                                        <span class="badge zara-badge-status zara-status-refunded">Refunded</span>
                                    @else
                                        <span class="badge zara-badge-status">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>

                                <td class="zara-td zara-td-center fw-semibold">
                                    {{ number_format($payment->amount ?? 0, 2) }} €
                                </td>

                                <td class="zara-td text-muted">
                                    {{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}
                                </td>

                                <td class="zara-td zara-td-actions">
                                    <div class="zara-actions">
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                            class="btn btn-sm btn-outline-primary zara-action">View</a>
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
@endsection
