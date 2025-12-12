<!-- TOP HEADER -->
<header class="bg-white font-sans">
    <style>
        @media (max-width: 640px) {
            .header-wrap {
                flex-wrap: wrap;
                height: auto !important;
                padding-bottom: 1rem;
            }

            .search-mobile {
                width: 100%;
                margin-top: .75rem;
            }
        }


        /* Account Dropdown Animation */
        .account-dropdown-menu {
            transition: all 0.2s ease-out;
        }

        .account-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .account-dropdown-toggle svg:last-child {
            transition: transform 0.2s ease-out;
        }

        .account-dropdown-toggle.active svg:last-child {
            transform: rotate(180deg);
        }

        /* Sidebar Animation */
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        .sidebar-overlay.open {
            opacity: 0.3;
            visibility: visible;
        }
    </style>
    <div
        class="header-wrap mx-auto px-4 sm:px-8 md:px-16 lg:px-32 xl:px-56 py-4 flex items-center justify-between gap-4">

        <!-- Hamburger Menu for md and below -->
        <button
            class="lg:hidden flex items-center justify-center w-8 h-8 text-gray-700 hover:text-green-600 transition-colors duration-200"
            id="hamburger-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <!-- Logo -->
        <div class="flex flex-col items-center gap-1 sm:gap-2 shrink-0">
            <div class="text-3xl sm:text-4xl md:text-5xl font-bold text-green-700">Nann</div>
            <span class="text-gray-500 text-sm sm:text-base md:text-lg">MART & GROCERY</span>
        </div>

        <!-- Search Bar -->
        <div
            class="search-mobile flex flex-1 max-w-7xl border border-emerald-400 rounded-md overflow-hidden hover:shadow-md transition-all duration-200">

            <input type="text" placeholder="Search for items..."
                class="flex-1 px-2 sm:px-4 focus:outline-none focus:ring-0 text-gray-600 h-10 sm:h-12 text-sm sm:text-base md:text-lg">
            <button class="px-2 sm:px-4 text-gray-500 hover:bg-gray-100 hover:scale-105 transition-all duration-200">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>

        <div>
            <!-- Icons -->
            <div
                class="flex items-center gap-2 sm:gap-4 md:gap-6 text-gray-700 shrink-0 text-sm sm:text-base md:text-lg">

                @auth
                    <a href="{{ route('cart.index') }}"
                        class="relative flex items-center gap-1 hover:text-blue-500 hover:scale-105 transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3">
                            </path>
                        </svg>
                        Cart
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                        @endphp
                        @if ($cartCount > 0)
                            <span
                                class="absolute -top-2 -right-3 bg-green-600 text-white text-xs px-1 rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endauth

                <div class="relative">
                    <div
                        class="flex items-center gap-1 hover:text-gray-900 hover:scale-105 transition-all duration-200 cursor-pointer account-dropdown-toggle">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Account
                        @if (auth()->check() && is_null(auth()->user()->phone_number))
                            <span
                                class="absolute left-1/2 top-10 -translate-x-1/2
               bg-yellow-300 text-black font-semibold
               text-[11px] px-3 py-[6px]
               rounded-full whitespace-nowrap
               shadow-md animate-pulse">
                                Lengkapi Nomor WhatsApp Anda
                            </span>
                        @endif


                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>

                    <!-- Dropdown Menu -->
                    <div
                        class="account-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transform translate-y-2 transition-all duration-200 ease-out z-50">
                        <div class="py-2">
                            @auth
                                <a href="{{ route('profile.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="/admin"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Admin Panel
                                    </a>
                                @endif

                                @if (auth()->user()->role === 'user')
                                    <a href="{{ route('orders.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        Pesanan Saya
                                    </a>
                                @endif
                                <hr class="my-1 border-gray-200">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-150">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                    Register
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GREEN MENU BAR -->
    <div class="hidden lg:block bg-green-400">
        <div
            class="justify-center space-x-2 sm:space-x-4 md:space-x-5 mx-auto px-4 sm:px-6 py-4 flex h-16 items-center gap-4 sm:gap-6 md:gap-8 text-white">

            <a href="#"
                class="text-xl flex items-center gap-1 hover:text-yellow-200 hover:scale-105 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                </svg>
                Deals
            </a>
            <a href="{{ route('home') }}"
                class="text-xl hover:text-yellow-200 hover:scale-105 transition-all duration-200">Home</a>
            <a href="
                {{ route('about') }}"
                class="text-xl hover:text-yellow-200 hover:scale-105 transition-all duration-200">About</a>
            <a href="{{ route('products.index') }}"
                class="text-xl hover:text-yellow-200 hover:scale-105 transition-all duration-200">Shop</a>
            <a href="{{ route('articles.index') }}"
                class="text-xl hover:text-yellow-200 hover:scale-105 transition-all duration-200">Blog</a>
            <a href="{{ route('contact') }}"
                class="text-xl hover:text-yellow-200 hover:scale-105 transition-all duration-200">Contact</a>
        </div>
    </div>

    <!-- SIDEBAR for md and below -->
    <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>
    <div id="sidebar" class="sidebar fixed top-0 left-0 w-64 h-full bg-green-400 z-50 lg:hidden">
        <div class="flex flex-col h-full">
            <!-- Close Button -->
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class="text-white hover:text-yellow-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu Items -->
            <nav class="flex flex-col px-4 py-4 space-y-4">
                <a href="#"
                    class="text-white text-lg flex items-center gap-2 hover:text-yellow-200 hover:scale-105 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                    </svg>
                    Deals
                </a>
                <a href="{{ route('home') }}"
                    class="text-white text-lg hover:text-yellow-200 hover:scale-105 transition-all duration-200">Home</a>
                <a href="{{ route('about') }}"
                    class="text-white text-lg hover:text-yellow-200 hover:scale-105 transition-all duration-200">About</a>
                <a href="{{ route('products.index') }}"
                    class="text-white text-lg hover:text-yellow-200 hover:scale-105 transition-all duration-200">Shop</a>
                <a href="{{ route('articles.index') }}"
                    class="text-white text-lg hover:text-yellow-200 hover:scale-105 transition-all duration-200">Blog</a>
                <a href="{{ route('contact') }}"
                    class="text-white text-lg hover:text-yellow-200 hover:scale-105 transition-all duration-200">Contact</a>
            </nav>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function openSidebar() {
                sidebar.classList.add('open');
                sidebarOverlay.classList.add('open');
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('open');
            }

            hamburgerBtn.addEventListener('click', openSidebar);
            closeSidebarBtn.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
        });
    </script>
</header>
