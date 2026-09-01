@extends('layouts.public')

@section('title', 'Katalog & Paket Software Bisnis | COOCA.ID')
@section('description', 'Pilihan fitur & paket langganan COOCA.ID. Otomatiskan sistem kerja, analisa data secara real-time, dan buat keputusan bisnis lebih cepat serta akurat.')
@section('keywords', 'harga software bisnis, fitur software erp, paket langganan cooca, software analisa data bisnis, aplikasi manajemen perusahaan')

@push('styles')
{{-- Font Awesome 6 untuk mengganti emoji --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')

{{-- Page Hero --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="text-align: center; max-width: 680px; margin: 0 auto;">
            <span class="lp-eyebrow">KATALOG PRODUK</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(40px,5vw,60px); margin-bottom: 16px;">
                Temukan <span class="gradient-text">Solusi ERP</span><br>untuk Bisnis Anda
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto 40px;">{{ $products->count() > 0 ? $products->count() . ' produk' : 'Banyak produk' }} tersedia, siap digunakan dalam 24 jam.</p>
        </div>
    </div>
</section>

{{-- Filter Bar --}}
<section style="position: sticky; top: 72px; z-index: 100; background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-bottom: 1px solid var(--border); padding: 16px 0;">
    <div class="lp-container">
        <form method="GET" action="{{ route('products.index') }}" id="filter-form">
            <div class="filter-row">
                {{-- Search --}}
                <div class="filter-search" style="position: relative;">
                    <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:16px;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk ERP..." class="filter-input"
                        style="width:100%;padding:10px 14px 10px 40px;border:1px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-size:14px;outline:none;font-family:inherit;"
                        onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                </div>

                {{-- Category Filter --}}
                @if($categories->count() > 0)
                <select name="category" class="filter-select" style="padding:10px 16px;border:1px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-size:14px;outline:none;font-family:inherit;cursor:pointer;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @endif

                {{-- Type Filter --}}
                <select name="product_type" class="filter-select" style="padding:10px 16px;border:1px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-size:14px;outline:none;font-family:inherit;cursor:pointer;" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    @foreach($productTypes as $key => $label)
                    <option value="{{ $key }}" {{ request('product_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn-primary-glow filter-btn" style="padding:10px 20px;font-size:14px;border-radius:10px;">Filter</button>

                @if(request()->anyFilled(['search','category','category_id','product_type']))
                <a href="{{ route('products.index') }}" class="filter-btn-reset" style="padding:10px 16px;border:1px solid var(--border);border-radius:10px;font-size:14px;color:var(--text-muted);text-decoration:none;white-space:nowrap;display:inline-flex;align-items:center;gap:6px;justify-content:center;">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- Products Grid --}}
<section class="lp-section">
    <div class="lp-container">
        @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $index => $product)
            <article class="product-card" id="product-card-{{ $product->id }}">
                <div class="product-inner">
                    <div class="product-thumb">
                        @if($product->thumbnail_url)
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="product-thumb-fallback" style="display:none; justify-content:center; align-items:center; width:100%; height:100%;">
                                <div class="clay-icon">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            </div>
                        @else
                            <div class="product-thumb-fallback">
                                <div class="clay-icon">
                                    {{-- Tetap menggunakan icon dari database jika ada, fallback ke fa-box --}}
                                    @if($product->icon && !str_starts_with($product->icon, 'fa-'))
                                        {{ $product->icon }}
                                    @else
                                        <i class="fa-solid fa-box"></i>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,.5);backdrop-filter:blur(8px);padding:4px 10px;border-radius:100px;font-size:12px;font-weight:700;color:#f59e0b;">
                            <i class="fa-solid fa-star"></i> 4.9
                        </div>
                        @if($product->category)
                        <div style="position:absolute;top:12px;left:12px;background:var(--primary);padding:3px 10px;border-radius:100px;font-size:10px;font-weight:700;color:#fff;text-transform:uppercase;">{{ $product->category->name }}</div>
                        @endif
                        @if($product->is_featured)
                        <div style="position:absolute;bottom:12px;left:12px;background:linear-gradient(90deg,#f59e0b,#ef4444);padding:3px 10px;border-radius:100px;font-size:10px;font-weight:700;color:#fff;">
                            <i class="fa-solid fa-star"></i> UNGGULAN
                        </div>
                        @endif
                    </div>
                    <div class="product-body">
                        <div class="product-category">{{ $product->product_type_label ?? strtoupper($product->product_type ?? '') }}</div>
                        <h2 class="product-name">{{ $product->name }}</h2>
                        <p class="product-desc">{{ $product->short_description ?? Str::limit($product->description, 110) }}</p>
                        @if(is_array($product->features) && count($product->features) > 0)
                        <div class="product-features">
                            @foreach(array_slice($product->features, 0, 4) as $feature)
                            <span class="product-feature-tag">
                                <i class="fa-solid fa-check"></i> {{ is_array($feature) ? ($feature['title'] ?? $feature['name'] ?? $feature['label'] ?? '') : $feature }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="product-card-pricing-wrap">
                        @php
                            $lowestPlan = $product->subscriptionPlans->where('is_active', true)->sortBy('price')->first();
                            
                            if ($lowestPlan) {
                                $rawPrice = (float)$lowestPlan->price;
                                $planDiscount = (float)($lowestPlan->discount_percent ?? 0);
                                if ($planDiscount > 0) {
                                    $origPrice = $rawPrice;
                                    $finalPrice = $origPrice * (1 - $planDiscount / 100);
                                    $discount = $planDiscount;
                                } else {
                                    $finalPrice = $rawPrice;
                                    $origPrice = $rawPrice * 2;
                                    $discount = 50;
                                }
                                $period = $lowestPlan->duration_months >= 999 ? '/ Lifetime' : ($lowestPlan->duration_months == 1 ? '/ bulan' : '/' . $lowestPlan->duration_months . ' bln');
                            } else {
                                $base = (float)($product->base_price ?? 350000);
                                $finalPrice = $base > 0 ? $base : 350000;
                                $origPrice = $finalPrice * 2;
                                $discount = 50;
                                $period = '/ bulan';
                            }
                            $savings = $origPrice - $finalPrice;
                        @endphp

                        @if($origPrice > 0 || $finalPrice > 0)
                            <div class="product-pricing-glass-card">
                                {{-- Normal Price Strikethrough & Discount Pill --}}
                                <div class="product-pricing-top-meta">
                                    <span class="product-anchor-text">
                                        Harga Normal <del class="product-anchor-del">Rp {{ number_format($origPrice, 0, ',', '.') }}</del>
                                    </span>
                                    <span class="product-discount-pill">
                                        <i class="fa-solid fa-bolt"></i> HEMAT {{ number_format($discount, 0) }}%
                                    </span>
                                </div>

                                {{-- Main Hero Price Line --}}
                                <div class="product-price-hero-row">
                                    <span class="price-currency">Rp</span>
                                    <span class="price-val">{{ number_format($finalPrice, 0, ',', '.') }}</span>
                                    <span class="price-period">{{ $period }}</span>
                                </div>

                                {{-- Savings Tag Box --}}
                                <div class="product-savings-box">
                                    <div class="product-savings-left">
                                        <i class="fa-solid fa-tags"></i>
                                        <span>Anda hemat Rp {{ number_format($savings, 0, ',', '.') }}</span>
                                    </div>
                                    <i class="fa-solid fa-circle-check product-savings-check"></i>
                                </div>

                                {{-- Urgency Notice --}}
                                <div class="product-urgency-row">
                                    <i class="fa-solid fa-bolt"></i>
                                    <span>Harga promo onboarding terbatas</span>
                                </div>
                            </div>
                        @else
                            <div class="product-pricing-glass-card">
                                <div class="product-price-hero-row">
                                    <span class="price-val" style="font-size: 20px;">Hubungi Sales</span>
                                </div>
                                <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Kustomisasi modul sesuai kebutuhan korporasi</p>
                            </div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="btn-product-cta" aria-label="Lihat detail {{ $product->name }}">
                            Pilih Paket Ini <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
        <div style="display:flex;justify-content:center;margin-top:56px;">
            {{ $products->links() }}
        </div>
        @endif

        @else
        <div style="text-align:center;padding:80px 20px;">
            <div style="font-size:64px;margin-bottom:20px;color:var(--text-muted);">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h2 style="font-size:24px;font-weight:700;color:var(--text);margin-bottom:12px;">Tidak Ada Produk Ditemukan</h2>
            <p style="font-size:16px;color:var(--text-muted);margin-bottom:32px;">Coba ubah filter pencarian Anda atau <a href="{{ route('products.index') }}" style="color:var(--primary);">reset filter</a>.</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="lp-section--sm">
    <div class="lp-container">
        <div class="cta-section">
            <div class="cta-glow"></div>
            <h2 class="cta-title">Tidak Menemukan yang Dicari?</h2>
            <p class="cta-desc">Kami menerima permintaan pengembangan produk custom sesuai kebutuhan spesifik bisnis Anda.</p>
            <div class="cta-actions">
                <a href="{{ route('contact') }}" class="btn-white">
                    <i class="fa-solid fa-comment-dots"></i> Konsultasi Gratis
                </a>
                <a href="{{ route('customer.register') }}" class="btn-white-outline">
                    <i class="fa-solid fa-rocket"></i> Coba Gratis 14 Hari
                </a>
            </div>
        </div>
    </div>
</section>

@endsection