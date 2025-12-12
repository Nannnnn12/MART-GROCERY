@extends('layouts.app')

@section('title', 'Checkout - Toko Online')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        @unless (session('show_success_modal'))
            <!-- Progress Bar -->
            <div class="mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8 rounded-r-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST"
                    class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @csrf

                    <!-- Hidden fields for single product checkout -->
                    @if (isset($is_single_product) && $is_single_product)
                        <input type="hidden" name="is_single_product" value="1">
                        <input type="hidden" name="product_id" value="{{ $carts->first()->product->id }}">
                        <input type="hidden" name="quantity" value="{{ $carts->first()->quantity }}">
                    @else
                        <!-- Hidden cart IDs -->
                        @foreach ($carts as $cart)
                            <input type="hidden" name="carts[]" value="{{ $cart->id }}">
                        @endforeach
                    @endif

                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Shipping Information -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                            <div class="flex items-center mb-8">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Informasi Pengiriman</h2>
                                    <p class="text-gray-600 mt-1">Ke mana kami harus mengirim pesanan Anda?</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Provinsi --}}
                                    <div class="mb-4">
                                        <label for="province_select" class="block text-gray-700 mb-2">Provinsi Tujuan</label>
                                        <div class="flex space-x-2">
                                            <select id="province_select" name="province" required
                                                class="flex-1 px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white">
                                                <option value="">Pilih Provinsi</option>
                                            </select>
                                        </div>
                                        <p id="province_status" class="text-xs text-gray-500 mt-1 hidden">Memuat daftar
                                            provinsi...
                                        </p>
                                        <p id="province_error" class="text-xs text-red-500 mt-1 hidden"></p>
                                        <input type="hidden" id="province_id" name="province_id"
                                            value="{{ old('province_id') }}">
                                        <input type="hidden" id="province_name" name="province" value="{{ old('province') }}">
                                        @error('province')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Kota --}}
                                    <div class="mb-4">
                                        <label for="city_select" class="block text-gray-700 mb-2">Kota/Kabupaten Tujuan</label>
                                        <select id="city_select" name="city" required
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                        </select>
                                        <p id="city_status" class="text-xs text-gray-500 mt-1 hidden">Memuat daftar kota...</p>
                                        <p id="city_error" class="text-xs text-red-500 mt-1 hidden"></p>
                                        <input type="hidden" id="city_id" name="city_id" value="{{ old('city_id') }}">
                                        <input type="hidden" id="city_name" name="city" value="{{ old('city') }}">
                                        @error('city')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Kecamatan --}}
                                    <div class="mb-4">
                                        <label for="district_select" class="block text-gray-700 mb-2">Kecamatan Tujuan</label>
                                        <select id="district_select" name="district"
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white">
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                        <p id="district_status" class="text-xs text-gray-500 mt-1 hidden">Memuat daftar
                                            kecamatan...
                                        </p>
                                        <p id="district_error" class="text-xs text-red-500 mt-1 hidden"></p>
                                        <input type="hidden" id="district_id" name="district_id"
                                            value="{{ old('district_id') }}">
                                        <input type="hidden" id="district_name" name="district"
                                            value="{{ old('district') }}">
                                        @error('district')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Kode Pos --}}
                                    <div class="mb-4">
                                        <label for="postal_code" class="block text-gray-700 mb-2">Kode Pos</label>
                                        <input type="text" id="postal_code" name="postal_code" required
                                            value="{{ old('postal_code') }}"
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white">
                                        @error('postal_code')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Alamat --}}
                                <div class="mb-6">
                                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-3">Alamat
                                        Pengiriman <span class="text-red-500">*</span></label>
                                    <textarea id="address" name="address" rows="5"
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 resize-none bg-white"
                                        placeholder="Masukkan alamat lengkap (jalan, nomor rumah, RT/RW, kode pos, dll)" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                    @error('address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    {{-- Nomor WhatsApp --}}
                                    <div class="mb-6">
                                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-3">Nomor
                                            WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="text" id="phone_number" name="phone_number"
                                            value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}"
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white"
                                            placeholder="Masukkan nomor WhatsApp (contoh: 08123456789)" required>
                                        @error('phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Kurir --}}
                                    <div class="mb-4">
                                        <label for="courier" class="block text-gray-700 mb-2">Kurir Pengiriman</label>
                                        <select id="courier" name="courier" required
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white">
                                            <option value="">Pilih Kurir</option>
                                            @foreach ($shippingMethods as $method)
                                                <option value="{{ $method['code'] }}"
                                                    {{ old('courier') == $method['code'] ? 'selected' : '' }}>
                                                    {{ $method['name'] }}</option>
                                            @endforeach
                                        </select>

                                        @error('courier')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- Layanan Kurir --}}
                                    <div class="mb-4">
                                        <label for="courier_service" class="block text-gray-700 mb-2">Layanan
                                            Pengiriman</label>
                                        <select id="courier_service" name="courier_service" required
                                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 bg-white">
                                            <option value="">Pilih Layanan</option>
                                        </select>
                                        <div id="courier_status" class="hidden text-gray-500 text-sm mb-2">
                                            Memuat layanan...
                                        </div>
                                        <div id="courier_error" class="hidden text-red-500 text-sm mb-2"></div>
                                        @error('courier_service')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Biaya --}}
                                    <div class="mb-4">
                                        <label for="shipping_cost" class="block text-gray-700 mb-2">Biaya Pengiriman
                                            (Rp)</label>
                                        <input type="number" id="shipping_cost" name="shipping_cost" readonly
                                            value="{{ old('shipping_cost', 0) }}"
                                            class="w-full p-3 border-2 border-gray-200 rounded-xl bg-gray-100 focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500">
                                        @error('shipping_cost')
                                            <div class="text-red-500 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Catatan --}}
                                <div>
                                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-3">
                                        Catatan Pesanan <span class="text-gray-500 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea id="notes" name="notes" rows="4"
                                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 resize-none bg-white"
                                        placeholder="Instruksi pengiriman khusus, waktu pengiriman, dll">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <!-- Payment Method -->
                            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mt-8">
                                <div class="flex items-center mb-8">
                                    <div
                                        class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">Metode Pembayaran</h2>
                                        <p class="text-gray-600 mt-1">Pilih cara pembayaran yang Anda inginkan</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <!-- COD Option -->
                                    <div class="relative">
                                        <input id="cod" name="payment_method" type="radio" value="cod" required
                                            class="sr-only peer" checked>
                                        <label for="cod"
                                            class="flex items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all duration-200 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:shadow-lg">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl mr-4 peer-checked:bg-green-500">
                                                <svg class="w-6 h-6 text-green-600 peer-checked:text-white" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 text-lg">Bayar di Tempat (COD)</div>
                                                <div class="text-gray-600 mt-1">Bayar saat pesanan tiba di depan pintu Anda
                                                </div>
                                                <div class="flex items-center mt-2">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Available Now
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-white rounded-full peer-checked:bg-white"></div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Midtrans Option -->
                                    <div class="relative">
                                        <input id="midtrans" name="payment_method" type="radio" value="midtrans"
                                            class="sr-only peer">
                                        <label for="midtrans"
                                            class="flex items-center p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all duration-200 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:shadow-lg">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl mr-4 peer-checked:bg-blue-500">
                                                <svg class="w-6 h-6 text-blue-600 peer-checked:text-white" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 text-lg">Midtrans Payment</div>
                                                <div class="text-gray-600 mt-1">Bayar online dengan berbagai metode pembayaran
                                                </div>
                                                <div class="flex items-center mt-2">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Secure Payment
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                                    <div class="w-3 h-3 bg-white rounded-full peer-checked:bg-white"></div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <p class="mt-4 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 sticky top-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Ringkasan Pesanan</h3>
                            </div>

                            <!-- Order Items -->
                            <div class="space-y-4 mb-6">
                                @foreach ($carts as $cart)
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <img class="w-16 h-16 rounded-lg object-cover border border-gray-200"
                                                src="{{ $cart->product->images->first() ? asset('storage/' . $cart->product->images->first()->image_path) : asset('images/placeholder-product.png') }}"
                                                alt="{{ $cart->product->product_name }}">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $cart->product->product_name }}</h4>
                                            <p class="text-sm text-gray-600">Qty: {{ $cart->quantity }}</p>
                                            <p class="text-sm font-bold text-gray-900 mt-1">Rp
                                                {{ number_format($cart->total, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals -->
                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">Rp
                                        {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pengiriman</span>
                                    <span class="font-medium text-green-600" id="display-shipping">Rp. 0</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <span>Total</span>
                                        <span id="display-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-8 space-y-3">
                                @if (isset($is_single_product) && $is_single_product)
                                    <a href="{{ route('products.show', $carts->first()->product) }}"
                                        class="w-full flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Kembali ke Produk
                                    </a>
                                @else
                                    <a href="{{ route('cart.index') }}"
                                        class="w-full flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Kembali ke Keranjang
                                    </a>
                                @endif

                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-transparent rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-green-100 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buat Pesanan
                                </button>
                            </div>

                            <!-- Security Badge -->
                            <div class="mt-6 flex items-center justify-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Checkout aman dengan enkripsi SSL
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endunless
    </div>
    <!-- Success Modal -->
    @if (session('show_success_modal'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-t-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Pesanan Berhasil Dibuat!</h3>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <div class="text-center mb-6">
                        <p class="text-gray-600 mb-4">Terima kasih atas pesanan Anda. Pesanan Anda telah dikonfirmasi dan
                            sedang diproses.</p>

                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Kode Pesanan:</span>
                                <span
                                    class="font-mono text-sm font-semibold text-gray-900">{{ session('order_code') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Jumlah:</span>
                                <span class="font-bold text-lg text-green-600">Rp
                                    {{ number_format(session('order_total'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Konfirmasi pesanan dikirim ke email Anda
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Metode pembayaran: Bayar di Tempat
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Perkiraan pengiriman: 2-3 hari kerja
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <button onclick="continueShopping()"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Lanjut Belanja
                        </button>
                        <button onclick="viewOrders()"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Lihat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function continueShopping() {
                window.location.href = '{{ route('products.index') }}';
            }

            function viewOrders() {
                // Redirect to orders page
                window.location.href = '{{ route('orders.index') }}';
            }

            function closeModal() {
                document.getElementById('successModal').style.display = 'none';
            }

            // Close modal when clicking outside
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('successModal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            closeModal();
                        }
                    });

                    // Auto-close after 10 seconds
                    setTimeout(function() {
                        if (modal.style.display !== 'none') {
                            closeModal();
                        }
                    }, 10000);
                }
            });
        </script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const provinceSelect = document.getElementById("province_select");
            const citySelect = document.getElementById("city_select");
            const districtSelect = document.getElementById("district_select");
            const courierSelect = document.getElementById("courier");
            const serviceSelect = document.getElementById("courier_service");
            const costInput = document.getElementById("shipping_cost");

            const originCityId = {{ $originCityId }};
            const totalWeight = {{ $totalWeight }};

            // --- Load daftar provinsi ---
            async function loadProvinces() {
                const statusEl = document.getElementById("province_status");
                const errorEl = document.getElementById("province_error");
                statusEl.classList.remove("hidden");
                errorEl.classList.add("hidden");
                errorEl.textContent = "";

                try {
                    const res = await fetch("/rajaongkir/provinces");
                    if (!res.ok) throw new Error("Gagal memuat provinsi");
                    const data = await res.json();
                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    data.rajaongkir.results.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.id || province.province_id;
                        option.textContent = province.name || province.province;
                        provinceSelect.appendChild(option);
                    });
                    statusEl.classList.add("hidden");
                } catch (err) {
                    console.error("Province fetch failed:", err);
                    statusEl.classList.add("hidden");
                    errorEl.textContent = "Gagal memuat daftar provinsi. Silakan coba lagi.";
                    errorEl.classList.remove("hidden");
                    provinceSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
                }
            }

            // --- Load daftar kota ---
            async function loadCities(provinceId) {
                const statusEl = document.getElementById("city_status");
                const errorEl = document.getElementById("city_error");
                statusEl.classList.remove("hidden");
                errorEl.classList.add("hidden");
                errorEl.textContent = "";

                try {
                    const res = await fetch(`/rajaongkir/cities?province_id=${provinceId}`);
                    if (!res.ok) throw new Error("Gagal memuat kota/kabupaten");
                    const data = await res.json();
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    if (data.rajaongkir && data.rajaongkir.results) {
                        data.rajaongkir.results.forEach(city => {
                            const option = document.createElement("option");
                            option.value = city.city_id;
                            option.textContent = city.city_name;
                            citySelect.appendChild(option);
                        });
                    }
                    statusEl.classList.add("hidden");
                } catch (err) {
                    console.error("City fetch failed:", err);
                    statusEl.classList.add("hidden");
                    errorEl.textContent = "Gagal memuat daftar kota/kabupaten. Silakan coba lagi.";
                    errorEl.classList.remove("hidden");
                    citySelect.innerHTML = '<option value="">Gagal memuat kota/kabupaten</option>';
                }
            }

            // --- Load daftar kecamatan ---
            async function loadDistricts(cityId) {
                const statusEl = document.getElementById("district_status");
                const errorEl = document.getElementById("district_error");
                statusEl.classList.remove("hidden");
                errorEl.classList.add("hidden");
                errorEl.textContent = "";

                try {
                    const res = await fetch(`/rajaongkir/districts?city_id=${cityId}`);
                    if (!res.ok) throw new Error("Gagal memuat kecamatan");
                    const data = await res.json();
                    districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    if (data.rajaongkir && data.rajaongkir.results) {
                        data.rajaongkir.results.forEach(district => {
                            const option = document.createElement("option");
                            option.value = district.subdistrict_id || district.district_id;
                            option.textContent = district.subdistrict_name || district.district_name;
                            districtSelect.appendChild(option);
                        });
                    }
                    statusEl.classList.add("hidden");
                } catch (err) {
                    console.error("District fetch failed:", err);
                    statusEl.classList.add("hidden");
                    errorEl.textContent = "Gagal memuat daftar kecamatan. Silakan coba lagi.";
                    errorEl.classList.remove("hidden");
                    districtSelect.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
                }
            }

            // --- Load layanan pengiriman ---
            async function loadCourierServices() {
                const courier = courierSelect.value;
                const destination = districtSelect.value || citySelect.value; // pakai district jika ada

                if (!courier || !destination) {
                    serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                    costInput.value = 0;
                    return;
                }

                const statusEl = document.getElementById("courier_status");
                const errorEl = document.getElementById("courier_error");
                statusEl.classList.remove("hidden");
                errorEl.classList.add("hidden");
                errorEl.textContent = "";

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch("/rajaongkir/cost", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: JSON.stringify({
                            origin: originCityId,
                            destination,
                            weight: totalWeight,
                            courier
                        }),
                    });

                    if (!res.ok) throw new Error("Gagal memuat layanan");
                    const data = await res.json();

                    serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';

                    if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                        data.data.forEach(service => {
                            const option = document.createElement("option");
                            option.value = service.service;
                            option.textContent =
                                `${service.name} - ${service.description} (Rp ${service.cost.toLocaleString()})`;
                            option.dataset.cost = service.cost;
                            serviceSelect.appendChild(option);
                        });
                    } else {
                        serviceSelect.innerHTML = '<option value="">Tidak ada layanan tersedia</option>';
                    }
                    statusEl.classList.add("hidden");
                } catch (err) {
                    console.error("Load courier services failed:", err);
                    statusEl.classList.add("hidden");
                    errorEl.textContent = "Gagal memuat layanan pengiriman. Silakan coba lagi.";
                    errorEl.classList.remove("hidden");
                    serviceSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
                }
            }

            provinceSelect?.addEventListener("change", (e) => {
                const selectedOption = e.target.selectedOptions[0];
                document.getElementById("province_name").value = selectedOption
                    .textContent; // nama provinsi
                document.getElementById("province_id").value = selectedOption.value; // id provinsi
                loadCities(e.target.value);
            });
            citySelect?.addEventListener("change", (e) => {
                const selectedOption = e.target.selectedOptions[0];
                document.getElementById("city_name").value = selectedOption.textContent;
                document.getElementById("city_id").value = selectedOption.value;
                loadDistricts(e.target.value);
            });
            courierSelect?.addEventListener("change", loadCourierServices);
            districtSelect?.addEventListener("change", (e) => {
                const selectedOption = e.target.selectedOptions[0];
                document.getElementById("district_name").value = selectedOption.textContent;
                document.getElementById("district_id").value = selectedOption.value;
                loadCourierServices();
            }); // reload layanan kalau tujuan berubah
            serviceSelect?.addEventListener("change", (e) => {
                const cost = e.target.selectedOptions[0]?.dataset.cost || 0;
                costInput.value = cost;
                updateTotalCost(cost);
            });

            function updateTotalCost(shippingCost) {
                const subtotal = {{ $total }};
                const total = subtotal + parseInt(shippingCost);
                document.getElementById("display-shipping").textContent =
                    `Rp ${parseInt(shippingCost).toLocaleString()}`;
                document.getElementById("display-total").textContent = `Rp ${total.toLocaleString()}`;
            }

            loadProvinces();
        });
    </script>


@endsection
