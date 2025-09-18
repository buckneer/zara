@php

    $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $primaryUrl = $primary ? '/storage/' . $primary->path : null;
@endphp

<div class="col">
	<div class="card h-100 border-0">
		<a href="{{ route('products.show', $product) }}" class="d-block">
			<img src="{{ $primaryUrl }}" alt="{{ $product->name }}" class="img-fluid w-100" style="object-fit:cover;">
		</a>

		<div class="card-body px-0 pt-3">
			<a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
				<h2 class="h6 text-uppercase mb-1">{{ $product->name }}</h2>
			</a>
			<div class="d-flex justify-content-between align-items-center">
				<div class="fw-bold">{{ number_format($product->price, 2) }} {{ $product->currency ?? '€' }}</div>
				<form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
					@csrf
					<button type="submit" class="btn btn-sm btn-outline-dark">Add</button>
				</form>
			</div>
		</div>
	</div>
</div>
