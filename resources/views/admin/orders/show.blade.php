@extends('layouts.admin')

@section('title', 'Order #'.$order->id)

@section('content')
<div class="p-6">
	<h1 class="text-2xl font-bold mb-6">Order #{{ $order->id }}</h1>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
		{{-- Order details --}}
		<div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
			<h2 class="text-lg font-semibold mb-4">Items</h2>
			<table class="w-full text-sm">
				<thead class="border-b">
					<tr>
						<th class="text-left pb-2">Product</th>
						<th class="text-left pb-2">Variant</th>
						<th class="text-left pb-2">Qty</th>
						<th class="text-left pb-2">Unit</th>
						<th class="text-left pb-2">Line total</th>
					</tr>
				</thead>
				<tbody>
					@foreach($order->items as $item)
					<tr class="border-b">
						<td class="py-2">{{ $item->product->title ?? '—' }}</td>
						<td class="py-2">{{ $item->variant->name ?? '-' }}</td>
						<td class="py-2">{{ $item->qty }}</td>
						<td class="py-2">{{ number_format($item->unit_price, 2) }} €</td>
						<td class="py-2 font-semibold">{{ number_format($item->unit_price * $item->qty, 2) }} €</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		{{-- Sidebar --}}
		<aside class="bg-white shadow rounded-lg p-6 space-y-4">
			<div>
				<h2 class="font-semibold">Customer</h2>
				<p>{{ $order->user->name ?? 'Guest' }}</p>
				<p class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</p>
			</div>

			<div>
				<h2 class="font-semibold">Status</h2>
				<form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-2">
					@csrf
					@method('PUT')
					<select name="status" class="w-full border rounded p-2">
						@foreach(['pending','processing','shipped','completed','cancelled','refunded'] as $status)
						<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
						@endforeach
					</select>
					<button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded">Update</button>
				</form>
			</div>

			<div>
				<h2 class="font-semibold">Placed at</h2>
				<p>{{ $order->placed_at?->format('Y-m-d H:i') }}</p>
			</div>

			<div>
				<h2 class="font-semibold">Total</h2>
				<p class="text-lg font-bold">{{ number_format($order->total ?? 0, 2) }} €</p>
			</div>
		</aside>
	</div>
</div>
@endsection