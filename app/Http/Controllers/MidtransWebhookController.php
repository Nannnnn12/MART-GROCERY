<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        try {
            // Initialize Midtrans configuration
            \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production', false);
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');

            // Create notification object from request
            $notification = new Notification();

            // Get order ID from notification
            $orderId = $notification->order_id;
            $status = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;

            // Find the transaction
            $transaction = Transaction::where('order_code', $orderId)->first();

            if (!$transaction) {
                Log::error('Midtrans Webhook: Transaction not found', [
                    'order_id' => $orderId,
                    'status' => $status,
                    'payment_type' => $paymentType,
                ]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Update transaction status based on notification
            switch ($status) {
                case 'capture':
                    if ($paymentType == 'credit_card') {
                        if ($fraudStatus == 'challenge') {
                            $transaction->update(['status' => 'pending']);
                        } else {
                            $transaction->update(['status' => 'paid']);
                        }
                    }
                    break;
                case 'settlement':
                    $transaction->update(['status' => 'paid']);
                    break;
                case 'pending':
                    $transaction->update(['status' => 'pending']);
                    break;
                case 'deny':
                    $transaction->update(['status' => 'failed']);
                    break;
                case 'expire':
                    $transaction->update(['status' => 'expired']);
                    break;
                case 'cancel':
                    $transaction->update(['status' => 'cancelled']);
                    break;
                default:
                    Log::warning('Midtrans Webhook: Unknown transaction status', [
                        'order_id' => $orderId,
                        'status' => $status,
                        'payment_type' => $paymentType,
                        'fraud_status' => $fraudStatus,
                    ]);
                    break;
            }

            Log::info('Midtrans Webhook processed successfully', [
                'order_id' => $orderId,
                'status' => $status,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'transaction_status' => $transaction->status,
            ]);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
