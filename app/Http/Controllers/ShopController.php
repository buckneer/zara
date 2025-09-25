<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    protected $sessionKey = 'cart';

    public function index(Request $request, $filter = null)
    {
        $query = Product::with(['images', 'variants', 'categories'])->active();

        
        $filterNorm = $filter ? Str::slug($filter, '-') : null;


        $selectedCategory = null;

        if ($filterNorm) {
            

            
            $selectedCategory = Category::where('slug', $filterNorm)->first();

            if ($selectedCategory) {
              
                $query->whereHas('categories', function ($q) use ($selectedCategory) {
                    $q->where('categories.id', $selectedCategory->id);
                });
            } else {
               
                $query->whereRaw('0 = 1');
            }
        }

        // Price filters
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float)$request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float)$request->price_max);
        }

        // Sorting
        $sort = $request->get('sort', 'position');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('position', 'asc');
                break;
        }

        $products = $query->paginate(12)->appends($request->except('page'));

        
        $categories = Category::orderBy('position', 'asc')->get();

        $cartItems = [];
        $subtotal = 0.0;

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()], ['meta' => []]);
            $cart->load(['items.product.images', 'items.variant']);

            $cartItems = $cart->items->map(function ($it) {
                return [
                    'key' => "{$it->product_id}:" . ($it->variant_id ?? '0'),
                    'product' => $it->product,
                    'variant' => $it->variant,
                    'qty' => $it->qty,
                    'unit_price' => $it->unit_price,
                    'line_total' => ($it->unit_price ?? ($it->product->price ?? 0)) * $it->qty,
                ];
            })->toArray();

            $subtotal = method_exists($cart, 'total') ? $cart->total() : array_sum(array_map(function ($it) {
                return $it['line_total'];
            }, $cartItems));
        } else {
            $sessionCart = session($this->sessionKey, []);
            foreach ($sessionCart as $key => $item) {
                $product = Product::with('images')->find($item['product_id'] ?? null);
                if (! $product) continue;
                $variant = isset($item['variant_id']) && $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;
                $price = ($variant && $variant->price !== null) ? $variant->price : $product->price;
                $qty = max(1, (int)($item['qty'] ?? 1));
                $lineTotal = $price * $qty;
                $cartItems[$key] = [
                    'key' => $key,
                    'product' => $product,
                    'variant' => $variant,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ];
                $subtotal += $lineTotal;
            }
            $subtotal = (float)$subtotal;
        }

        return view('shop.index', compact('products', 'categories', 'selectedCategory', 'filter', 'cartItems', 'subtotal'));
    }
}
