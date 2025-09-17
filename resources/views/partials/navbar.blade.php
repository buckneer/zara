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
                    <a class="nav-link" href="#">New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Editorial</a>
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

                {{-- Cart (keeps your fallback logic) --}}
                @php
                $cartCount = 0;
                    if (auth()->check()) {
                        if (method_exists(auth()->user(), 'cartItems')) {
                            try { $cartCount = auth()->user()->cartItems()->count(); } catch (\Throwable $e) { $cartCount = 0; }
                        } elseif (method_exists(auth()->user(), 'cart')) {
                            try { $cartCount = auth()->user()->cart()->count(); } catch (\Throwable $e) { $cartCount = 0; }
                        } else {
                            $cartCount = session('cart.items') ? count(session('cart.items')) : 0;
                        }
                    } else {
                        $cartCount = session('cart.items') ? count(session('cart.items')) : 0;
                    }
                @endphp

                <li class="nav-item me-2 position-relative">
                    <a class="nav-link icon-btn" href="{{ route("cart.index") }}" aria-label="Cart">
                        <i class="bi bi-cart"></i>
                        @if($cartCount > 0)
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
                    <a class="nav-link"href="{{ route("orders.index") }}">Orders</a>
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