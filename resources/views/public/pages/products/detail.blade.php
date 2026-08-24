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

                    {{-- Price & CTA --}}
                    <div
                        style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 24px; margin-bottom: 24px;">
                        @php
                            $lowestPlan = $product->subscriptionPlans
                                ->where('is_active', true)
                                ->sortBy('price')
                                ->first();
                            $origPrice = $lowestPlan ? (float)$lowestPlan->price : (float)($product->base_price ?? 0);
                            $discount = $lowestPlan ? (float)($lowestPlan->discount_percent ?? 0) : 0;
                            $finalPrice = $discount > 0 ? $origPrice * (1 - $discount / 100) : $origPrice;
                            $period = '';
                            if ($lowestPlan) {
                                if ($lowestPlan->duration_months >= 999) {
                                    $period = ' / Lifetime';
                                } elseif ($lowestPlan->duration_months == 1) {
                                    $period = '/bulan';
                                } elseif ($lowestPlan->duration_months == 12) {
                                    $period = '/tahun';
                                } else {
                                    $period = '/' . $lowestPlan->duration_months . ' bulan';
                                }
                            }
                        @endphp

                        <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;">
                            <span style="font-size: 13px; color: var(--text-muted); font-weight: 600;">Mulai dari</span>
                            @if ($origPrice > 0 || $finalPrice > 0)
                                @if ($discount > 0)
                                    <span style="font-size: 16px; color: var(--text-muted); text-decoration: line-through; margin-right: 4px;">
                                        Rp {{ number_format($origPrice, 0, ',', '.') }}
                                    </span>
                                    <span
                                        style="font-size: clamp(28px, 6vw, 36px); font-weight: 900; color: var(--text); letter-spacing: -.03em;">
                                        Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                    </span>
                                    <span style="font-size: 14px; color: var(--text-muted);">{{ $period }}</span>
                                    <span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); font-size: 12px; font-weight: 600; padding: 3px 8px; border-radius: 999px;">
                                        Hemat {{ number_format($discount, ($discount == floor($discount) ? 0 : 2)) }}%
                                    </span>
                                @else
                                    <span
                                        style="font-size: clamp(28px, 6vw, 36px); font-weight: 900; color: var(--text); letter-spacing: -.03em;">
                                        Rp {{ number_format($origPrice, 0, ',', '.') }}
                                    </span>
                                    <span style="font-size: 14px; color: var(--text-muted);">{{ $period }}</span>
                                @endif
                            @else
                                <span
                                    style="font-size: clamp(24px, 5vw, 28px); font-weight: 800; color: var(--text);">Hubungi
                                    Sales</span>
                            @endif
                        </div>

                        <div class="hero-actions" style="margin-top: 0; gap: 12px;">
                            <a href="{{ route('customer.register') }}" class="btn-primary-glow"
                                style="flex: 1; justify-content: center; padding: 14px; font-size: 15px;">
                                <i class="fa-solid fa-rocket"></i> Coba Gratis 14 Hari
                            </a>
                            <a href="{{ route('contact') }}" class="btn-ghost"
                                style="flex: 1; justify-content: center; padding: 14px 24px; font-size: 15px;">
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
                                    {{-- Jika product->icon adalah emoji atau tidak ada, ganti dengan ikon default --}}
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

    {{-- Subscription Plans --}}
    @if ($product->subscriptionPlans && $product->subscriptionPlans->count() > 0)
        <section class="lp-section">
            <div class="lp-container">
                <div class="lp-section-header reveal">
                    <span class="lp-eyebrow">PILIHAN PAKET</span>
                    <h2 class="lp-heading">Paket Langganan <span class="gradient-text">{{ $product->name }}</span></h2>
                </div>

                <div class="pricing-grid reveal">
                    @foreach ($product->subscriptionPlans as $i => $plan)
                        @php
                            $origPrice = (float) $plan->price;
                            $discount = (float) ($plan->discount_percent ?? 0);
                            $finalPrice = $discount > 0 ? $origPrice * (1 - $discount / 100) : $origPrice;
                        @endphp
                        <div class="pricing-card {{ $i === 1 ? 'featured' : '' }}">
                            @if ($i === 1)
                                <div class="pricing-badge"><i class="fa-solid fa-bolt"></i> Best Plan</div>
                            @endif
                            <div class="pricing-name">{{ $plan->name }}</div>
                            <div class="pricing-price-wrap" style="margin: 12px 0;">
                                @if ($discount > 0)
                                    <div style="font-size: 13px; color: var(--text-muted); text-decoration: line-through; margin-bottom: 2px;">
                                        Rp {{ number_format($origPrice, 0, ',', '.') }}
                                    </div>
                                    <div class="pricing-price" style="margin-top: 0;">
                                        Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                        <span class="pricing-period">
                                            @if ($plan->duration_months >= 999)
                                                / Lifetime
                                            @elseif($plan->duration_months == 1)
                                                / bln
                                            @elseif($plan->duration_months == 12)
                                                / thn
                                            @else
                                                / {{ $plan->duration_months }} bln
                                            @endif
                                        </span>
                                    </div>
                                    <div style="margin-top: 6px;">
                                        <span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px;">
                                            Hemat {{ number_format($discount, ($discount == floor($discount) ? 0 : 2)) }}%
                                        </span>
                                    </div>
                                @else
                                    <div class="pricing-price">
                                        Rp {{ number_format($origPrice, 0, ',', '.') }}
                                        <span class="pricing-period">
                                            @if ($plan->duration_months >= 999)
                                                / Lifetime
                                            @elseif($plan->duration_months == 1)
                                                / bln
                                            @elseif($plan->duration_months == 12)
                                                / thn
                                            @else
                                                / {{ $plan->duration_months }} bln
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="pricing-divider"></div>
                            <ul class="pricing-features">
                                <li class="pricing-feature"><span class="check"><i class="fa-solid fa-check"></i></span>
                                    Akses penuh modul {{ $product->name }}</li>
                                @if ($plan->duration_months >= 999 && $product->maintenance_fee > 0)
                                    <li class="pricing-feature"><span class="check"><i
                                                class="fa-solid fa-screwdriver-wrench"
                                                style="color: var(--primary);"></i></span> Maint. Fee: Rp
                                        {{ number_format($product->maintenance_fee, 0, ',', '.') }}/thn</li>
                                @endif
                                <li class="pricing-feature"><span class="check"><i class="fa-solid fa-check"></i></span>
                                    Dukungan teknis 24/7</li>
                                <li class="pricing-feature"><span class="check"><i class="fa-solid fa-check"></i></span>
                                    Free update selamanya</li>
                            </ul>
                            <a href="{{ route('customer.register') }}"
                                class="{{ $i === 1 ? 'btn-white' : 'btn-ghost' }}"
                                style="text-align:center; justify-content:center; width:100%;">
                                Pilih Paket Ini
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
