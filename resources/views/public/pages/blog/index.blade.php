@extends('layouts.public')

@section('title', 'Wawasan Bisnis & Analisis Data | COOCA.ID Blog')
@section('description', 'Temukan artikel seputar otomatisasi, efisiensi operasional, dan strategi data-driven decision untuk membawa bisnis Anda melaju lebih cepat.')
@section('keywords', 'blog bisnis indonesia, cara mengambil keputusan bisnis, tips efisiensi operasional, strategi data driven business, panduan software erp')

@section('content')

{{-- Hero Section --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="text-align: center; max-width: 720px; margin: 0 auto;">
            <span class="lp-eyebrow">BLOG &amp; WAKTU BISNIS</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(40px, 5vw, 60px); margin-bottom: 16px;">
                Wawasan <span class="gradient-text">Pertumbuhan Bisnis</span>
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto 32px;">
                Strategi, panduan teknis, dan tren industri terupdate untuk memajukan operasional bisnis Anda.
            </p>

            {{-- Main Search Bar --}}
            <form action="{{ route('blog.index') }}" method="GET" class="reveal filter-row" style="max-width: 580px; margin: 0 auto;">
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="filter-search" style="position: relative;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 16px; color: var(--text-muted);">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel, topik, atau kata kunci..."
                        style="width: 100%; padding: 14px 16px 14px 44px; border: 1px solid var(--border); border-radius: 14px; background: var(--surface); color: var(--text); font-size: 14px; outline: none; font-family: inherit; box-shadow: var(--shadow-sm); transition: border-color .2s;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <button type="submit" class="btn-primary-glow filter-btn" style="padding: 14px 24px; border-radius: 14px; font-size: 14px;">Cari</button>
            </form>
        </div>
    </div>
</section>

{{-- Category Filter Pills --}}
<section style="background: var(--bg-section); border-bottom: 1px solid var(--border); padding: 16px 0; position: sticky; top: 72px; z-index: 90; backdrop-filter: var(--glass-blur);">
    <div class="lp-container">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div class="blog-category-pills">
                <a href="{{ route('blog.index', request()->only('search')) }}"
                    style="flex-shrink: 0; padding: 6px 16px; border-radius: 100px; font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all .2s; {{ !request('category') ? 'background: var(--primary); color: #fff;' : 'background: var(--surface); color: var(--text-muted); border: 1px solid var(--border);' }}">
                    Semua Kategori
                </a>

                @if(isset($categories) && count($categories) > 0)
                    @foreach($categories as $cat)
                    <a href="{{ route('blog.index', array_merge(request()->only('search'), ['category' => $cat])) }}"
                        style="flex-shrink: 0; padding: 6px 16px; border-radius: 100px; font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all .2s; {{ request('category') === $cat ? 'background: var(--primary); color: #fff;' : 'background: var(--surface); color: var(--text-muted); border: 1px solid var(--border);' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                @else
                    @php $demoCats = ['Restoran & F&B', 'Klinik & Medis', 'Bengkel & Otomotif', 'Notaris & Legal', 'Teknologi & Cloud', 'Manajemen Stok']; @endphp
                    @foreach($demoCats as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat]) }}"
                        style="flex-shrink: 0; padding: 6px 16px; border-radius: 100px; font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap; transition: all .2s; {{ request('category') === $cat ? 'background: var(--primary); color: #fff;' : 'background: var(--surface); color: var(--text-muted); border: 1px solid var(--border);' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                @endif
            </div>

            @if(request()->anyFilled(['search', 'category']))
            <div class="hapus-filter-wrapper">
                <a href="{{ route('blog.index') }}" style="font-size: 12px; font-weight: 600; color: #ef4444; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    ✕ Hapus Filter
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Main Content & Sidebar Layout --}}
<section class="lp-section">
    <div class="lp-container">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 48px; align-items: start;">

            {{-- Main Blog Feed --}}
            <div>
                @if(request('search') || request('category'))
                <div style="margin-bottom: 24px; padding: 14px 20px; background: var(--surface); border: 1px solid var(--border); border-radius: 14px; font-size: 14px; color: var(--text-muted);">
                    Menampilkan hasil untuk
                    @if(request('search')) "<strong>{{ request('search') }}</strong>" @endif
                    @if(request('category')) kategori <strong style="color: var(--primary);">{{ request('category') }}</strong> @endif
                    ({{ $posts->total() ?? (isset($posts) ? count($posts) : 0) }} artikel)
                </div>
                @endif

                @if(isset($posts) && $posts->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 40px;">
                    @foreach($posts as $i => $post)
                    <article class="reveal reveal-delay-{{ ($i % 2) + 1 }}" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: all .3s ease;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                        <div style="height: 200px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); overflow: hidden; position: relative;">
                            @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:48px;">📰</div>
                            @endif
                            @if($post->category)
                            <a href="{{ route('blog.index', ['category' => $post->category]) }}" style="position:absolute;top:12px;left:12px;background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;text-transform:uppercase;text-decoration:none;">{{ $post->category }}</a>
                            @endif
                        </div>

                        <div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">
                                <span>📅 {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                <span><i class="fa-solid fa-eye"></i> {{ $post->views_count ?? 0 }} views</span>
                            </div>

                            <h2 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 10px; line-height: 1.4;">
                                <a href="{{ route('blog.show', $post->slug) }}" style="color: inherit; text-decoration: none;">{{ $post->title }}</a>
                            </h2>

                            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; flex: 1;">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 110) }}
                            </p>

                            <a href="{{ route('blog.show', $post->slug) }}" style="font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
                <div style="display:flex;justify-content:center;margin-top:40px;">
                    {{ $posts->links() }}
                </div>
                @endif

                @else
                {{-- Fallback Blog Cards if DB is empty or search returns 0 --}}
                @php
                $demoPosts = [
                    ['slug'=>'manajemen-stok-restoran','title'=>'Cara Mengoptimalkan Manajemen Stok Bahan Baku Restoran','cat'=>'Restoran & F&B','date'=>'24 Jul 2025','views'=>1240,'desc'=>'Pelajari bagaimana sistem POS terintegrasi mampu mencegah kebocoran bahan baku hingga 25% di bisnis F&B Anda.'],
                    ['slug'=>'rekam-medis-elektronik-klinik','title'=>'Transformasi Digital Rekam Medis Klinik sesuai Regulasi Kemenkes','cat'=>'Klinik & Medis','date'=>'20 Jul 2025','views'=>980,'desc'=>'Panduan lengkap penerapan EMR digital untuk efisiensi antrian dan kepatuhan standar rekam medis nasional.'],
                    ['slug'=>'kelola-cabang-bengkel','title'=>'Mengelola Cabang Bengkel Secara Terpusat Tanpa Ribet','cat'=>'Bengkel & Otomotif','date'=>'15 Jul 2025','views'=>850,'desc'=>'Tips praktis memantau produktivitas teknisi, stok sparepart, dan arus kas di banyak cabang bengkel sekaligus.'],
                    ['slug'=>'manajemen-dokumen-legal-notaris','title'=>'Strategi Efisiensi Manajemen Dokumen Legal & Akta Notaris','cat'=>'Notaris & Legal','date'=>'10 Jul 2025','views'=>620,'desc'=>'Bagaimana teknologi cloud membantu kantor Notaris & PPAT mengamankan berkas akta dan mempercepat proses penagihan.'],
                ];
                @endphp
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 40px;">
                    @foreach($demoPosts as $i => $dp)
                    <article class="reveal reveal-delay-{{ ($i % 2) + 1 }}" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: all .3s ease;">
                        <div style="height: 200px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); display: flex; align-items: center; justify-content: center; font-size: 56px; position: relative;">
                            📰
                            <span style="position:absolute;top:12px;left:12px;background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:100px;text-transform:uppercase;">{{ $dp['cat'] }}</span>
                        </div>
                        <div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">
                                <span>📅 {{ $dp['date'] }}</span>
                                <span><i class="fa-solid fa-eye"></i> {{ $dp['views'] }} views</span>
                            </div>
                            <h2 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 10px; line-height: 1.4;">{{ $dp['title'] }}</h2>
                            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; flex: 1;">{{ $dp['desc'] }}</p>
                            <a href="{{ route('blog.index') }}" style="font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none;">Baca Selengkapnya →</a>
                        </div>
                    </article>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ── SIDEBAR (Search, Categories, Recent, Popular) ───────────────── --}}
            <aside style="display: flex; flex-direction: column; gap: 32px; position: sticky; top: 140px;">


                {{-- Widget 2: Terpopuler (Popular Posts) --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span>🔥</span> Artikel Terpopuler
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @if(isset($popularPosts) && $popularPosts->count() > 0)
                            @foreach($popularPosts as $popIndex => $pop)
                            <a href="{{ route('blog.show', $pop->slug) }}" style="display: flex; gap: 12px; text-decoration: none; group;">
                                <div style="width: 28px; height: 28px; border-radius: 8px; background: {{ $popIndex === 0 ? 'linear-gradient(135deg,#f59e0b,#ef4444)' : 'var(--bg)' }}; color: {{ $popIndex === 0 ? '#fff' : 'var(--text-muted)' }}; font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border);">
                                    {{ $popIndex + 1 }}
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.4; margin-bottom: 4px; transition: color .2s;">{{ Str::limit($pop->title, 55) }}</h4>
                                    <div style="font-size: 11px; color: var(--text-muted); display: flex; gap: 8px;">
                                        <span><i class="fa-solid fa-eye"></i> {{ $pop->views_count ?? 0 }} views</span>
                                        @if($pop->category)<span>· {{ $pop->category }}</span>@endif
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        @else
                            @php
                            $popDemos = [
                                ['Cara Mengoptimalkan Manajemen Stok Bahan Baku Restoran', '1,240 views'],
                                ['Transformasi Digital Rekam Medis Klinik sesuai Kemenkes', '980 views'],
                                ['Mengelola Cabang Bengkel Terpusat Tanpa Ribet', '850 views'],
                                ['Efisiensi Berkas Akta Notaris di Cloud Platform', '620 views'],
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
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span><i class="fa-solid fa-sparkles"></i></span> Artikel Terbaru
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @if(isset($recentPosts) && $recentPosts->count() > 0)
                            @foreach($recentPosts as $rec)
                            <a href="{{ route('blog.show', $rec->slug) }}" style="display: flex; align-items: flex-start; gap: 10px; text-decoration: none;">
                                <span style="font-size: 14px;">⚡</span>
                                <div>
                                    <h4 style="font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.4; margin-bottom: 2px;">{{ Str::limit($rec->title, 50) }}</h4>
                                    <span style="font-size: 11px; color: var(--text-muted);">{{ $rec->published_at ? $rec->published_at->format('d M Y') : $rec->created_at->format('d M Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        @else
                            @php
                            $recDemos = [
                                ['Panduan Menyiapkan Pembukuan Usaha Retail', '24 Jul 2025'],
                                ['5 Fitur Wajib POS Restoran Modern 2025', '22 Jul 2025'],
                                ['Keuntungan Menggunakan Cloud ERP Dibandingkan Server Local', '18 Jul 2025'],
                            ];
                            @endphp
                            @foreach($recDemos as $rd)
                            <a href="{{ route('blog.index') }}" style="display: flex; align-items: flex-start; gap: 10px; text-decoration: none;">
                                <span style="font-size: 14px;">⚡</span>
                                <div>
                                    <h4 style="font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.4; margin-bottom: 2px;">{{ $rd[0] }}</h4>
                                    <span style="font-size: 11px; color: var(--text-muted);">{{ $rd[1] }}</span>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Widget 4: Kategori (Categories List) --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span><i class="fa-solid fa-tags"></i></span> Kategori Artikel
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="{{ route('blog.index') }}" style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 12px; border-radius: 8px; color: var(--text); text-decoration: none; background: var(--bg);">
                            <span>Semua Artikel</span>
                            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted);">All</span>
                        </a>

                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $cName)
                            <a href="{{ route('blog.index', ['category' => $cName]) }}"
                                style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 12px; border-radius: 8px; color: {{ request('category') === $cName ? 'var(--primary)' : 'var(--text-muted)' }}; text-decoration: none; transition: background .2s;"
                                onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                                <span>📁 {{ $cName }}</span>
                                <span>→</span>
                            </a>
                            @endforeach
                        @else
                            @foreach(['Restoran & F&B', 'Klinik & Medis', 'Bengkel & Otomotif', 'Notaris & Legal', 'Teknologi & Cloud'] as $cName)
                            <a href="{{ route('blog.index', ['category' => $cName]) }}"
                                style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 8px 12px; border-radius: 8px; color: var(--text-muted); text-decoration: none;"
                                onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                                <span>📁 {{ $cName }}</span>
                                <span>→</span>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Widget 5: Newsletter Sub --}}
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 8px;">📩 Dapatkan Update Wawasan Bisnis</h3>
                    <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px;">Berlangganan newsletter mingguan kami langsung ke email Anda.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" required placeholder="Email kamu..."
                            style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 10px; background: var(--bg); color: var(--text); font-size: 13px; margin-bottom: 10px; outline: none; font-family: inherit;">
                        <button type="submit" class="btn-primary-glow" style="width: 100%; padding: 10px; font-size: 13px; border-radius: 10px; justify-content: center; display: flex; align-items: center;">
                            Berlangganan Sekarang
                        </button>
                    </form>
                </div>

            </aside>
        </div>
    </div>
</section>

@endsection
