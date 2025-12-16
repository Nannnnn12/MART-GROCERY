@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="min-h-screen">
        <section class="relative overflow-hidden mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12 first-section-bg">
            <div class="absolute inset-x-0 top-0 h-full bg-emerald-500"></div>
            <div
                class="swiper mySwiper mx-auto h-40 sm:h-56 md:h-72 lg:h-[500px] rounded-2xl overflow-hidden shadow-lg relative group">

                <div class="swiper-wrapper">
                    @foreach ($sliderImages as $sliderImage)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $sliderImage->image_path) }}"
                                alt="{{ $sliderImage->title ?? 'Slider Image' }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Modern Navigation Buttons -->
                <div
                    class="swiper-button-next absolute inset-y-0 -right-12 z-20
    opacity-0 pointer-events-none
    group-hover:opacity-100 group-hover:pointer-events-auto
    transition duration-300
    !w-12 !h-12 !rounded-full !bg-white/40 !shadow-lg !backdrop-blur-md hover:!bg-white/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>

                <div
                    class="swiper-button-prev absolute inset-y-0 -left-12 z-20
    opacity-0 pointer-events-none
    group-hover:opacity-100 group-hover:pointer-events-auto
    transition duration-300
    !w-12 !h-12 !rounded-full !bg-white/40 !shadow-lg !backdrop-blur-md hover:!bg-white/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>

            </div>

            <div class="absolute bottom-0 left-0 w-full leading-[0]">
                <svg viewBox="0 0 1440 120" class="w-full h-[120px]" preserveAspectRatio="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <!-- fill di sini harus sama dengan warna background halaman di bawahnya (mis. putih) -->
                    <path d="M0 20 C240 100 480 0 720 30 C960 60 1200 0 1440 40 L1440 120 L0 120 Z" fill="#ffffff" />
                </svg>
            </div>

        </section>

        <!-- Categories Section -->
        <section class="mx-auto px-6 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
            <div class="mx-auto group">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Categories</h2>
                </div>

                <div
                    class="swiper categoriesSwiper overflow-hidden mx-auto h-64 sm:h-72 md:h-80 lg:h-96 xl:h-[400px] relative">
                    <div class="swiper-wrapper">
                        @foreach ($categories as $category)
                            <div class="swiper-slide">
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="group flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm bg-gradient-to-br from-white via-white to-gray-50 rounded-2xl w-32 sm:w-36 md:w-44 lg:w-52 xl:w-60 h-48 sm:h-52 md:h-56 lg:h-64 xl:h-72 p-4 sm:p-5 md:p-6 lg:p-7 xl:p-8 mx-2 sm:mx-3 md:mx-4
                   shadow-lg hover:shadow-2xl hover:ring-4 hover:ring-green-300/50 hover:scale-105 hover:-translate-y-1
                   border border-white/20
                   transition-all duration-300 ease-out">

                                    @if (!empty($category->icon))
                                        <img src="{{ asset('storage/' . $category->icon) }}"
                                            alt="{{ $category->category_name }}" title="{{ $category->category_name }}"
                                            class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 lg:w-22 lg:h-22 xl:w-24 xl:h-24 object-contain group-hover:scale-110 transition-transform duration-300 drop-shadow-sm">
                                    @else
                                        <div
                                            class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 lg:w-22 lg:h-22 xl:w-24 xl:h-24
                       bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center
                       text-gray-500 text-lg sm:text-xl md:text-2xl font-bold group-hover:scale-110 transition-all duration-300 shadow-inner">
                                            {{ strtoupper(substr($category->category_name, 0, 2)) }}
                                        </div>
                                    @endif

                                    <span
                                        class="mt-2 sm:mt-3 text-gray-800 font-bold text-sm sm:text-base md:text-lg lg:text-xl text-center group-hover:text-green-700 transition-colors duration-300 leading-tight">
                                        {{ $category->category_name }}
                                    </span>
                                    <span
                                        class="text-xs sm:text-sm text-gray-500 text-center group-hover:text-gray-600 transition-colors duration-300">
                                        {{ $category->products_count }} items
                                    </span>
                                </a>
                            </div>
                        @endforeach

                    </div>

                    <!-- Navigation buttons -->
                    <div
                        class="swiper-button-prev categories-prev absolute top-1/2 -left-12 -translate-y-1/2 z-20
                opacity-0 group-hover:opacity-100 transition duration-300
                flex items-center justify-center !w-12 !h-12 !rounded-full
                !bg-white/80 shadow-lg backdrop-blur-md hover:!bg-white">

                        <!-- SVG Panah Kiri -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                    <div
                        class="swiper-button-next categories-next absolute top-1/2 -right-12 -translate-y-1/2 z-20
    opacity-0 group-hover:opacity-100 transition duration-300
    flex items-center justify-center !w-12 !h-12 !rounded-full
    !bg-white/80 shadow-lg backdrop-blur-md hover:!bg-white">

                        <!-- SVG Panah Kanan -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    <!-- Tombol Next -->

                </div>
            </div>
        </section>

        <!-- Popular Products Section -->
        <section class="relative mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">

            <div class="w-full flex justify-center mb-6">
                <div class="w-[90%] h-[1px] bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
            </div>


            <div class="mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Popular Products</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($products as $product)
                        <!-- Product Card -->
                        <div
                            class="bg-white rounded-xl p-3 sm:p-4 shadow-sm ring-0 hover:ring-1 hover:ring-green-300 hover:shadow-lg hover:scale-[1.02] transition-all duration-200 overflow-hidden relative">

                            <!-- Badge -->
                            <div
                                class="absolute top-2 left-2 bg-red-500 text-white text-[10px] sm:text-xs px-1.5 py-0.5 rounded-full font-semibold z-10">
                                Hot
                            </div>

                            <!-- IMAGE WRAPPER -->
                            <div class="aspect-[4/5] w-full bg-gray-100 rounded-md overflow-hidden relative mb-3">
                                @if ($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->product_name }}"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}';">
                                @else
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- DETAILS -->
                            <div class="space-y-1.5">

                                <!-- NAME -->
                                <h3
                                    class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2 leading-tight hover:text-green-600 transition">
                                    <a href="{{ route('products.show', $product) }}">{{ $product->product_name }}</a>
                                </h3>

                                <!-- RATING -->
                                <div class="flex items-center space-x-1">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($product->reviews_avg_rating ?? 0))
                                                <svg class="w-3 h-3 fill-current sm:w-4 sm:h-4" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 fill-gray-300 sm:w-4 sm:h-4" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>

                                    <span class="text-xs sm:text-sm text-gray-500">
                                        ({{ number_format($product->reviews_avg_rating ?? 0, 1) }})
                                    </span>
                                </div>

                                <!-- BRAND -->
                                <p class="text-xs sm:text-sm text-gray-600">
                                    Brand:
                                    <span
                                        class="text-teal-600 hover:text-teal-700 font-medium cursor-pointer">FreshFarm</span>
                                </p>

                                <!-- PRICE & BUTTON -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                                    <span class="text-lg sm:text-xl font-bold text-green-600">
                                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                    </span>

                                    <form method="POST" action="{{ route('cart.add', $product) }}">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">

                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-md flex items-center justify-center space-x-1 transition text-xs sm:text-sm">

                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                                viewBox="0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3">
                                                </path>
                                            </svg>

                                            <span class="font-medium">Add</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>

        <!-- Customer Reviews Section -->
        <section class="mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
            <div class="w-full flex justify-center mb-6">
                <div class="w-[90%] h-[1px] bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
            </div>
            <div class="mx-auto group">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Customer Reviews</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">See what our happy customers are saying about their
                        experience with our products.</p>
                </div>

                <div class="swiper reviewsSwiper overflow-hidden mx-auto h-40 sm:h-56 md:h-72 lg:h-[170px] relative">
                    <div class="swiper-wrapper">
                        @foreach ($reviews as $review)
                            <div class="swiper-slide">
                                <div
                                    class="bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-200">
                                    <!-- User Info -->
                                    <div class="flex items-center mb-4">
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                            @if ($review->user->avatar)
                                                <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                                    alt="{{ $review->user->name }}"
                                                    class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <span
                                                    class="text-gray-600 font-semibold">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ $review->user->name }}</h4>
                                        </div>
                                    </div>

                                    <!-- Rating -->
                                    <div class="flex items-center mb-3">
                                        <div class="flex text-yellow-400 mr-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 fill-gray-300" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $review->rating }}/5</span>
                                    </div>

                                    <!-- Comment -->
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {{ Str::limit($review->comment, 150) }}</p>

                                    @if ($review->image)
                                        <div class="mt-4">
                                            <img src="{{ $review->imageUrl }}" alt="Review Image"
                                                class="w-full h-32 object-cover rounded-lg">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation buttons -->
                    <div
                        class="swiper-button-next reviews-next absolute top-1/2 -left-12 transform -translate-y-1/2 z-20
            opacity-0 pointer-events-none
            group-hover:opacity-100 group-hover:pointer-events-auto
            transition duration-300
            !w-12 !h-12 !rounded-full !bg-white/40 !shadow-lg !backdrop-blur-md hover:!bg-white/70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>

                    </div>
                    <div
                        class="swiper-button-prev reviews-prev absolute top-1/2 -left-12 transform -translate-y-1/2 z-20
            opacity-0 pointer-events-none
            group-hover:opacity-100 group-hover:pointer-events-auto
            transition duration-300
            !w-12 !h-12 !rounded-full !bg-white/40 !shadow-lg !backdrop-blur-md hover:!bg-white/70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Floating WhatsApp Button -->
    @if ($store && $store->whatsapp)
        <div class="fixed bottom-4 right-4 z-50 group">
            <a href="https://wa.me/{{ $store->whatsapp }}" target="_blank"
                class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg
              transition-all duration-300 hover:scale-110 flex items-center gap-2 relative">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                </svg>
            </a>

            <!-- Teks muncul saat hover -->
            <span
                class="absolute right-16 bottom-1/2 translate-y-1/2 opacity-0 group-hover:opacity-100
                 bg-gray-800 text-white text-sm px-3 py-1 rounded-lg shadow-lg
                 transition-opacity duration-300 whitespace-nowrap">
                Hubungi Admin
            </span>
        </div>
    @endif
@endsection
