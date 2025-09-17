
@php
    $route = request()->route() ? request()->route()->getName() : '';
    $isActive = fn($names) => collect((array)$names)->contains(fn($n) => str($route)->startsWith($n));
@endphp

<div class="mb-4">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
        <div class="me-2">
            <span class="badge bg-primary text-white">A</span>
        </div>
        <div>
            <div class="fw-bold">{{ config('app.name') }}</div>
            <div class="small text-muted">Administration</div>
        </div>
    </a>
</div>

<nav class="mb-4">
    <div class="sidebar-section mb-2">Manage</div>
    <ul class="nav nav-pills flex-column">


        <li class="nav-item mb-1">
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ $isActive('admin.products') ? 'active' : '' }}">Products</a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ $isActive('admin.categories') ? 'active' : '' }}">Categories</a>
        </li>

        <li class="nav-item mb-1">
            {{-- Orders route may not exist yet, keep as placeholder --}}
            <a href="{{ route('admin.orders.index') ?? '#' }}" class="nav-link {{ $isActive('admin.orders') ? 'active' : '' }}">Orders</a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.payments.index') ?? '#' }}" class="nav-link {{ $isActive('admin.payments') ? 'active' : '' }}">Payments</a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.users.index') ?? '#' }}" class="nav-link {{ $isActive('admin.users') ? 'active' : '' }}">Users</a>
        </li>
    </ul>
</nav>

<div class="sidebar-section mb-2">Quick actions</div>
<div class="d-grid gap-2 mb-3">
    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">New product</a>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-primary">New category</a>
</div>

<hr class="mt-3">

<div class="small text-muted">
    <div>Signed in as</div>
    <div class="fw-semibold">{{ Auth::user()->name ?? Auth::user()->email }}</div>
</div>