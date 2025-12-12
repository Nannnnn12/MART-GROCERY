@extends('layouts.app')
@section('title', $product->product_name ?? 'Product Details')
@section('content')
    <style>
        .product-image {
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .thumbnail-image {
            transition: all 0.3s ease;
        }

        .thumbnail-image:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .thumbnail-active {
            border-color: #16a34a;
        }

        .quantity-btn {
            transition: all 0.2s ease;
        }

        .quantity-btn:hover {
            background-color: #16a34a;
            color: white;
        }

        .tab-btn {
            transition: all 0.2s ease;
            border-bottom-color: #d1d5db;
        }

        .tab-btn:hover {
            transform: translateY(-2px);
            color: #16a34a;
        }

        .tab-active {
            color: #16a34a;
            border-bottom-color: #16a34a;
        }

        .add-to-cart-btn {
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }

        .checkout-btn {
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .related-product {
            transition: all 0.3s ease;
        }

        .related-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Hilangkan spinner di Chrome, Safari, Edge, Opera */
        .no-spinner::-webkit-inner-spin-button,
        .no-spinner::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hilangkan spinner di Firefox */
        .no-spinner {
            -moz-appearance: textfield;
        }
    </style>

    <div class="min-h-screen bg-white mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-12">
        <!-- Breadcrumb -->
        <div class="bg-white border-b border-gray-300">
            <div class="py-10 px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-5.5 5.5A1 1 0 004 8.5V18a2 2 0 002 2h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a2 2 0 002-2V8.5a1 1 0 00-.293-.707l-5.5-5.5z" />
                                    </svg>
                                    <span class="sr-only">Home</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('products.index') }}"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Products</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span
                                    class="ml-4 text-sm font-medium text-gray-500">{{ $product->product_name ?? 'Product' }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Main Product Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Image gallery -->
                <div class="w-full">
                    <div class="aspect-w-1 aspect-h-1 w-full bg-gray-100 rounded-lg overflow-hidden">
                        @if ($product->images->count() > 0)
                            <img id="main-image" src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                alt="{{ $product->product_name }}"
                                class="w-full h-full object-center object-cover product-image">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Image selector -->
                    @if ($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-4 mt-4">
                            @foreach ($product->images as $index => $image)
                                <button type="button"
                                    onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', {{ $index }})"
                                    class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden thumbnail-image {{ $index === 0 ? 'thumbnail-active' : '' }}"
                                    id="thumbnail-{{ $index }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-full h-full object-center object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product info -->
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->product_name }}</h1>

                    <!-- Rating -->
                    <div class="mt-3 flex items-center">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($product->reviews_avg_rating ?? 0))
                                    <svg class="flex-shrink-0 h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <p class="ml-3 text-sm text-gray-500">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}
                            ({{ $product->reviews->count() }} reviews)</p>
                    </div>

                    <!-- Price -->
                    <div class="mt-6">
                        <p class="text-3xl font-bold text-green-600">Rp.
                            {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                        @if ($product->original_price && $product->original_price > $product->sell_price)
                            <p class="text-lg text-gray-500 line-through">Rp.
                                {{ number_format($product->original_price, 0, ',', '.') }}</p>
                            <p class="text-sm text-red-600 font-medium">
                                {{ round((1 - $product->sell_price / $product->original_price) * 100) }}% off</p>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="mt-6">
                        @if ($product->stock > 0)
                            <p class="text-green-600 font-medium">In Stock ({{ $product->stock }} available)</p>
                        @else
                            <p class="text-red-600 font-medium">Out of Stock</p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('cart.add', $product) }}"
                        class="space-y-4 border-t border-gray-200 py-5">
                        @csrf
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="decrementQuantity()"
                                    class="p-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                        </path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1"
                                    max="{{ $product->stock }}"
                                    class="no-spinner w-20 text-center px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                                    onchange="updateCheckoutQuantity({{ $product->id }})">
                                <button type="button" onclick="incrementQuantity()"
                                    class="p-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if ($product->stock > 0)
                                <button type="submit"
                                    class="w-full bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors font-medium">
                                    Tambah ke Keranjang
                                </button>

                                <a href="{{ route('checkout.index', ['product_id' => $product->id, 'quantity' => 1]) }}"
                                    id="checkout-link"
                                    class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium inline-block text-center">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Beli Sekarang
                                </a>
                            @else
                                <button type="button" disabled
                                    class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                    Stok Habis
                                </button>

                                <button type="button" disabled
                                    class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="border border-gray-200 rounded-xl mt-46 p-6">
                <div class="border-gray-200">
                    <nav class="-mb-px flex space-x-2" aria-label="Tabs">
                        <button type="button" data-tab="description" id="tab-description"
                            class="tab-btn tab-active whitespace-nowrap p-4 shadow rounded-full font-medium text-sm">
                            Description
                        </button>
                        <button type="button" data-tab="specifications" id="tab-specifications"
                            class="tab-btn whitespace-nowrap p-4 shadow rounded-full font-medium text-sm">
                            Specifications
                        </button>
                        <button type="button" data-tab="reviews" id="tab-reviews"
                            class="tab-btn whitespace-nowrap p-4 shadow rounded-full font-medium text-sm">
                            Reviews ({{ $product->reviews->count() }})
                        </button>
                    </nav>
                </div>

                <div class="mt-8">
                    <!-- Description Tab -->
                    <div id="content-description" class="tab-content">
                        <div class="prose prose-sm max-w-none text-gray-500">
                            @php
                                $description = $product->description ?? 'No description available.';
                                $truncated = Str::limit(strip_tags($description), 300);
                                $isLong = strlen(strip_tags($description)) > 300;
                            @endphp
                            <div id="description-content" data-full="{{ htmlspecialchars($description) }}" data-truncated="{{ htmlspecialchars($truncated) }}">
                                {!! $isLong ? $truncated : $description !!}
                            </div>
                            @if($isLong)
                                <button id="toggle-description" class="mt-4 text-green-600 hover:text-green-700 font-medium text-sm">
                                    Show More
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div id="content-specifications" class="tab-content hidden">
                        <div class="prose prose-sm max-w-none text-gray-500">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-500">
                                        {{ $product->category->category_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Stock Quantity</dt>
                                    <dd class="mt-1 text-sm text-gray-500">{{ $product->stock }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Weight</dt>
                                    <dd class="mt-1 text-sm text-gray-500">{{ $product->weight ?? 'N/A' }} kg</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Dimensions</dt>
                                    <dd class="mt-1 text-sm text-gray-500">{{ $product->dimensions ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div id="content-reviews" class="tab-content hidden">
                        @include('pages.products.partials.reviews', ['product' => $product])
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900">Related Products</h2>
                <div class="mt-8 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach ($relatedProducts ?? [] as $relatedProduct)
                        <div class="group relative related-product">
                            <div
                                class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                @if ($relatedProduct->images->count() > 0)
                                    <img src="{{ asset('storage/' . $relatedProduct->images->first()->image_path) }}"
                                        alt="{{ $relatedProduct->product_name }}"
                                        class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('products.show', $relatedProduct->id) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $relatedProduct->product_name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $relatedProduct->category->category_name ?? 'N/A' }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Rp.
                                    {{ number_format($relatedProduct->sell_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => tab.classList.remove('tab-active'));

            // Add active class to clicked tab
            const activeTab = document.getElementById('tab-' + tabName);
            if (activeTab) {
                activeTab.classList.add('tab-active');
            }

            // Hide all content
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));

            // Show selected content
            const activeContent = document.getElementById('content-' + tabName);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle filter button clicks with AJAX
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('filter-btn') || e.target.closest('.filter-btn')) {
                    e.preventDefault();
                    const button = e.target.classList.contains('filter-btn') ? e.target : e.target.closest('.filter-btn');
                    const filterValue = button.getAttribute('data-filter');

                    // Update URL without page reload
                    const url = new URL(window.location);
                    url.searchParams.set('sort', filterValue);
                    window.history.pushState({}, '', url.toString());

                    // Show loading state
                    const reviewsContainer = document.getElementById('content-reviews');
                    if (reviewsContainer) {
                        reviewsContainer.style.opacity = '0.5';
                    }

                    // Make AJAX request
                    fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update the reviews content
                        if (reviewsContainer && data.html) {
                            reviewsContainer.innerHTML = data.html;
                            reviewsContainer.style.opacity = '1';

                            // Ensure reviews tab remains active
                            showTab('reviews');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading reviews:', error);
                        if (reviewsContainer) {
                            reviewsContainer.style.opacity = '1';
                        }
                    });
                }
            });
        });

        function changeImage(imageSrc, index) {
            document.getElementById('main-image').src = imageSrc;

            // Update thumbnail selection
            const thumbnails = document.querySelectorAll('[id^="thumb-"]');
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.remove('border-transparent', 'hover:border-yellow-400');
                    thumb.classList.add('border-yellow-500', 'shadow-lg');
                } else {
                    thumb.classList.remove('border-yellow-500', 'shadow-lg');
                    thumb.classList.add('border-transparent', 'hover:border-yellow-400');
                }
            });
        }

        function scrollThumbnails(direction) {
            const container = document.querySelector('.grid.grid-cols-5');
            const scrollAmount = 200; // Adjust based on thumbnail size

            if (direction === 'left') {
                container.scrollLeft -= scrollAmount;
            } else {
                container.scrollLeft += scrollAmount;
            }
        }

        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxStock = {{ $product->stock }};
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
                updateCheckoutQuantity({{ $product->id }});
            }
        }

        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
            updateCheckoutQuantity({{ $product->id }});
        }

        function updateCheckoutQuantity(productId) {
            const quantityInput = document.getElementById('quantity');
            const checkoutLink = document.getElementById('checkout-link');
            if (checkoutLink) {
                const url = new URL(checkoutLink.href);
                url.searchParams.set('quantity', quantityInput.value);
                checkoutLink.href = url.toString();
            }
        }

        // Initialize tabs
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to tab buttons
            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    showTab(tabName);
                });
            });

            // Description toggle
            const toggleDescriptionBtn = document.getElementById('toggle-description');
            if (toggleDescriptionBtn) {
                toggleDescriptionBtn.addEventListener('click', function() {
                    const content = document.getElementById('description-content');
                    const isExpanded = content.classList.contains('expanded');

                    if (isExpanded) {
                        content.innerHTML = `@php echo addslashes($truncated); @endphp`;
                        this.textContent = 'Show More';
                        content.classList.remove('expanded');
                    } else {
                        content.innerHTML = `@php echo addslashes($description); @endphp`;
                        this.textContent = 'Show Less';
                        content.classList.add('expanded');
                    }
                });
            }

            // Reviews toggle
            const toggleReviewsBtn = document.getElementById('toggle-reviews');
            if (toggleReviewsBtn) {
                toggleReviewsBtn.addEventListener('click', function() {
                    const hiddenReviews = document.getElementById('reviews-hidden');
                    const isExpanded = hiddenReviews.classList.contains('show');

                    if (isExpanded) {
                        hiddenReviews.classList.add('hidden');
                        hiddenReviews.classList.remove('show');
                        this.textContent = 'Show More Reviews';
                    } else {
                        hiddenReviews.classList.remove('hidden');
                        hiddenReviews.classList.add('show');
                        this.textContent = 'Show Less Reviews';
                    }
                });
            }
        });
    </script>
@endsection
