@extends('layouts.guest')

@section('title', 'Home')

@section('content')
    <main class="landing">

        {{-- HERO SLIDER (Bootstrap carousel) --}}
        <section class="hero mb-4">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/1.jpg') }}" class="d-block w-100 hero-img" alt="Slide 1">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class="hero-title">Minimal pieces. Lasting style.</h1>
                            <p class="lead">Discover the new season — crafted lines, quiet elegance.</p>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="#" class="btn btn-dark btn-hero me-2">Shop women</a>
                                <a href="#" class="btn btn-outline-dark btn-hero">Shop men</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="{{ asset('images/2.jpg') }}" class="d-block w-100 hero-img" alt="Slide 2">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class="hero-title">New arrivals curated</h1>
                            <p class="lead">Refined cuts, thoughtful materials.</p>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="{{ asset('images/3.jpg') }}" class="d-block w-100 hero-img" alt="Slide 3">
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class="hero-title">Crafted for everyday</h1>
                            <p class="lead">Style that lasts beyond the season.</p>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section class="py-6">
            <div class="container">
                <div class="row g-4 align-items-center featured">
                    <div class="col-md-6 order-md-2">
                        <img src="https://picsum.photos/900/900?random=11" class="img-fluid rounded" alt="Featured look">
                    </div>
                    <div class="col-md-6 order-md-1">
                        <h2 class="mb-3" style="font-family:'Playfair Display', serif;">The new edit</h2>
                        <p class="muted-small mb-4">Selected pieces that define the season — pared-back shapes with careful
                            details.</p>

                        <ul class="list-unstyled muted-small mb-4">
                            <li class="py-1">— Tailored outerwear</li>
                            <li class="py-1">— Elevated basics</li>
                            <li class="py-1">— Premium essentials</li>
                        </ul>

                        <a href="#" class="btn btn-dark">Explore collection</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="product-grid py-5 bg-white">
            <div class="container">

                <style>
                    /* minimal styles only */
                    .product-card { position: relative; }
                    .discount-badge { position: absolute; left: 10px; top: 10px; background: #dc3545; color: #fff; font-weight:700; padding:6px 8px; font-size:0.85rem; border-radius:4px; z-index:9999; box-shadow:0 2px 6px rgba(0,0,0,.12); line-height:1; }
                    .product-badge { position: absolute; right: 10px; top: 10px; background:#111; color:#fff; font-weight:700; padding:6px 8px; font-size:0.75rem; border-radius:4px; z-index:9999; text-transform:uppercase; letter-spacing:.04em; }
                    .product-card .image-wrap { overflow: hidden; }
                    .product-card .original-price { text-decoration: line-through; opacity:.7; margin-right:.5rem; }
                    .product-card .discounted-price { color:#000; font-weight:700; }

                    /* portrait-friendly image style: show entire image */
                    .portrait-img {
                        width: 100%;
                        height: 420px;           /* taller for portrait images; adjust if needed */
                        object-fit: contain;     /* show the whole image (letterbox if needed) */
                        object-position: center;
                        display: block;
                        background-color: #fff;  /* neutral letterbox background */
                    }

                    .image-placeholder { height: 420px; }

                    @media (max-width: 576px) {
                        .portrait-img, .image-placeholder { height: 360px; }
                    }

                    /* hero image minimal sizing */
                    .hero-img { height: 520px; object-fit: cover; object-position: center; }
                    @media (max-width: 768px) { .hero-img { height: 360px; } }

                    /* small tweaks for captions */
                    .carousel-caption { bottom: 20% !important; }
                    .hero-title { font-size: 2rem; font-weight:700; }
                </style>

                {{-- Featured products --}}
                @if (!empty($featuredProducts) && $featuredProducts->count())
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <h3 class="mb-0">Featured</h3>
                        <a href="{{ route('products.index') }}" class="muted-small">View all</a>
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
                                <div class="product-card border-0 bg-transparent"
                                     data-discount-percent="{{ $discountPercent }}"
                                     data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                                     data-badge="{{ $product->badge ?? '' }}">
                                    @if ($hasDiscount)
                                        <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
                                    @endif
                                    @if (!empty($product->badge))
                                        <span class="product-badge">{{ $product->badge }}</span>
                                    @endif

                                    <a href="{{ route('products.show', $product) }}" class="d-block text-decoration-none text-dark">
                                        @if ($imgUrl)
                                            <div class="image-wrap">
                                                <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" class="portrait-img">
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light w-100 image-placeholder">
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
                                                @if ($hasDiscount)
                                                    <span class="small text-muted d-block">
                                                        <span class="original-price" data-original="{{ number_format($basePrice, 2, '.', '') }}">
                                                            {{ number_format($basePrice, 2) }} €
                                                        </span>
                                                    </span>

                                                    <span class="product-price discounted-price"
                                                          data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                                                          data-discount-percent="{{ $discountPercent }}">
                                                        {{ $discountedPrice }} €
                                                    </span>
                                                @else
                                                    <span class="product-price"
                                                          data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                                                          data-discount-percent="0">
                                                        {{ number_format($basePrice, 2) }} €
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="text-end">
                                                <a href="{{ route('products.show', $product) }}" class="small text-decoration-none text-muted">Details</a>
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
                                                            <option value="{{ $variant->id }}"
                                                                    data-price="{{ number_format($variant->price ?? $product->price, 2, '.', '') }}"
                                                                    data-discount-percent="{{ isset($variant->discount_percent) ? (float)$variant->discount_percent : $discountPercent }}">
                                                                {{ $variant->name ?? $variant->sku }} —
                                                                {{ number_format($variant->price ?? $product->price, 2) }} €
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>

                                            <div class="col-auto">
                                                <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm" style="width:72px;">
                                            </div>

                                            <div class="col">
                                                <button type="submit" class="btn btn-dark btn-sm w-100" aria-label="Add {{ $product->title }} to cart">Add</button>
                                            </div>

                                            <div class="col-auto">
                                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark btn-sm">View</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Categories --}}
                @foreach ($categories as $category)
                    <div class="d-flex justify-content-between align-items-end mb-4 mt-4">
                        <h3 class="mb-0">{{ $category->name }}</h3>
                        {{-- goes to /shop/{slug or id} --}}
                        <a href="{{ url('/shop/' . ($category->slug ?? $category->id)) }}" class="muted-small">View all</a>
                    </div>

                    <div class="row g-4 mb-5">
                        @forelse($category->products as $product)
                            @php
                                $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                                $imgUrl = $img ? '/storage/' . $img->path : null;
                                $discountPercent = (float) ($product->discount_percent ?? 0);
                                $hasDiscount = $discountPercent > 0;
                                $basePrice = (float) ($product->price ?? 0);
                                $discountedPrice = $hasDiscount ? number_format(max(0, $basePrice * (1 - $discountPercent / 100)), 2, '.', '') : number_format($basePrice, 2, '.', '');
                            @endphp

                            <div class="col-6 col-md-4">
                                <div class="product-card border-0 bg-transparent"
                                     data-discount-percent="{{ $discountPercent }}"
                                     data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                                     data-badge="{{ $product->badge ?? '' }}">
                                    @if ($hasDiscount)
                                        <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
                                    @endif
                                    @if (!empty($product->badge))
                                        <span class="product-badge">{{ $product->badge }}</span>
                                    @endif

                                    <a href="{{ route('products.show', $product) }}" class="d-block text-decoration-none text-dark">
                                        @if ($imgUrl)
                                            <div class="image-wrap">
                                                <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" class="portrait-img">
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light w-100 image-placeholder">
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
                                                @if ($hasDiscount)
                                                    <span class="small text-muted d-block">
                                                        <span class="original-price" data-original="{{ number_format($basePrice, 2, '.', '') }}">
                                                            {{ number_format($basePrice, 2) }} €
                                                        </span>
                                                    </span>

                                                    <span class="product-price discounted-price"
                                                          data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                                                          data-discount-percent="{{ $discountPercent }}">
                                                        {{ $discountedPrice }} €
                                                    </span>
                                                @else
                                                    <span class="product-price"
                                                          data-base-price="{{ number_format($basePrice, 2, '.', '') }}"
                                                          data-discount-percent="0">
                                                        {{ number_format($basePrice, 2) }} €
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="text-end">
                                                <a href="{{ route('products.show', $product) }}" class="small text-decoration-none text-muted">Details</a>
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
                                                            <option value="{{ $variant->id }}"
                                                                    data-price="{{ number_format($variant->price ?? $product->price, 2, '.', '') }}"
                                                                    data-discount-percent="{{ isset($variant->discount_percent) ? (float)$variant->discount_percent : $discountPercent }}">
                                                                {{ $variant->name ?? $variant->sku }} —
                                                                {{ number_format($variant->price ?? $product->price, 2) }} €
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>

                                            <div class="col-auto">
                                                <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm" style="width:72px;">
                                            </div>

                                            <div class="col">
                                                <button type="submit" class="btn btn-dark btn-sm w-100" aria-label="Add {{ $product->title }} to cart">Add</button>
                                            </div>

                                            <div class="col-auto">
                                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark btn-sm">View</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No products in this category yet.</p>
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </section>

        {{-- About the store (brief) --}}
        <section class="py-5 bg-light mb-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="mb-3">About our store</h3>
                        <p class="muted-small mb-3">We curate minimal, high-quality pieces designed for everyday wear.
                            Thoughtful materials, clean cuts and a focus on longevity — that's what drives our collections.</p>
                        <p class="mb-0">Free returns within 14 days · Worldwide shipping · Secure payments</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="https://picsum.photos/600/400?random=31" class="img-fluid rounded" alt="About image">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-6">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="newsletter text-center">
                            <h4 class="mb-2">Join the list</h4>
                            <p class="muted-small mb-4">Get early access to drops and exclusive offers. We send only a few
                                emails a season.</p>

                            <form method="POST" action="#" class="row g-2 justify-content-center align-items-center">
                                @csrf
                                <div class="col-12 col-sm-8">
                                    <label for="newsletter-email" class="visually-hidden">Email</label>
                                    <input id="newsletter-email" name="email" type="email"
                                        class="form-control input-plain" placeholder="Your email address" required>
                                </div>
                                <div class="col-12 col-sm-3 d-grid">
                                    <button class="btn btn-dark btn-plain" type="submit">Subscribe</button>
                                </div>
                            </form>

                            <p class="muted-small mt-3 mb-0" style="font-size:0.9rem;">No spam — unsubscribe anytime.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function parseNum(v) {
                if (v === null || v === undefined) return 0;
                v = String(v).trim().replace(/\u00A0/g, '').replace(',', '.').replace(/[^\d.\-]/g, '');
                const n = parseFloat(v);
                return isNaN(n) ? 0 : n;
            }
            function formatPrice(n) { return (parseFloat(n) || 0).toFixed(2) + ' €'; }

            document.querySelectorAll('.product-card').forEach(function(card) {
                const priceEl = card.querySelector('.product-price');
                const originalEl = card.querySelector('.original-price');
                const select = card.querySelector('.variant-select');

                const cardDiscount = parseNum(card.dataset.discountPercent || (priceEl ? priceEl.dataset.discountPercent : 0));
                const cardBase = parseNum(card.dataset.basePrice || (priceEl ? priceEl.dataset.basePrice : (originalEl ? originalEl.dataset.original : (priceEl ? priceEl.textContent : 0))));

                function applyPrice(base, discount) {
                    const b = parseNum(base || cardBase);
                    const d = (typeof discount !== 'undefined') ? parseNum(discount) : cardDiscount;
                    if (!priceEl) return;
                    priceEl.dataset.basePrice = b.toFixed(2);
                    priceEl.dataset.discountPercent = d.toString();
                    if (d > 0) {
                        const after = Math.max(0, b * (1 - d / 100));
                        if (originalEl) {
                            originalEl.dataset.original = b.toFixed(2);
                            originalEl.textContent = formatPrice(b);
                            originalEl.style.display = '';
                        }
                        priceEl.textContent = formatPrice(after);
                    } else {
                        if (originalEl) originalEl.style.display = 'none';
                        priceEl.textContent = formatPrice(b);
                    }
                }

                applyPrice(cardBase, cardDiscount);

                if (select) {
                    select.addEventListener('change', function() {
                        const opt = select.options[select.selectedIndex];
                        const optPrice = opt ? (opt.dataset.price || '') : '';
                        const optDiscount = opt ? (opt.dataset.discountPercent || '') : '';
                        const useDiscount = optDiscount !== '' ? optDiscount : card.dataset.discountPercent;
                        applyPrice(optPrice || card.dataset.basePrice, useDiscount);
                    });
                }
            });
        });
    </script>
@endsection
