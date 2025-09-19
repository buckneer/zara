@php
    $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $primaryUrl = $primary ? '/storage/' . $primary->path : 'https://picsum.photos/600/900?random=1';
    $discountPercent = (float) ($product->discount_percent ?? 0);
    $hasDiscount = $discountPercent > 0;
    $basePrice = (float) ($product->price ?? 0);
    $discountedPrice = $hasDiscount
        ? number_format(max(0, $basePrice * (1 - $discountPercent / 100)), 2, '.', '')
        : number_format($basePrice, 2, '.', '');
    $currency = $product->currency ?? '€';
@endphp

<style>

    .product-card { position: relative; }
    .image-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 360px;              
        overflow: hidden;           
    }

    .image-wrap img {
        max-height: 100%;
        width: auto;
        height: auto;                
        display: block;
        object-fit: contain;        
    }

    /* badge + price visuals */
    .discount-badge {
        position: absolute;
        left: 10px;
        top: 10px;
        background: #dc3545;
        color: #fff;
        font-weight: 700;
        padding: 6px 8px;
        font-size: 0.85rem;
        border-radius: 4px;
        z-index: 9999;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        line-height: 1;
        text-transform: uppercase;
    }
    .price-row .original-price { text-decoration: line-through; opacity:.7; margin-right:.5rem; font-size:.95rem; }
    .price-row .discounted-price { font-weight:700; font-size:1rem; }
</style>

<div class="col">
    <div class="card h-100 border-0 product-card" data-discount-percent="{{ $discountPercent }}" data-base-price="{{ number_format($basePrice, 2, '.', '') }}">
        <a href="{{ route('products.show', $product) }}" class="d-block image-wrap">
            @if ($hasDiscount)
                <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
            @endif

            <img
                src="{{ $primaryUrl }}"
                alt="{{ $product->name ?? $product->title }}"
                class="img-fluid"
                loading="lazy"
            >
        </a>

        <div class="card-body px-0 pt-3">
            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                <h2 class="h6 text-uppercase mb-1">{{ $product->name ?? $product->title }}</h2>
            </a>

            <div class="d-flex justify-content-between align-items-center price-row">
                <div class="fw-bold">
                    @if ($hasDiscount)
                        <span class="small text-muted d-block">
                            <span class="original-price" data-original="{{ number_format($basePrice, 2, '.', '') }}">
                                {{ number_format($basePrice, 2) }} {{ $currency }}
                            </span>
                        </span>
                        <span class="product-price discounted-price"
                              data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                              data-discount-percent="{{ $discountPercent }}">
                            {{ $discountedPrice }} {{ $currency }}
                        </span>
                    @else
                        <span class="product-price" data-base-price="{{ number_format($basePrice, 2, '.', '') }}" data-discount-percent="0">
                            {{ number_format($basePrice, 2) }} {{ $currency }}
                        </span>
                    @endif
                </div>

                <form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
