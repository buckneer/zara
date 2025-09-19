@extends('layouts.guest')

@section('title', 'Home')

@section('content')
<main class="landing">

    {{-- HERO --}}
    <section class="hero">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/1.jpg') }}" class="d-block w-100 hero-img" alt="Slide 1">
                    <div class="hero-caption">
                        <h1 class="hero-title">Minimal pieces. Lasting style.</h1>
                        <p class="hero-sub">Discover the new season — crafted lines, quiet elegance.</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('shop.index', ['filter'=>'woman']) }}" class="btn-hero">Shop women</a>
                            <a href="{{ route('shop.index', ['filter'=>'man']) }}" class="btn-hero btn-hero-outline">Shop men</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/2.jpg') }}" class="d-block w-100 hero-img" alt="Slide 2">
                    <div class="hero-caption">
                        <h1 class="hero-title">New arrivals curated</h1>
                        <p class="hero-sub">Refined cuts, thoughtful materials.</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="{{ asset('images/3.jpg') }}" class="d-block w-100 hero-img" alt="Slide 3">
                    <div class="hero-caption">
                        <h1 class="hero-title">Crafted for everyday</h1>
                        <p class="hero-sub">Style that lasts beyond the season.</p>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6 L9 12 L15 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6 L15 12 L9 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    {{-- FEATURED EDITORIAL --}}
    <section class="py-6">
        <div class="container">
            <div class="row g-4 align-items-center featured">
                <div class="col-md-6 order-md-2">
                    <img src="https://picsum.photos/900/900?random=11" class="img-fluid rounded" alt="Featured look">
                </div>
                <div class="col-md-6 order-md-1">
                    <h2 class="mb-3" style="font-family:'Playfair Display', serif;">The new edit</h2>
                    <p class="muted-small mb-4">Selected pieces that define the season — pared-back shapes with careful details.</p>

                    <ul class="list-unstyled muted-small mb-4">
                        <li class="py-1">— Tailored outerwear</li>
                        <li class="py-1">— Elevated basics</li>
                        <li class="py-1">— Premium essentials</li>
                    </ul>

                    <a href="{{ route('products.index') }}" class="btn-hero">Explore collection</a>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCT GRID --}}
    <section class="product-grid py-5">
        <div class="container">
            @if (!empty($featuredProducts) && $featuredProducts->count())
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h3 class="mb-0">Featured</h3>
                    <a href="{{ route('shop.index') }}" class="muted-small">View all</a>
                </div>

                <div class="row g-4 mb-5">
                    @foreach ($featuredProducts as $product)
                        @php
                            $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                            $imgUrl = $img ? '/storage/' . $img->path : null;
                            $discountPercent = (float) ($product->discount_percent ?? 0);
                            $hasDiscount = $discountPercent > 0;
                            $basePrice = (float) ($product->price ?? 0);
                            $discountedPrice = $hasDiscount ? number_format(max(0, $basePrice * (1 - $discountPercent / 100)), 2, '.', '') : number_format($basePrice, 2, '.', '');
                        @endphp

                        <div class="col-6 col-md-4">
                            <div class="zara-card p-2 product-card" data-discount-percent="{{ $discountPercent }}" data-base-price="{{ number_format($basePrice,2,'.','') }}" data-badge="{{ $product->badge ?? '' }}">
                                @if ($hasDiscount)
                                    <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 0, '.', ''), '0'), '.') }}% OFF</span>
                                @endif
                                @if (!empty($product->badge))
                                    <span class="product-badge">{{ $product->badge }}</span>
                                @endif

                                <a href="{{ route('products.show', $product) }}" class="d-block image-wrap">
                                    @if ($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" class="portrait-img" loading="lazy">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light image-placeholder" style="height:420px;">
                                            <small class="text-muted">No image</small>
                                        </div>
                                    @endif
                                </a>

                                <div class="meta">
                                    <h3>{{ $product->title }}</h3>
                                    @if ($product->sku)
                                        <div class="sku small">{{ $product->sku }}</div>
                                    @endif

                                    <div class="price-row">
                                        <div>
                                            @if ($hasDiscount)
                                                <span class="original-price">{{ number_format($basePrice,2) }} €</span>
                                                <span class="price">{{ $discountedPrice }} €</span>
                                            @else
                                                <span class="price">{{ number_format($basePrice,2) }} €</span>
                                            @endif
                                        </div>

                                        <div class="d-flex flex-column align-items-end">
                                            <a href="{{ route('products.show', $product) }}" class="small text-decoration-none muted-small mb-2">Details</a>

                                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2 align-items-center">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm" style="width:72px;">
                                                <button class="btn-add" type="submit">Add</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Categories (short preview) --}}
            @foreach ($categories as $category)
                <div class="d-flex justify-content-between align-items-end mb-4 mt-4">
                    <h3 class="mb-0">{{ $category->name }}</h3>
                    <a href="{{ url('/shop/' . ($category->slug ?? $category->id)) }}" class="muted-small">View all</a>
                </div>

                <div class="row g-4 mb-5">
                    @forelse($category->products->take(6) as $product)
                        @php
                            $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                            $imgUrl = $img ? '/storage/' . $img->path : null;
                            $discountPercent = (float) ($product->discount_percent ?? 0);
                            $hasDiscount = $discountPercent > 0;
                            $basePrice = (float) ($product->price ?? 0);
                            $discountedPrice = $hasDiscount ? number_format(max(0, $basePrice * (1 - $discountPercent / 100)), 2, '.', '') : number_format($basePrice, 2, '.', '');
                        @endphp

                        <div class="col-6 col-md-4">
                            <div class="zara-card product-card" data-discount-percent="{{ $discountPercent }}" data-base-price="{{ number_format($basePrice,2,'.','') }}">
                                @if ($hasDiscount)
                                    <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 0, '.', ''), '0'), '.') }}% OFF</span>
                                @endif

                                <a href="{{ route('products.show', $product) }}" class="d-block image-wrap">
                                    @if ($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" class="portrait-img" loading="lazy">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light image-placeholder" style="height:420px;">
                                            <small class="text-muted">No image</small>
                                        </div>
                                    @endif
                                </a>

                                <div class="meta">
                                    <h3>{{ $product->title }}</h3>
                                    <div class="price-row">
                                        <div>
                                            @if ($hasDiscount)
                                                <span class="original-price">{{ number_format($basePrice,2) }} €</span>
                                                <span class="price">{{ $discountedPrice }} €</span>
                                            @else
                                                <span class="price">{{ number_format($basePrice,2) }} €</span>
                                            @endif
                                        </div>

                                        <div>
                                            <a href="{{ route('products.show', $product) }}" class="small text-decoration-none muted-small">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-muted">No products in this category yet.</p></div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>

    {{-- ABOUT --}}
    <section class="py-5 bg-light about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-3">About our store</h3>
                    <p class="muted-small mb-3">We curate minimal, high-quality pieces designed for everyday wear. Thoughtful materials, clean cuts and a focus on longevity.</p>
                    <p class="mb-0">Free returns within 14 days · Worldwide shipping · Secure payments</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://picsum.photos/600/400?random=31" class="img-fluid rounded" alt="About image">
                </div>
            </div>
        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section class="py-6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center newsletter">
                    <h4 class="mb-2">Join the list</h4>
                    <p class="muted-small mb-4">Get early access to drops and exclusive offers. We send only a few emails a season.</p>

                    <form method="POST" action="#" class="row g-2 justify-content-center align-items-center">
                        @csrf
                        <div class="col-12 col-sm-8">
                            <label for="newsletter-email" class="visually-hidden">Email</label>
                            <input id="newsletter-email" name="email" type="email" class="form-control input-plain" placeholder="Your email address" required>
                        </div>
                        <div class="col-12 col-sm-3 d-grid">
                            <button class="btn-hero" type="submit">Subscribe</button>
                        </div>
                    </form>

                    <p class="muted-small mt-3 mb-0" style="font-size:.9rem;">No spam — unsubscribe anytime.</p>
                </div>
            </div>
        </div>
    </section>

</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // pause carousel on hover (Bootstrap Carousel instance)
    const hero = document.getElementById('heroCarousel');
    if (hero && typeof bootstrap !== 'undefined') {
        hero.addEventListener('mouseenter', () => bootstrap.Carousel.getInstance(hero)?.pause());
        hero.addEventListener('mouseleave', () => bootstrap.Carousel.getInstance(hero)?.cycle());
    }

    // lazy swap small thumbs (if any)
    document.querySelectorAll('[data-thumb-src]').forEach(el => {
        el.addEventListener('click', () => {
            const src = el.dataset.thumbSrc;
            const img = document.querySelector('.hero-img');
            if (img && src) img.src = src;
        });
    });
});
</script>
@endpush

@endsection
