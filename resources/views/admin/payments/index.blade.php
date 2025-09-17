@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Payments</h1>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold">#</th>
                    <th class="px-4 py-2 text-left font-semibold">Order</th>
                    <th class="px-4 py-2 text-left font-semibold">Method</th>
                    <th class="px-4 py-2 text-left font-semibold">Status</th>
                    <th class="px-4 py-2 text-left font-semibold">Amount</th>
                    <th class="px-4 py-2 text-left font-semibold">Paid At</th>
                    <th class="px-4 py-2 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-4 py-2">{{ $payment->id }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-blue-600 hover:underline">
                                #{{ $payment->order_id }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $payment->method ?? '-' }}</td>
                        <td class="px-4 py-2 capitalize">{{ $payment->status }}</td>
                        <td class="px-4 py-2 font-semibold">{{ number_format($payment->amount ?? 0, 2) }} €</td>
                        <td class="px-4 py-2">{{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
