@extends('layouts.guest')

@section('content')
    <div class="container py-4">
        <div class="row g-4">
            <aside class="col-md-3">
                <div class="card mb-4 border-0">
                    <div class="card-body">
                        <h3 class="h6 text-uppercase mb-3">Filters</h3>

                        <form method="GET" action="{{ route('shop.index', ['filter' => $filter ?? null]) }}">
                            <div class="mb-3">
                                <label class="form-label small">Price min</label>
                                <input type="number" step="0.01" name="price_min" value="{{ request('price_min') }}"
                                    class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Price max</label>
                                <input type="number" step="0.01" name="price_max" value="{{ request('price_max') }}"
                                    class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label small">Sort</label>
                                <select name="sort" class="form-select">
                                    <option value="position" {{ request('sort') == 'position' ? 'selected' : '' }}>Default
                                    </option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price:
                                        Low → High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Price: High → Low</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-dark btn-sm" type="submit">Apply</button>
                                <a href="{{ route('shop.index', ['filter' => $filter ?? null]) }}"
                                    class="btn btn-outline-dark btn-sm">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

               
                @include('shop._cart_sidebar', [
                    'cartItems' => $cartItems ?? [],
                    'subtotal' => $subtotal ?? 0,
                ])
            </aside>

            
            <main class="col-md-9">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 text-uppercase mb-0">
                        @if ($filter === 'man')
                            Men
                        @elseif($filter === 'woman')
                            Women
                        @elseif($filter === 'exclusive')
                            Exclusive
                        @else
                            Shop
                        @endif
                    </h1>
                    <div class="small text-muted">{{ $products->total() }} products</div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @forelse($products as $product)
                        @include('shop._product_card', ['product' => $product])
                    @empty
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-body text-center">
                                    <div class="text-muted">No products found.</div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 zara-pagination">
                    <div class="meta">
                        Showing {{ $products->firstItem() ?? 0 }} — {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>

                    <nav aria-label="Product pagination">
                        <ul class="pagination">
                            {{-- First --}}
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->url(1) }}" aria-label="First">First</a>
                            </li>

                            {{-- Previous --}}
                            <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">Previous</a>
                            </li>

                            @php
                                $start = max(1, $products->currentPage() - 2);
                                $end = min($products->lastPage(), $products->currentPage() + 2);
                            @endphp

                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled"><span class="page-link">…</span></li>
                                @endif
                            @endif

                            @for($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($end < $products->lastPage())
                                @if($end < $products->lastPage() - 1)
                                    <li class="page-item disabled"><span class="page-link">…</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Next --}}
                            <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">Next</a>
                            </li>

                            {{-- Last --}}
                            <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $products->url($products->lastPage()) }}" aria-label="Last">Last</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </main>
        </div>
    </div>
@endsection
