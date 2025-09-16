<div class="bg-dark text-white vh-100 p-3" style="width: 250px;">
    <h4 class="mb-4">Admin Panel</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('admin.products.index') }}">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('admin.orders.index') }}">Orders</a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-white p-0 mt-2">Logout</button>
            </form>
        </li>
    </ul>
</div>
