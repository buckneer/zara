{{-- resources/views/partials/product-tile.blade.php --}}
@php
    // $product must be passed in
    $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $imgUrl = $img ? '/storage/' . $img->path : "https://picsum.photos/600/800?random=" . ($product->id ?? rand(1,9999));
@endphp

<article class="product-card" aria-labelledby="product-{{ $product->id }}-title">
    {{-- optional tag if product has new/featured flag --}}
    @if(!empty($product->is_new) || !empty($product->featured))
        <span class="tag">@if(!empty($product->featured)) Featured @else New @endif</span>
    @endif

    <a href="{{ route('products.show', $product) }}" class="d-block text-decoration-none text-dark">
        <img src="{{ $imgUrl }}" class="card-img-top product-img" alt="{{ $img->alt ?? $product->title }}" loading="lazy" style="display:block; width:100%; height:460px; object-fit:cover;">
    </a>

    <div class="card-body px-0 pt-2 pb-0">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="small text-uppercase muted-small">{{ $product->brand ?? 'Essential' }}</div>
                <h3 id="product-{{ $product->id }}-title" class="fw-medium" style="font-size:1rem; margin:2px 0;">{{ $product->title }}</h3>
            </div>
            <div class="price">€{{ number_format($product->price, 0) }}</div>
        </div>
    </div>
</article>
