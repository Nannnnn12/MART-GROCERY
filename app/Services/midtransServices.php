<?php
namespace App\Services;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Arr;
use Midtrans\Config;
use Midtrans\Snap;
use function App\Helpers\store;

class MidtransServices
{
    public function __construct(){
        $store = store();
        Config::$isProduction = (bool) ($store->is_production ?? false);
        Config::$serverKey = $store->midtrans_server_key ?? '';
        Config::$clientKey = $store->midtrans_client_key ?? '';
        Config::$isSanitized = true;
        Config::$curlOptions = config('services.midtrans.options', []);
        if (!isset(Config::$curlOptions[CURLOPT_HTTPHEADER])) {
            Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
        }
    }

    /**
     * Membuat transaksi Snap dan mengembalikan payload.
     */
    public function createTransaction(Transaction $transaction, array $customer): array {
        // Build transaction details
        $transactionDetails = [
            'order_id' => $transaction->order_code,
            'gross_amount' => $transaction->total,
        ];

        // Build item details
        $itemDetails = [];
        foreach ($transaction->items as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->product_name,
            ];
        }
        if (!empty($transaction->shipping_cost) && $transaction->shipping_cost > 0) {
        $itemDetails[] = [
            'id' => 'shipping',
            'price' => $transaction->shipping_cost,
            'quantity' => 1,
            'name' => 'Biaya Pengiriman',
        ];
    }

        // Build customer details
        $customerDetails = [
            'first_name' => Arr::get($customer, 'first_name', ''),
            'last_name' => Arr::get($customer, 'last_name', ''),
            'email' => Arr::get($customer, 'email', ''),
            'phone' => Arr::get($customer, 'phone', ''),
        ];

        // Build the full payload
        $payload = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        // Create transaction via Midtrans Snap
        $snapResponse = Snap::createTransaction($payload);

        return (array) $snapResponse;
    }
} // Akhir dari kelas service MidtransGateway.
