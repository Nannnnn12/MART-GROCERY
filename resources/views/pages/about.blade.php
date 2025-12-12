@extends('layouts.app')

@section('title', 'Tentang Kami - Toko Online')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tentang Kami</h1>
            <p class="mt-2 text-gray-600">Pelajari lebih lanjut tentang toko kami dan komitmen kami terhadap pelanggan</p>
        </div>

        <!-- Store Information -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tentang {{ $store->store_name ?? 'TokoKu' }}</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mr-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $store->store_name ?? 'TokoKu' }}</h3>
                        <p class="text-gray-600">Toko Online Terpercaya dengan Pengiriman Cepat</p>
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed mb-4">
                    {{ $store->description ?? 'Kami adalah toko online terpercaya yang menyediakan berbagai produk berkualitas dengan harga terjangkau. Komitmen kami adalah memberikan pengalaman belanja yang mudah, aman, dan memuaskan bagi setiap pelanggan.' }}
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Dengan tim yang berpengalaman dan sistem pengiriman yang efisien, kami memastikan produk Anda sampai dengan tepat waktu dan dalam kondisi terbaik.
                </p>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Mission -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Misi Kami</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Memberikan Layanan Terbaik</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Kami berkomitmen untuk memberikan layanan pelanggan yang luar biasa, produk berkualitas tinggi, dan pengalaman belanja yang menyenangkan.
                    </p>
                </div>
            </div>

            <!-- Vision -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Visi Kami</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Menjadi Pilihan Utama</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Menjadi toko online terdepan yang menjadi pilihan utama pelanggan untuk kebutuhan sehari-hari dengan inovasi dan keunggulan layanan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mengapa Memilih Kami?</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Quality -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kualitas Terjamin</h3>
                        <p class="text-gray-600 text-sm">Semua produk kami melalui proses seleksi ketat untuk memastikan kualitas terbaik.</p>
                    </div>

                    <!-- Fast Delivery -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengiriman Cepat</h3>
                        <p class="text-gray-600 text-sm">Layanan pengiriman yang cepat dan dapat diandalkan ke seluruh Indonesia.</p>
                    </div>

                    <!-- Customer Service -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Layanan Pelanggan</h3>
                        <p class="text-gray-600 text-sm">Tim dukungan kami siap membantu Anda 24/7 dengan senyuman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
