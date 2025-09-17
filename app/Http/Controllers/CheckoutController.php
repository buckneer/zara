<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product.images','items.variant')->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = $user->addresses()->orderBy('id', 'desc')->get();

        // compute subtotal using model helper
        $subtotal = (float) $cart->total();

        return view('checkout.checkout', compact('cart', 'addresses', 'subtotal'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product','items.variant')->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'shipping_address_id' => 'required|integer',
            'billing_address_id' => 'nullable|integer',
            'same_as_shipping' => 'nullable|boolean',
            'shipping_method' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cod,card',
            'notes' => 'nullable|string',
        ]);

        // ensure shipping address belongs to user
        $shipping = Address::where('id', $data['shipping_address_id'])->where('user_id', $user->id)->first();
        if (!$shipping) {
            return redirect()->back()->withInput()->with('error', 'Invalid shipping address.');
        }

        if (!empty($data['same_as_shipping']) || empty($data['billing_address_id'])) {
            $billing = $shipping;
        } else {
            $billing = Address::where('id', $data['billing_address_id'])->where('user_id', $user->id)->first();
            if (!$billing) {
                return redirect()->back()->withInput()->with('error', 'Invalid billing address.');
            }
        }

        // pricing rules (simple): flat shipping or free over 100
        $subtotal = (float) $cart->total();
        $shipping_total = $subtotal >= 100 ? 0.00 : 5.00;
        $tax_rate = 0.20; // 20%
        $tax_total = round($subtotal * $tax_rate, 2);
        $discount_total = 0.00;
        $grand_total = round($subtotal + $shipping_total + $tax_total - $discount_total, 2);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => strtoupper('ORD-'.substr(uniqid(), -8)),
                'user_id' => $user->id,
                'billing_address_id' => $billing->id,
                'shipping_address_id' => $shipping->id,
                'subtotal' => $subtotal,
                'shipping_total' => $shipping_total,
                'tax_total' => $tax_total,
                'discount_total' => $discount_total,
                'grand_total' => $grand_total,
                'status' => 'pending',
                'shipping_method' => $data['shipping_method'] ?? null,
                'payment_status' => 'unpaid',
                'meta' => ['notes' => $data['notes'] ?? null],
                'placed_at' => Carbon::now(),
            ]);

            // create order items from cart items
            foreach ($cart->items as $item) {
                $unitPrice = (float) ($item->unit_price ?? ($item->product->price ?? 0));
                $quantity = (int) $item->qty;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'sku' => $item->product->sku ?? ($item->variant->sku ?? null),
                    'title' => $item->product->title ?? ($item->variant->name ?? 'Product'),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => round($unitPrice * $quantity, 2),
                    'meta' => $item->meta ?? null,
                ]);
            }

            // create payment record depending on method (simple)
            if ($data['payment_method'] === 'card') {
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $grand_total,
                    'method' => 'card',
                    'transaction_id' => strtoupper('TX-'.substr(uniqid(), -8)),
                    'status' => 'succeeded', // NOTE: in a real app integrate with gateway and confirm
                    'details' => ['simulated' => true],
                    'paid_at' => Carbon::now(),
                ]);

                // mark order as paid
                $order->payment_status = 'paid';
                $order->save();
            } else {
                // cod (cash on delivery) or other -> leave unpaid
            }

            // clear cart items
            $cart->items()->delete();
            // optionally remove cart record, but we keep it
            DB::commit();

            return redirect()->route('checkout.thankyou', ['order' => $order->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            // log($e->getMessage()); // optional
            return redirect()->back()->withInput()->with('error', 'Could not place order. Try again.');
        }
    }

    /**
     * Show order confirmation to the customer.
     */
    public function thankyou(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load('items.product.images','billingAddress','shippingAddress','payments');

        return view('account.orders.show', compact('order'));
    }
}
