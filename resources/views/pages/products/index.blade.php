@extends('layouts.app')
@section('title', 'Products')
@section('content')
    <style>
        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .category-item {
            transition: all 0.2s ease;
        }

        .category-item:hover {
            background-color: #dcfce7;
            transform: translateX(5px);
        }

        .category-item.active {
            background-color: #16a34a;
            color: white;
        }

        #categories-list {
            transition: all 0.3s ease-in-out;
            overflow: hidden;
        }

        #categories-list.collapsed {
            max-height: 0;
            opacity: 0;
        }

        #categories-list.expanded {
            max-height: 1000px;
            opacity: 1;
        }

        .filter-btn {
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            background-color: #16a34a;
            color: white;
        }

        .filter-btn.active {
            background-color: #15803d;
            color: white;
        }
    </style>

    <div class="min-h-screen bg-gray-50">
        @if (session('show_cart_modal'))
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50" id="cart-modal">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-xl animate-[fadeIn_0.25s_ease]">

                    <!-- Header -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>

                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ session('cart_action') == 'added' ? 'Product Added to Cart' : 'Cart Updated' }}
                            </h3>
                            <p class="mt-1 text-gray-600 text-sm">
                                {{ session('product_name') }} telah berhasil
                                {{ session('cart_action') == 'added' ? 'ditambahkan ke keranjang' : 'diperbarui' }}.
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-900">
                                Jumlah: {{ session('quantity') }} item{{ session('quantity') > 1 ? 's' : '' }}
                            </p>

                            <p class="mt-3 text-[13px] text-gray-500 leading-relaxed">
                                Kamu bisa melanjutkan belanja untuk menambah produk lain, atau langsung menuju keranjang
                                untuk melihat dan menyelesaikan pesananmu.
                            </p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-3 mt-6">
                        <a href="{{ route('cart.index') }}"
                            class="flex-1 bg-green-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-green-700 transition">
                            View Cart
                        </a>

                        <button onclick="closeModal()"
                            class="flex-1 bg-gray-100 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </div>

            <script>
                function closeModal() {
                    document.getElementById('cart-modal').style.display = 'none';
                }
                // Auto close after 5 seconds
                setTimeout(closeModal, 5000);
            </script>
        @endif

        <!-- Main Content -->
        <div class="mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
            <div class="flex flex-col xl:flex-row gap-8">

                <!-- Main Content Area -->
                <div class="flex-1">
                    <!-- Filters and Sort -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                    All Products
                                </button>
                                <button
                                    class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                    On Sale
                                </button>
                                <button
                                    class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                                    New Arrivals
                                </button>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Sort by:</span>
                                <select
                                    class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option>Popularity</option>
                                    <option>Price: Low to High</option>
                                    <option>Price: High to Low</option>
                                    <option>Newest</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
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

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="mt-8 flex justify-center">
                            <div class="flex space-x-2">
                                <button
                                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    Previous
                                </button>
                                <button class="px-4 py-2 bg-green-600 text-white rounded-lg">1</button>
                                <button
                                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">2</button>
                                <button
                                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">3</button>
                                <button
                                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    Next
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Sidebar - Categories -->
                <div class="w-full xl:w-72 2xl:w-80">
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-5 md:p-6 top-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Categories</h3>

                        </div>
                        <div id="categories-list" class="space-y-2">
                            <a href="#"
                                class="category-item active flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 bg-green-600 text-white rounded-lg text-sm sm:text-base">
                                <span class="font-medium">All Categories</span>
                                <span
                                    class="text-xs sm:text-sm bg-white bg-opacity-20 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">{{ $categories->sum('products_count') }}</span>
                            </a>
                            @foreach ($categories->take(5) as $category)
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="category-item flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 text-sm sm:text-base">
                                    <div class="flex items-center space-x-2 sm:space-x-3">
                                        @if ($category->icon)
                                            <img src="{{ asset('storage/' . $category->icon) }}"
                                                alt="{{ $category->category_name }}" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 object-contain">
                                        @else
                                            <div class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-xs font-semibold text-gray-600">{{ strtoupper(substr($category->category_name, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                        <span class="font-medium text-sm sm:text-base">{{ $category->category_name }}</span>
                                    </div>
                                    <span
                                        class="text-xs sm:text-sm bg-gray-200 text-gray-600 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">{{ $category->products_count }}</span>
                                </a>
                            @endforeach
                            @if ($categories->count() > 5)
                                <div id="additional-categories" class="hidden space-y-2">
                                    @foreach ($categories->skip(5) as $category)
                                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                            class="category-item flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 text-sm sm:text-base">
                                            <div class="flex items-center space-x-2 sm:space-x-3">
                                                @if ($category->icon)
                                                    <img src="{{ asset('storage/' . $category->icon) }}"
                                                        alt="{{ $category->category_name }}"
                                                        class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 object-contain">
                                                @else
                                                    <div
                                                        class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                        <span
                                                            class="text-xs font-semibold text-gray-600">{{ strtoupper(substr($category->category_name, 0, 2)) }}</span>
                                                    </div>
                                                @endif
                                                <span class="font-medium text-sm sm:text-base">{{ $category->category_name }}</span>
                                            </div>
                                            <span
                                                class="text-xs sm:text-sm bg-gray-200 text-gray-600 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">{{ $category->products_count }}</span>
                                        </a>
                                    @endforeach
                                </div>
                                <button id="show-all-categories"
                                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                                    Show All Categories ({{ $categories->count() }})
                                </button>
                            @endif
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mt-8">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Price Range</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">Under Rp 50,000</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">Rp 50,000 - Rp 100,000</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">Rp 100,000 - Rp 200,000</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">Over Rp 200,000</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mt-8">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Brands</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">FreshFarm</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">OrganicPlus</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">GreenHarvest</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-categories');
            const categoriesList = document.getElementById('categories-list');
            const icon = document.getElementById('categories-icon');

            toggleBtn.addEventListener('click', function() {
                categoriesList.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });

            const showAllBtn = document.getElementById('show-all-categories');
            const additionalCategories = document.getElementById('additional-categories');

            if (showAllBtn && additionalCategories) {
                showAllBtn.addEventListener('click', function() {
                    additionalCategories.classList.toggle('hidden');
                    if (additionalCategories.classList.contains('hidden')) {
                        showAllBtn.textContent = 'Show All Categories ({{ $categories->count() }})';
                    } else {
                        showAllBtn.textContent = 'Show Less';
                    }
                });
            }
        });
    </script>
@endsection
