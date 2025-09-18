{{-- resources/views/partials/sidebar.blade.php (black & white version) --}}
@php
    $route = request()->route() ? request()->route()->getName() : '';
    $isActive = fn($names) => collect((array) $names)->contains(fn($n) => str($route)->startsWith($n));
@endphp

<div class="d-flex flex-column p-3 bg-white border rounded min-vh-100">
    {{-- Brand --}}
    <div class="d-flex align-items-center mb-3">
        <div class="bg-black text-white rounded-circle d-flex align-items-center justify-content-center me-2"
            style="width:40px; height:40px;">
            <strong>A</strong>
        </div>

        <div>
            <div class="fw-bold text-black">{{ config('app.name') }}</div>
            <div class="small text-muted">Administration</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="mb-3">
        <div class="small text-uppercase text-muted mb-2">Manage</div>

        <ul class="nav nav-pills flex-column">

            <li class="nav-item mb-1">
                <a href="{{ route('admin.products.index') }}"
                    class="nav-link {{ $isActive('admin.products') ? 'active bg-black text-white' : 'text-dark' }}"
                    @if ($isActive('admin.products')) aria-current="page" @endif>
                    <i class="bi bi-box-seam me-2"></i> Products
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.categories.index') }}"
                    class="nav-link {{ $isActive('admin.categories') ? 'active bg-black text-white' : 'text-dark' }}"
                    @if ($isActive('admin.categories')) aria-current="page" @endif>
                    <i class="bi bi-tags me-2"></i> Categories
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.orders.index') }}"
                    class="nav-link {{ $isActive('admin.orders') ? 'active bg-black text-white' : 'text-dark' }}"
                    @if ($isActive('admin.orders')) aria-current="page" @endif>
                    <i class="bi bi-receipt me-2"></i> Orders
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.payments.index') }}"
                    class="nav-link {{ $isActive('admin.payments') ? 'active bg-black text-white' : 'text-dark' }}"
                    @if ($isActive('admin.payments')) aria-current="page" @endif>
                    <i class="bi bi-credit-card me-2"></i> Payments
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('admin.users.index') }}"
                    class="nav-link {{ $isActive('admin.users') ? 'active bg-black text-white' : 'text-dark' }}"
                    @if ($isActive('admin.users')) aria-current="page" @endif>
                    <i class="bi bi-people me-2"></i> Users
                </a>
            </li>
        </ul>
    </nav>

    {{-- Quick actions --}}
    <div class="mb-3">
        <div class="small text-uppercase text-muted mb-2">Quick actions</div>

        <div class="d-grid gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-dark text-white">New product</a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-dark">New category</a>
        </div>
    </div>

    <hr class="my-3">

    {{-- User info --}}
    <div class="mt-auto">
        <div class="small text-muted">Signed in as</div>
        <div class="fw-semibold text-black">{{ Auth::user()->name ?? Auth::user()->email }}</div>
    </div>
</div>
