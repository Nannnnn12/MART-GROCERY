@extends('layouts.app')

@section('title', 'Artikel - ' . $store->store_name ?? 'Toko Online')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50">

        <div class="py-8 px-4 sm:px-8 md:px-16 lg:px-56">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Artikel & Berita
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Temukan informasi menarik, tips, dan berita terbaru dari {{ $store->store_name ?? 'toko kami' }}
                </p>
            </div>

            <!-- Main Content and Sidebar Layout -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-3">
                    <!-- Articles Grid -->
                    @if ($articles->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($articles as $article)
                        <article class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-gray-100 group">
                            <a href="{{ route('articles.show', $article->slug) }}" class="block">
                                <div class="relative overflow-hidden">
                                    @if ($article->image)
                                        <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-gray-100 to-gray-200">
                                            <img src="{{ asset('storage/' . $article->image) }}"
                                                alt="{{ $article->title }}"
                                                class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700"
                                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder-article.png') }}';">
                                        </div>
                                    @else
                                        <div class="w-full h-56 bg-gradient-to-br from-yellow-100 via-purple-50 to-pink-100 flex items-center justify-center">
                                            <svg class="w-20 h-20 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-white/95 backdrop-blur-sm text-gray-800 px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                            @if($article->category)
                                                {{ $article->category->name }}
                                            @else
                                                Artikel
                                            @endif
                                        </span>
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>

                                <div class="p-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-green-600 transition-colors duration-300">
                                        {{ $article->title }}
                                    </h2>

                                    <p class="text-gray-600 text-base mb-6 line-clamp-3 leading-relaxed">
                                        {!! Str::limit(strip_tags($article->content), 180) !!}
                                    </p>

                                    <div class="flex items-center justify-between">
                                        <time datetime="{{ $article->created_at->format('Y-m-d') }}" class="text-sm text-gray-500 font-medium">
                                            {{ $article->created_at->format('d M Y') }}
                                        </time>
                                        <span class="flex items-center text-green-600 font-semibold text-sm group-hover:text-green-700 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Baca Selengkapnya
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                        <!-- Pagination -->
                        <div class="flex justify-center">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-20">
                            <div class="w-32 h-32 bg-gradient-to-br from-green-100 via-emerald-50 to-teal-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl">
                                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">Belum Ada Artikel</h3>
                            <p class="text-gray-600 mb-10 max-w-lg mx-auto text-lg">
                                @if($selectedCategory)
                                    Tidak ada artikel dalam kategori ini. Coba pilih kategori lain atau lihat semua artikel.
                                @else
                                    Kami sedang menyiapkan konten menarik untuk Anda. Silakan kembali lagi nanti!
                                @endif
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                @if($selectedCategory)
                                    <a href="{{ route('articles.index') }}"
                                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        Lihat Semua Artikel
                                    </a>
                                @endif
                                <a href="{{ route('home') }}"
                                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-2xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="sticky top-8">
                        <!-- Categories Section -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Kategori Artikel
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('articles.index') }}"
                                    class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ !$selectedCategory ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                                    <span>Semua Kategori</span>
                                    @if(!$selectedCategory)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('articles.index', ['category' => $category->id]) }}"
                                        class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ $selectedCategory == $category->id ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                                        <span>{{ $category->name }}</span>
                                        @if($selectedCategory == $category->id)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Articles -->
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Artikel Terbaru
                            </h3>
                            <div class="space-y-4">
                                @foreach($articles->take(5) as $recentArticle)
                                    <a href="{{ route('articles.show', $recentArticle->slug) }}" class="block group">
                                        <div class="flex items-start space-x-3">
                                            @if($recentArticle->image)
                                                <img src="{{ asset('storage/' . $recentArticle->image) }}"
                                                    alt="{{ $recentArticle->title }}"
                                                    class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                            @else
                                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors line-clamp-2">
                                                    {{ $recentArticle->title }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $recentArticle->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
