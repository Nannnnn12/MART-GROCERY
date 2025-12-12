<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function create($transactionId, $productId)
    {
        $user = Auth::user();

        // Ambil transaksi milik user
        $transaction = Transaction::where('id', $transactionId)
            ->where('customer_id', $user->id)
            ->whereIn('status', ['delivered'])
            ->with(['items.product'])
            ->firstOrFail();

        // Ambil produk dari transaksi tersebut
        $product = $transaction->items->where('product_id', $productId)->first()?->product;

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan dalam transaksi ini.');
        }

        // Cek apakah sudah pernah direview
        $existingReview = \App\Models\Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('transaction_id', $transactionId)
            ->first();

        if ($existingReview) {
            // Jika sudah ada, redirect ke halaman edit review
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'Anda sudah memberikan ulasan, silakan edit jika ingin mengubah.');
        }

        $transactionItem = $transaction->items->firstWhere('product_id', $productId);

        if (!$transactionItem) {
            abort(404, 'Produk tidak ditemukan dalam transaksi ini.');
        }

        // Kirim data ke view
        return view('pages.review', compact('product', 'transaction', 'transactionItem'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB max
        ]);

        // Cek apakah user sudah memberikan review untuk produk ini di order ini
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('transaction_id', $request->transaction_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // Cek apakah order milik user dan statusnya success
        $order = Transaction::where('id', $request->transaction_id)
            ->where('customer_id', Auth::id())
            ->where('status', 'delivered')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order tidak valid.');
        }

        // Cek apakah produk ada di order ini
        $orderDetail = $order->items()->where('product_id', $request->product_id)->first();
        if (!$orderDetail) {
            return back()->with('error', 'Produk tidak ditemukan dalam order ini.');
        }

        $reviewData = [
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'transaction_id' => $request->transaction_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('reviews', 'public');
            $reviewData['image'] = $imagePath;
        }

        Review::create($reviewData);

        // Redirect kembali ke halaman review dengan flag session
    return redirect()->route('orders.index', ['status' => 'delivered', 'sub_tab' => 'belum_dinilai'])
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih!');


    }

    public function edit(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengedit ulasan ini.');
        }

        return view('pages.reviewedit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Pastikan user hanya bisa edit review miliknya sendiri
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit ulasan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $reviewData = [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('reviews', 'public');
            $reviewData['image'] = $imagePath;
        }

        $review->update($reviewData);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        // Pastikan user hanya bisa hapus review miliknya sendiri
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        // Delete image if exists
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
