@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="p-6">
	<h1 class="text-2xl font-bold mb-6">Orders</h1>

	@if(session('success'))
	<div class="mb-4 text-green-600">{{ session('success') }}</div>
	@endif

	<div class="bg-white shadow rounded-lg overflow-hidden">
		<table class="min-w-full divide-y divide-gray-200 text-sm">
			<thead class="bg-gray-50">
				<tr>
					<th class="px-4 py-2 text-left font-semibold">#</th>
					<th class="px-4 py-2 text-left font-semibold">Customer</th>
					<th class="px-4 py-2 text-left font-semibold">Status</th>
					<th class="px-4 py-2 text-left font-semibold">Placed At</th>
					<th class="px-4 py-2 text-left font-semibold">Total</th>
					<th class="px-4 py-2 text-right font-semibold">Actions</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-100">
				@forelse($orders as $order)
				<tr>
					<td class="px-4 py-2">{{ $order->id }}</td>
					<td class="px-4 py-2">{{ $order->user->name ?? 'Guest' }}</td>
					<td class="px-4 py-2 capitalize">{{ $order->status }}</td>
					<td class="px-4 py-2">{{ $order->placed_at?->format('Y-m-d H:i') }}</td>
					<td class="px-4 py-2 font-semibold">{{ number_format($order->total ?? 0, 2) }} €</td>
					<td class="px-4 py-2 text-right space-x-2">
						<a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">View</a>
						<form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Delete this order?');">
							@csrf
							@method('DELETE')
							<button type="submit" class="text-red-600 hover:underline">Delete</button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="6" class="px-4 py-6 text-center text-gray-500">No orders found.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<div class="mt-4">
		{{ $orders->links() }}
	</div>
</div>
@endsection