@extends('layouts.public')

@section('title', 'COOCA.ID - Move Faster. Decide Better | Software Bisnis')
@section('description', 'Eksekusi operasional lebih cepat dan ambil keputusan bisnis lebih tepat dengan COOCA.ID. Software manajemen bisnis terpadu untuk efisiensi total usaha Anda.')
@section('keywords', 'cooca id, software bisnis indonesia, move faster decide better, software manajemen operasional, platform otomatisasi bisnis, software pendukung keputusan')

@push('styles')
{{-- Font Awesome 6 untuk mengganti emoji --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════════ --}}
<section class="hero-section aurora-bg" id="hero" aria-labelledby="hero-heading">
    <div class="lp-container">
        <div class="hero-grid">
            {{-- Left: Content --}}
            <div class="hero-content">
                {{-- Badge --}}
                <!--<div class="hero-badge">-->
                <!--    <span class="badge-dot"></span>-->
                <!--    <span class="badge-label">Dari Cooca Untuk UMKM</span>-->
                <!--</div>-->


                {{-- Headline --}}
                <h1 class="hero-title" id="hero-heading">
                    Optimalkan Bisnis<br>
                    Dengan <span class="highlight">ERP</span>
                </h1>
                
                <p class="hero-desc">
                    Kelola operasional lebih rapi, cepat, dan terukur dengan satu platform ERP dari Cooca.
                </p>



                {{-- CTAs --}}
                <div class="hero-actions">
                    <a href="{{ route('customer.register') }}" class="btn-primary-glow btn-hero" id="hero-cta-primary">
                        <i class="fa-solid fa-rocket"></i> Mulai Coba Gratis 14 Hari
                    </a>
                    <a href="{{ route('products.index') }}" class="btn-outline-glow btn-hero" id="hero-cta-secondary">
                        <span>Jelajahi Produk ERP</span>
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div class="hero-trust-badges">
                    <div class="trust-badge-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1l1.8 3.6 4 .6-2.9 2.8.7 4L8 10.1 4.4 12l.7-4L2.2 5.2l4-.6L8 1z" fill="#22C55E"/></svg>
                        SOC2 Ready
                    </div>
                    <div class="trust-badge-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1l1.8 3.6 4 .6-2.9 2.8.7 4L8 10.1 4.4 12l.7-4L2.2 5.2l4-.6L8 1z" fill="#22C55E"/></svg>
                        99.9% SLA
                    </div>
                    <div class="trust-badge-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1l1.8 3.6 4 .6-2.9 2.8.7 4L8 10.1 4.4 12l.7-4L2.2 5.2l4-.6L8 1z" fill="#22C55E"/></svg>
                        Setup 24 Jam
                    </div>
                    <div class="trust-badge-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M8 1l1.8 3.6 4 .6-2.9 2.8.7 4L8 10.1 4.4 12l.7-4L2.2 5.2l4-.6L8 1z" fill="#22C55E"/></svg>
                        Support 24/7
                    </div>
                </div>
            </div>

            {{-- Right: Dashboard Mockup --}}
            <div class="hero-visual">
                {{-- Floating Card 1: Revenue --}}
                <div class="floating-card card-1">
                    <div class="fc-label">Total Pendapatan</div>
                    <div class="fc-value">Rp 128.4j</div>
                    <div class="fc-change">↑ +8.4% bulan ini</div>
                </div>

                {{-- Floating Card 2: Active Users --}}
                <div class="floating-card card-2">
                    <div class="fc-label">Bisnis Aktif</div>
                    <div class="fc-value">12 Unit</div>
                    <div class="fc-change" style="color: var(--accent);">All Online</div>
                </div>

                {{-- Floating Card 3: Notifications --}}
                <div class="floating-card card-3" style="padding: 10px 14px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:18px;"><i class="fa-solid fa-bell"></i></span>
                        <div>
                            <div class="fc-label">Notifikasi Baru</div>
                            <div style="font-size:12px; font-weight:700; color: var(--text);">Transaksi berhasil</div>
                        </div>
                    </div>
                </div>

                {{-- Dashboard Mockup --}}
                <div class="dashboard-mockup">
                    <div class="dashboard-mockup-bar">
                        <span class="mockup-dot red"></span>
                        <span class="mockup-dot yellow"></span>
                        <span class="mockup-dot green"></span>
                        <div class="mockup-url">https://app.cooca.id/dashboard</div>
                        <span class="mockup-live">LIVE</span>
                    </div>
                    <div class="dashboard-inner">
                        <div class="db-title-row">
                            <div>
                                <div class="db-title">Cooca Enterprise POS &amp; ERP</div>
                                <div class="db-subtitle">Tenant: Restoran &amp; HQ Outlets</div>
                            </div>
                            <span class="db-badge">✓ Active</span>
                        </div>

                        <div class="db-stats-row">
                            <div class="db-stat-card">
                                <div class="db-stat-label">Total Pendapatan</div>
                                <div class="db-stat-value">Rp 128.4j</div>
                                <div class="db-stat-change up">↑ +8.4%</div>
                            </div>
                            <div class="db-stat-card">
                                <div class="db-stat-label">Lisensi Aktif</div>
                                <div class="db-stat-value">12 Unit</div>
                                <div class="db-stat-change" style="color:var(--accent);">All Online</div>
                            </div>
                            <div class="db-stat-card">
                                <div class="db-stat-label">Tenant Aktif</div>
                                <div class="db-stat-value">48 Bisnis</div>
                                <div class="db-stat-change up">+6 bulan ini</div>
                            </div>
                            <div class="db-stat-card">
                                <div class="db-stat-label">Support Ticket</div>
                                <div class="db-stat-value">0 Pending</div>
                                <div class="db-stat-change up">100% Resolved</div>
                            </div>
                        </div>

                        <div class="db-chart-area">
                            <div class="db-chart-label">Grafik Pendapatan — 7 Hari Terakhir</div>
                            <svg class="mini-chart" viewBox="0 0 400 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Revenue chart">
                                <defs>
                                    <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#4F46E5" stop-opacity="0.3"/>
                                        <stop offset="100%" stop-color="#4F46E5" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M0,60 C20,55 40,45 80,35 C120,25 140,50 180,40 C220,30 260,20 300,15 C340,10 370,25 400,20" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M0,60 C20,55 40,45 80,35 C120,25 140,50 180,40 C220,30 260,20 300,15 C340,10 370,25 400,20 L400,80 L0,80Z" fill="url(#chartGrad)"/>
                                <circle cx="300" cy="15" r="4" fill="#4F46E5"/>
                                <circle cx="400" cy="20" r="4" fill="#06B6D4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     TRUST BAR — Client Logos Marquee
═══════════════════════════════════════════════════ --}}
<section class="trust-section" aria-label="Trusted by leading companies">
    <div class="lp-container">
        <p class="trust-heading">Dipercaya oleh</p>
    </div>

    {{-- Infinite Marquee --}}
    <div class="marquee-container">
        {{-- Track 1 (Left) --}}
        <div class="marquee-track" aria-hidden="true">
            @if($clients->count() > 0)
                @foreach($clients->merge($clients) as $client)
                <div class="client-logo">
                    @if($client->logo_path)
                        <img src="{{ Storage::url($client->logo_path) }}" alt="{{ $client->company_name }}" style="max-height: 40px; object-fit: contain;">
                    @else
                        <span class="font-semibold text-lg">{!! $client->company_name !!}</span>
                    @endif
                </div>
                @endforeach
            @else
                @php
                $dummyClients = [
                    ['icon'=>'fa-solid fa-burger','name'=>'Bengkel Express'],
                    ['icon'=>'fa-solid fa-hospital','name'=>'Klinik Sehat'],
                    ['icon'=>'fa-solid fa-scissors','name'=>'Sushi Yayi'],
                    ['icon'=>'fa-solid fa-scale-balanced','name'=>'LEGALP'],
                    ['icon'=>'fa-solid fa-paw','name'=>'Salon Cantik'],
                ];
                @endphp
                @foreach(array_merge($dummyClients, $dummyClients) as $client)
                <div class="client-logo">
                    <span style="font-size:20px;"><i class="{{ $client['icon'] }}"></i></span>
                    <span>{!! $client['name'] !!}</span>
                </div>
                @endforeach
            @endif
        </div>
        
        {{-- Track 2 (Right) --}}
        <div class="marquee-track-reverse" aria-hidden="true">
            @if($clients->count() > 0)
                @foreach($clients->reverse()->merge($clients->reverse()) as $client)
                <div class="client-logo">
                    @if($client->logo_path)
                        <img src="{{ Storage::url($client->logo_path) }}" alt="{{ $client->company_name }}" style="max-height: 40px; object-fit: contain;">
                    @else
                        <span class="font-semibold text-lg">{!! $client->company_name !!}</span>
                    @endif
                </div>
                @endforeach
            @else
                @foreach(array_merge(array_reverse($dummyClients), array_reverse($dummyClients)) as $client)
                <div class="client-logo">
                    <span style="font-size:20px;"><i class="{{ $client['icon'] }}"></i></span>
                    <span>{!! $client['name'] !!}</span>
                </div>
                @endforeach
            @endif
        </div>
    </div>

</section>

{{-- ═══════════════════════════════════════════════════
     ERP ECOSYSTEM — BENTO GRID
═══════════════════════════════════════════════════ --}}
{{--
<section class="lp-section section-bg-alt" id="ecosystem" aria-labelledby="ecosystem-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">EKOSISTEM COOCA</span>
            <h2 class="lp-heading" id="ecosystem-heading">
                Ekosistem ERP Modular<br>
                <span class="gradient-text">Terintegrasi Penuh</span>
            </h2>
            <p class="lp-subheading">Dirancang khusus untuk berbagai jenis bisnis di Indonesia dengan standar enterprise tinggi</p>
        </div>

        <div class="bento-grid">
            <div class="bento-card reveal reveal-delay-1">
                <div class="bento-icon"><i class="fa-solid fa-utensils"></i></div>
                <h3 class="bento-title">ERP Restoran &amp; F&amp;B</h3>
                <p class="bento-desc">POS meja, manajemen dapur, QR Table, kitchen display system, laporan harian otomatis, dan manajemen bahan baku.</p>
                <div class="bento-tags">
                    <span class="bento-tag">POS Meja</span>
                    <span class="bento-tag">QR Order</span>
                    <span class="bento-tag">Kitchen</span>
                    <span class="bento-tag">Struk Digital</span>
                </div>
                <a href="{{ route('products.index') }}" class="bento-link">
                    Lihat Fitur Restoran
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="bento-card reveal reveal-delay-2">
                <div class="bento-icon"><i class="fa-solid fa-hospital"></i></div>
                <h3 class="bento-title">ERP Klinik &amp; Medis</h3>
                <p class="bento-desc">Rekam medis digital, antrian pasien otomatis, manajemen dokter, billing, dan terintegrasi WhatsApp &amp; resep digital.</p>
                <div class="bento-tags">
                    <span class="bento-tag">EMR</span>
                    <span class="bento-tag">Antrian</span>
                    <span class="bento-tag">Resep Digital</span>
                </div>
                <a href="{{ route('products.index') }}" class="bento-link">
                    Selengkapnya
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="bento-card reveal reveal-delay-3">
                <div class="bento-icon"><i class="fa-solid fa-wrench"></i></div>
                <h3 class="bento-title">ERP Bengkel &amp; Otomotif</h3>
                <p class="bento-desc">Work order digital, manajemen sparepart, tracking servis kendaraan, kasir teknisi &amp; laporan produktivitas.</p>
                <div class="bento-tags">
                    <span class="bento-tag">Work Order</span>
                    <span class="bento-tag">Sparepart</span>
                    <span class="bento-tag">Telford</span>
                </div>
                <a href="{{ route('products.index') }}" class="bento-link">
                    Selengkapnya
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="bento-card bento-wide reveal reveal-delay-1">
                <div class="bento-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                <h3 class="bento-title">ERP Legal &amp; Notaris</h3>
                <p class="bento-desc">Manajemen dokumen legal, tracking klien &amp; akta, pembuatan surat otomatis, dan arsip digital berstandar hukum.</p>
                <div class="bento-tags">
                    <span class="bento-tag">Akta</span>
                    <span class="bento-tag">Finance</span>
                    <span class="bento-tag">Dokumen</span>
                </div>
                <a href="{{ route('products.index') }}" class="bento-link">
                    Selengkapnya
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="bento-card reveal reveal-delay-2">
                <div class="bento-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <h3 class="bento-title">Sistem Booking &amp; Reservasi</h3>
                <p class="bento-desc">Portal booking mandiri, konfirmasi otomatis via WhatsApp, manajemen jadwal multi-outlet yang terintegrasi.</p>
                <div class="bento-tags">
                    <span class="bento-tag">Online</span>
                    <span class="bento-tag">WhatsApp</span>
                    <span class="bento-tag">Kalender</span>
                </div>
                <a href="{{ route('products.index') }}" class="bento-link">Lihat Katalog →</a>
            </div>

            <div class="bento-card bento-featured reveal reveal-delay-3">
                <div class="bento-icon" style="background: rgba(255,255,255,.15); border-color: rgba(255,255,255,.2);"><i class="fa-solid fa-cloud"></i></div>
                <div style="font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,255,255,.7); margin-bottom:8px;">Enterprise Ready</div>
                <h3 class="bento-title" style="color:#fff;">Cloud Infrastructure</h3>
                <p class="bento-desc" style="color:rgba(255,255,255,.8);">Infrastruktur cloud enterprise, auto-scaling, 99.9% uptime, auto-backup &amp; disaster recovery.</p>
                <a href="{{ route('about') }}" class="bento-link" style="color:rgba(255,255,255,.9); margin-top:20px;">
                    Learn More →
                </a>
            </div>
        </div>
    </div>
</section>
--}}

{{-- ═══════════════════════════════════════════════════
     PRODUCT SHOWCASE — Dynamic from DB
═══════════════════════════════════════════════════ --}}
<section class="lp-section" id="products" aria-labelledby="products-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">PRODUK ERP SIAP PAKAI</span>
            <h2 class="lp-heading" id="products-heading">
                Produk ERP <span class="gradient-text">Siap Pakai</span>
            </h2>
            <p class="lp-subheading">Pilih produk yang sesuai dengan jenis bisnis Anda. Semua sudah termasuk setup &amp; training.</p>
        </div>

        @if($products->count() > 0)
        <div class="products-grid reveal">
            @foreach($products->take(6) as $index => $product)
            <article class="product-card reveal reveal-delay-{{ ($index % 3) + 1 }}" id="product-{{ $product->id }}">
                <div class="product-inner">
                    {{-- Thumbnail --}}
                    <div class="product-thumb">
                        @if($product->thumbnail_url)
                            <img src="{{ $product->thumbnail_url }}"
                                 alt="{{ $product->name }}"
                                 width="380"
                                 height="220"
                                 loading="lazy"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="product-thumb-fallback" style="display:none;">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        @else
                            <div class="product-thumb-fallback">
                                @if($product->icon && !str_starts_with($product->icon, 'fa-'))
                                    {{ $product->icon }}
                                @elseif($product->icon)
                                    <i class="{{ $product->icon }}"></i>
                                @else
                                    <i class="fa-solid fa-box"></i>
                                @endif
                            </div>
                        @endif

                        {{-- Rating Badge --}}
                        <div style="position:absolute; top:12px; right:12px; background:rgba(0,0,0,.5); backdrop-filter:blur(8px); padding:4px 10px; border-radius:100px; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:700; color:#f59e0b;">
                            <i class="fa-solid fa-star"></i> 4.9
                        </div>

                        {{-- Category badge --}}
                        @if($product->category)
                        <div style="position:absolute; top:12px; left:12px; background:var(--primary); padding:3px 10px; border-radius:100px; font-size:10px; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:.05em;">
                            {{ $product->category->name }}
                        </div>
                        @endif
                    </div>

                    <div class="product-body">
                        <div class="product-category">
                            {{ $product->product_type_label ?? strtoupper($product->product_type ?? 'PRODUK') }}
                        </div>
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-desc">
                            {{ $product->short_description ?? Str::limit($product->description, 100) }}
                        </p>

                        {{-- Features --}}
                        @if(is_array($product->features) && count($product->features) > 0)
                        <div class="product-features">
                            @foreach(array_slice($product->features, 0, 4) as $feature)
                            <span class="product-feature-tag"><i class="fa-solid fa-check"></i> {{ is_array($feature) ? ($feature['title'] ?? $feature['name'] ?? $feature['label'] ?? '') : $feature }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="product-card-pricing-wrap">
                        @php
                            $lowestPlan = $product->subscriptionPlans
                                ->where('is_active', true)
                                ->sortBy('price')
                                ->first();
                            
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
                                $period = $lowestPlan->duration_months >= 999 ? ' / Lifetime' : ($lowestPlan->duration_months == 1 ? '/bulan' : '/' . $lowestPlan->duration_months . ' bln');
                            } else {
                                $base = (float)($product->base_price ?? 350000);
                                $finalPrice = $base > 0 ? $base : 350000;
                                $origPrice = $finalPrice * 2;
                                $discount = 50;
                                $period = '/bulan';
                            }
                            $savings = $origPrice - $finalPrice;
                        @endphp

                        @if($origPrice > 0 || $finalPrice > 0)
                            <div class="product-pricing-glass-card">
                                <div class="product-pricing-top-meta">
                                    <span class="product-anchor-text">
                                        Normal: <del class="product-anchor-del">Rp {{ number_format($origPrice, 0, ',', '.') }}</del>
                                    </span>
                                    <span class="product-discount-pill">
                                        <i class="fa-solid fa-bolt"></i> HEMAT {{ number_format($discount, 0) }}%
                                    </span>
                                </div>

                                <div class="product-price-hero-row">
                                    <span class="price-val">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                    <span class="price-period">{{ $period }}</span>
                                </div>

                                <div class="product-savings-line">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Hemat Rp {{ number_format($savings, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="product-pricing-glass-card">
                                <div class="product-price-hero-row">
                                    <span class="price-val" style="font-size: 18px;">Hubungi Sales</span>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="btn-product-cta" aria-label="Pilih paket {{ $product->name }}">
                            Pilih Paket Ini <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @else
        {{-- Placeholder if no products in DB yet --}}
        <div class="products-grid reveal">
            @php
            $demoProducts = [
                ['icon'=>'fa-solid fa-wrench','name'=>'Cooca Auto Bengkel & Sparepart','category'=>'BENGKEL & OTOMOTIF','desc'=>'Cooca Auto mengelola proses estimasi&breakdown komponen & penjualan unit. Bengkel semakin terukur, efisien, dan memberikan pengalaman pelanggan lebih baik.','price'=>'350.000','period'=>'/bln'],
                ['icon'=>'fa-solid fa-spa','name'=>'Cooca Booking & Salon Services','category'=>'BOOKING & RESERVASI JASA','desc'=>'Sistem booking mandiri bagi Salon, Barbershop, Klinik, Estetika, Nail Art. Terintegras lengkap CRM Pelanggan dan laporan keuangan.','price'=>'200.000','period'=>'/bln'],
                ['icon'=>'fa-solid fa-scale-balanced','name'=>'Cooca Legal Notaris & Akta','category'=>'NOTARIS & LEGAL','desc'=>'Dirancang khusus untuk Kantor Notaris & Pejabat Pembuat Akta Tanah (PPAT). Manajemen dokumen legal, billing, klien, dan arsip.','price'=>'500.000','period'=>'/bln'],
            ];
            @endphp
            @foreach($demoProducts as $i => $demo)
            <article class="product-card reveal reveal-delay-{{ $i+1 }}">
                <div class="product-inner">
                    <div class="product-thumb">
                        <div class="product-thumb-fallback"><i class="{{ $demo['icon'] }}"></i></div>
                        <div style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,.5);backdrop-filter:blur(8px);padding:4px 10px;border-radius:100px;display:flex;align-items:center;gap:4px;font-size:12px;font-weight:700;color:#f59e0b;">
                            <i class="fa-solid fa-star"></i> 4.9
                        </div>
                        <div style="position:absolute;top:12px;left:12px;background:var(--primary);padding:3px 10px;border-radius:100px;font-size:10px;font-weight:700;color:#fff;text-transform:uppercase;">{{ $demo['category'] }}</div>
                    </div>
                    <div class="product-body">
                        <div class="product-category">{{ $demo['category'] }}</div>
                        <h3 class="product-name">{{ $demo['name'] }}</h3>
                        <p class="product-desc">{{ $demo['desc'] }}</p>
                    </div>
                    <div class="product-card-pricing">
                        <div class="product-pricing-anchor-line">
                            <span class="product-pricing-old">Harga Normal: Rp 700.000</span>
                            <span class="badge-discount-save">HEMAT 50%</span>
                        </div>

                        <div class="product-pricing-main">
                            <span class="product-pricing-val">Rp {{ $demo['price'] }}</span>
                            <span class="product-pricing-period">{{ $demo['period'] }}</span>
                        </div>

                        <div class="product-pricing-savings">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Hemat Rp 350.000 setiap bulan</span>
                        </div>

                        <div class="product-pricing-urgency">
                            <i class="fa-solid fa-bolt"></i> Harga promo terbatas
                        </div>

                        <div style="margin-top: 14px;">
                            <a href="{{ route('products.index') }}" class="btn-primary-glow" style="width: 100%; justify-content: center; padding: 10px 14px; font-size: 13px; border-radius: 10px;">
                                Pilih Paket Ini <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif

        <div style="text-align:center; margin-top: 48px;" class="reveal">
            <a href="{{ route('products.index') }}" class="btn-outline-glow" id="see-all-products-btn">
                <span>Lihat Semua Produk →</span>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     DASHBOARD PREVIEW
═══════════════════════════════════════════════════ --}}
<section class="lp-section section-bg-alt" id="dashboard-preview" aria-labelledby="preview-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">PREVIEW DASHBOARD</span>
            <h2 class="lp-heading" id="preview-heading">
                Antarmuka yang <span class="gradient-text">Intuitif</span>
            </h2>
            <p class="lp-subheading">Dashboard real-time yang mudah digunakan oleh siapapun, dari kasir hingga direktur.</p>
        </div>

        <div class="preview-browser reveal">
            <div class="preview-browser-bar">
                <span class="mockup-dot red"></span>
                <span class="mockup-dot yellow"></span>
                <span class="mockup-dot green"></span>
                <div class="mockup-url" style="max-width: 280px;">https://app.cooca.id/dashboard</div>
                <div class="preview-tabs">
                    <button class="preview-tab active" data-target="preview-pos" id="tab-pos">POS</button>
                    <button class="preview-tab" data-target="preview-finance" id="tab-finance">Finance</button>
                    <button class="preview-tab" data-target="preview-crm" id="tab-crm">CRM</button>
                    <button class="preview-tab" data-target="preview-inventory" id="tab-inventory">Inventory</button>
                </div>
            </div>

            <div class="preview-content">
                {{-- POS Screen --}}
                <div class="preview-screen active" id="preview-pos">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:24px;">
                        <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                            <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:12px;">TRANSAKSI HARI INI</div>
                            <div style="font-size:32px;font-weight:900;color:var(--text);letter-spacing:-.03em;">Rp 8.4j</div>
                            <div style="font-size:12px;color:#22c55e;font-weight:600;margin-top:4px;">↑ +12% dari kemarin</div>
                        </div>
                        <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                            <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:12px;">TOTAL TRANSAKSI</div>
                            <div style="font-size:32px;font-weight:900;color:var(--text);letter-spacing:-.03em;">247</div>
                            <div style="font-size:12px;color:var(--accent);font-weight:600;margin-top:4px;"><i class="fa-solid fa-credit-card"></i> Rata-rata Rp 34rb/trx</div>
                        </div>
                        <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;grid-column:span 2;">
                            <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:16px;">TRANSAKSI TERBARU</div>
                            @php $rows = [['#TRX-0024','Makan Siang Set','Rp 85.000','<i class="fa-solid fa-check-circle"></i> Lunas'],['#TRX-0023','Kopi Susu Gula Aren','Rp 25.000','<i class="fa-solid fa-check-circle"></i> Lunas'],['#TRX-0022','Paket Hemat 3 Orang','Rp 180.000','<i class="fa-solid fa-clock"></i> Proses']]; @endphp
                            @foreach($rows as $row)
                            <div style="display:grid;grid-template-columns: 1.2fr 2.5fr 1.5fr 1fr;gap:12px;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);text-align:left;">
                                <div style="font-size:12px;font-weight:700;color:var(--primary);">{{ $row[0] }}</div>
                                <div style="font-size:13px;color:var(--text);">{{ $row[1] }}</div>
                                <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $row[2] }}</div>
                                <div style="font-size:12px;">{!! $row[3] !!}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Finance Screen --}}
                <div class="preview-screen" id="preview-finance">
                    <div style="padding:24px;">
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:16px;">
                            @php $fCards = [['fa-solid fa-money-bill','Total Revenue','Rp 128.4 Juta','↑ +8.4%','#22c55e'],['fa-solid fa-chart-bar','Total Expense','Rp 64.2 Juta','↓ -2.1%','#ef4444'],['fa-solid fa-gem','Net Profit','Rp 64.2 Juta','↑ +15%','#22c55e']]; @endphp
                            @foreach($fCards as $fc)
                            <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                                <div class="clay-icon-sm" style="margin-bottom:12px;"><i class="{{ $fc[0] }}"></i></div>
                                <div style="font-size:11px;color:var(--text-muted);font-weight:600;margin-bottom:4px;">{{ $fc[1] }}</div>
                                <div style="font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.02em;">{{ $fc[2] }}</div>
                                <div style="font-size:12px;font-weight:600;color:{{ $fc[4] }};margin-top:4px;">{{ $fc[3] }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                            <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:16px;">GRAFIK KEUANGAN — 6 BULAN</div>
                            <svg width="100%" height="100" viewBox="0 0 600 100" fill="none" aria-label="Finance chart">
                                <defs><linearGradient id="fg" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#4F46E5" stop-opacity=".3"/><stop offset="100%" stop-color="#4F46E5" stop-opacity="0"/></linearGradient></defs>
                                <path d="M0,80 L100,65 L200,50 L300,40 L400,30 L500,20 L600,10" stroke="#4F46E5" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M0,80 L100,65 L200,50 L300,40 L400,30 L500,20 L600,10 L600,100 L0,100Z" fill="url(#fg)"/>
                                <path d="M0,90 L100,85 L200,80 L300,75 L400,70 L500,72 L600,68" stroke="#06B6D4" stroke-width="1.5" stroke-dasharray="4,4" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- CRM Screen --}}
                <div class="preview-screen" id="preview-crm">
                    <div style="padding:24px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                            <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                                <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:8px;">TOTAL PELANGGAN</div>
                                <div style="font-size:32px;font-weight:900;color:var(--text);">2,847</div>
                                <div style="font-size:12px;color:#22c55e;font-weight:600;margin-top:4px;">+47 bulan ini</div>
                            </div>
                            <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                                <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:8px;">PELANGGAN AKTIF</div>
                                <div style="font-size:32px;font-weight:900;color:var(--text);">1,203</div>
                                <div style="font-size:12px;color:var(--accent);font-weight:600;margin-top:4px;">42.3% retention</div>
                            </div>
                        </div>
                        <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                            <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:12px;">PELANGGAN TERBARU</div>
                            @php $customers = [['fa-solid fa-user','Andi Setiawan','Langganan Pro','2 jam lalu'],['fa-solid fa-user','Sari Dewi','Bergabung Baru','5 jam lalu'],['fa-solid fa-user','Budi Santoso','Perpanjang','1 hari lalu']]; @endphp
                            @foreach($customers as $c)
                            <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid var(--border);">
                                <div class="clay-icon-sm" style="margin-right:12px;width:36px;height:36px;">
                                    <i class="{{ $c[0] }}"></i>
                                </div>
                                <div style="flex:1;">
                                    <div style="font-size:13px;font-weight:700;color:var(--text);">{{ $c[1] }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $c[2] }}</div>
                                </div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ $c[3] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Inventory Screen --}}
                <div class="preview-screen" id="preview-inventory">
                    <div style="padding:24px;">
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;">
                            @php $invStats = [['fa-solid fa-box','Total SKU','1,247'],['fa-solid fa-check','Stok Aman','1,089'],['fa-solid fa-triangle-exclamation','Stok Rendah','98'],['fa-solid fa-xmark','Habis','60']]; @endphp
                            @foreach($invStats as $s)
                            <div style="background:var(--surface);border-radius:10px;border:1px solid var(--border);padding:16px;text-align:left;">
                                <div class="clay-icon-sm" style="margin-bottom:10px;"><i class="{{ $s[0] }}"></i></div>
                                <div style="font-size:10px;color:var(--text-muted);margin-bottom:2px;">{{ $s[1] }}</div>
                                <div style="font-size:20px;font-weight:800;color:var(--text);">{{ $s[2] }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div style="background:var(--surface);border-radius:12px;border:1px solid var(--border);padding:20px;">
                            <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:12px;">STOK PERLU REORDER</div>
                            @php $inv = [['Bahan Baku Tepung','98 sak','Rendah','#f59e0b'],['Gas LPG 12kg','12 tabung','Kritis','#ef4444'],['Minyak Goreng','24 liter','Rendah','#f59e0b']]; @endphp
                            @foreach($inv as $item)
                            <div style="display:grid;grid-template-columns: 2fr 1fr auto;gap:12px;align-items:center;padding:12px 0;border-bottom:1px solid var(--border);text-align:left;">
                                <div style="font-size:13px;color:var(--text);font-weight:600;">{{ $item[0] }}</div>
                                <div style="font-size:12px;color:var(--text-muted);">{{ $item[1] }}</div>
                                <div style="text-align:left;">
                                    <span style="font-size:11px;font-weight:700;padding:4px 12px;border-radius:100px;background:{{ $item[2]==='Kritis' ? 'rgba(239,68,68,.1)':'rgba(245,158,11,.1)' }};color:{{ $item[3] }};">{{ $item[2] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     WHY COOCA — BENTO LAYOUT
═══════════════════════════════════════════════════ --}}
<section class="lp-section" id="why" aria-labelledby="why-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">MENGAPA COOCA.ID</span>
            <h2 class="lp-heading" id="why-heading">
                Dibangun untuk <span class="gradient-text">Enterprise</span>,<br>
                Terjangkau untuk <span class="gradient-text">Semua</span>
            </h2>
            <p class="lp-subheading">Teknologi kelas enterprise yang sebelumnya hanya bisa dinikmati perusahaan besar, kini hadir untuk bisnis Anda.</p>
        </div>

        <div class="why-grid">
            @php
            $whyItems = [
                ['fa-solid fa-cloud','Cloud Native','Berjalan di cloud modern dengan auto-scaling dan infrastruktur terdistribusi.'],
                ['fa-solid fa-shield','High Security','Enkripsi end-to-end, 2FA, audit log, dan compliance GDPR siap pakai.'],
                ['fa-solid fa-puzzle-piece','Modular','Aktifkan hanya fitur yang Anda butuhkan. Bayar sesuai pemakaian.'],
                ['fa-solid fa-building-columns','Multi Tenant','Kelola banyak outlet atau bisnis dalam satu akun terpusat.'],
                ['fa-solid fa-plug','API Ready','REST API & Webhook untuk integrasi dengan sistem Anda yang sudah ada.'],
                ['fa-solid fa-bolt','Realtime','Data transaksi dan laporan diperbarui secara realtime tanpa refresh.'],
                ['fa-solid fa-mobile-screen','Offline Ready','Tetap bisa beroperasi meski koneksi internet terputus sementara.'],
                ['fa-solid fa-robot','AI Ready','Laporan cerdas dan prediksi berbasis AI untuk pengambilan keputusan.'],
            ];
            @endphp
            @foreach($whyItems as $i => $item)
            <div class="why-card reveal reveal-delay-{{ ($i % 4) + 1 }}">
                <div class="why-icon clay-icon" style="margin-bottom: 20px;"><i class="{{ $item[0] }}"></i></div>
                <h3 class="why-title">{{ $item[1] }}</h3>
                <p class="why-desc">{{ $item[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     HOW IT WORKS — 3 STEPS
═══════════════════════════════════════════════════ --}}
<section class="lp-section section-bg-alt" id="how-it-works" aria-labelledby="how-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">CARA KERJA</span>
            <h2 class="lp-heading" id="how-heading">
                Mulai dalam <span class="gradient-text">3 Langkah Mudah</span>
            </h2>
            <p class="lp-subheading">Dari daftar hingga sistem berjalan penuh, hanya perlu 24 jam.</p>
        </div>

        <div class="how-grid">
            <div class="how-step reveal reveal-delay-1">
                <div class="how-step-num">
                    <span class="how-step-icon">01</span>
                </div>
                <h3 class="how-step-title">Daftar Akun</h3>
                <p class="how-step-desc">Buat akun gratis dalam 2 menit. Tidak perlu kartu kredit. Tidak perlu instalasi apapun.</p>
            </div>
            <div class="how-step reveal reveal-delay-2">
                <div class="how-step-num" style="background: linear-gradient(135deg, #7C3AED, var(--accent));">
                    <span class="how-step-icon">02</span>
                </div>
                <h3 class="how-step-title">Pilih Produk ERP</h3>
                <p class="how-step-desc">Pilih modul yang sesuai bisnis Anda. Tim kami akan bantu setup dan konfigurasi awal.</p>
            </div>
            <div class="how-step reveal reveal-delay-3">
                <div class="how-step-num" style="background: linear-gradient(135deg, var(--accent), #06B6D4);">
                    <span class="how-step-icon">03</span>
                </div>
                <h3 class="how-step-title">Langsung Aktif</h3>
                <p class="how-step-desc">Sistem langsung bisa digunakan. Kami sediakan training gratis untuk tim Anda.</p>
            </div>
        </div>

        <div style="text-align:center; margin-top:56px;" class="reveal">
            <a href="{{ route('customer.register') }}" class="btn-primary-glow btn-hero" id="how-cta-btn">
                <i class="fa-solid fa-rocket"></i> Mulai Sekarang — Gratis 14 Hari
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     PRICING SECTION — Psychological Pricing & Conversion Focus
═══════════════════════════════════════════════════ --}}
<section class="lp-section" id="pricing" aria-labelledby="pricing-heading" style="position: relative; overflow: hidden;">
    <div class="lp-container">
        {{-- Section Header with Marketing Psychology Narrative --}}
        <div class="lp-section-header reveal" style="max-width: 820px; margin: 0 auto 56px; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(79,70,229,.1); border: 1px solid rgba(79,70,229,.25); padding: 6px 16px; border-radius: 999px; font-size: 11.5px; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px;">
                <i class="fa-solid fa-tags"></i> PENAWARAN SPESIAL TERBATAS
            </div>
            <h2 class="lp-heading" id="pricing-heading" style="font-size: clamp(32px, 5vw, 48px); margin-bottom: 16px; line-height: 1.2;">
                Kelola Bisnis Lebih Mudah dalam <span class="gradient-text">Satu Sistem</span>
            </h2>
            <p class="lp-subheading" style="font-size: 17px; color: var(--text-muted); line-height: 1.7; margin: 0 auto;">
                COOCA membantu bisnis Anda mengelola operasional, data, dan proses kerja dalam satu platform yang terintegrasi.
            </p>
        </div>

        {{-- Pricing Grid (3-Tier Layout matching Reference Mockup: 6 Bulan, Tahunan, Bulanan) --}}
        <div class="cooca-pricing-grid reveal">

            {{-- 1. 6 Bulan Tier (Left) --}}
            <div class="cooca-card theme-side reveal reveal-delay-1">
                {{-- Diagonal Ribbon --}}
                <div class="corner-ribbon-wrap">
                    <div class="corner-ribbon corner-ribbon-muted">
                        HEMAT<br>30%
                    </div>
                </div>

                <div>
                    <span class="cooca-badge-duration badge-duration-blue">6 BULAN</span>
                </div>
                <h3 class="cooca-tier-title">6 Bulan</h3>

                {{-- Normal Price Strikethrough --}}
                <div class="cooca-normal-price-row">
                    <span>Harga Normal</span>
                    <del class="cooca-normal-del">Rp5.328.000</del>
                </div>

                {{-- Price Box --}}
                <div class="cooca-hero-price-box">
                    <div class="cooca-main-price-line">
                        <span class="currency">Rp</span>
                        <span class="amount">3.729.600</span>
                        <span class="period">/ 6 bln</span>
                    </div>

                    <div class="cooca-savings-box">
                        <div class="cooca-savings-left">
                            <i class="fa-solid fa-tags"></i>
                            <span>Anda hemat Rp 1.598.400</span>
                        </div>
                        <i class="fa-solid fa-circle-check cooca-savings-check"></i>
                    </div>

                    <div class="cooca-urgency-row">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Harga promo onboarding terbatas</span>
                    </div>
                </div>

                {{-- Features List --}}
                <ul class="cooca-feature-list">
                    <li><i class="fa-solid fa-circle-check"></i> <span>Akses penuh modul Cooca ERP &amp; POS</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span>Dukungan teknis prioritas 24/7</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span>Free update &amp; cloud backup otomatis</span></li>
                </ul>

                <a href="{{ route('customer.register') }}" class="cooca-btn cooca-btn-side">
                    Pilih Paket Ini <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            {{-- 2. Tahunan Tier (Center - Featured Most Popular) --}}
            <div class="cooca-card theme-featured reveal reveal-delay-2">
                {{-- Most Popular Floating Badge --}}
                <div class="cooca-floating-badge">
                    <i class="fa-solid fa-star"></i> MOST POPULAR • REKOMENDASI
                </div>

                {{-- Diagonal Gold Ribbon --}}
                <div class="corner-ribbon-wrap">
                    <div class="corner-ribbon corner-ribbon-gold">
                        HEMAT<br>35%
                    </div>
                </div>

                <div>
                    <span class="cooca-badge-duration badge-duration-purple">12 BULAN</span>
                </div>
                <h3 class="cooca-tier-title">Tahunan</h3>

                {{-- Normal Price Strikethrough --}}
                <div class="cooca-normal-price-row">
                    <span>Harga Normal</span>
                    <del class="cooca-normal-del">Rp10.656.000</del>
                </div>

                {{-- Main Price Box with Gold Crown --}}
                <div class="cooca-hero-price-box">
                    <i class="fa-solid fa-crown cooca-price-crown"></i>

                    <div class="cooca-main-price-line">
                        <span class="currency">Rp</span>
                        <span class="amount">6.926.400</span>
                        <span class="period">/ tahun</span>
                    </div>

                    <div class="cooca-savings-box">
                        <div class="cooca-savings-left">
                            <i class="fa-solid fa-tags"></i>
                            <span>Anda hemat Rp 3.729.600</span>
                        </div>
                        <i class="fa-solid fa-circle-check cooca-savings-check"></i>
                    </div>

                    <div class="cooca-urgency-row">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Harga promo onboarding terbatas</span>
                    </div>
                </div>

                {{-- Features List --}}
                <ul class="cooca-feature-list">
                    <li><i class="fa-solid fa-circle-check"></i> <span>Akses penuh seluruh modul Cooca ERP &amp; POS</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span>Dukungan teknis prioritas 24/7</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span>Free update &amp; cloud backup otomatis</span></li>
                </ul>

                <a href="{{ route('customer.register') }}" class="cooca-btn cooca-btn-featured">
                    Pilih Paket Ini <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            {{-- 3. Bulanan Tier (Right) --}}
            <div class="cooca-card theme-side reveal reveal-delay-3">
                {{-- Diagonal Ribbon --}}
                <div class="corner-ribbon-wrap">
                    <div class="corner-ribbon corner-ribbon-muted">
                        HEMAT<br>18%
                    </div>
                </div>

                <div>
                    <span class="cooca-badge-duration badge-duration-green">1 BULAN</span>
                </div>
                <h3 class="cooca-tier-title">Bulanan</h3>

                {{-- Normal Price Strikethrough --}}
                <div class="cooca-normal-price-row">
                    <span>Harga Normal</span>
                    <del class="cooca-normal-del">Rp888.000</del>
                </div>

                {{-- Price Box --}}
                <div class="cooca-hero-price-box">
                    <div class="cooca-main-price-line">
                        <span class="currency">Rp</span>
                        <span class="amount">728.160</span>
                        <span class="period">/ bulan</span>
                    </div>

                    <div class="cooca-savings-box">
                        <div class="cooca-savings-left">
                            <i class="fa-solid fa-tags"></i>
                            <span>Anda hemat Rp 159.840</span>
                        </div>
                        <i class="fa-solid fa-circle-check cooca-savings-check"></i>
                    </div>

                    <div class="cooca-urgency-row">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Harga promo onboarding terbatas</span>
                    </div>
                </div>

                {{-- Features List --}}
                <ul class="cooca-feature-list">
                    <li><i class="fa-solid fa-circle-check"></i> <span>Akses penuh modul Cooca ERP &amp; POS</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span>Dukungan teknis prioritas 24/7</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span>Free update &amp; cloud backup otomatis</span></li>
                </ul>

                <a href="{{ route('customer.register') }}" class="cooca-btn cooca-btn-side">
                    Pilih Paket Ini <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>

        {{-- Bottom Trust Bar --}}
        <div class="cooca-trust-bar reveal" style="margin-top: 10px;">
            <div class="cooca-trust-item">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Aman &amp; Terpercaya</span>
            </div>
            <span>•</span>
            <div class="cooca-trust-item">
                <i class="fa-solid fa-cloud"></i>
                <span>Cloud Backup</span>
            </div>
            <span>•</span>
            <div class="cooca-trust-item">
                <i class="fa-solid fa-headset"></i>
                <span>Support 24/7</span>
            </div>
            <span>•</span>
            <div class="cooca-trust-item">
                <i class="fa-solid fa-rotate"></i>
                <span>Free Update</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     TESTIMONIALS — Dynamic from DB
═══════════════════════════════════════════════════ --}}
<section class="lp-section" id="testimonials" aria-labelledby="testimonials-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">TERPERCAYA</span>
            <h2 class="lp-heading" id="testimonials-heading">
                Dipercaya oleh <span class="gradient-text">Ribuan Pengusaha</span><br>di Indonesia
            </h2>
        </div>

        @if($testimonials->count() > 0)
        <div class="testimonials-grid">
            @foreach($testimonials->take(6) as $index => $testimonial)
            <div class="testimonial-card reveal reveal-delay-{{ ($index % 3) + 1 }}">
                <div class="testimonial-stars">
                    @for($s = 1; $s <= ($testimonial->rating ?? 5); $s++)<i class="fa-solid fa-star"></i>@endfor
                    @for($s = ($testimonial->rating ?? 5) + 1; $s <= 5; $s++)<span style="opacity:.3;"><i class="fa-solid fa-star"></i></span>@endfor
                </div>
                <p class="testimonial-content">"{{ $testimonial->content }}"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">
                        @if($testimonial->avatar)
                            <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" loading="lazy">
                        @else
                            {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="testimonial-name">{{ $testimonial->name }}</div>
                        <div class="testimonial-role">{{ $testimonial->position }}{{ $testimonial->company ? ' · ' . $testimonial->company : '' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- Placeholder testimonials --}}
        <div class="testimonials-grid">
            @php
            $demoTestimonials = [
                ['name'=>'Andi Kurniawan','role'=>'Founder Bengkel Jaya Pro','rating'=>5,'content'=>'Cooca Auto membantu saya mengelola beberapa cabang sekaligus dengan lebih efisien. Laporan keuangan jadi lebih akurat, dan saya bisa fokus ekspansi bisnis.'],
                ['name'=>'dr. Melissa Furi','role'=>'Direktur Klinik Furi','rating'=>5,'content'=>'EMR digital dari Cooca Klinik membuat klinik kami sangat terorganisir dan pelayanan pasien meningkat drastis. Sangat recommended!'],
                ['name'=>'Dewi Lestari','role'=>'Owner Salon Cantik Premium','rating'=>5,'content'=>'Sistem booking dan reminder WhatsApp sangat membantu. No show berkurang hingga 70%. Saya tidak bisa bayangkan tanpa Cooca.id!'],
            ];
            @endphp
            @foreach($demoTestimonials as $i => $t)
            <div class="testimonial-card reveal reveal-delay-{{ $i+1 }}">
                <div class="testimonial-stars">
                    @for($s = 1; $s <= $t['rating']; $s++)<i class="fa-solid fa-star"></i>@endfor
                </div>
                <p class="testimonial-content">"{{ $t['content'] }}"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">{{ strtoupper(substr($t['name'],0,1)) }}</div>
                    <div>
                        <div class="testimonial-name">{{ $t['name'] }}</div>
                        <div class="testimonial-role">{{ $t['role'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     BLOG SECTION
═══════════════════════════════════════════════════ --}}
<section class="lp-section section-bg-alt" id="blog" aria-labelledby="blog-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">BLOG & INSIGHTS</span>
            <h2 class="lp-heading" id="blog-heading">
                Berita <span class="gradient-text">Terbaru</span>
            </h2>
            <p class="lp-subheading">Kumpulan artikel, tips bisnis, dan update terbaru dari COOCA.</p>
        </div>

        <div class="pricing-grid reveal" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
            @forelse($latestPosts as $post)
            <div class="pricing-card" style="padding: 24px; text-align: left; display: flex; flex-direction: column; gap: 16px;">
                @if($post->featured_image)
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                @else
                    <div style="width: 100%; height: 200px; background: var(--border-color); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-newspaper" style="font-size: 48px; color: var(--text-muted); opacity: 0.5;"></i>
                    </div>
                @endif
                <div style="font-size: 14px; color: var(--accent); font-weight: 600;">
                    {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                </div>
                <h3 style="font-size: 20px; font-weight: 700; color: var(--text-color); margin: 0; line-height: 1.4;">
                    {{ $post->title }}
                </h3>
                <p style="color: var(--text-muted); font-size: 15px; margin: 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                    {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                </p>
                <div style="margin-top: auto; padding-top: 16px;">
                    <a href="{{ route('blog.show', $post->slug) }}" style="color: var(--primary); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: color 0.2s;">
                        Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">
                Belum ada artikel terbaru.
            </div>
            @endforelse
        </div>

        @if($latestPosts->count() > 0)
        <div style="text-align: center; margin-top: 40px;" class="reveal">
            <a href="{{ route('blog.index') }}" class="btn-ghost">Lihat Semua Artikel</a>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     FAQ — Dynamic from DB
═══════════════════════════════════════════════════ --}}
<section class="lp-section" id="faq" aria-labelledby="faq-heading">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">FAQ</span>
            <h2 class="lp-heading" id="faq-heading">Pertanyaan yang <span class="gradient-text">Sering Ditanya</span></h2>
        </div>

        <div class="faq-list">
            @if($faqs->count() > 0)
                @foreach($faqs as $index => $faq)
                <div class="faq-item {{ $index === 0 ? 'open' : '' }}" id="faq-{{ $faq->id }}">
                    <div class="faq-question" role="button" tabindex="0" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                        <span class="faq-question-text">{{ $faq->question }}</span>
                        <span class="faq-icon" aria-hidden="true"><i class="fa-solid fa-plus"></i></span>
                    </div>
                    <div class="faq-answer" role="region">
                        <div class="faq-answer-inner">{{ $faq->answer }}</div>
                    </div>
                </div>
                @endforeach
            @else
            {{-- Default FAQs --}}
            @php
            $defaultFaqs = [
                ['q'=>'Apakah saya perlu install software khusus?','a'=>'Tidak sama sekali. COOCA.ID adalah platform berbasis cloud yang bisa diakses dari browser manapun — laptop, tablet, atau smartphone. Tidak ada instalasi yang diperlukan.'],
                ['q'=>'Berapa lama proses setup setelah daftar?','a'=>'Setup dasar bisa selesai dalam 24 jam kerja. Tim kami akan menghubungi Anda untuk konfigurasi awal dan training penggunaan sistem.'],
                ['q'=>'Apakah data saya aman di COOCA.ID?','a'=>'Keamanan data adalah prioritas utama kami. Semua data dienkripsi dengan AES-256, backup otomatis harian, dan infrastruktur kami memenuhi standar SOC2 dan ISO 27001.'],
                ['q'=>'Bisa digunakan untuk berapa banyak outlet?','a'=>'Tergantung paket yang Anda pilih. Starter mendukung 1 outlet, Professional hingga 5 outlet, dan Enterprise tidak terbatas. Anda bisa upgrade kapanpun.'],
                ['q'=>'Apakah ada biaya tersembunyi?','a'=>'Tidak ada biaya tersembunyi. Harga yang tercantum sudah all-inclusive termasuk hosting, update sistem, dan dukungan teknis dasar. Transparansi adalah nilai utama kami.'],
                ['q'=>'Bagaimana jika saya tidak puas?','a'=>'Kami menawarkan garansi uang kembali 30 hari tanpa syarat. Jika tidak puas dalam 30 hari pertama, kami kembalikan pembayaran Anda penuh.'],
            ];
            @endphp
            @foreach($defaultFaqs as $i => $faq)
            <div class="faq-item {{ $i === 0 ? 'open' : '' }}" id="faq-default-{{ $i }}">
                <div class="faq-question" role="button" tabindex="0" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                    <span class="faq-question-text">{{ $faq['q'] }}</span>
                    <span class="faq-icon" aria-hidden="true"><i class="fa-solid fa-plus"></i></span>
                </div>
                <div class="faq-answer" role="region">
                    <div class="faq-answer-inner">{{ $faq['a'] }}</div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     FINAL CTA SECTION
═══════════════════════════════════════════════════ --}}
<section class="lp-section--sm" id="cta" aria-labelledby="cta-heading">
    <div class="lp-container">
        <div class="cta-section">
            <div class="cta-glow"></div>
            <div class="cta-glow-2"></div>

            <span class="cta-eyebrow"><i class="fa-solid fa-bullseye"></i> TERBATAS · PROMO ONBOARDING 2025</span>

            <h2 class="cta-title" id="cta-heading">
                Siap Mentransformasi<br>Bisnis Anda?
            </h2>
            <p class="cta-desc">
                Bergabunglah bersama 1.000+ pengusaha di Indonesia yang telah mempercayakan sistem operasi mereka kepada Cooca.id.
            </p>

            <div class="cta-actions">
                <a href="{{ route('customer.register') }}" class="btn-white" id="cta-primary-btn">
                    <i class="fa-solid fa-rocket"></i> Daftar Sekarang — Gratis 14 Hari
                </a>
                <a href="{{ route('contact') }}" class="btn-white-outline" id="cta-demo-btn">
                    <i class="fa-solid fa-phone"></i> Konsultasi Tim Expert
                </a>
            </div>

            <p class="cta-trust">
                Tidak perlu kartu kredit · Cancel saat apapun · Data 100% aman
            </p>
        </div>
    </div>
</section>

@endsection