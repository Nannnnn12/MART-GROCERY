@extends('layouts.app')

@section('title', 'Pesanan #' . $transaction->order_code . ' - Toko Online')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center text-green-600 hover:text-green-800 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Pesanan
                </a>
            </div>

            <!-- Order Header -->
            <div class="bg-white rounded-2xl shadow-[0_4px_16px_rgba(0,0,0,0.05)] overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-white">
                                Pesanan #{{ $transaction->order_code }}
                            </h1>
                            <p class="text-green-100 mt-2 text-lg">
                                {{ $transaction->created_at->locale('id')->translatedFormat('d F Y \p\u\k\u\l H:i') }}
                            </p>
                        </div>
                        <div class="mt-6 sm:mt-0">
                            <div class="flex flex-col items-end space-y-3">
                                <span
                                    class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold
                                @if ($transaction->status == 'belum_dibayar') bg-green-100 text-green-800
                                @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($transaction->status == 'shipped') bg-orange-100 text-orange-800
                                @elseif($transaction->status == 'delivered') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                    @if ($transaction->status == 'belum_dibayar')
                                        Belum Dibayar
                                    @elseif($transaction->status == 'cancelled')
                                        Dibatalkan
                                    @else
                                        {{ ucfirst($transaction->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="px-8 py-6 bg-green-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Metode Pembayaran
                                    </h3>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $transaction->payment_method == 'cod' ? 'Bayar di Tempat' : 'Pembayaran Online' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100">
                            <div class="flex space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Alamat Pengiriman
                                    </h3>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $transaction->address ?? 'Alamat tidak disediakan' }}
                                    </p>
                                    @if ($transaction->province || $transaction->city || $transaction->district)
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ $transaction->province }}, {{ $transaction->city }},
                                            {{ $transaction->district }}
                                            @if ($transaction->postal_code)
                                                - {{ $transaction->postal_code }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kurir & Layanan
                                    </h3>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        @if ($transaction->courier && $transaction->courier_service)
                                            {{ strtoupper($transaction->courier) }} - {{ $transaction->courier_service }}
                                        @else
                                            Informasi kurir tidak tersedia
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-green-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Jumlah</h3>
                                    <p class="mt-1 text-lg font-bold text-green-600">
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Item Pesanan</h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($transaction->items as $item)
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if ($item->product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                            alt="{{ $item->product->product_name }}"
                                            class="w-20 h-20 object-cover rounded-lg"
                                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}';">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <a href="{{ route('products.show', $item->product) }}"
                                            class="hover:text-blue-600 transition-colors">
                                            {{ $item->product->product_name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-sm text-gray-500">
                                            Jumlah: {{ $item->quantity }}
                                        </span>
                                        <span class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->price, 0, ',', '.') }} per item
                                        </span>
                                    </div>
                                </div>

                                <!-- Item Total -->
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-end">
                        <div class="w-full max-w-xs">
                            @php
                                $subtotal = $transaction->items->sum(function ($item) {
                                    return $item->quantity * $item->price;
                                });
                                $shippingCost = $transaction->shipping_cost;
                            @endphp
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Pengiriman</span>
                                <span>{{ $shippingCost > 0 ? 'Rp ' . number_format($shippingCost, 0, ',', '.') : 'Gratis' }}</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2">
                                <div class="flex justify-between text-lg font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status Timeline -->
            <div class="bg-white rounded-2xl shadow-[0_4px_16px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="px-8 py-6 border-b border-green-100 bg-green-50/30">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status Pesanan
                        </h2>
                        @if (in_array($transaction->status, ['shipped', 'delivered']))
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Kode Resi:</span>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span id="tracking-number"
                                        class="text-sm font-bold text-gray-900 select-all bg-gray-100 px-3 py-1 rounded-lg">
                                        {{ $transaction->tracking_number ?? 'Belum tersedia' }}
                                    </span>

                                    <!-- Copy button -->
                                    <button type="button" id="copy-tracking-btn"
                                        class="inline-flex items-center px-3 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 focus:outline-none transition-colors shadow-sm"
                                        title="Salin nomor resi">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16h8M8 12h8M8 8h8M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Salin
                                    </button>
                                </div>

                                <!-- feedback kecil -->
                                <span id="copy-feedback"
                                    class="text-sm text-green-600 font-medium hidden">Tersalin!</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-8 py-6">
                    <div class="space-y-6">
                        @php
                            $statuses = [
                                'belum_dibayar' => 'Belum Dibayar',
                                'pending' => 'Pending',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Diterima',
                            ];
                            $statusKeys = array_keys($statuses);
                            $currentStatusIndex = array_search($transaction->status, $statusKeys);
                        @endphp

                        @foreach ($statuses as $key => $label)
                            @php $index = array_search($key, $statusKeys); @endphp
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center shadow-sm
                                    @if ($index <= $currentStatusIndex) bg-green-500 text-white
                                    @else bg-gray-200 text-gray-400 @endif">
                                        @if ($index < $currentStatusIndex)
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($index == $currentStatusIndex)
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-6 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-gray-900">
                                                {{ $label }}
                                            </p>
                                            @if ($index == $currentStatusIndex)
                                                <p class="text-sm text-green-600 font-medium mt-1">
                                                    Status saat ini
                                                </p>
                                                @if ($transaction->status == 'shipped')
                                                    <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                                        <p class="text-sm font-semibold text-gray-900 mb-1">Alamat
                                                            Pengiriman:</p>
                                                        <p class="text-sm text-gray-700">
                                                            {{ $transaction->address ?? 'Alamat tidak disediakan' }}
                                                        </p>
                                                        @if ($transaction->province || $transaction->city || $transaction->district)
                                                            <p class="text-xs text-gray-600 mt-1">
                                                                {{ $transaction->province }}, {{ $transaction->city }},
                                                                {{ $transaction->district }}
                                                                @if ($transaction->postal_code)
                                                                    - {{ $transaction->postal_code }}
                                                                @endif
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        @if ($index <= $currentStatusIndex)
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->updated_at->locale('id')->translatedFormat('d F Y, H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($index < count($statuses) - 1)
                                <div class="ml-6 w-px h-12 bg-green-200"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @if (!in_array($transaction->status, ['shipped', 'delivered', 'cancelled']))
                <form action="{{ route('orders.cancel', $transaction->order_code) }}" method="POST" class="inline ">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border w-full h-16 mt-6 border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Batalkan Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-tracking-btn');
            const trackingSpan = document.getElementById('tracking-number');
            const feedback = document.getElementById('copy-feedback');

            if (!copyBtn || !trackingSpan) return;

            async function showFeedback(text = 'Tersalin!', isError = false) {
                if (!feedback) return;
                feedback.textContent = text;
                feedback.classList.remove('hidden');
                feedback.classList.toggle('text-red-600', isError);
                feedback.classList.toggle('text-green-600', !isError);
                // hide after 1.6s
                setTimeout(() => {
                    feedback.classList.add('hidden');
                }, 1600);
            }

            async function copyToClipboard(text) {
                // Modern API (https, localhost)
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    try {
                        await navigator.clipboard.writeText(text);
                        return true;
                    } catch (err) {
                        // fallback below
                    }
                }

                // Fallback: create a temporary textarea, select and execCommand
                try {
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    // avoid scrolling to bottom
                    textarea.style.position = 'fixed';
                    textarea.style.left = '-9999px';
                    document.body.appendChild(textarea);
                    textarea.select();
                    textarea.setSelectionRange(0, textarea.value.length);
                    const successful = document.execCommand('copy');
                    document.body.removeChild(textarea);
                    return successful;
                } catch (err) {
                    return false;
                }
            }

            copyBtn.addEventListener('click', async function() {
                const value = (trackingSpan.textContent || '').trim();
                if (!value || value.toLowerCase().includes('belum')) {
                    showFeedback('Nomor resi belum tersedia', true);
                    return;
                }

                const ok = await copyToClipboard(value);
                if (ok) {
                    showFeedback('Tersalin!');
                    // optional: briefly change button text or icon
                    copyBtn.classList.add('opacity-80');
                    setTimeout(() => copyBtn.classList.remove('opacity-80'), 800);
                } else {
                    showFeedback('Gagal menyalin', true);
                }
            });

            // optional: allow click on the number itself to copy
            trackingSpan?.addEventListener('click', async () => {
                const value = (trackingSpan.textContent || '').trim();
                if (!value || value.toLowerCase().includes('belum')) {
                    showFeedback('Nomor resi belum tersedia', true);
                    return;
                }
                const ok = await copyToClipboard(value);
                if (ok) showFeedback('Tersalin!');
                else showFeedback('Gagal menyalin', true);
            });
        });
    </script>

@endsection
