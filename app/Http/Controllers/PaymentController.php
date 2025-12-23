<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show($orderCode)
    {
        $transaction = Transaction::where('order_code', $orderCode)
            ->where('customer_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        // If snap_token is not set and payment method is midtrans and status is belum_dibayar, try to generate it
        if (!$transaction->snap_token && $transaction->payment_method == 'midtrans' && $transaction->status == 'belum_dibayar') {
            try {
                $midtransService = app(\App\Services\MidtransServices::class);
                $customer = [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone_number ?? '',
                ];
                $snapResponse = $midtransService->createTransaction($transaction, $customer);
                $transaction->update(['snap_token' => $snapResponse['token']]);
            } catch (\Exception $e) {
                Log::error('Midtrans error in payment show: ' . $e->getMessage());
                return redirect()->route('orders.index')->with('error', 'Payment setup failed. Please try again.');
            }
        }

        // If still no snap_token, redirect
        if (!$transaction->snap_token) {
            return redirect()->route('orders.index')->with('error', 'Payment token not found.');
        }

        return view('pages.payment', compact('transaction'));
    }
}
