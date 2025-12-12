<!-- Footer -->
<footer class="border border-t-gray-200 bg-white text-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Store Info -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex flex-col mb-6">
                    @if (isset($store) && $store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->store_name ?? 'Store Logo' }}"
                            class="h-8 w-8">
                    @endif

                    <h1 class="text-xl font-bold text-black">
                        {{ $store->store_name ?? 'TokoKu' }}
                    </h1>
                </div>
                <p class="text-gray-600 mb-4">
                    {{ $store->description ?? 'Your trusted online store for quality products and excellent service.' }}
                </p>
                @if ($store->address)
                    <p class="text-gray-600 mb-4">
                        {{ $store->address }}
                    </p>
                @endif
                <div class="flex space-x-4">
                    @if ($store->whatsapp ?? false)
                        <a href="https://wa.me/{{ $store->whatsapp }}" target="_blank"
                            class="text-gray-600 hover:text-green-400 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                            </svg>
                        </a>
                    @endif
                    @if ($store->facebook ?? false)
                        <a href="{{ $store->facebook }}" target="_blank"
                            class="text-gray-600 hover:text-blue-400 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                    @endif
                    @if ($store->instagram ?? false)
                        <a href="{{ $store->instagram }}" target="_blank"
                            class="text-gray-600 hover:text-pink-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32"
                                aria-hidden="true" role="img">
                                <path fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5z" />
                                <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor"
                                    stroke-width="1.6" />
                                <circle cx="17.5" cy="6.5" r="0.6" fill="currentColor" />
                            </svg>

                        </a>
                    @endif
                    @if ($store->tiktok ?? false)
                        <a href="{{ $store->tiktok }}" target="_blank"
                            class="text-gray-600 hover:text-black transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-white transition-colors">Products</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-white transition-colors">Categories</a>
                    </li>
                    <li><a href="{{ route('about') }}" class="text-gray-600 hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-600 hover:text-white transition-colors">Contact</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                <ul class="space-y-2">
                    <li><a href="/shipping" class="text-gray-600 hover:text-white transition-colors">Shipping Info</a>
                    </li>
                    <li><a href="/returns" class="text-gray-600 hover:text-white transition-colors">Returns</a></li>
                    <li><a href="/faq" class="text-gray-600 hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="/support" class="text-gray-600 hover:text-white transition-colors">Support</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ $store->store_name ?? 'TokoKu' }}. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="/privacy" class="text-white hover:text-white text-sm transition-colors">Privacy Policy</a>
                    <a href="/terms" class="text-white hover:text-white text-sm transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>
