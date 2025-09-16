<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order')->orderBy('created_at','desc')->paginate(40);
        return request()->wantsJson() ? response()->json($payments) : view('admin.payments.index', compact('payments'));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,succeeded,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
            'paid_at' => 'nullable|date',
        ]);

        $payment->update($data);

        // optionally update order payment_status
        if ($payment->status === 'succeeded') {
            $order = $payment->order;
            $order->payment_status = 'paid';
            $order->save();
        }

        return $request->wantsJson() ? response()->json($payment) : redirect()->back()->with('success','Payment updated.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return request()->wantsJson() ? response()->json(['message'=>'deleted']) : redirect()->back()->with('success','Payment removed.');
    }
}
