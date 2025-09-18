<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected $sessionKey = 'cart';

    
    public function index(Request $request)
    {
        if (Auth::check()) {
            $cart = $this->getOrCreateCartForUser(Auth::user()->id);
            $cart->load(['items.product.images', 'items.variant']);
            $subtotal = $cart->total();
            return view('guest.cart.index', [
                'items' => $cart->items->map(function ($it) {
                    return [
                        'key' => "{$it->product_id}:" . ($it->variant_id ?? '0'),
                        'product' => $it->product,
                        'variant' => $it->variant,
                        'qty' => $it->qty,
                        'unit_price' => $it->unit_price,
                        'line_total' => ($it->unit_price ?? ($it->product->price ?? 0)) * $it->qty,
                    ];
                })->toArray(),
                'subtotal' => $subtotal,
            ]);
        }

        
        $cart = session($this->sessionKey, []);
        $items = [];
        $subtotal = 0;
        foreach ($cart as $key => $item) {
            $product = Product::with('images')->find($item['product_id']);
            if (! $product) continue;
            $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;
            $price = $variant && $variant->price !== null ? $variant->price : $product->price;
            $qty = max(1, (int)$item['qty']);
            $lineTotal = $price * $qty;
            $items[$key] = [
                'key' => $key,
                'product' => $product,
                'variant' => $variant,
                'qty' => $qty,
                'unit_price' => $price,
                'line_total' => $lineTotal,
            ];
            $subtotal += $lineTotal;
        }

        return view('guest.cart.index', compact('items', 'subtotal'));
    }

   
    public function add(Request $request)
    {
        $request->merge(['variant_id' => $request->input('variant_id') ?: null]);

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'qty' => 'nullable|integer|min:1',
        ]);

        $qty = $data['qty'] ?? 1;
        $key = $data['product_id'] . ':' . ($data['variant_id'] ?? '0');

        if (Auth::check()) {
            $cart = $this->getOrCreateCartForUser(Auth::id());

            
            $item = $cart->items()->where('product_id', $data['product_id'])->where('variant_id', $data['variant_id'] ?? null)->first();

            $unitPrice = $this->resolvePrice($data['product_id'], $data['variant_id']);

            if ($item) {
                $item->qty = max(1, $item->qty + $qty);
                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $data['product_id'],
                    'variant_id' => $data['variant_id'] ?? null,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }

            return back()->with('success', 'Added to cart.');
        }

        
        $cart = session($this->sessionKey, []);
        if (isset($cart[$key])) {
            $cart[$key]['qty'] = max(1, $cart[$key]['qty'] + $qty);
        } else {
            $cart[$key] = [
                'product_id' => (int)$data['product_id'],
                'variant_id' => $data['variant_id'] ? (int)$data['variant_id'] : null,
                'qty' => $qty,
            ];
        }
        session([$this->sessionKey => $cart]);

        return back()->with('success', 'Added to cart.');
    }

    
    public function update(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'qty' => 'required|integer|min:0',
        ]);

        if (Auth::check()) {
            $cart = $this->getOrCreateCartForUser(Auth::id());
            [$product_id, $variant_part] = explode(':', $data['key']);
            $variant_id = $variant_part === '0' ? null : (int)$variant_part;

            $item = $cart->items()->where('product_id', $product_id)->where('variant_id', $variant_id)->first();
            if (! $item) {
                return back()->with('error', 'Cart item not found.');
            }
            if ((int)$data['qty'] <= 0) {
                $item->delete();
            } else {
                $item->qty = (int)$data['qty'];
                $item->save();
            }

            return back()->with('success', 'Cart updated.');
        }

        
        $cart = session($this->sessionKey, []);
        if (! isset($cart[$data['key']])) {
            return back()->with('error', 'Cart item not found.');
        }
        if ((int)$data['qty'] <= 0) {
            unset($cart[$data['key']]);
        } else {
            $cart[$data['key']]['qty'] = (int)$data['qty'];
        }
        session([$this->sessionKey => $cart]);
        return back()->with('success', 'Cart updated.');
    }

    
    public function remove(Request $request)
    {
        $data = $request->validate(['key' => 'required|string']);

        if (Auth::check()) {
            $cart = $this->getOrCreateCartForUser(Auth::id());
            [$product_id, $variant_part] = explode(':', $data['key']);
            $variant_id = $variant_part === '0' ? null : (int)$variant_part;
            $item = $cart->items()->where('product_id', $product_id)->where('variant_id', $variant_id)->first();
            if ($item) $item->delete();
            return back()->with('success', 'Removed from cart.');
        }

        $cart = session($this->sessionKey, []);
        if (isset($cart[$data['key']])) {
            unset($cart[$data['key']]);
            session([$this->sessionKey => $cart]);
        }
        return back()->with('success', 'Removed from cart.');
    }

   
    public function mergeSessionIntoDatabase(Request $request, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        if (! $userId) return;

        $sessionCart = session($this->sessionKey, []);
        if (empty($sessionCart)) return;

        DB::transaction(function () use ($sessionCart, $userId) {
            $cart = $this->getOrCreateCartForUser($userId);

            foreach ($sessionCart as $key => $line) {
                $productId = $line['product_id'];
                $variantId = $line['variant_id'] ?? null;
                $qty = max(1, (int)($line['qty'] ?? 1));

                $existing = $cart->items()->where('product_id', $productId)->where('variant_id', $variantId)->first();
                if ($existing) {
                    $existing->qty = $existing->qty + $qty;
                    $existing->save();
                    continue;
                }

                $unitPrice = $this->resolvePrice($productId, $variantId);

                $cart->items()->create([
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                ]);
            }

            
            session()->forget($this->sessionKey);
        });
    }

   
    protected function getOrCreateCartForUser(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId], ['meta' => []]);
    }


    protected function resolvePrice(int $productId, $variantId = null)
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->price !== null) {
                return (float)$variant->price;
            }
        }
        $product = Product::find($productId);
        return (float)($product->price ?? 0.0);
    }
}
