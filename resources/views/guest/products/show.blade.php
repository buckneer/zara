@extends('layouts.guest')

@section('content')
    <div class="container py-5">
        <div class="row gx-4 gy-4">
            
            <div class="col-12 col-lg-8">
                @php
                    $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    $primaryUrl = $primary ? '/storage/' . $primary->path : null;
                @endphp

                @if ($primaryUrl)
                    <img id="product-primary-image" src="{{ $primaryUrl }}" alt="{{ $primary->alt ?? $product->title }}"
                        class="img-fluid w-100 mb-3" style="height:560px; object-fit:cover; display:block;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center mb-3" style="height:560px;">
                        <small class="text-muted">No image</small>
                    </div>
                @endif

                @if ($product->images->count() > 1)
                    <div class="d-flex flex-row flex-wrap gap-2">
                        @foreach ($product->images as $img)
                            @php $thumbUrl = '/storage/' . $img->path; @endphp
                            <img src="{{ $thumbUrl }}" alt="{{ $img->alt ?? $product->title }}" class="img-fluid"
                                style="width:96px; height:96px; object-fit:cover; cursor:pointer; display:block;"
                                data-path="{{ $thumbUrl }}" role="button" aria-label="View image">
                        @endforeach
                    </div>
                @endif
            </div>

            
            <aside class="col-12 col-lg-4">
                <h1 class="h4 text-uppercase mb-2" style="letter-spacing:0.04em;">{{ $product->title }}</h1>

                @if ($product->sku)
                    <div class="text-muted small mb-3">{{ $product->sku }}</div>
                @endif

                <div class="mb-3">
                    <div class="h3 fw-bold mb-0">
                        <span id="product-price"
                            data-base-price="{{ $product->price }}">{{ number_format($product->price, 2) }} €</span>
                    </div>
                </div>

                @if (!empty($product->description))
                    <div class="mb-3 text-muted">{!! nl2br(e($product->description)) !!}</div>
                @endif

                @if ($product->categories->count())
                    <div class="mb-3">
                        <strong class="small text-uppercase">Categories</strong>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @foreach ($product->categories as $cat)
                                <span class="small text-muted px-2 py-1"
                                    style="background:transparent;">{{ $cat->name }}</span>
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
                                <select name="variant_id" id="product-variant-select" class="form-select form-select-sm"
                                    required>
                                    <option value="">{{ __('Choose an option') }}</option>
                                    @foreach ($product->variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            data-price="{{ $variant->price ?? $product->price }}">
                                            {{ $variant->name ?? $variant->sku }} —
                                            {{ number_format($variant->price ?? $product->price, 2) }} €
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3 d-flex align-items-center gap-2">
                            <div>
                                <label class="form-label small mb-1">Quantity</label>
                                <input type="number" name="qty" value="1" min="1"
                                    class="form-control form-control-sm" style="width:96px;">
                            </div>

                            <div class="flex-fill d-flex gap-2">
                                <button type="submit" class="btn btn-dark btn-sm flex-grow-1 align-self-end">Add to
                                    cart</button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-dark btn-sm align-self-end">View
                                    cart</a>
                            </div>
                        </div>
                    </form>
                </div>

                
                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="btn btn-outline-secondary btn-sm">Edit</a>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                onsubmit="return confirm('Delete product?');">
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
            // Variant -> update price
            const variantSelect = document.getElementById('product-variant-select');
            const priceEl = document.getElementById('product-price');

            if (variantSelect && priceEl) {
                variantSelect.addEventListener('change', function() {
                    const opt = variantSelect.options[variantSelect.selectedIndex];
                    const price = opt && opt.dataset.price ? parseFloat(opt.dataset.price) : parseFloat(
                        priceEl.dataset.basePrice || 0);
                    priceEl.textContent = price.toFixed(2) + ' €';
                });
            }

            // thumbnails: click to change primary image
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
