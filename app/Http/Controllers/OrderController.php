<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function index(Request $request)
{
    $query = Transaction::where('customer_id', Auth::id())
        ->with(['items.product']);

    $status = $request->status;
    if ($status == 'dinilai') {
        $status = 'delivered';
    } elseif ($status == 'dibatalkan') {
        $status = 'cancelled';
    }

    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $status);
    }

    $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

    // Load reviews jika status dinilai
    if ($request->status === 'dinilai') {
        $transactions->getCollection()->load([
            'items.product.reviews' => function ($q) {
                $q->where('user_id', Auth::id());
            }
        ]);

        // Default sub_tab ke 'belum_dinilai' jika tidak ada
        $subTab = $request->get('sub_tab', 'belum_dinilai');
        $filteredItems = collect();

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $hasReview = $item->product->reviews->where('user_id', Auth::id())->isNotEmpty();

                if (($subTab === 'belum_dinilai' && !$hasReview) ||
                    ($subTab === 'lihat_review' && $hasReview)) {
                    $filteredItems->push([
                        'transaction' => $transaction,
                        'item' => $item,
                        'review' => $hasReview ? $item->product->reviews->where('user_id', Auth::id())->first() : null
                    ]);
                }
            }
        }

        // For lihat_review, ensure unique products
        if ($subTab === 'lihat_review') {
            $filteredItems = $filteredItems->unique(function ($item) {
                return $item['item']->product_id;
            });
        }

        // Pass filtered items ke view
        return view('pages.orders.index', compact('transactions', 'filteredItems', 'subTab'));
    }

    return view('pages.orders.index', compact('transactions'));
}


    public function show(Transaction $transaction)
    {
        // Ensure pages can only view their own orders
        if ($transaction->customer_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['items.product', 'customer']);

        return view('pages.orders.show', compact('transaction'));
    }

    public function cancel(Transaction $transaction)
    {
        // Ensure user can only cancel their own orders
        if ($transaction->customer_id !== Auth::id()) {
            abort(403);
        }

        // Only allow cancellation if status is not shipped or delivered
        if (in_array($transaction->status, ['shipped', 'delivered'])) {
            return back()->with('error', 'Pesanan yang sudah dikirim atau diterima tidak dapat dibatalkan.');
        }

        $transaction->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
