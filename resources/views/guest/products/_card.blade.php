{{-- resources/views/products/_card.blade.php --}}
@php
    $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $imgUrl = $img ? \Illuminate\Support\Facades\Storage::url($img->path) : null;
@endphp

<div class="product-card border rounded overflow-hidden p-0">
    <a href="{{ route('products.show', $product) }}" class="block">
        @if($imgUrl)
        <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" style="width:100%; height:220px; object-fit:cover;">
        @else
        <div style="width:100%;height:220px;background:#f3f3f3;display:flex;align-items:center;justify-content:center;">
            <small>No image</small>
        </div>
        @endif
    </a>

    <div style="padding:.75rem;">
        <h3 class="text-lg font-semibold">{{ $product->title }}</h3>
        <div class="text-sm text-gray-600">{{ $product->sku }}</div>

        {{-- price (if variants exist we show base price; JS updates it if the user picks a variant) --}}
        <div class="mt-2 font-bold">
            <span class="product-price" data-base-price="{{ $product->price }}">{{ number_format($product->price,2) }} €</span>
        </div>

        {{-- Add to cart form --}}
        <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            @if($product->variants && $product->variants->count())
            <div class="mb-2">
                <select name="variant_id" class="w-full border p-2 variant-select">
                    <option value="">Choose option</option>
                    @foreach($product->variants as $variant)
                    <option value="{{ $variant->id }}"
                        data-price="{{ $variant->price ?? $product->price }}">
                        {{ $variant->name ?? $variant->sku }} — {{ number_format($variant->price ?? $product->price,2) }} €
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex items-center gap-2">
                <input type="number" name="qty" value="1" min="1" class="w-20 border p-2">
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">
                    Add to cart
                </button>

                {{-- link to product page for more details --}}
                <a href="{{ route('products.show', $product) }}" class="ml-2 px-3 py-2 border rounded text-sm">View</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // update price on variant select change (for card component)
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