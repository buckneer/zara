<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Cart;

class OrderController extends Controller
{
    /**
     * Show list of current user's orders.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->orderBy('placed_at', 'desc')->paginate(20);
        return view('account.orders.index', compact('orders'));
    }

    /**
     * Show a single order for the current user.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items.product.images','items.variant','payments','billingAddress','shippingAddress');

        return view('account.orders.show', compact('order'));
    }

    /**
     * Reorder: copy items from an order into the user's cart (merge quantities).
     * POST /account/orders/{order}/reorder
     */
    public function reorder(Request $request, Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        DB::transaction(function () use ($user, $order) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id], ['meta' => []]);

            foreach ($order->items as $item) {
                $existing = $cart->items()
                    ->where('product_id', $item->product_id)
                    ->where('variant_id', $item->variant_id)
                    ->first();

                if ($existing) {
                    $existing->qty = $existing->qty + $item->quantity;
                    $existing->save();
                } else {
                    $cart->items()->create([
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'qty' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'meta' => null,
                    ]);
                }
            }
        });

        return redirect()->route('cart.index')->with('success', 'Order items added to cart.');
    }
}
