@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <!-- Payment Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border border-gray-100">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran Anda</h1>
                    <p class="text-gray-600">Pembayaran aman dengan Midtrans</p>
                </div>

                <!-- Order Details -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-600">Order Code:</span>
                        <span class="font-mono text-sm font-semibold text-gray-900">{{ $transaction->order_code }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="font-bold text-lg text-green-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Instruksi Pembayaran</h2>
                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-green-600 font-semibold text-xs">1</span>
                        </div>
                        <p>Klik tombol "Bayar Sekarang" di bawah untuk membuka popup pembayaran aman.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-green-600 font-semibold text-xs">2</span>
                        </div>
                        <p>Pilih metode pembayaran yang Anda inginkan (transfer bank, e-wallet, dll.).</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-green-600 font-semibold text-xs">3</span>
                        </div>
                        <p>Selesaikan pembayaran dalam batas waktu yang ditampilkan.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-green-600 font-semibold text-xs">4</span>
                        </div>
                        <p>Setelah pembayaran berhasil, Anda akan diarahkan ke halaman pesanan.</p>
                    </div>
                </div>

                <!-- Pay Now Button -->
                <div class="mt-8">
                    <button id="pay-button" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Bayar Sekarang - Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </div>
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 flex items-center justify-center text-xs text-gray-500">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pembayaran diamankan dengan enkripsi Midtrans
                </div>
            </div>

            <!-- Back to Orders Link -->
            <div class="text-center mt-6">
                <a href="{{ route('orders.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    ← Kembali ke Pesanan Saya
                </a>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap.js Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            snap.pay('{{ $transaction->snap_token }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route('orders.index') }}';
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route('orders.index') }}';
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    console.log('Payment popup closed');
                }
            });
        });
    </script>
@endsection
