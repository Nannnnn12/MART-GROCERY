<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('category')->where('is_published', true);

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(12);

        $categories = \App\Models\ArticleCategory::all();
        $store = \App\Models\Store::first();
        $selectedCategory = $request->category;

        return view('pages.articles.index', compact('articles', 'categories', 'store', 'selectedCategory'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $store = \App\Models\Store::first();

        return view('pages.articles.show', compact('article', 'store'));
    }
}
