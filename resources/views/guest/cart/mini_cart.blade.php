@php
    // Try DB for logged in user; for guest use session
    $count = 0;
    $subtotal = 0.0;

    if (auth()->check()) {
        try {
            $cartModel = auth()->user()->cart()->with('items')->first();
            if ($cartModel) {
                $cartItems = $cartModel->items;
                $count = $cartItems->sum('qty');
                $subtotal = $cartModel->total();
            }
        } catch (\Throwable $e) {
            // silently fallback to session
            $count = array_sum(array_column((array) session('cart', []), 'qty') ?: [0]);
        }
    } else {
        $sess = session('cart', []);
        $count = array_sum(
            array_map(function ($it) {
                return $it['qty'] ?? 0;
            }, $sess),
        );
    }
@endphp

<a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h12l-2-7M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
    </svg>
    <span>Cart</span>
    <span class="ml-1 inline-block bg-gray-200 px-2 py-0.5 rounded text-sm">{{ $count }}</span>
</a>
