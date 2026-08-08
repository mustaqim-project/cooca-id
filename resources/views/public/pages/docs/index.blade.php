@extends('layouts.public')

@section('title', 'Dokumentasi & Panduan Penggunaan — COOCA.ID')
@section('description', 'Panduan lengkap penggunaan platform ERP COOCA.ID, panduan API, integrasi hardware, dan manajemen tenant.')

@section('content')

{{-- Hero --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="text-align: center; max-width: 680px; margin: 0 auto;">
            <span class="lp-eyebrow">DOKUMENTASI SISTEM</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(36px,5vw,56px); margin-bottom: 16px;">
                Panduan &amp; <span class="gradient-text">Dokumentasi ERP</span>
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto;">Semua panduan langkah demi langkah untuk mengoptimalkan operasional bisnis Anda.</p>
        </div>
    </div>
</section>

{{-- Docs Grid --}}
<section class="lp-section">
    <div class="lp-container">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            <div class="reveal reveal-delay-1" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 28px; display: flex; flex-direction: column; grid-column: 1 / -1;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow)); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; border: 1px solid var(--border);"><i class="fa-solid fa-server"></i></div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text); margin-bottom: 8px;">Dokumentasi Teknis & Sistem COOCA</h3>
                <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; flex: 1;">Dokumen spesifikasi kebutuhan bisnis, arsitektur, API, hingga panduan Go-Live untuk platform COOCA.ID.</p>

                <ul style="list-style: none; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 12px; border-top: 1px solid var(--border); padding-top: 16px;">
                    @foreach($docs as $doc)
                    <li>
                        <a href="{{ route('docs.show', $doc['slug']) }}" style="font-size: 14px; color: var(--primary); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; transition: 0.2s;" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='transparent'">
                            <span>📄</span> {{ $doc['title'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection
