<nav class="navbar navbar-expand-lg site-navbar bg-white shadow-sm">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') ?? url('/') }}">
            <span class="brand">Z A R A</span>
            <small class="ms-2 text-muted small-brand">style</small>
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            {{-- Center nav links --}}
            <ul class="navbar-nav mx-auto nav-main align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route("shop.index") }}">New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index', ['filter' => 'woman']) }}">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop.index', ['filter' => 'man']) }}">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route("shop.index") }}">Editorial</a>
                </li>
            </ul>

            {{-- Right icons / auth --}}
            <ul class="navbar-nav ms-auto align-items-center nav-icons">

                {{-- Search (small form) --}}
                <li class="nav-item me-2 d-none d-md-block">
                    <form class="d-flex align-items-center" role="search" method="GET" action="#">
                        <input name="q" class="form-control form-control-sm search-input" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>

                {{-- Wishlist / favorites (icon) --}}
                <li class="nav-item me-2">
                    <a class="nav-link icon-btn" href="#" title="Favorites" aria-label="Favorites">
                        <i class="bi bi-heart"></i>
                    </a>
                </li>

                @php
                use App\Models\Cart;

                $cartCount = 0;

                if (auth()->check()) {
                // count total qty in DB cart for the user (safe/fallbacks)
                try {
                $cart = Cart::where('user_id', auth()->id())->with('items')->first();
                if ($cart) {
                // sum of item quantities
                $cartCount = $cart->items->sum('qty');
                } else {
                $cartCount = 0;
                }
                } catch (\Throwable $e) {
                $cartCount = 0;
                }
                } else {
                // guest: session stores an array under 'cart' => [ 'product:variant' => [...], ... ]
                $sessionCart = session('cart', []);
                if (is_array($sessionCart) && !empty($sessionCart)) {
                // If you want the number of distinct lines use count($sessionCart)
                // If you want total items (qty sum) use array_sum of 'qty' values:
                $cartCount = array_sum(array_map(fn($line) => (int)($line['qty'] ?? 0), $sessionCart));
                } else {
                $cartCount = 0;
                }
                }
                @endphp

                <li class="nav-item me-2 position-relative">
                    <a class="nav-link icon-btn d-flex align-items-center" href="{{ route('cart.index') }}" aria-label="Cart" aria-haspopup="false">
                        {{-- Inline SVG cart icon (accessible) --}}
                        <svg class="cart-svg" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <path d="M6 6h13l-1.4 8.4a1 1 0 0 1-.98.76H9.4a1 1 0 0 1-.98-.76L6 6z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                            <circle cx="10" cy="20" r="1.2" fill="currentColor" />
                            <circle cx="18" cy="20" r="1.2" fill="currentColor" />
                        </svg>

                        {{-- Visible count for screen readers --}}
                        <span class="visually-hidden">Cart ({{ $cartCount ?? 0 }})</span>

                        {{-- Numeric badge (only shown when >0) --}}
                        @if(!empty($cartCount))
                        <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>

                {{-- Guest links --}}
                @guest
                <li class="nav-item me-2">
                    <a class="nav-link btn-link-small" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-dark btn-sm rounded-pill px-3" href="{{ route('register') }}">Sign up</a>
                </li>
                @endguest

                {{-- Authenticated user --}}
                @auth
                @if(method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
                <li class="nav-item me-2 d-none d-lg-block">
                    <a class="nav-link" href="{{ route("admin.products.index") }}">Admin</a>
                </li>
                @else
                <li class="nav-item me-2 d-none d-lg-block">
                    <a class="nav-link" href="{{ route("orders.index") }}">Orders</a>
                </li>
                @endif

                {{-- User dropdown --}}
                <li class="nav-item dropdown ms-2">
                    <a id="navbarUser" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- avatar (initials fallback) --}}
                        <span class="avatar me-2">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                        <span class="d-none d-lg-inline">{{ auth()->user()->full_name ?? auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUser">
                        <li><a class="dropdown-item" href="{{ route("account.profile") }}">Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route("orders.index") }}">Orders</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="px-3">
                                @csrf
                                <button type="submit" class="btn btn-link dropdown-item text-start p-0">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>