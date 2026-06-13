@extends('layouts.app')

@section('title', 'Blog - Cooca.id')
@section('description', 'Artikel terbaru tentang ERP, bisnis, dan teknologi untuk membantu pertumbuhan usaha Anda.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-50 via-white to-purple-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Blog & Insights
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Artikel terbaru tentang ERP, manajemen bisnis, dan teknologi untuk membantu pertumbuhan usaha Anda
            </p>
        </div>
    </div>
</section>

<!-- Featured Posts -->
@if($featuredPosts->count() > 0)
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Featured Articles</h2>
            <span class="text-sm text-gray-500">Pilihan terbaik untuk Anda</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredPosts as $post)
            <article class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <span class="text-4xl font-bold text-white">{{ substr($post->title, 0, 2) }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                            {{ $post->category ?? 'General' }}
                        </span>
                        @if($post->is_featured)
                        <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">
                            ⭐ Featured
                        </span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                        <a href="{{ route('blog.show', $post->slug) }}">
                            {{ $post->title }}
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit(strip_tags($post->content), 150) }}
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ $post->author ? substr($post->author->name, 0, 2) : 'A' }}
                            </div>
                            <span class="text-sm text-gray-600">
                                {{ $post->author?->name ?? 'Admin' }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">
                            {{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}
                        </span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Content with Sidebar -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Blog List -->
            <div class="lg:col-span-3">
                <!-- Search and Filter -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <form action="{{ route('blog.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    🔍 Cari Artikel
                                </label>
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Cari judul atau konten..." 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    📁 Kategori
                                </label>
                                <select 
                                    name="category" 
                                    id="category" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Filter
                            </button>
                            <a href="{{ route('blog.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Blog Posts Grid -->
                @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-40 object-cover">
                            @else
                            <div class="w-full h-40 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">{{ substr($post->title, 0, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                                {{ $post->category ?? 'General' }}
                            </span>
                            
                            <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ Str::limit(strip_tags($post->content), 100) }}
                            </p>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}</span>
                                <span>👁 {{ $post->view_count ?? 0 }}</span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links('vendor.pagination.tailwind') }}
                </div>
                @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada artikel</h3>
                    <p class="text-gray-600">Artikel akan segera hadir. Stay tuned!</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-6">
                <!-- About Widget -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tentang Blog</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Blog Cooca.id berbagi insights tentang ERP, manajemen bisnis, teknologi, dan tips pengembangan usaha untuk UMKM Indonesia.
                    </p>
                    <a href="{{ route('about') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Learn more →
                    </a>
                </div>

                <!-- Categories Widget -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('blog.index') }}" class="flex items-center justify-between text-gray-700 hover:text-blue-600 transition-colors">
                                <span>Semua</span>
                                <span class="px-2 py-1 text-xs bg-gray-100 rounded-full">{{ $posts->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blog.index', ['category' => $cat]) }}" class="flex items-center justify-between text-gray-700 hover:text-blue-600 transition-colors">
                                <span>{{ $cat }}</span>
                                <span class="px-2 py-1 text-xs bg-gray-100 rounded-full">
                                    {{ BlogPost::where('category', $cat)->where('is_published', true)->count() }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- CTA Widget -->
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-md p-6 text-white">
                    <h3 class="text-lg font-bold mb-2">Butuh Solusi ERP?</h3>
                    <p class="text-sm text-blue-100 mb-4">
                        Konsultasikan kebutuhan bisnis Anda dengan tim ahli kami.
                    </p>
                    <a href="{{ route('contact') }}" class="block w-full bg-white text-blue-600 text-center py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors">
                        Hubungi Kami
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Subscribe Newsletter</h2>
        <p class="text-gray-600 mb-6">Dapatkan artikel terbaru langsung ke inbox Anda</p>
        <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
            <input 
                type="email" 
                placeholder="Email Anda" 
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                Subscribe
            </button>
        </form>
    </div>
</section>
@endsection
