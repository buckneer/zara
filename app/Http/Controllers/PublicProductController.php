<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images','categories')
            ->when(method_exists(Product::class, 'scopeActive'), fn($q) => $q->active(), fn($q) => $q)
            ->orderBy('position')
            ->paginate(12);

        return view('guest.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('images','variants','categories','reviews');
        return view('guest.products.show', compact('product'));
    }
}
