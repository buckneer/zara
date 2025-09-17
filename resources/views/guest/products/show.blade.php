@extends('layouts.guest')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex gap-8">
        <div class="flex-1">
            @php
            $primary = $product->images->where('is_primary', true)->first() ?? $product->images->first();
            $primaryUrl = $primary ? \Illuminate\Support\Facades\Storage::url($primary->path) : null;
            @endphp

            @if($primaryUrl)
                <img id="product-primary-image" src="{{ $primaryUrl }}" alt="{{ $primary->alt ?? $product->title }}" style="width:100%; height:480px; object-fit:cover;">
            @else
                <div style="width:100%;height:480px;background:#f3f3f3;display:flex;align-items:center;justify-content:center;">
                    <small>No image</small>
                </div>
            @endif

            @if($product->images->count() > 1)
                <div class="mt-4 flex gap-2">
                    @foreach($product->images as $img)
                        <img class="thumbnail" src="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}" style="width:90px;height:90px;object-fit:cover;cursor:pointer;" alt="{{ $img->alt ?? $product->title }}" data-path="{{ \Illuminate\Support\Facades\Storage::url($img->path) }}">
                    @endforeach
                </div>
            @endif
        </div>

        <aside style="width:360px;">
            <h1 class="text-2xl font-bold">{{ $product->title }}</h1>
            <div class="text-gray-600">{{ $product->sku }}</div>

            {{-- price display: JS will update when variant changes --}}
            <div class="text-3xl font-extrabold mt-4">
                <span id="product-price" data-base-price="{{ $product->price }}">{{ number_format($product->price,2) }} €</span>
            </div>

            <div class="mt-4">{!! nl2br(e($product->description)) !!}</div>

            @if($product->categories->count())
                <div class="mt-4">
                    <strong>Categories:</strong>
                    <div class="flex gap-2 mt-2">
                        @foreach($product->categories as $cat)
                            <span class="px-2 py-1 bg-gray-100 rounded">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ADD TO CART FORM --}}
            <div class="mt-6">
                <form action="{{ route('cart.add') }}" method="POST" id="product-add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($product->variants->count())
                        <div class="mb-3">
                            <label class="block mb-1 font-medium">Options</label>
                            <select name="variant_id" id="product-variant-select" class="w-full border p-2" required>
                                <option value="">Choose an option</option>
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" data-price="{{ $variant->price ?? $product->price }}">
                                        {{ $variant->name ?? $variant->sku }} — {{ number_format($variant->price ?? $product->price,2) }} €
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="block mb-1 font-medium">Quantity</label>
                        <input type="number" name="qty" value="1" min="1" class="w-28 border p-2">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Add to cart</button>
                        <a href="{{ route('cart.index') }}" class="px-4 py-2 border rounded">View cart</a>
                    </div>
                </form>
            </div>

            {{-- admin buttons (unchanged) --}}
            @auth
            @if(auth()->user()->isAdmin())
            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-2 border rounded">Edit</a>

                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
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
        // Variant -> update price on product page
        const variantSelect = document.getElementById('product-variant-select');
        const priceEl = document.getElementById('product-price');

        if (variantSelect && priceEl) {
            variantSelect.addEventListener('change', function() {
                const opt = variantSelect.options[variantSelect.selectedIndex];
                const price = opt && opt.dataset.price ? parseFloat(opt.dataset.price) : parseFloat(priceEl.dataset.basePrice || 0);
                priceEl.textContent = price.toFixed(2) + ' €';
            });
        }

        // thumbnails: click to change primary image
        document.querySelectorAll('.thumbnail').forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                const path = thumb.dataset.path;
                const primaryImg = document.getElementById('product-primary-image');
                if (primaryImg && path) primaryImg.src = path;
            });
        });
    });
</script>
@endpush