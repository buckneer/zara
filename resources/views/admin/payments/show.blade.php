@extends('layouts.admin')

@section('title', 'Payment #' . $payment->id)

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Payment #{{ $payment->id }}</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white shadow rounded-lg p-6 space-y-4">
                <div>
                    <h2 class="font-semibold">Order</h2>
                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-blue-600 hover:underline">
                        #{{ $payment->order_id }}
                    </a>
                </div>

                <div>
                    <h2 class="font-semibold">Method</h2>
                    <p>{{ $payment->method ?? '-' }}</p>
                </div>

                <div>
                    <h2 class="font-semibold">Amount</h2>
                    <p class="text-lg font-bold">{{ number_format($payment->amount ?? 0, 2) }} €</p>
                </div>

                <div>
                    <h2 class="font-semibold">Status</h2>
                    <p class="capitalize">{{ $payment->status }}</p>
                </div>

                <div>
                    <h2 class="font-semibold">Paid At</h2>
                    <p>{{ $payment->paid_at?->format('Y-m-d H:i') ?? '-' }}</p>
                </div>

                <div>
                    <h2 class="font-semibold">Meta</h2>
                    <pre class="bg-gray-50 rounded p-2 text-xs">{{ json_encode($payment->meta ?? [], JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

            
            <aside class="bg-white shadow rounded-lg p-6 space-y-4">
                <h2 class="font-semibold">Actions</h2>
                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST"
                    onsubmit="return confirm('Delete this payment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded">Delete Payment</button>
                </form>
            </aside>
        </div>
    </div>
@endsection
