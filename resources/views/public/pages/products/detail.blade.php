@extends('layouts.public')

@section('title', ($product->name ?? 'Detail Produk') . ' — COOCA.ID')
@section('description', $product->short_description ?? $product->name . ' dari COOCA.ID — Solusi ERP Enterprise.')

@push('styles')
    {{-- Font Awesome 6 untuk mengganti emoji --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')

@section('og_title', ($product->name ?? 'Detail Produk') . ' — COOCA.ID')
@section('og_description', $product->short_description ?? $product->name . ' dari COOCA.ID — Solusi ERP Enterprise.')
@section('og_image', isset($product->thumbnail_url) && $product->thumbnail_url ? url($product->thumbnail_url) :
    asset('images/og-image.png'))

    {{-- Product Header --}}
    <section class="aurora-bg page-hero">
        <div class="lp-container">
            <div class="hero-grid" style="align-items: center;">
                {{-- Left Info --}}
                <div class="reveal">
                    <a href="{{ route('products.index') }}"
                        style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; margin-bottom: 16px;">
                        ← Kembali ke Katalog Produk
                    </a>

                    @if ($product->category)
                        <div style="margin-bottom: 12px;">
                            <span
                                style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; padding: 4px 12px; border-radius: 100px; background: rgba(79,70,229,.12); color: var(--primary);">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    @endif

                    <h1 class="lp-heading" style="font-size: clamp(32px, 5vw, 48px); margin-bottom: 16px;">
                        {{ $product->name }}
                    </h1>

                    <p style="font-size: 18px; color: var(--text-muted); line-height: 1.7; margin-bottom: 28px;">
                        {{ $product->short_description ?? Str::limit($product->description, 160) }}
                    </p>

                    {{-- Price & CTA Box with Psychological Pricing --}}
                    <div style="background: var(--surface); border: 1.5px solid rgba(79, 70, 229, 0.2); border-radius: 20px; padding: 24px; margin-bottom: 24px; box-shadow: var(--shadow-md);">
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

                        <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(79,70,229,.1); border: 1px solid rgba(79,70,229,.25); padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 12px;">
                            <i class="fa-solid fa-tags"></i> PENAWARAN SPESIAL
                        </div>

                        {{-- Price Anchoring --}}
                        <div class="pricing-anchor-top" style="margin-bottom: 4px;">
                            <span class="pricing-anchor-normal" style="font-size: 13.5px;">
                                Harga Normal: <span class="pricing-anchor-strikethrough" style="font-size: 14px;">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                            </span>
                            <span class="badge-discount-save" style="background: #10B981; color: #FFFFFF; font-size: 11.5px; padding: 3px 9px;">
                                <i class="fa-solid fa-bolt"></i> HEMAT {{ number_format($discount, 0) }}%
                            </span>
                        </div>

                        {{-- Priority #1: Promo Main Price --}}
                        <div class="pricing-main-price-row" style="margin-bottom: 8px;">
                            <span class="pricing-main-amount" style="color: var(--primary); font-size: clamp(32px, 5vw, 42px);">
                                <span class="currency" style="color: var(--primary);">Rp</span>{{ number_format($finalPrice, 0, ',', '.') }}
                            </span>
                            <span class="pricing-main-period" style="font-size: 15px; font-weight: 700;">{{ $period }}</span>
                        </div>

                        {{-- Priority #4: Value Reinforcement --}}
                        <div class="pricing-savings-ribbon" style="margin-bottom: 6px;">
                            <i class="fa-solid fa-circle-check"></i>
                            <span><strong>✓ Hemat Rp {{ number_format($savings, 0, ',', '.') }}</strong> setiap bulan</span>
                        </div>

                        {{-- Priority #5: Urgency Copy --}}
                        <div class="pricing-urgency-tag" style="margin-bottom: 18px;">
                            <i class="fa-solid fa-fire-flame-curved"></i> Harga promo onboarding terbatas
                        </div>

                        {{-- Conversion CTAs --}}
                        <div class="hero-actions" style="margin-top: 0; gap: 12px;">
                            <a href="{{ route('customer.register') }}" class="btn-primary-glow"
                                style="flex: 1.2; justify-content: center; padding: 14px; font-size: 15px; font-weight: 700;">
                                <i class="fa-solid fa-rocket"></i> Mulai Coba Gratis 14 Hari
                            </a>
                            <a href="{{ route('contact') }}" class="btn-ghost"
                                style="flex: 1; justify-content: center; padding: 14px 20px; font-size: 14px;">
                                <i class="fa-solid fa-comment-dots"></i> Konsultasi
                            </a>
                        </div>
                    </div>

                    {{-- Quick specs --}}
                    <div class="quick-specs" style="gap: 16px 24px; font-size: 13px; color: var(--text-muted);">
                        <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-check-circle"
                                style="color: var(--primary);"></i> Setup 24 Jam</span>
                        <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield"
                                style="color: var(--primary);"></i> Support 24/7</span>
                        <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-bolt"
                                style="color: var(--primary);"></i> SLA 99.9%</span>
                        <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-mobile-screen"
                                style="color: var(--primary);"></i> Cloud Native</span>
                    </div>
                </div>

                {{-- Right Image / Mockup --}}
                <div class="reveal reveal-delay-2">
                    <div
                        style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 24px; box-shadow: var(--shadow-xl); position: relative; overflow: hidden;">
                        @if ($product->thumbnail_url)
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                                style="width: 100%; height: 320px; object-fit: cover; border-radius: 16px;">
                        @else
                            <div
                                style="height: 320px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 96px;">
                                <div class="clay-icon"
                                    style="width: 140px; height: 140px; font-size: 64px; border-radius: 40px;">
                                    @if ($product->icon && !str_starts_with($product->icon, 'fa-'))
                                        {{ $product->icon }}
                                    @else
                                        <i class="fa-solid fa-box"></i>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="lp-section section-bg-alt">
        <div class="lp-container">
            <div class="lp-section-header reveal">
                <span class="lp-eyebrow">FITUR UNGGULAN</span>
                <h2 class="lp-heading">Kemampuan <span class="gradient-text">{{ $product->name }}</span></h2>
            </div>

            @if (is_array($product->features) && count($product->features) > 0)
                <div class="features-grid">
                    @foreach ($product->features as $i => $feature)
                        @php
                            $title = is_array($feature) ? $feature['title'] ?? ($feature['name'] ?? 'Fitur') : $feature;
                            $desc = is_array($feature)
                                ? $feature['description'] ?? 'Fitur bawaan platform COOCA.ID.'
                                : 'Fitur terlengkap untuk mengoptimalkan operasional bisnis Anda.';
                            $icon = is_array($feature)
                                ? $feature['icon'] ?? 'fa-solid fa-sparkles'
                                : 'fa-solid fa-sparkles';
                        @endphp
                        <div class="reveal reveal-delay-{{ ($i % 3) + 1 }}"
                            style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 28px;">
                            <div
                                style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; border: 1px solid var(--border);">
                                <i class="{{ $icon }}"></i>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                                {{ $title }}</h3>
                            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="features-grid">
                    @php
                        $defaultFeats = [
                            [
                                'fa-solid fa-bolt',
                                'Manajemen Operasional Realtime',
                                'Kelola transaksi dan alur kerja bisnis secara instan dari dashboard mana saja.',
                            ],
                            [
                                'fa-solid fa-chart-bar',
                                'Laporan Keuangan Otomatis',
                                'Laporan laba rugi, arus kas, dan neraca langsung terbentuk tanpa perlu input manual.',
                            ],
                            [
                                'fa-solid fa-mobile-screen',
                                'Akses Multi-Device',
                                'Bisa diakses dari laptop, tablet, hingga smartphone Android / iOS.',
                            ],
                            [
                                'fa-solid fa-shield',
                                'Keamanan & Backup Harian',
                                'Data tersimpan aman di cloud server dengan enkripsi AES-256 dan backup berkala.',
                            ],
                            [
                                'fa-solid fa-bell',
                                'Notifikasi WhatsApp',
                                'Kirim konfirmasi pesanan, booking, atau tagihan langsung ke pelanggan via WA.',
                            ],
                            [
                                'fa-solid fa-plug',
                                'Integrasi API & Webhook',
                                'Siap dihubungkan ke payment gateway, logistik, atau sistem internal Anda.',
                            ],
                        ];
                    @endphp
                    @foreach ($defaultFeats as $i => $f)
                        <div class="reveal reveal-delay-{{ ($i % 3) + 1 }}"
                            style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 28px;">
                            <div
                                style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; border: 1px solid var(--border);">
                                <i class="{{ $f[0] }}"></i>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                                {{ $f[1] }}</h3>
                            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">{{ $f[2] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Subscription Plans with Psychological Pricing Hierarchy --}}
    @if ($product->subscriptionPlans && $product->subscriptionPlans->count() > 0)
        <section class="lp-section">
            <div class="lp-container">
                <div class="lp-section-header reveal" style="text-align: center; max-width: 720px; margin: 0 auto 48px;">
                    <span class="lp-eyebrow">PILIHAN PAKET LANGGANAN</span>
                    <h2 class="lp-heading">Investasi Terbaik untuk <span class="gradient-text">{{ $product->name }}</span></h2>
                    <p class="lp-subheading">Pilih paket durasi yang paling hemat untuk efisiensi maksimal bisnis Anda.</p>
                </div>

                <div class="pricing-grid reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 28px; align-items: stretch;">
                    @foreach ($product->subscriptionPlans as $i => $plan)
                        @php
                            $rawPrice = (float) $plan->price;
                            $planDiscount = (float) ($plan->discount_percent ?? 0);
                            if ($planDiscount > 0) {
                                $origPrice = $rawPrice;
                                $finalPrice = $origPrice * (1 - $planDiscount / 100);
                                $discount = $planDiscount;
                            } else {
                                $finalPrice = $rawPrice;
                                $origPrice = $rawPrice * 2;
                                $discount = 50;
                            }
                            $savings = $origPrice - $finalPrice;
                            $isPopular = ($i === 1 || $plan->is_popular);
                        @endphp
                        <div class="pricing-psych-card {{ $isPopular ? 'featured-card' : '' }}" style="position: relative;">
                            @if ($isPopular)
                                <div class="pricing-badge-special">
                                    <i class="fa-solid fa-star"></i> MOST POPULAR · REKOMENDASI
                                </div>
                            @endif

                            <div style="margin-top: {{ $isPopular ? '8px' : '0' }}; margin-bottom: 8px;">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: {{ $isPopular ? 'var(--primary)' : 'var(--text-muted)' }}; padding: 3px 8px; background: {{ $isPopular ? 'var(--primary-soft)' : 'var(--bg)' }}; border-radius: 6px;">
                                    {{ $plan->duration_months >= 999 ? 'LIFETIME ACCESS' : ($plan->duration_months . ' BULAN') }}
                                </span>
                            </div>

                            <h3 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 6px;">{{ $plan->name }}</h3>

                            {{-- Price Anchor Box --}}
                            <div class="pricing-anchor-container" style="{{ $isPopular ? 'background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(6, 182, 212, 0.06) 100%); border-color: rgba(79, 70, 229, 0.25);' : '' }}">
                                <div class="pricing-anchor-top">
                                    <span class="pricing-anchor-normal">
                                        Harga Normal: <span class="pricing-anchor-strikethrough">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                                    </span>
                                    <span class="badge-discount-save">
                                        HEMAT {{ number_format($discount, 0) }}%
                                    </span>
                                </div>

                                <div class="pricing-main-price-row">
                                    <span class="pricing-main-amount" style="{{ $isPopular ? 'color: var(--primary);' : '' }}">
                                        <span class="currency">Rp</span>{{ number_format($finalPrice, 0, ',', '.') }}
                                    </span>
                                    <span class="pricing-main-period">
                                        @if ($plan->duration_months >= 999)
                                            / Lifetime
                                        @elseif($plan->duration_months == 1)
                                            / bulan
                                        @elseif($plan->duration_months == 12)
                                            / tahun
                                        @else
                                            / {{ $plan->duration_months }} bln
                                        @endif
                                    </span>
                                </div>

                                <div class="pricing-savings-ribbon">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span><strong>✓ Hemat Rp {{ number_format($savings, 0, ',', '.') }}</strong></span>
                                </div>

                                <div class="pricing-urgency-tag">
                                    <i class="fa-solid fa-bolt"></i> Harga promo onboarding terbatas
                                </div>
                            </div>

                            <div style="border-top: 1px solid var(--border); margin: 8px 0 16px;"></div>

                            <ul class="pricing-features" style="list-style: none; padding: 0; margin: 0 0 24px; display: flex; flex-direction: column; gap: 10px; flex: 1;">
                                <li style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--text);">
                                    <i class="fa-solid fa-check text-success"></i> Akses penuh modul {{ $product->name }}
                                </li>
                                @if ($plan->duration_months >= 999 && $product->maintenance_fee > 0)
                                    <li style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--text);">
                                        <i class="fa-solid fa-screwdriver-wrench text-primary"></i> Maint. Fee: Rp {{ number_format($product->maintenance_fee, 0, ',', '.') }}/thn
                                    </li>
                                @endif
                                <li style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--text);">
                                    <i class="fa-solid fa-check text-success"></i> Dukungan teknis prioritas 24/7
                                </li>
                                <li style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: var(--text);">
                                    <i class="fa-solid fa-check text-success"></i> Free update &amp; cloud backup
                                </li>
                            </ul>

                            <a href="{{ route('customer.register') }}"
                                class="{{ $isPopular ? 'btn-primary-glow' : 'btn-ghost' }}"
                                style="text-align:center; justify-content:center; width:100%; padding: 12px; font-weight: 700; border-radius: 12px;">
                                Pilih Paket Ini <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="lp-section--sm">
        <div class="lp-container">
            <div class="cta-section">
                <div class="cta-glow"></div>
                <h2 class="cta-title">Siap Mencoba {{ $product->name }}?</h2>
                <p class="cta-desc">Mulai ujicoba gratis 14 hari tanpa perlu kartu kredit. Setup dibantu tim ahli kami.</p>
                <div class="cta-actions">
                    <a href="{{ route('customer.register') }}" class="btn-white">
                        <i class="fa-solid fa-rocket"></i> Coba Gratis 14 Hari
                    </a>
                    <a href="{{ route('contact') }}" class="btn-white-outline">
                        <i class="fa-solid fa-phone"></i> Minta Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
