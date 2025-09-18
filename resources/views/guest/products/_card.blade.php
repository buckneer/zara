
@php
    $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $imgUrl = $img ? '/storage/' . $img->path : null;
@endphp

<div class="product-card border-0 bg-transparent">
    <a href="{{ route('products.show', $product) }}" class="d-block text-decoration-none text-dark">
        @if ($imgUrl)
            
            <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" class="img-fluid w-100"
                style="height:320px; object-fit:cover; display:block;">
        @else
            <div class="d-flex align-items-center justify-content-center bg-light w-100" style="height:320px;">
                <small class="text-muted">No image</small>
            </div>
        @endif
    </a>

    <div class="pt-3 pb-2 px-0">
        <h3 class="h6 mb-1 text-uppercase" style="letter-spacing:0.04em;">{{ $product->title }}</h3>
        @if ($product->sku)
            <div class="text-muted small mb-2">{{ $product->sku }}</div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="fw-bold h5 mb-0">
                <span class="product-price"
                    data-base-price="{{ $product->price }}">{{ number_format($product->price, 2) }} €</span>
            </div>

            
            <div class="text-end">
                <a href="{{ route('products.show', $product) }}"
                    class="small text-decoration-none text-muted">Details</a>
            </div>
        </div>

        
        <form action="{{ route('cart.add') }}" method="POST" class="row gx-2 gy-2 align-items-center">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="col-12">
                @if ($product->variants && $product->variants->count())
                    <select name="variant_id" class="form-select form-select-sm variant-select">
                        <option value="">{{ __('Choose option') }}</option>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-price="{{ $variant->price ?? $product->price }}">
                                {{ $variant->name ?? $variant->sku }} —
                                {{ number_format($variant->price ?? $product->price, 2) }} €
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-auto">
                <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm"
                    style="width:72px;">
            </div>

            <div class="col">
                <button type="submit" class="btn btn-dark btn-sm w-100"
                    aria-label="Add {{ $product->title }} to cart">
                    Add
                </button>
            </div>

            <div class="col-auto">
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark btn-sm">
                    View
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-card').forEach(function(card) {
                const select = card.querySelector('.variant-select');
                const priceEl = card.querySelector('.product-price');

                if (select && priceEl) {
                    select.addEventListener('change', function() {
                        const opt = select.options[select.selectedIndex];
                        const newPrice = opt ? opt.dataset.price : priceEl.dataset.basePrice;
                        if (newPrice !== undefined) {
                            priceEl.textContent = (parseFloat(newPrice) || 0).toFixed(2) + ' €';
                        }
                    });
                }
            });
        });
    </script>
@endpush
