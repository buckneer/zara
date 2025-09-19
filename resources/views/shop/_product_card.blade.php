@php
    // Product image selection
    $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $primaryUrl = $primary ? '/storage/' . $primary->path : 'https://picsum.photos/600/900?random=1';

    // Raw discount value from model / DB
    $discountRaw = (float) ($product->discount_percent ?? 0);

    // Normalize: if value is a fraction (0 < x <= 1) treat as fraction (0.1 => 10%)
    // otherwise treat as percent (10 => 10%). Then clamp to [0,100].
    if ($discountRaw > 0 && $discountRaw <= 1) {
        $discountPercent = $discountRaw * 100;
    } else {
        $discountPercent = $discountRaw;
    }
    $discountPercent = max(0, min(100, $discountPercent));

    // Display-friendly integer for the badge
    $displayDiscount = (int) round($discountPercent);
    $hasDiscount = $discountPercent > 0;

    // Prices
    $basePrice = (float) ($product->price ?? 0.0);
    $basePriceFormatted = number_format($basePrice, 2, '.', '');

    $discountedPriceFloat = $hasDiscount
        ? max(0, $basePrice * (1 - $discountPercent / 100))
        : $basePrice;

    $discountedPriceFormatted = number_format($discountedPriceFloat, 2, '.', '');

    $currency = $product->currency ?? '€';
@endphp

<div class="col">
    <div class="zara-card card h-100" data-discount-percent="{{ $discountPercent }}">
        <div class="card-inner">
            <a href="{{ route('products.show', $product) }}" class="image-wrap d-block" aria-label="{{ $product->name ?? $product->title }}">
                @if ($hasDiscount)
                    <span class="discount-badge" aria-hidden="true">
                        {{ $displayDiscount }}%
                    </span>
                @endif

                <img
                    src="{{ $primaryUrl }}"
                    alt="{{ $product->name ?? $product->title }}"
                    loading="lazy"
                >
            </a>

            <div class="card-body px-0 pt-2">
                <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-reset">
                    <h2 class="product-title mb-2">{{ $product->name ?? $product->title }}</h2>
                </a>

                <div class="price-row d-flex justify-content-between align-items-center">
                    <div>
                        @if ($hasDiscount)
                            <div class="d-flex align-items-baseline gap-2">
                                <span class="original-price" data-original="{{ $basePriceFormatted }}">
                                    {{ $basePriceFormatted }} {{ $currency }}
                                </span>

                                <span class="price product-price"
                                      data-base-price="{{ $basePriceFormatted }}"
                                      data-discount-percent="{{ $discountPercent }}">
                                    {{ $discountedPriceFormatted }} {{ $currency }}
                                </span>
                            </div>
                        @else
                            <span class="price product-price" data-base-price="{{ $basePriceFormatted }}">
                                {{ $basePriceFormatted }} {{ $currency }}
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-add" aria-label="Add {{ $product->name ?? $product->title }}">
                            Add
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Optional inline styles for the badge / original price. Move to your stylesheet as needed. --}}
<style>
    .discount-badge {
        position: absolute;
        left: 12px;
        top: 12px;
        background: #e53935;
        color: #fff;
        padding: 6px 8px;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 4px;
        z-index: 10;
        line-height: 1;
    }
    .original-price {
        text-decoration: line-through;
        opacity: 0.7;
        font-size: 0.95rem;
    }
    .price.product-price {
        font-weight: 700;
        font-size: 1rem;
        margin-left: 6px;
    }
    .image-wrap { position: relative; display: block; }
</style>
