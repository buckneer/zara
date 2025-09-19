<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($q) {
                $q->where('active', true)
                    ->with(['images'])
                    ->orderBy('created_at', 'desc')
                    ->take(6);
            }])
            ->whereHas('products', function ($q) {
                $q->where('active', true);
            })
            ->orderBy('name')
            ->take(3)
            ->get();
        $featuredProducts = Product::with('images')
            ->where('active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('landing.home', compact('categories', 'featuredProducts'));
    }
}
