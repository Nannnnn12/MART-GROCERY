<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->withAvg('reviews', 'rating')
            ->withSum('transactionItems', 'quantity')
            ->where('status', 'active');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Search by name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'product_name');

        switch ($sortBy) {
            case 'price':
                $query->orderBy('sell_price', 'desc');
                break;
            case 'product_name':
            default:
                $query->orderBy('product_name', 'asc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('pages.products.index', compact('products', 'categories'));
    }

    public function show(Product $product, Request $request)
    {
        // Check if product is active
        if ($product->status !== 'active') {
            abort(404);
        }

        $sortBy = $request->get('sort', 'all');

        $reviewsQuery = $product->reviews()->with('user');

        switch ($sortBy) {
            case 'rating_5':
                $reviewsQuery->where('rating', 5);
                break;
            case 'rating_4':
                $reviewsQuery->where('rating', 4);
                break;
            case 'rating_3':
                $reviewsQuery->where('rating', 3);
                break;
            case 'rating_2':
                $reviewsQuery->where('rating', 2);
                break;
            case 'rating_1':
                $reviewsQuery->where('rating', 1);
                break;
            case 'rating_desc':
                $reviewsQuery->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'date_desc':
                $reviewsQuery->orderBy('created_at', 'desc');
                break;
            case 'all':
            default:
                // No filter, show all reviews ordered by latest first
                $reviewsQuery->orderBy('created_at', 'desc');
                break;
        }

        $product->load(['category', 'images', 'transactionItems']);
        $product->setRelation('reviews', $reviewsQuery->get());
        $product->loadAvg('reviews', 'rating');
        $product->loadSum('transactionItems', 'quantity');
        $totalReviewsCount = $product->reviews()->count();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->withAvg('reviews', 'rating')
            ->withSum('transactionItems', 'quantity')
            ->where('status', 'active')
            ->limit(4)
            ->get();

        // Handle AJAX request for reviews section
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.products.partials.reviews', compact('product', 'sortBy', 'totalReviewsCount'))->render()
            ]);
        }

        return view('pages.products.show', compact('product', 'relatedProducts', 'sortBy', 'totalReviewsCount'));
    }
}
