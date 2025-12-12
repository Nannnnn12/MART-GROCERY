@extends('layouts.app')

@section('title', $article->title . ' - ' . ($store->store_name ?? 'Toko Online'))

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Article Header -->
            <header class="mb-12">
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6">
                    @if($article->category)
                        <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full font-semibold shadow-lg">
                            {{ $article->category->name }}
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full font-semibold">
                            Artikel
                        </span>
                    @endif
                    <span class="text-gray-400">•</span>
                    <time datetime="{{ $article->created_at->format('Y-m-d') }}" class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $article->created_at->setTimezone('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y') }}
                    </time>
                </div>

                <h1 class="text-5xl font-bold text-gray-900 mb-8 leading-tight bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                    {{ $article->title }}
                </h1>

                @if ($article->image)
                    <div class="relative overflow-hidden rounded-3xl shadow-2xl mb-12 group">
                        <img src="{{ asset('storage/' . $article->image) }}"
                            alt="{{ $article->title }}"
                            class="w-full h-80 sm:h-96 lg:h-[500px] object-cover group-hover:scale-105 transition-transform duration-700"
                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder-article.png') }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                @endif
            </header>

            <!-- Article Content -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <article class="lg:col-span-3 bg-white rounded-3xl shadow-xl p-8 lg:p-12 overflow-hidden">
                    @if ($article->image)
                        <div class="relative overflow-hidden rounded-2xl shadow-lg mb-8 group">
                            <img src="{{ asset('storage/' . $article->image) }}"
                                alt="{{ $article->title }}"
                                class="w-full h-64 sm:h-80 object-cover group-hover:scale-105 transition-transform duration-500"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder-article.png') }}';">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    @endif
                    <div class="prose prose-xl max-w-none break-words overflow-wrap-anywhere prose">
                        {!! $article->content !!}
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-6">
                    <!-- Categories Section -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Kategori Artikel
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('articles.index') }}"
                                class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 text-gray-700 hover:bg-gray-50 border border-gray-200">
                                <span>Semua Kategori</span>
                            </a>
                            @foreach($categories ?? [] as $category)
                                <a href="{{ route('articles.index', ['category' => $category->id]) }}"
                                    class="flex items-center justify-between px-4 py-3 rounded-lg font-medium transition-all duration-200 {{ $article->category && $article->category->id == $category->id ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                                    <span>{{ $category->name }}</span>
                                    @if($article->category && $article->category->id == $category->id)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Author Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tentang Artikel</h3>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Ditulis oleh Admin</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Status: {{ $article->is_published ? 'Dipublikasikan' : 'Draft' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0V1m10 3V1m0 3l1 1v16a2 2 0 01-2 2H6a2 2 0 01-2-2V5l1-1z"></path>
                                </svg>
                                <span>Judul: {{ $article->title }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Share Article -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Bagikan Artikel</h3>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="shareOnFacebook()" class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </button>
                            <button onclick="shareOnTwitter()" class="flex items-center justify-center w-10 h-10 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </button>
                            <button onclick="shareOnWhatsApp()" class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                            </button>
                            <button onclick="copyLink()" class="flex items-center justify-center w-10 h-10 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-3xl shadow-xl p-8 mt-8">
                <a href="{{ route('articles.index') }}"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 rounded-2xl hover:from-gray-200 hover:to-gray-300 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Artikel
                </a>

                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-2xl hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </nav>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .prose {
        color: #374151;
        font-size: 1.125rem;
        line-height: 1.8;
    }

    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
        color: #111827;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
    }

    .prose h1 { font-size: 2.5rem; line-height: 2.75rem; }
    .prose h2 { font-size: 2rem; line-height: 2.5rem; }
    .prose h3 { font-size: 1.75rem; line-height: 2.25rem; }
    .prose h4 { font-size: 1.5rem; line-height: 2rem; }

    .prose p {
        margin-bottom: 1.75rem;
        line-height: 1.8;
    }

    .prose ul, .prose ol {
        margin-bottom: 1.75rem;
        padding-left: 1.75rem;
    }

    .prose li {
        margin-bottom: 0.75rem;
        line-height: 1.7;
    }

    .prose blockquote {
        border-left: 5px solid #3b82f6;
        padding-left: 1.25rem;
        margin: 2.5rem 0;
        font-style: italic;
        color: #6b7280;
        background: #f8fafc;
        padding: 1.5rem 1.25rem 1.5rem 2rem;
        border-radius: 0 0.5rem 0.5rem 0;
        position: relative;
    }

    .prose blockquote::before {
        content: '"';
        font-size: 4rem;
        color: #3b82f6;
        position: absolute;
        top: -0.5rem;
        left: 0.5rem;
        opacity: 0.3;
    }

    .prose img {
        border-radius: 1rem;
        margin: 2.5rem 0;
        max-width: 100%;
        height: auto;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease;
    }

    .prose img:hover {
        transform: scale(1.02);
    }

    .prose a {
        color: #3b82f6;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: border-color 0.2s ease;
    }

    .prose a:hover {
        border-bottom-color: #3b82f6;
        color: #1d4ed8;
    }

    .prose strong {
        font-weight: 600;
        color: #111827;
    }

    .prose code {
        background: #f1f5f9;
        color: #dc2626;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-size: 0.875em;
        font-weight: 500;
    }

    .prose pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1.5rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        margin: 2rem 0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        word-wrap: break-word;
        white-space: pre-wrap;
        word-break: break-all;
    }

    .prose pre code {
        background: transparent;
        color: inherit;
        padding: 0;
    }

    .prose table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }

    .prose thead th {
        background: #f8fafc;
        color: #374151;
        font-weight: 600;
        text-align: left;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
    }

    .prose tbody td {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        color: #374151;
    }

    .prose tbody tr:nth-child(even) {
        background: #f8fafc;
    }

    .prose tbody tr:hover {
        background: #f1f5f9;
    }

    .prose blockquote {
        border-left: 4px solid #10b981;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #6b7280;
        background: #f0fdf4;
        padding: 1.5rem 1.5rem 1.5rem 2.5rem;
        border-radius: 0 0.5rem 0.5rem 0;
        position: relative;
        font-size: 1.125rem;
        line-height: 1.75rem;
    }

    .prose blockquote::before {
        content: '"';
        font-size: 3rem;
        color: #10b981;
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        opacity: 0.4;
        font-family: Georgia, serif;
    }

    .prose blockquote p {
        margin: 0;
        display: inline;
    }

    .prose blockquote p:first-child::before {
        content: '"';
        font-size: 2rem;
        color: #10b981;
        margin-right: 0.25rem;
        opacity: 0.6;
    }

    .prose blockquote p:last-child::after {
        content: '"';
        font-size: 2rem;
        color: #10b981;
        margin-left: 0.25rem;
        opacity: 0.6;
    }
</style>
@endpush

@push('scripts')
<script>
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`Baca artikel: ${document.title}`);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent(`Baca artikel: ${document.title} - ${url}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        // Simple feedback
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    });
}
</script>
@endpush
