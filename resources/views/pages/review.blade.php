@extends('layouts.app')

@section('title', 'Beri Ulasan - ' . $transactionItem->product->product_name . ' - Toko Online')

@section('content')
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="mb-4">
                    <a href="{{ route('orders.index', ['status' => 'delivered']) }}"
                        class="text-gray-700 hover:text-gray-900 font-medium">
                        &larr; Kembali ke Pesanan
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Beri Ulasan</h1>
                <p class="text-gray-600">Bagikan pengalaman Anda dengan produk ini</p>

                @if (session('success'))
                    <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Success Modal -->
                @if (session('show_success_modal'))
                    <div id="successModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Ulasan Berhasil Dikirim!</h3>
                            <p class="text-gray-600 mb-6">Terima kasih atas ulasan Anda. Ulasan Anda membantu kami untuk
                                memberikan pelayanan yang lebih baik.</p>
                            <a href="{{ route('orders.index') }}"
                                class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                Kembali ke Pesanan Saya
                            </a>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Produk yang direview -->
                <div class="mt-8 border-t pt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Produk yang Direview</h2>
                    <div class="flex items-center space-x-6">
                        <div class="w-28 h-28 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                            @if ($transactionItem->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $transactionItem->product->images->first()->image_path) }}"
                                    alt="{{ $transactionItem->product->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $transactionItem->product->product_name }}
                            </h3>
                            <p class="text-gray-600 mt-1">Jumlah: {{ $transactionItem->quantity }}</p>
                            <p class="text-gray-900 font-bold text-lg mt-2">Rp
                                {{ number_format($transactionItem->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Review -->
                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-8 space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $transactionItem->product->id }}">
                    <input type="hidden" name="transaction_id" value="{{ $transactionItem->transaction_id }}">

                    <!-- Rating -->
                    <div class="border-t pt-6">
                        <label class="block text-lg font-semibold text-gray-900 mb-3">Rating</label>
                        <div class="flex space-x-1" id="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    class="star-btn cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors duration-200"
                                    data-rating="{{ $i }}">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.158c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.285-3.957a1 1 0 00-.364-1.118L2.027 9.384c-.783-.57-.38-1.81.588-1.81h4.158a1 1 0 00.95-.69l1.286-3.957z" />
                                    </svg>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" id="rating-input" value="">
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Klik bintang untuk memberikan rating</p>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="border-t pt-6">
                        <label for="comment" class="block text-lg font-semibold text-gray-900 mb-3">Komentar</label>
                        <textarea name="comment" id="comment" rows="5"
                            class="w-full border-gray-300 p-5 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200 resize-none"
                            placeholder="Bagikan pengalaman Anda dengan produk ini...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="border-t pt-6">
                        <label for="image" class="block text-lg font-semibold text-gray-900 mb-3">Upload Gambar <span
                                class="text-sm font-normal text-gray-500">(Opsional)</span></label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-400 transition-colors duration-200">
                            <input type="file" name="image" id="image" accept="image/*" class="hidden"
                                onchange="previewImage(event)">
                            <label for="image" class="cursor-pointer">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-gray-600">Klik untuk upload gambar</p>
                                <p class="text-sm text-gray-500 mt-1">PNG, JPG, WEBP hingga 2MB</p>
                            </label>
                            <div id="image-preview" class="mt-4 hidden">
                                <img id="preview-img" class="max-w-full h-32 object-cover rounded-lg mx-auto">
                            </div>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="border-t pt-6">
                        <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg font-semibold hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            const img = document.getElementById('preview-img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }

        // Enhanced star rating interaction using buttons
        const starButtons = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-input');
        let currentRating = 0;

        function updateStars(rating) {
            starButtons.forEach((button, index) => {
                if (index < rating) {
                    button.classList.add('text-yellow-500');
                    button.classList.remove('text-gray-300', 'text-yellow-400');
                } else {
                    button.classList.add('text-gray-300');
                    button.classList.remove('text-yellow-500', 'text-yellow-400');
                }
            });
        }

        starButtons.forEach((button, index) => {
            button.addEventListener('mouseenter', () => {
                starButtons.forEach((btn, i) => {
                    if (i <= index) {
                        btn.classList.add('text-yellow-400');
                        btn.classList.remove('text-gray-300');
                    } else {
                        btn.classList.remove('text-yellow-400');
                        btn.classList.add('text-gray-300');
                    }
                });
            });

            button.addEventListener('mouseleave', () => {
                updateStars(currentRating);
            });

            button.addEventListener('click', () => {
                currentRating = index + 1;
                ratingInput.value = currentRating;
                updateStars(currentRating);
            });
        });
    </script>
@endsection
