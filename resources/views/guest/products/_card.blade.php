@php
    $img = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $imgUrl = $img ? '/storage/' . $img->path : null;
    $discountPercent = (float) ($product->discount_percent ?? 0);
    $hasDiscount = $discountPercent > 0;
    $badge = $product->badge ?? null;

    $basePrice = (float) ($product->price ?? 0);
    $discountedPrice = $hasDiscount ? number_format(max(0, $basePrice * (1 - $discountPercent / 100)), 2, '.', '') : number_format($basePrice, 2, '.', '');
@endphp

<style>
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
        z-index: 5;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        line-height: 1;
    }
    .product-badge {
        position: absolute;
        right: 10px;
        top: 10px;
        background: #111;
        color: #fff;
        font-weight: 700;
        padding: 6px 8px;
        font-size: 0.75rem;
        border-radius: 4px;
        z-index: 6;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        line-height: 1;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .product-card .image-wrap { position: relative; overflow: hidden; }
    .product-card .original-price { text-decoration: line-through; opacity: .7; margin-right: .5rem; }
    .product-card .discounted-price { color: #000; font-weight: 700; }
</style>

<div class="product-card border-0 bg-transparent" data-discount-percent="{{ $discountPercent }}" data-badge="{{ $badge ?? '' }}">
    <a href="{{ route('products.show', $product) }}" class="d-block text-decoration-none text-dark">
        @if ($imgUrl)
            <div class="image-wrap">
                @if ($hasDiscount)
                    <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
                @endif
                @if ($badge)
                    <span class="product-badge">{{ $badge }}</span>
                @endif
                <img src="{{ $imgUrl }}" alt="{{ $img->alt ?? $product->title }}" class="img-fluid w-100"
                    style="height:320px; object-fit:cover; display:block;">
            </div>
        @else
            <div class="d-flex align-items-center justify-content-center bg-light w-100" style="height:320px; position:relative;">
                @if ($hasDiscount)
                    <span class="discount-badge">{{ rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') }}% OFF</span>
                @endif
                @if ($badge)
                    <span class="product-badge">{{ $badge }}</span>
                @endif
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
  function parseNum(v) {
    if (v === null || v === undefined) return 0;
    v = String(v).trim().replace(/\u00A0/g, ''); // remove NBSP
    // replace comma with dot if present (e.g., "10,00")
    v = v.replace(',', '.');
    // remove currency symbols and spaces
    v = v.replace(/[^\d.\-]/g, '');
    const n = parseFloat(v);
    return isNaN(n) ? 0 : n;
  }

  function formatPrice(n) {
    return (parseFloat(n) || 0).toFixed(2) + ' €';
  }

  document.querySelectorAll('.product-card').forEach(function(card, idx) {
    const priceEl = card.querySelector('.product-price');
    let originalEl = card.querySelector('.original-price');
    const select = card.querySelector('.variant-select');

    // determine card-level discount (from card or priceEl)
    const cardDiscountRaw = card.dataset.discountPercent ?? (priceEl ? priceEl.dataset.discountPercent : '');
    const cardDiscount = parseNum(cardDiscountRaw);

    // determine base price (prefer data-base-price on priceEl, then originalEl.dataset.original, then priceEl.textContent)
    let baseRaw = '';
    if (priceEl && priceEl.dataset.basePrice) {
      baseRaw = priceEl.dataset.basePrice;
    } else if (originalEl && originalEl.dataset.original) {
      baseRaw = originalEl.dataset.original;
    } else if (priceEl) {
      baseRaw = priceEl.textContent;
    }

    let basePrice = parseNum(baseRaw);

    // If originalEl doesn't exist but we have a discount, create it (so we can show crossed price)
    if (!originalEl && cardDiscount > 0) {
      originalEl = document.createElement('span');
      originalEl.className = 'original-price';
      originalEl.dataset.original = basePrice.toFixed(2);
      originalEl.textContent = formatPrice(basePrice);
      const wrapper = document.createElement('span');
      wrapper.className = 'small text-muted d-block';
      wrapper.appendChild(originalEl);
      if (priceEl) priceEl.parentNode.insertBefore(wrapper, priceEl);
    }

    // central function to apply price + discount
    function applyPrice(newBase, discountToUse) {
      const b = parseNum(newBase || basePrice);
      const d = (typeof discountToUse !== 'undefined') ? parseNum(discountToUse) : cardDiscount;

      // store base price on element for future use
      if (priceEl) priceEl.dataset.basePrice = b.toFixed(2);

      if (d > 0) {
        const after = Math.max(0, b * (1 - d / 100));
        if (originalEl) {
          originalEl.dataset.original = b.toFixed(2);
          originalEl.textContent = formatPrice(b);
          originalEl.style.display = '';
        }
        if (priceEl) {
          priceEl.textContent = formatPrice(after);
          priceEl.dataset.discountPercent = d.toString();
        }
      } else {
        if (originalEl) originalEl.style.display = 'none';
        if (priceEl) {
          priceEl.textContent = formatPrice(b);
          priceEl.dataset.discountPercent = '0';
        }
      }
    }

    // initial application (if priceEl exists)
    if (priceEl) {
      // If priceEl has dataset.discountPercent prefer it, otherwise use card-level
      const discountFromPriceEl = priceEl.dataset.discountPercent ?? cardDiscount;
      // If priceEl has dataset.basePrice use it, else fallback to computed basePrice
      const baseFromPriceEl = priceEl.dataset.basePrice ?? basePrice;
      applyPrice(baseFromPriceEl, discountFromPriceEl);
    } else {
      // No priceEl found — debug
      console.warn('No .product-price found for product-card index', idx, card);
    }

    // When variant changes — check option.dataset.price and option.dataset.discountPercent
    if (select && priceEl) {
      select.addEventListener('change', function() {
        const opt = select.options[select.selectedIndex];
        const optPrice = opt ? (opt.dataset.price ?? '') : '';
        const optDiscount = opt ? (opt.dataset.discountPercent ?? '') : '';

        if (!optPrice && priceEl.dataset.basePrice) {
          // fallback to base price on priceEl
          applyPrice(priceEl.dataset.basePrice, optDiscount !== '' ? optDiscount : card.dataset.discountPercent);
          return;
        }

        // apply variant price and variant discount (or fallback to card discount)
        applyPrice(optPrice || priceEl.dataset.basePrice, optDiscount !== '' ? optDiscount : card.dataset.discountPercent);
      });
    }

    // debug logs — remove after testing
    console.debug('product-card', idx, {
      cardDiscountRaw, cardDiscount, basePrice,
      priceElData: priceEl ? {...priceEl.dataset} : null,
      originalElData: originalEl ? {...originalEl.dataset} : null
    });
  });
});
</script>
@endpush

