<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('placed_at', 'desc')->paginate(30);
        return request()->wantsJson() ? response()->json($orders) : view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product','items.variant','payments','billingAddress','shippingAddress');
        return request()->wantsJson() ? response()->json($order) : view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required','string', Rule::in(['pending','processing','shipped','completed','cancelled','refunded'])],
            'shipping_tracking' => 'nullable|string|max:255',
            'shipping_method' => 'nullable|string|max:255',
            'payment_status' => ['nullable','string', Rule::in(['unpaid','paid','refunded'])],
        ]);

        $order->update($data);

        return $request->wantsJson() ? response()->json($order) : redirect()->back()->with('success','Order updated.');
    }

    public function destroy(Order $order)
    {
        // be careful: in production you might want to soft-delete or prevent deletion
        $order->delete();
        return request()->wantsJson() ? response()->json(['message'=>'deleted']) : redirect()->route('orders.index')->with('success','Order removed.');
    }
}
