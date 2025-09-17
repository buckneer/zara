{{-- resources/views/account/orders/show.blade.php --}}
@extends('layouts.guest')

@section('content')
<div class="container py-4">
	<div class="d-flex align-items-start justify-content-between mb-3">
		<div>
			<h4 class="mb-0">Order #{{ $order->order_number }}</h4>
			<small class="text-muted">Placed: {{ optional($order->placed_at)->toDayDateTimeString() ?? '-' }}</small>
		</div>

		<div class="text-end">
			<span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'completed' ? 'success' : 'secondary'))) }} text-capitalize">
				{{ $order->status }}
			</span>
			<div class="mt-2">
				<button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">Print</button>
				{{-- If you have a route to download invoice, replace '#' with route('orders.invoice', $order) --}}
				<a href="#" class="btn btn-outline-primary btn-sm">Download invoice</a>
			</div>
		</div>
	</div>

	{{-- Top summary cards --}}
	<div class="row mb-4 g-3">
		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="card-title">Payment</h6>
					<p class="mb-1"><strong class="me-2 text-capitalize">{{ $order->payment_status ?? 'unpaid' }}</strong></p>
					@if($order->payments && $order->payments->count())
					<small class="text-muted">Last payment: {{ optional($order->payments->last())->paid_at ? optional($order->payments->last())->paid_at->toDayDateTimeString() : '-' }}</small>
					<ul class="list-unstyled mt-2 mb-0 small">
						@foreach($order->payments as $p)
						<li>
							<strong class="me-1 text-muted">{{ ucfirst($p->method ?? 'payment') }}:</strong>
							{{ number_format($p->amount, 2) }} €
							<span class="text-muted">• {{ $p->status }}</span>
						</li>
						@endforeach
					</ul>
					@else
					<div class="small text-muted">No payments recorded.</div>
					@endif
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="card-title">Totals</h6>
					<div class="d-flex justify-content-between">
						<div class="text-muted small">Subtotal</div>
						<div>{{ number_format($order->subtotal ?? 0, 2) }} €</div>
					</div>
					<div class="d-flex justify-content-between">
						<div class="text-muted small">Shipping</div>
						<div>{{ number_format($order->shipping_total ?? 0, 2) }} €</div>
					</div>
					<div class="d-flex justify-content-between">
						<div class="text-muted small">Tax</div>
						<div>{{ number_format($order->tax_total ?? 0, 2) }} €</div>
					</div>
					@if($order->discount_total && $order->discount_total > 0)
					<div class="d-flex justify-content-between">
						<div class="text-muted small">Discount</div>
						<div>-{{ number_format($order->discount_total, 2) }} €</div>
					</div>
					@endif
					<hr>
					<div class="d-flex justify-content-between fw-bold">
						<div>Total</div>
						<div>{{ number_format($order->grand_total ?? 0, 2) }} €</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card h-100">
				<div class="card-body">
					<h6 class="card-title">Shipping</h6>
					<div class="small text-muted mb-2">{{ $order->shipping_method ?? '—' }}</div>
					@if($order->shipping_tracking)
					<div class="mb-1"><strong>Tracking:</strong> {{ $order->shipping_tracking }}</div>
					{{-- Optionally link to carrier tracking if you store carrier URL in meta --}}
					@if(is_array($order->meta) && !empty($order->meta['tracking_url']))
					<a href="{{ $order->meta['tracking_url'] }}" target="_blank" class="small">Track shipment</a>
					@endif
					@else
					<div class="small text-muted">No tracking information.</div>
					@endif
				</div>
			</div>
		</div>
	</div>

	{{-- Addresses --}}
	<div class="row mb-4 g-3">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">Shipping address</div>
				<div class="card-body">
					@if($order->shippingAddress)
					<strong>{{ $order->shippingAddress->name ?? '' }}</strong><br>
					{{ $order->shippingAddress->line1 }}@if($order->shippingAddress->line2), {{ $order->shippingAddress->line2 }}@endif<br>
					{{ $order->shippingAddress->city }}@if($order->shippingAddress->state), {{ $order->shippingAddress->state }}@endif<br>
					{{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->country }}<br>
					@if($order->shippingAddress->phone)<small class="text-muted">Phone: {{ $order->shippingAddress->phone }}</small>@endif
					@else
					<div class="text-muted small">No shipping address saved.</div>
					@endif
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-header">Billing address</div>
				<div class="card-body">
					@if($order->billingAddress)
					<strong>{{ $order->billingAddress->name ?? '' }}</strong><br>
					{{ $order->billingAddress->line1 }}@if($order->billingAddress->line2), {{ $order->billingAddress->line2 }}@endif<br>
					{{ $order->billingAddress->city }}@if($order->billingAddress->state), {{ $order->billingAddress->state }}@endif<br>
					{{ $order->billingAddress->postal_code }} {{ $order->billingAddress->country }}<br>
					@if($order->billingAddress->phone)<small class="text-muted">Phone: {{ $order->billingAddress->phone }}</small>@endif
					@else
					<div class="text-muted small">No billing address saved.</div>
					@endif
				</div>
			</div>
		</div>
	</div>

	{{-- Items --}}
	<div class="card mb-4">
		<div class="card-header">Items</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table mb-0 align-middle">
					<thead class="table-light small text-muted">
						<tr>
							<th>Product</th>
							<th style="width:100px;">Qty</th>
							<th style="width:140px;">Unit</th>
							<th style="width:140px;">Total</th>
						</tr>
					</thead>
					<tbody>
						@foreach($order->items as $item)
						<tr>
							<td>
								<div class="d-flex">
									{{-- Try display product image if available --}}
									@php
									$img = optional($item->product)->images ? optional($item->product->images->where('is_primary', true)->first()) ?? optional($item->product->images->first()) : null;
									$imgUrl = $img ? \Illuminate\Support\Facades\Storage::url($img->path) : null;
									@endphp

									@if($imgUrl)
									<img src="{{ $imgUrl }}" alt="{{ $img->alt ?? ($item->title ?? '') }}" class="rounded me-3" style="width:64px;height:64px;object-fit:cover;">
									@endif

									<div>
										<div class="fw-semibold">{{ $item->title }}</div>
										@if($item->variant)
										<div class="small text-muted">{{ $item->variant->name ?? '' }}</div>
										@endif
										@if($item->meta && is_array($item->meta))
										<div class="small text-muted mt-1">@foreach($item->meta as $k => $v) <strong>{{ $k }}:</strong> {{ $v }} @if(!$loop->last) • @endif @endforeach</div>
										@endif
									</div>
								</div>
							</td>

							<td>{{ $item->quantity }}</td>
							<td>{{ number_format($item->unit_price, 2) }} €</td>
							<td>{{ number_format($item->total_price, 2) }} €</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	{{-- Order notes / meta --}}
	@if(is_array($order->meta) && !empty(array_filter($order->meta)))
	<div class="card mb-4">
		<div class="card-header">Order notes</div>
		<div class="card-body">
			<pre class="mb-0 small">{{ json_encode($order->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
		</div>
	</div>
	@endif

	{{-- Footer actions --}}
	<div class="d-flex gap-2">
		<a href="{{ route('account.profile') }}" class="btn btn-outline-secondary">Back to account</a>
		<a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue shopping</a>
		{{-- Example reorder action (implement route if desired) --}}
		<form action="{{ route('orders.reorder', $order->id) ?? '#' }}" method="POST" class="d-inline">
			@csrf
			<button class="btn btn-success">Reorder</button>
		</form>
	</div>
</div>
@endsection