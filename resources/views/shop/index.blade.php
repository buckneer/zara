@extends('layouts.guest')

@section('content')
<div class="container py-4">
	<div class="row g-4">
		{{-- LEFT: filters + cart --}}
		<aside class="col-md-3">
			<div class="card mb-4 border-0">
				<div class="card-body">
					<h3 class="h6 text-uppercase mb-3">Filters</h3>

					<form method="GET" action="{{ route('shop.index', ['filter' => $filter ?? null]) }}">
						<div class="mb-3">
							<label class="form-label small">Price min</label>
							<input type="number" step="0.01" name="price_min" value="{{ request('price_min') }}" class="form-control" />
						</div>

						<div class="mb-3">
							<label class="form-label small">Price max</label>
							<input type="number" step="0.01" name="price_max" value="{{ request('price_max') }}" class="form-control" />
						</div>

						<div class="mb-3">
							<label class="form-label small">Sort</label>
							<select name="sort" class="form-select">
								<option value="position" {{ request('sort') == 'position' ? 'selected' : '' }}>Default</option>
								<option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
								<option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
							</select>
						</div>

						<div class="d-flex justify-content-between">
							<button class="btn btn-dark btn-sm" type="submit">Apply</button>
							<a href="{{ route('shop.index', ['filter' => $filter ?? null]) }}" class="btn btn-outline-dark btn-sm">Reset</a>
						</div>
					</form>
				</div>
			</div>

			{{-- Cart Mini --}}
			@include('shop._cart_sidebar', ['cartItems' => $cartItems ?? [], 'subtotal' => $subtotal ?? 0])
		</aside>

		{{-- RIGHT: products --}}
		<main class="col-md-9">
			<div class="d-flex align-items-center justify-content-between mb-3">
				<h1 class="h4 text-uppercase mb-0">
					@if ($filter === 'man') Men
					@elseif($filter === 'woman') Women
					@elseif($filter === 'exclusive') Exclusive
					@else Shop @endif
				</h1>
				<div class="small text-muted">{{ $products->total() }} products</div>
			</div>

			<div class="row row-cols-1 row-cols-md-3 g-4">
				@forelse($products as $product)
					@include('shop._product_card', ['product' => $product])
				@empty
				<div class="col-12">
					<div class="card border-0">
						<div class="card-body text-center">
							<div class="text-muted">No products found.</div>
						</div>
					</div>
				</div>
				@endforelse
			</div>

			<div class="mt-4 d-flex justify-content-center">
				{{ $products->links('pagination::bootstrap-5') }}
			</div>
		</main>
	</div>
</div>
@endsection
