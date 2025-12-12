<!-- Reviews Section -->
<div class="space-y-8">
    <!-- Reviews Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Filter Reviews:</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <span class="text-sm text-gray-600">Filter:</span>
                <div class="flex flex-wrap gap-1 sm:gap-1">
                    <button data-filter="all"
                        class="filter-btn px-2 sm:px-3 py-1 text-xs rounded-full {{ $sortBy == 'all' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua
                    </button>

                    @for ($i = 5; $i >= 1; $i--)
                        <button data-filter="rating_{{ $i }}"
                            class="filter-btn px-2 sm:px-3 py-1 text-xs rounded-full {{ $sortBy == 'rating_' . $i ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} flex items-center space-x-1">
                            <span>{{ $i }}</span>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                    @endfor

                    <button data-filter="date_desc"
                        class="filter-btn px-2 sm:px-3 py-1 text-xs rounded-full {{ $sortBy == 'date_desc' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Terbaru
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($product->reviews->count() > 0)

        @php
            $reviewsToShow = $product->reviews->take(3);
            $hasMoreReviews = $product->reviews->count() > 3;
        @endphp

        <!-- Reviews Grid -->
        <div id="reviews-visible" class="grid gap-6 md:grid-cols-1">
            @foreach ($reviewsToShow as $review)
                <!-- Review Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start space-x-4">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            @if ($review->user && $review->user->profile_image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $review->user->profile_image) }}"
                                        alt="{{ $review->user->name }}"
                                        class="w-14 h-14 rounded-full object-cover ring-2 ring-yellow-100">
                                    <div
                                        class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white">
                                    </div>
                                </div>
                            @else
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-lg">
                                        {{ $review->user ? strtoupper(substr($review->user->name, 0, 1)) : 'A' }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Review Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 leading-tight">
                                        {{ $review->user ? $review->user->name : 'Anonymous' }}
                                    </h4>
                                    <div class="flex items-center mt-2 space-x-3">
                                        <!-- Rating -->
                                        <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <svg class="w-4 h-4 text-yellow-500 fill-current"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300 fill-current"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm font-medium text-yellow-700">
                                                {{ $review->rating }}/5
                                            </span>
                                        </div>
                                        <!-- Date -->
                                        <span class="text-sm text-gray-500 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $review->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Comment -->
                            @if ($review->comment)
                                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                    <p class="text-gray-700 leading-relaxed italic">
                                        "{{ $review->comment }}"
                                    </p>
                                </div>
                            @endif

                            <!-- Review Image -->
                            @if ($review->image)
                                <div class="mt-4">
                                    <div class="relative group">
                                        <img src="{{ $review->image_url }}" alt="Review image"
                                            class="max-w-sm h-auto rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all duration-300">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Hidden Reviews -->
        @if ($hasMoreReviews)
            <div id="reviews-hidden" class="hidden grid gap-6 md:grid-cols-1">
                @foreach ($product->reviews->skip(3) as $review)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-start space-x-4">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                @if ($review->user && $review->user->profile_image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $review->user->profile_image) }}"
                                            alt="{{ $review->user->name }}"
                                            class="w-14 h-14 rounded-full object-cover ring-2 ring-yellow-100">
                                        <div
                                            class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white">
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-full flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-lg">
                                            {{ $review->user ? strtoupper(substr($review->user->name, 0, 1)) : 'A' }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 leading-tight">
                                            {{ $review->user ? $review->user->name : 'Anonymous' }}
                                        </h4>
                                        <div class="flex items-center mt-2 space-x-3">
                                            <!-- Rating -->
                                            <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                                                <div class="flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <svg class="w-4 h-4 text-yellow-500 fill-current"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300 fill-current"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="ml-2 text-sm font-medium text-yellow-700">
                                                    {{ $review->rating }}/5
                                                </span>
                                            </div>
                                            <!-- Date -->
                                            <span class="text-sm text-gray-500 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $review->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comment -->
                                @if ($review->comment)
                                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                        <p class="text-gray-700 leading-relaxed italic">
                                            "{{ $review->comment }}"
                                        </p>
                                    </div>
                                @endif

                                <!-- Review Image -->
                                @if ($review->image)
                                    <div class="mt-4">
                                        <div class="relative group">
                                            <img src="{{ $review->image_url }}" alt="Review image"
                                                class="max-w-sm h-auto rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all duration-300">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Show More Button -->
            <div class="text-center mt-8">
                <button id="toggle-reviews"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-full hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Show More Reviews
                </button>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl">
            <div class="max-w-md mx-auto">
                <div
                    class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Reviews Yet</h3>
                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                    Be the first to share your experience with this product. Your review helps other customers make
                    informed decisions.
                </p>
                <div class="flex justify-center space-x-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
            </div>
        </div>
    @endif
</div>
