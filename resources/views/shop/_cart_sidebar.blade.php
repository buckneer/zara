@php
    use Illuminate\Support\Str;

    // prefer explicit view vars, then session fallback
    $items = $items ?? $cartItems ?? session('cart.items') ?? session('cart') ?? [];
    // normalize: if session('cart') is an object with 'items' key or array of lines
    if (is_array($items) && isset($items['items']) && is_array($items['items'])) {
        $items = $items['items'];
    }

    // compute subtotal if not passed
    $subtotal = $subtotal ?? array_reduce($items, function($carry, $it) {
        $line = $it['line_total'] ?? (($it['unit_price'] ?? 0) * ($it['qty'] ?? 0));
        return $carry + $line;
    }, 0);
@endphp

<div class="card border-0">
    <div class="card-body">
        <h5 class="h6 mb-3 text-uppercase">Order summary</h5>

        @if(count($items) > 0)
            <ul class="list-unstyled mb-0">
                @foreach($items as $item)
                    @php
                        $prod = $item['product'] ?? null;
                        $variant = $item['variant'] ?? null;

                         $img = optional($prod)->images->where('is_primary', true)->first() ?? optional($prod)->images->first();
                                            $imgUrl = $img ? asset('storage/' . $img->path) : null;

                        $title = $prod->title ?? ($item['name'] ?? 'Product');
                        $unit = $item['unit_price'] ?? 0;
                        $qty = $item['qty'] ?? 1;
                        $lineTotal = $item['line_total'] ?? ($unit * $qty);
                    @endphp

                    <li class="d-flex align-items-center py-2 border-bottom">
                        <a href="{{ $prod ? route('products.show', $prod) : '#' }}" class="me-3 d-block" style="width:80px;flex:0 0 80px;">
                            <img src="{{ $imgUrl }}"
                                 alt="{{ $img->alt ?? $title }}"
                                 class="img-fluid rounded"
                                 style="width:80px;height:80px;object-fit:cover;">
                        </a>

                        <div class="flex-grow-1">
                            <a href="{{ $prod ? route('products.show', $prod) : '#' }}" class="text-dark fw-semibold d-block mb-1">
                                {{ $title }}
                                @if($variant)
                                    <small class="d-block text-muted mt-1">{{ $variant->name ?? $variant->sku }}</small>
                                @endif
                            </a>

                            <div class="small text-muted">
                                {{ number_format($unit, 2) }} € × {{ $qty }}
                            </div>
                        </div>

                        <div class="text-end ms-3 fw-semibold">
                            {{ number_format($lineTotal, 2) }} €
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-uppercase small text-muted">Subtotal</div>
                <div class="h5 mb-0 fw-bold">{{ number_format($subtotal, 2) }} €</div>
            </div>
        @else
            <div class="small text-muted">Your cart is empty.</div>
        @endif
    </div>
</div>
