<?php

// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Example: get 3 categories to show on the landing (change query as you like)
        $categories = Cache::remember('home_categories', 60, function () {
            return Category::with(['products' => function ($q) {
                $q->where('active', true)
                    ->with(['images'])
                    ->orderBy('created_at', 'desc')
                    ->take(6); // show up to 6 per category
            }])
                ->whereHas('products', function ($q) {
                    $q->where('active', true);
                })
                ->orderBy('name')
                ->take(3)
                ->get();
        });

        // Optionally a featured products row (if you have a `featured` flag)
        $featuredProducts = Cache::remember('home_featured_products', 60, function () {
            return Product::with('images')->where('active', true)
                ->where('featured', true)
                ->latest()
                ->take(6)
                ->get();
        });

        return view('landing.home', compact('categories', 'featuredProducts'));
    }
}
