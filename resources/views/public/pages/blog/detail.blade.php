@extends('layouts.public')

@section('title', ($post->title ?? 'Detail Artikel') . ' — COOCA.ID Blog')
@section('description', $post->excerpt ?? ($post->title . ' — Baca artikel selengkapnya di Blog COOCA.ID.'))

@section('content')

@section('og_title', ($post->title ?? 'Detail Artikel') . ' — COOCA.ID Blog')
@section('og_description', $post->excerpt ?? ($post->title . ' — Baca artikel selengkapnya di Blog COOCA.ID.'))
@section('og_image', isset($post->featured_image) && $post->featured_image ? url(Storage::url($post->featured_image)) : asset('images/og-image.png'))

{{-- Blog Detail Header --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <a href="{{ route('blog.index') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: var(--text-muted); text-decoration: none; margin-bottom: 24px; padding: 8px 20px; border-radius: 100px; border: 1px solid var(--border); background: var(--surface); transition: all .2s; box-shadow: var(--shadow-sm);" onmouseover="this.style.color='var(--primary)'; this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.color='var(--text-muted)'; this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog Blog
            </a>

            @if(isset($post->category) && $post->category)
            <div style="margin-bottom: 16px;">
                <a href="{{ route('blog.index', ['category' => $post->category]) }}" style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; padding: 6px 16px; border-radius: 100px; background: rgba(79,70,229,.12); color: var(--primary); text-decoration: none; border: 1px solid rgba(79,70,229,.2);">
                    {{ $post->category }}
                </a>
            </div>
            @endif

            <h1 class="lp-heading reveal" style="font-size: clamp(32px, 5vw, 52px); margin-bottom: 24px; line-height: 1.15;">
                {{ $post->title }}
            </h1>

            <div style="display: inline-flex; align-items: center; justify-content: center; gap: 24px; font-size: 13px; font-weight: 500; color: var(--text-muted); flex-wrap: wrap; background: var(--surface); border: 1px solid var(--border); padding: 12px 28px; border-radius: 100px; box-shadow: var(--shadow-sm);">
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-regular fa-calendar" style="color: var(--primary);"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-regular fa-eye" style="color: var(--primary);"></i> {{ $post->views_count ?? 0 }} Pembaca</span>
                @if(isset($post->author) && $post->author)
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-regular fa-user" style="color: var(--primary);"></i> {{ $post->author->name }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Main Layout: Article Body + Sidebar --}}
<section class="lp-section">
    <div class="lp-container">
        <div class="blog-detail-grid">

            {{-- Main Article --}}
            <div>
                @if(isset($post->featured_image) && $post->featured_image)
                <div style="margin-bottom: 32px; border-radius: 20px; overflow: hidden; border: 1px solid var(--border);">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; max-height: 440px; object-fit: cover;">
                </div>
                @endif

                {{-- Mobile TOC --}}
                <div class="toc-widget toc-mobile-only" style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 32px; display: none;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(79,70,229,.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fa-solid fa-list-ul"></i>
                        </div>
                        Daftar Isi
                    </h3>
                    <ul class="toc-list" style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 12px;">
                        <!-- TOC Items will be generated here -->
                    </ul>
                </div>

                <article class="blog-content blog-article-card">
                    {!! $post->content ?? $post->excerpt ?? '' !!}
                </article>

                <style>
                    .blog-content {
                        font-size: 17px;
                        line-height: 1.85;
                        color: var(--text);
                    }
                    .blog-content p {
                        margin-bottom: 20px;
                    }
                    .blog-content h1, .blog-content h2, .blog-content h3, .blog-content h4 {
                        font-weight: 800;
                        color: var(--text);
                        margin-top: 40px;
                        margin-bottom: 16px;
                        line-height: 1.3;
                    }
                    .blog-content h1 { font-size: 32px; }
                    .blog-content h2 { font-size: 26px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
                    .blog-content h3 { font-size: 22px; }
                    .blog-content ul, .blog-content ol {
                        margin-bottom: 24px;
                        padding-left: 24px;
                    }
                    .blog-content li {
                        margin-bottom: 8px;
                    }
                    .blog-content a {
                        color: var(--primary);
                        text-decoration: underline;
                        text-underline-offset: 4px;
                    }
                    .blog-content strong {
                        font-weight: 700;
                        color: var(--text);
                    }
                    .blog-content blockquote {
                        border-left: 4px solid var(--primary);
                        padding-left: 16px;
                        margin: 24px 0;
                        font-style: italic;
                        color: var(--text-muted);
                        background: var(--bg);
                        padding: 16px 20px;
                        border-radius: 0 12px 12px 0;
                    }
                    .blog-content img {
                        max-width: 100%;
                        height: auto;
                        border-radius: 12px;
                        margin: 24px 0;
                    }
                </style>

                {{-- Author Bio --}}
                <div style="margin-top: 36px; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px; display: flex; align-items: center; gap: 20px;" class="reveal">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; font-weight: 800; flex-shrink: 0;">
                        <i class="fa-solid fa-pen-nib"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 4px;">Tim Redaksi COOCA.ID</h3>
                        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">Ditulis oleh tim spesialis industri &amp; teknologi ERP COOCA.ID.</p>
                    </div>
                </div>

                {{-- Related Posts Section --}}
                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                <div style="margin-top: 56px;" class="reveal">
                    <h3 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 24px;">Artikel Terkait</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        @foreach($relatedPosts as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 20px; text-decoration: none; display: flex; flex-direction: column; justify-content: space-between; transition: all .2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                            <div>
                                <span style="font-size: 11px; font-weight: 700; color: var(--primary); text-transform: uppercase;">{{ $rel->category ?? 'BLOG' }}</span>
                                <h4 style="font-size: 15px; font-weight: 700; color: var(--text); margin: 6px 0 8px; line-height: 1.4;">{{ Str::limit($rel->title, 60) }}</h4>
                            </div>
                            <span style="font-size: 12px; color: var(--text-muted);">{{ $rel->published_at ? $rel->published_at->format('d M Y') : $rel->created_at->format('d M Y') }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar (Search, Terpopuler, Terbaru, Kategori, Daftar Isi) --}}
            <aside style="display: flex; flex-direction: column; gap: 32px; position: sticky; top: 100px;">

                {{-- Widget 0: Daftar Isi (Table of Contents) --}}
                <div class="toc-widget toc-desktop-only" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px; display: none;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(79,70,229,.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fa-solid fa-list-ul"></i>
                        </div>
                        Daftar Isi
                    </h3>
                    <ul class="toc-list" style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 12px;">
                        <!-- TOC Items will be generated here -->
                    </ul>
                </div>

                {{-- Widget 1: Search --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(79,70,229,.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        Cari Artikel
                    </h3>
                    <form action="{{ route('blog.index') }}" method="GET">
                        <div style="position: relative; margin-bottom: 12px;">
                            <input type="text" name="search" placeholder="Ketik kata kunci..."
                                style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: var(--bg); color: var(--text); font-size: 13px; outline: none; font-family: inherit;">
                        </div>
                        <button type="submit" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 10px; font-size: 13px; border-radius: 10px;">
                            Cari Artikel
                        </button>
                    </form>
                </div>

                {{-- Widget 2: Terpopuler (Popular Posts) --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(79,70,229,.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fa-solid fa-fire"></i>
                        </div>
                        Artikel Terpopuler
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @if(isset($popularPosts) && $popularPosts->count() > 0)
                            @foreach($popularPosts as $popIndex => $pop)
                            <a href="{{ route('blog.show', $pop->slug) }}" style="display: flex; gap: 12px; text-decoration: none;">
                                <div style="width: 28px; height: 28px; border-radius: 8px; background: {{ $popIndex === 0 ? 'linear-gradient(135deg,#f59e0b,#ef4444)' : 'var(--bg)' }}; color: {{ $popIndex === 0 ? '#fff' : 'var(--text-muted)' }}; font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border);">
                                    {{ $popIndex + 1 }}
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.4; margin-bottom: 4px;">{{ Str::limit($pop->title, 55) }}</h4>
                                    <div style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-eye"></i> {{ $pop->views_count ?? 0 }} views</div>
                                </div>
                            </a>
                            @endforeach
                        @else
                            @php
                            $popDemos = [
                                ['Cara Mengoptimalkan Manajemen Stok Bahan Baku Restoran', '1,240 views'],
                                ['Transformasi Digital Rekam Medis Klinik sesuai Kemenkes', '980 views'],
                                ['Mengelola Cabang Bengkel Terpusat Tanpa Ribet', '850 views'],
                            ];
                            @endphp
                            @foreach($popDemos as $pi => $pdemo)
                            <a href="{{ route('blog.index') }}" style="display: flex; gap: 12px; text-decoration: none;">
                                <div style="width: 28px; height: 28px; border-radius: 8px; background: {{ $pi === 0 ? 'linear-gradient(135deg,#f59e0b,#ef4444)' : 'var(--bg)' }}; color: {{ $pi === 0 ? '#fff' : 'var(--text-muted)' }}; font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border);">
                                    {{ $pi + 1 }}
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.4; margin-bottom: 4px;">{{ $pdemo[0] }}</h4>
                                    <div style="font-size: 11px; color: var(--text-muted);"><i class="fa-solid fa-eye"></i> {{ $pdemo[1] }}</div>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Widget 3: Terbaru (Recent Posts) --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(79,70,229,.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        Artikel Terbaru
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @if(isset($recentPosts) && $recentPosts->count() > 0)
                            @foreach($recentPosts as $rec)
                            <a href="{{ route('blog.show', $rec->slug) }}" style="display: flex; align-items: flex-start; gap: 10px; text-decoration: none;">
                                <div style="width: 20px; height: 20px; border-radius: 6px; background: var(--bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border); color: var(--text-muted); font-size: 10px;">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                                <div>
                                    <h4 style="font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.4; margin-bottom: 2px;">{{ Str::limit($rec->title, 50) }}</h4>
                                    <span style="font-size: 11px; color: var(--text-muted);">{{ $rec->published_at ? $rec->published_at->format('d M Y') : $rec->created_at->format('d M Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        @else
                            @foreach(['Panduan Menyiapkan Pembukuan Usaha Retail', '5 Fitur Wajib POS Restoran Modern 2025'] as $rd)
                            <a href="{{ route('blog.index') }}" style="display: flex; align-items: flex-start; gap: 10px; text-decoration: none;">
                                <div style="width: 20px; height: 20px; border-radius: 6px; background: var(--bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border); color: var(--text-muted); font-size: 10px;">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                                <div>
                                    <h4 style="font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.4; margin-bottom: 2px;">{{ $rd }}</h4>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Widget 4: Kategori (Categories List) --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 10px; background: rgba(79,70,229,.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        Kategori Artikel
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $cName)
                            <a href="{{ route('blog.index', ['category' => $cName]) }}"
                                style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 12px; border-radius: 8px; color: var(--text-muted); text-decoration: none; border: 1px solid transparent; transition: all .2s;"
                                onmouseover="this.style.background='var(--bg)'; this.style.borderColor='var(--border)'" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent'">
                                <span style="display: flex; align-items: center; gap: 8px;"><i class="fa-regular fa-folder" style="color: var(--primary); opacity: 0.8;"></i> {{ $cName }}</span>
                                <span style="font-size: 10px; opacity: 0.5;"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                            @endforeach
                        @else
                            @foreach(['Restoran & F&B', 'Klinik & Medis', 'Bengkel & Otomotif', 'Notaris & Legal', 'Teknologi & Cloud'] as $cName)
                            <a href="{{ route('blog.index', ['category' => $cName]) }}"
                                style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 12px; border-radius: 8px; color: var(--text-muted); text-decoration: none; border: 1px solid transparent; transition: all .2s;"
                                onmouseover="this.style.background='var(--bg)'; this.style.borderColor='var(--border)'" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent'">
                                <span style="display: flex; align-items: center; gap: 8px;"><i class="fa-regular fa-folder" style="color: var(--primary); opacity: 0.8;"></i> {{ $cName }}</span>
                                <span style="font-size: 10px; opacity: 0.5;"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

            </aside>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const article = document.querySelector('.blog-content');
        if (!article) return;

        const headings = article.querySelectorAll('h1, h2, h3, h4');
        if (headings.length === 0) return;

        const tocWidgets = document.querySelectorAll('.toc-widget');
        const tocLists = document.querySelectorAll('.toc-list');
        
        tocWidgets.forEach(w => w.style.display = 'block');

        headings.forEach((heading, index) => {
            // Assign ID if it doesn't have one
            if (!heading.id) {
                heading.id = 'heading-' + index;
            }

            // Indentation based on heading level
            let level = 2; // Default to h2 level if something goes wrong
            const match = heading.tagName.match(/\d/);
            if (match) level = parseInt(match[0]);
            
            const isSub = level > 2;
            const indent = Math.max(0, (level - 2) * 16);

            tocLists.forEach(tocList => {
                const li = document.createElement('li');
                
                const a = document.createElement('a');
                a.href = '#' + heading.id;
                
                // Tambahkan ikon visual untuk sub-heading
                const iconHTML = isSub ? '<span style="opacity:0.5; font-size:14px; margin-right:8px; display:inline-block; transform:translateY(-2px);">↳</span>' : '';
                a.innerHTML = iconHTML + heading.textContent;
                
                a.style.fontSize = isSub ? '13px' : '14px';
                a.style.fontWeight = isSub ? '500' : '600';
                a.style.lineHeight = '1.4';
                a.style.color = 'var(--text-muted)';
                a.style.textDecoration = 'none';
                a.style.display = 'flex';
                a.style.alignItems = 'flex-start';
                a.style.paddingLeft = indent + 'px';
                a.style.transition = 'all 0.2s ease';
                
                a.onmouseover = () => {
                    a.style.color = 'var(--primary)';
                    if (!isSub) a.style.transform = 'translateX(4px)';
                };
                a.onmouseout = () => {
                    a.style.color = 'var(--text-muted)';
                    if (!isSub) a.style.transform = 'translateX(0)';
                };

                li.appendChild(a);
                tocList.appendChild(li);
            });
        });
    });
</script>
@endpush

@endsection
