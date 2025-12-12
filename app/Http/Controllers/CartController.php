<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $carts->sum(function ($cart) {
            return $cart->total;
        });

        return view('pages.cart', compact('carts', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        $wasExisting = $cart !== null;

        if ($cart) {
            $cart->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with([
            'show_cart_modal' => true,
            'cart_action' => $wasExisting ? 'updated' : 'added',
            'product_name' => $product->product_name,
            'quantity' => $request->quantity
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->update(['quantity' => $request->quantity]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function removeMultiple(Request $request)
    {
        $request->validate([
            'selected_carts' => 'required|array',
            'selected_carts.*' => 'integer|exists:carts,id',
        ]);

        $carts = Cart::whereIn('id', $request->selected_carts)
            ->where('user_id', Auth::id())
            ->get();

        foreach ($carts as $cart) {
            $cart->delete();
        }

        return redirect()->back()->with('success', 'Selected products removed from cart!');
    }
}
