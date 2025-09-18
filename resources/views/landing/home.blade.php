@extends('layouts.guest')

@section('title', 'Home')

@section('content')
    <main class="landing">

        {{-- HERO --}}
        <section class="hero d-flex align-items-center text-center mb-4">
            <div class="hero-overlay" aria-hidden="true"></div>

            <div class="container position-relative text-dark">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="brand text-uppercase mb-3">Z A R A - style</div>
                        <h1 class="hero-title">Minimal pieces. Lasting style.</h1>
                        <p class="lead mb-4">Discover the new season — crafted lines, quiet elegance.</p>

                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="#" class="btn btn-dark btn-hero me-2">Shop women</a>
                            <a href="#" class="btn btn-outline-dark btn-hero">Shop men</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Featured products / editorial --}}
        <section class="py-6">
            <div class="container">
                <div class="row g-4 align-items-center featured">
                    <div class="col-md-6 order-md-2">
                        <img src="https://picsum.photos/900/900?random=11" class="img-fluid rounded" alt="Featured look">
                    </div>
                    <div class="col-md-6 order-md-1">
                        <h2 class="mb-3" style="font-family:'Playfair Display', serif;">The new edit</h2>
                        <p class="muted-small mb-4">Selected pieces that define the season — pared-back shapes with careful
                            details.</p>

                        <ul class="list-unstyled muted-small mb-4">
                            <li class="py-1">— Tailored outerwear</li>
                            <li class="py-1">— Elevated basics</li>
                            <li class="py-1">— Premium essentials</li>
                        </ul>

                        <a href="#" class="btn btn-dark">Explore collection</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grid gallery --}}
        {{-- replace the "Grid gallery" section in your landing page with this --}}
        <section class="product-grid py-5 bg-white">
            <div class="container">
                {{-- Featured row (optional) --}}
                @if (!empty($featuredProducts) && $featuredProducts->count())
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <h3 class="mb-0">Featured</h3>
                        <a href="{{ route('products.index') }}" class="muted-small">View all</a>
                    </div>

                    <div class="row g-4 mb-5">
                        @foreach ($featuredProducts as $product)
                            <div class="col-6 col-md-4">
                                @include('partials.product-tile', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Loop categories (keeps your style intact) --}}
                @foreach ($categories as $category)
                    <div class="d-flex justify-content-between align-items-end mb-4 mt-4">
                        <h3 class="mb-0">{{ $category->name }}</h3>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="muted-small">View
                            all</a>
                    </div>

                    <div class="row g-4 mb-5">
                        @forelse($category->products as $product)
                            <div class="col-6 col-md-4">
                                @include('partials.product-tile', ['product' => $product])
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No products in this category yet.</p>
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </section>


        {{-- Newsletter / CTA --}}
        <section class="py-6">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="newsletter text-center">
                            <h4 class="mb-2">Join the list</h4>
                            <p class="muted-small mb-4">Get early access to drops and exclusive offers. We send only a few
                                emails a season.</p>

                            <form method="POST" action="#" class="row g-2 justify-content-center align-items-center">
                                @csrf
                                <div class="col-12 col-sm-8">
                                    <label for="newsletter-email" class="visually-hidden">Email</label>
                                    <input id="newsletter-email" name="email" type="email"
                                        class="form-control input-plain" placeholder="Your email address" required>
                                </div>
                                <div class="col-12 col-sm-3 d-grid">
                                    <button class="btn btn-dark btn-plain" type="submit">Subscribe</button>
                                </div>
                            </form>

                            <p class="muted-small mt-3 mb-0" style="font-size:0.9rem;">No spam — unsubscribe anytime.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
