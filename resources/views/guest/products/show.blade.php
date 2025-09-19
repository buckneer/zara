@extends('layouts.guest')

@section('content')
    <div class="container py-5 product-page">
        <div class="row gx-4 gy-4">
            <div class="col-12 col-lg-8">
                @php
                    $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    $primaryUrl = $primary ? '/storage/' . $primary->path : null;
                    $discountPercent = (float) ($product->discount_percent ?? 0);
                    $hasDiscount = $discountPercent > 0;
                    $basePrice = (float) ($product->price ?? 0);
                    $discountedPrice = $hasDiscount ? number_format(max(0, $basePrice * (1 - $discountPercent / 100)), 2, '.', '') : number_format($basePrice, 2, '.', '');
                @endphp

                @if ($primaryUrl)
                    <div class="gallery">
                        <div class="primary">
                            @if ($hasDiscount)
                                <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
                            @endif
                            <img id="product-primary-image" src="{{ $primaryUrl }}" alt="{{ $primary->alt ?? $product->title }}" loading="lazy">
                        </div>
                    </div>
                @else
                    <div class="gallery">
                        <div class="primary d-flex align-items-center justify-content-center" style="background:#f5f5f5;">
                            @if ($hasDiscount)
                                <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
                            @endif
                            <small class="text-muted">No image</small>
                        </div>
                    </div>
                @endif

                @if ($product->images->count() > 1)
                    <div class="thumbs mt-3">
                        @foreach ($product->images as $img)
                            @php $thumbUrl = '/storage/' . $img->path; @endphp
                            <img src="{{ $thumbUrl }}" alt="{{ $img->alt ?? $product->title }}" class="thumb-img"
                                 data-path="{{ $thumbUrl }}" role="button" aria-label="View image">
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="col-12 col-lg-4 meta">
                <h1 class="text-uppercase">{{ $product->title }}</h1>

                @if ($product->sku)
                    <div class="sku small mb-3">{{ $product->sku }}</div>
                @endif

                <div class="mb-3 price-wrap">
                    <div id="product-price" class="price" data-base-price="{{ number_format($basePrice, 2, '.', '') }}" data-discount-percent="{{ number_format($discountPercent, 2, '.', '') }}">
                        @if ($hasDiscount)
                            <del class="text-muted">{{ number_format($basePrice, 2) }} €</del>
                            <span class="fw-bold">{{ $discountedPrice }} €</span>
                        @else
                            {{ number_format($basePrice, 2) }} €
                        @endif
                    </div>
                </div>

                @if (!empty($product->description))
                    <div class="desc">{!! nl2br(e($product->description)) !!}</div>
                @endif

                @if ($product->categories->count())
                    <div class="mb-3">
                        <strong class="small text-uppercase">Categories</strong>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach ($product->categories as $cat)
                                <span class="small text-muted px-2 py-1">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <form action="{{ route('cart.add') }}" method="POST" id="product-add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @if ($product->variants->count())
                            <div class="mb-3">
                                <label for="product-variant-select" class="form-label small mb-1">Options</label>
                                <select name="variant_id" id="product-variant-select" class="form-select form-select-sm" required>
                                    <option value="">{{ __('Choose an option') }}</option>
                                    @foreach ($product->variants as $variant)
                                        <option value="{{ $variant->id }}" data-price="{{ number_format($variant->price ?? $product->price, 2, '.', '') }}">
                                            {{ $variant->name ?? $variant->sku }} — {{ number_format($variant->price ?? $product->price, 2) }} €
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3 d-flex align-items-center gap-2">
                            <div>
                                <label class="form-label small mb-1">Quantity</label>
                                <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm" style="width:96px;">
                            </div>

                            <div class="flex-fill d-flex gap-2">
                                <button type="submit" class="btn-zara">Add to cart</button>
                                <a href="{{ route('cart.index') }}" class="btn-zara-outline">View cart</a>
                            </div>
                        </div>
                    </form>
                </div>

                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="d-flex gap-2 admin-actions">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-zara-outline">Edit</a>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantSelect = document.getElementById('product-variant-select');
            const priceEl = document.getElementById('product-price');

            function parseNumber(v) {
                const n = parseFloat(String(v || '').replace(',', '.'));
                return isNaN(n) ? 0 : n;
            }

            function formatPrice(n) {
                return (parseFloat(n) || 0).toFixed(2) + ' €';
            }

            function renderPrice(base, discount) {
                const baseNum = parseNumber(base);
                const disc = parseNumber(discount);
                if (disc > 0) {
                    const after = Math.max(0, baseNum * (1 - disc / 100));
                    priceEl.innerHTML = '<del class="text-muted" style="margin-right:.5rem;">' + formatPrice(baseNum) + '</del><span class="fw-bold">' + formatPrice(after) + '</span>';
                    priceEl.dataset.basePrice = baseNum.toFixed(2);
                    priceEl.dataset.discountPercent = disc.toFixed(2);
                } else {
                    priceEl.textContent = formatPrice(baseNum);
                    priceEl.dataset.basePrice = baseNum.toFixed(2);
                    priceEl.dataset.discountPercent = '0';
                }
            }

            if (priceEl) {
                const initialBase = priceEl.dataset.basePrice || priceEl.textContent;
                const initialDisc = priceEl.dataset.discountPercent || '0';
                renderPrice(initialBase, initialDisc);
            }

            if (variantSelect && priceEl) {
                variantSelect.addEventListener('change', function() {
                    const opt = variantSelect.options[variantSelect.selectedIndex];
                    const newBase = opt && opt.dataset.price ? opt.dataset.price : priceEl.dataset.basePrice || 0;
                    const disc = priceEl.dataset.discountPercent || 0;
                    renderPrice(newBase, disc);
                });
            }

            document.querySelectorAll('[data-path]').forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    const path = thumb.dataset.path;
                    const primaryImg = document.getElementById('product-primary-image');
                    if (primaryImg && path) primaryImg.src = path;
                });
            });
        });
    </script>
@endpush
