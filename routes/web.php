<?php

use App\Http\Controllers\RajaOngkirController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ArticleController;
use App\Models\Product;

use App\Models\Category;

use App\Models\Review;

Route::get('/', function () {
    $store = App\Models\Store::first();
    $products = Product::with(['category', 'images'])->withAvg('reviews', 'rating')->withSum('transactionItems', 'quantity')->where('status', 'active')->limit(6)->get();
    $sliderImages = App\Models\SliderImage::where('is_active', true)->get();
    $categories = Category::withCount('products')->limit(10)->get();
    $reviews = Review::with('user', 'product')->whereIn('rating', [4, 5])->whereNotNull('comment')->latest()->limit(6)->get();
    return view('homepage', compact('store', 'products', 'sliderImages', 'categories', 'reviews'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/contact', function () {
    $store = App\Models\Store::first();
    return view('pages.contact', compact('store'));
})->name('contact');

Route::get('/about', function () {
    $store = App\Models\Store::first();
    return view('pages.about', compact('store'));
})->name('about');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/remove-multiple', [CartController::class, 'removeMultiple'])->name('cart.removeMultiple');

    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');


    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{transaction:order_code}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{transaction:order_code}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/review/{transactionId}/{productId}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::get('/review/{review}', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/review/{review}', [ReviewController::class, 'update'])->name('reviews.update');


    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

Route::get('/google/redirect', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::post('/midtrans/webhook', [App\Http\Controllers\MidtransWebhookController::class, 'handleWebhook'])->name('midtrans.webhook');
Route::get('/payment/{orderCode}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show')->middleware('auth');

Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinces'])->name('rajaongkir.provinces');
Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'getCities'])->name('rajaongkir.cities');
Route::get('/rajaongkir/districts', [RajaOngkirController::class, 'getDistricts'])->name('rajaongkir.districts');
Route::post('/rajaongkir/cost', [RajaOngkirController::class, 'getShippingCost'])->name('rajaongkir.cost');
Route::post('rajaongkir/getWaybill', [RajaOngkirController::class, 'getWaybill'])->name('rajaongkir.getWaybill');

Route::middleware('admin')->group(function () {
    // Admin routes can be added here if needed
});
