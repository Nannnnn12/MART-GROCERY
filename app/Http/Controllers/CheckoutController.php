<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Store;

class CheckoutController extends Controller
{
    private function sendWhatsAppNotification($phoneNumber, $message)
    {
        // Normalize phone number: remove country code if present
        if (str_starts_with($phoneNumber, '62')) {
            $phoneNumber = substr($phoneNumber, 2);
        } elseif (str_starts_with($phoneNumber, '+62')) {
            $phoneNumber = substr($phoneNumber, 3);
        } elseif (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('WA_API_TOKEN') // Gunakan token dari config
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            Log::error('Fonnte API error: ' . $error_msg);
        }
        curl_close($curl);

        return $response;
    }

    public function index(Request $request)
    {
        // ✅ Daftar layanan pengiriman
        $shippingMethods = [
            ['code' => 'jne', 'name' => 'Jalur Nugraha Ekakurir (JNE)'],
            ['code' => 'sicepat', 'name' => 'SiCepat Express'],
            ['code' => 'ide', 'name' => 'ID Express'],
            ['code' => 'sap', 'name' => 'Satria Antaran Prima'],
            ['code' => 'jnt', 'name' => 'J&T Express'],
            ['code' => 'ninja', 'name' => 'Ninja Xpress'],
            ['code' => 'tiki', 'name' => 'TIKI'],
            ['code' => 'lion', 'name' => 'Lion Parcel'],
            ['code' => 'anteraja', 'name' => 'AnterAja'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'ncs', 'name' => 'Nusantara Card Semesta (NCS)'],
            ['code' => 'rex', 'name' => 'Royal Express Indonesia (REX)'],
            ['code' => 'rpx', 'name' => 'RPX Holding'],
            ['code' => 'sentral', 'name' => 'Sentral Cargo'],
            ['code' => 'star', 'name' => 'Star Cargo'],
            ['code' => 'wahana', 'name' => 'Wahana Prestasi Logistik'],
            ['code' => 'dse', 'name' => '21 Express (DSE)'],
        ];

        // ✅ Metode pembayaran
        $paymentMethods = [
            ['code' => 'midtrans', 'name' => 'Pay with Midtrans'],
        ];

        // ✅ Cek apakah checkout langsung dari produk tunggal
        if ($request->has('product_id')) {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);

            $product = \App\Models\Product::findOrFail($productId);
            $total = $product->sell_price * $quantity;

            $defaultItemWeight = 1000; // 1kg in grams
            $totalWeight = ($product->weight ?? $defaultItemWeight) * $quantity; // weight is already in grams

            $originCityId = (int) config('services.rajaongkir.origin_city_id', 469);
            $originDistrictId = config('services.rajaongkir.origin_district_id')
                ? (int) config('services.rajaongkir.origin_district_id')
                : null;

            // Buat struktur "cart" sementara
            $singleItem = (object) [
                'id' => 'single_' . $productId,
                'product' => $product,
                'quantity' => $quantity,
                'total' => $total,
            ];

            return view('pages.checkout', [
                'carts' => collect([$singleItem]),
                'total' => $total,
                'is_single_product' => true,
                'shippingMethods' => $shippingMethods,
                'paymentMethods' => $paymentMethods,
                'totalWeight' => $totalWeight,
                'originCityId' => $originCityId,
                'originDistrictId' => $originDistrictId,
            ]);
        }

        // ✅ Checkout dari cart
        $selectedCartIds = $request->input('carts', []);

        if (empty($selectedCartIds)) {
            return redirect()->route('cart.index')->with('error', 'Please select items to checkout.');
        }

        $carts = Cart::whereIn('id', $selectedCartIds)
            ->where('user_id', Auth::id())
            ->with('product')
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Selected items not found.');
        }

        $total = $carts->sum(fn($cart) => $cart->total);

        // Default berat (gram) jika produk tidak punya berat
        $defaultItemWeight = 1000; // gram

        $totalWeight = $carts->sum(function ($cart) use ($defaultItemWeight) {
            $weightPerItem = $cart->product->weight ?? $defaultItemWeight; // weight is already in grams
            return $weightPerItem * $cart->quantity; // kalikan dengan qty
        });

        // Pastikan minimal 1kg
        $totalWeight = max($totalWeight, $defaultItemWeight);

        $originCityId = (int) config('services.rajaongkir.origin_city_id', 469);
        $originDistrictId = config('services.rajaongkir.origin_district_id')
            ? (int) config('services.rajaongkir.origin_district_id')
            : null;

        return view('pages.checkout', compact(
            'carts',
            'total',
            'shippingMethods',
            'paymentMethods',
            'totalWeight',
            'originCityId',
            'originDistrictId'
        ));
    }


    public function store(Request $request)
    {
        // Check if it's a direct product checkout or cart checkout
        if ($request->has('product_id')) {
            // Direct product checkout
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'address' => 'required|string|max:500',
                'phone_number' => 'required|string|max:15',
                'province_id' => 'required',
                'province' => 'required|string|max:255',
                'city_id' => 'required',
                'city' => 'required|string|max:255',
                'district_id' => 'required',
                'district' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'courier' => 'required|string|max:50',
                'courier_service' => 'required|string|max:50',
                'shipping_cost' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cod,midtrans',
            ]);

            $product = \App\Models\Product::findOrFail($request->product_id);
            $productTotal = $product->sell_price * $request->quantity;
            $total = $productTotal + $request->shipping_cost;

            $transaction = null;
            DB::transaction(function () use ($request, $product, $productTotal, $total, &$transaction) {
                // Check stock availability
                if ($product->stock < $request->quantity) {
                    throw new \Exception('Insufficient stock for product: ' . $product->product_name);
                }

                // Create transaction
                $transaction = Transaction::create([
                    'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                    'customer_id' => Auth::id(),
                    'address' => $request->address,
                    'province' => $request->province,
                    'city' => $request->city,
                    'phone_number' => $request->phone_number,
                    'district' => $request->district,
                    'postal_code' => $request->postal_code,
                    'courier' => $request->courier,
                    'courier_service' => $request->courier_service,
                    'shipping_cost' => $request->shipping_cost,
                    'payment_method' => $request->payment_method,
                    'total' => $total,
                    'status' => $request->payment_method === 'midtrans' ? 'belum_dibayar' : 'pending',
                ]);

                // Update user phone number if not set
                if (empty(Auth::user()->phone_number)) {
                    Auth::user()->update(['phone_number' => $request->phone_number]);
                }

                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'price' => $product->sell_price,
                    'quantity' => $request->quantity,
                ]);

                // Decrease product stock
                $product->decrement('stock', $request->quantity);

                // Send WhatsApp notification
                $paymentMethod = $request->payment_method === 'cod' ? 'COD' : 'Midtrans';
                $message = "Checkout berhasil! Pesanan kamu sedang diproses.\n\nNama Pelanggan: " . Auth::user()->name . "\nKode Pesanan: {$transaction->order_code}\nProduk:\n- {$product->product_name} (Qty: {$request->quantity}, Harga: Rp " . number_format($product->sell_price, 0, ',', '.') . ", Subtotal: Rp " . number_format($productTotal, 0, ',', '.') . ")" . "Ongkos Kirim: Rp " . number_format($request->shipping_cost, 0, ',', '.') . "\nTotal: Rp " . number_format($total, 0, ',', '.') . "\n\nAlamat Pengiriman: {$request->address}, {$request->district}, {$request->city}, {$request->province}, {$request->postal_code}\nKurir: {$request->courier} - {$request->courier_service}\nMetode Pembayaran: {$paymentMethod}";
                if (!empty($request->notes)) {
                    $message .= "\n\nCatatan: {$request->notes}";
                }
                $message .= "\n\nTerimakasih Telah Berbelanja Di " . Store::first()->store_name . "!";
                $this->sendWhatsAppNotification($request->phone_number, $message);
            });

            if ($request->payment_method === 'midtrans') {
                // Generate Midtrans snap token
                try {
                    $midtransService = app(\App\Services\MidtransServices::class);
                    $customer = [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->phone_number ?? '',
                    ];
                    $snapResponse = $midtransService->createTransaction($transaction, $customer);

                    // Update transaction with snap token
                    $transaction->update(['snap_token' => $snapResponse['token']]);
                } catch (\Exception $e) {
                    // Log the error
                    Log::error('Midtrans error: ' . $e->getMessage());
                    // Still redirect to payment page, even if midtrans fails
                }

                return redirect()->route('payment.show', $transaction->order_code);
            }

            return redirect()->route('products.index')->with([
                'show_success_modal' => true,
                'order_code' => $transaction->order_code,
                'order_total' => $total
            ]);
        } elseif ($request->has('is_single_product')) {
            // Single product checkout from checkout page
            $request->validate([
                'address' => 'required|string|max:500',
                'phone_number' => 'required|string|max:15',
                'province_id' => 'required',
                'province' => 'required|string|max:255',
                'city_id' => 'required',
                'city' => 'required|string|max:255',
                'district_id' => 'required',
                'district' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'courier' => 'required|string|max:50',
                'courier_service' => 'required|string|max:50',
                'shipping_cost' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cod,midtrans',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = \App\Models\Product::findOrFail($request->product_id);
            $productTotal = $product->sell_price * $request->quantity;
            $total = $productTotal + $request->shipping_cost;

            $transaction = null;
            DB::transaction(function () use ($request, $product, $productTotal, $total, &$transaction) {
                // Check stock availability
                if ($product->stock < $request->quantity) {
                    throw new \Exception('Insufficient stock for product: ' . $product->product_name);
                }

                // Create transaction
                $transaction = Transaction::create([
                    'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                    'customer_id' => Auth::id(),
                    'address' => $request->address,
                    'province' => $request->province,
                    'city' => $request->city,
                    'phone_number' => $request->phone_number,
                    'district' => $request->district,
                    'postal_code' => $request->postal_code,
                    'courier' => $request->courier,
                    'courier_service' => $request->courier_service,
                    'shipping_cost' => $request->shipping_cost,
                    'payment_method' => $request->payment_method,
                    'total' => $total,
                    'status' => $request->payment_method === 'midtrans' ? 'belum_dibayar' : 'pending',
                ]);

                // Update user phone number if not set
                if (empty(Auth::user()->phone_number)) {
                    Auth::user()->update(['phone_number' => $request->phone_number]);
                }

                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'price' => $product->sell_price,
                    'quantity' => $request->quantity,
                ]);

                // Decrease product stock
                $product->decrement('stock', $request->quantity);

                // Send WhatsApp notification
                $paymentMethod = $request->payment_method === 'cod' ? 'COD' : 'Midtrans';
                $message = "Checkout berhasil! Pesanan kamu sedang diproses.\n\nNama Pelanggan: " . Auth::user()->name . "\nKode Pesanan: {$transaction->order_code}\nProduk:\n- {$product->product_name} (Qty: {$request->quantity}, Harga: Rp " . number_format($product->sell_price, 0, ',', '.') . ", Subtotal: Rp " . number_format($productTotal, 0, ',', '.') . ")\nOngkos Kirim: Rp " . number_format($request->shipping_cost, 0, ',', '.') . "\nTotal: Rp " . number_format($total, 0, ',', '.') . "\n\nAlamat Pengiriman: {$request->address}, {$request->district}, {$request->city}, {$request->province}, {$request->postal_code}\nKurir: {$request->courier} - {$request->courier_service}\nMetode Pembayaran: {$paymentMethod}";
                if (!empty($request->notes)) {
                    $message .= "\n\nCatatan: {$request->notes}";
                }
                $message .= "\n\nTerimakasih Telah Berbelanja Di " . Store::first()->store_name . "!";
                $this->sendWhatsAppNotification($request->phone_number, $message);
            });

            if ($request->payment_method === 'midtrans') {
                // Generate Midtrans snap token
                try {
                    $midtransService = app(\App\Services\MidtransServices::class);
                    $customer = [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->phone_number ?? '',
                    ];
                    $snapResponse = $midtransService->createTransaction($transaction, $customer);

                    // Update transaction with snap token
                    $transaction->update(['snap_token' => $snapResponse['token']]);
                } catch (\Exception $e) {
                    // Log the error
                    Log::error('Midtrans error: ' . $e->getMessage());
                    // Still redirect to payment page, even if midtrans fails
                }

                return redirect()->route('payment.show', $transaction->order_code);
            }

            return redirect()->back()->with([
                'show_success_modal' => true,
                'order_code' => $transaction->order_code,
                'order_total' => $total
            ]);
        } else {
            // Cart checkout
            $request->validate([
                'address' => 'required|string|max:500',
                'phone_number' => 'required|string|max:15',
                'province_id' => 'required',
                'province' => 'required|string|max:255',
                'city_id' => 'required',
                'city' => 'required|string|max:255',
                'district_id' => 'required',
                'district' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'courier' => 'required|string|max:50',
                'courier_service' => 'required|string|max:50',
                'shipping_cost' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cod,midtrans',
                'notes' => 'nullable|string|max:500',
                'carts' => 'required|array|min:1',
                'carts.*' => 'exists:carts,id',
            ]);

            $selectedCartIds = $request->input('carts');
            $carts = Cart::whereIn('id', $selectedCartIds)
                ->where('user_id', Auth::id())
                ->with('product')
                ->get();

            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Selected items not found.');
            }

            $productTotal = $carts->sum(function ($cart) {
                return $cart->total;
            });
            $total = $productTotal + $request->shipping_cost;

            $transaction = null;
            DB::transaction(function () use ($request, $carts, $productTotal, $total, &$transaction) {
                // Check stock availability for all products
                foreach ($carts as $cart) {
                    if ($cart->product->stock < $cart->quantity) {
                        throw new \Exception('Insufficient stock for product: ' . $cart->product->product_name);
                    }
                }

                // Create transaction
                $transaction = Transaction::create([
                    'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                    'customer_id' => Auth::id(),
                    'address' => $request->address,
                    'province' => $request->province,
                    'city' => $request->city,
                    'phone_number' => $request->phone_number,
                    'district' => $request->district,
                    'postal_code' => $request->postal_code,
                    'courier' => $request->courier,
                    'courier_service' => $request->courier_service,
                    'shipping_cost' => $request->shipping_cost,
                    'payment_method' => $request->payment_method,
                    'total' => $total,
                    'notes' => $request->notes,
                    'status' => $request->payment_method === 'midtrans' ? 'belum_dibayar' : 'pending',
                ]);

                // Update user phone number if not set
                if (empty(Auth::user()->phone_number)) {
                    Auth::user()->update(['phone_number' => $request->phone_number]);
                }

                // Create transaction items and decrease stock
                foreach ($carts as $cart) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $cart->product_id,
                        'price' => $cart->product->sell_price,
                        'quantity' => $cart->quantity,
                    ]);

                    // Decrease product stock
                    $cart->product->decrement('stock', $cart->quantity);
                }

                // Remove items from cart
                Cart::whereIn('id', $carts->pluck('id'))->delete();

                // Send WhatsApp notification for cart checkout
                $productList = $carts->map(function ($cart) {
                    $subtotal = $cart->product->sell_price * $cart->quantity;
                    return "- {$cart->product->product_name} (Qty: {$cart->quantity}, Harga: Rp " . number_format($cart->product->sell_price, 0, ',', '.') . ", Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . ")";
                })->implode("\n");
                $paymentMethod = $request->payment_method === 'cod' ? 'COD' : 'Midtrans';
                $message = "Checkout berhasil! Pesanan kamu sedang diproses.\n\nNama Pelanggan: " . Auth::user()->name . "\nKode Pesanan: {$transaction->order_code}\nProduk:\n{$productList}\nOngkos Kirim: Rp " . number_format($request->shipping_cost, 0, ',', '.') . "\nTotal: Rp " . number_format($total, 0, ',', '.') . "\n\nAlamat Pengiriman: {$request->address}, {$request->district}, {$request->city}, {$request->province}, {$request->postal_code}\nKurir: {$request->courier} - {$request->courier_service}\nMetode Pembayaran: {$paymentMethod}";
                if (!empty($request->notes)) {
                    $message .= "\n\nCatatan: {$request->notes}";
                }
                $message .= "\n\nTerimakasih Telah Berbelanja Di " . Store::first()->store_name . "!";
                $this->sendWhatsAppNotification($request->phone_number, $message);
            });

            if ($request->payment_method === 'midtrans') {
                // Generate Midtrans snap token
                $midtransService = app(\App\Services\MidtransServices::class);
                $customer = [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '',
                ];
                $snapResponse = $midtransService->createTransaction($transaction, $customer);

                // Update transaction with snap token
                $transaction->update(['snap_token' => $snapResponse['token']]);

                return redirect()->route('payment.show', $transaction->order_code);
            }

            return redirect()->route('products.index')->with([
                'show_success_modal' => true,
                'order_code' => $transaction->order_code,
                'order_total' => $total
            ]);
        }
    }
}
