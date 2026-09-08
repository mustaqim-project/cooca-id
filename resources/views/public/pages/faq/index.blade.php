@extends('layouts.public')

@section('title', 'FAQ & Pusat Bantuan ERP | Pertanyaan Seputar COOCA.ID')
@section('description', 'Jawaban lengkap seputar setup ERP, keamanan data, harga, integrasi cabang, dan garansi 14 hari COOCA.ID. Temukan solusi untuk bisnis Anda di sini.')
@section('keywords', 'faq erp indonesia, pertanyaan software bisnis, bantuan teknis erp, onboarding erp, keamanan data cloud bisnis, biaya erp bulanan, cooca faq')

@push('seo')
<link rel="canonical" href="{{ url('/faq') }}">
<meta name="robots" content="index, follow">
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "Apakah saya perlu instalasi hardware khusus?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Tidak. COOCA.ID sepenuhnya cloud-based dan dapat diakses dari browser mana saja (laptop, tablet, HP Android/iOS). Anda cukup memiliki koneksi internet."
            }
        },
        {
            "@type": "Question",
            "name": "Berapa lama proses onboarding dan setup awal?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Setup akun selesai secara otomatis dalam hitungan menit. Konfigurasi awal dan pendampingan tim kami biasanya membutuhkan 1x24 jam kerja."
            }
        },
        {
            "@type": "Question",
            "name": "Apakah data bisnis kami terjamin keamanannya?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Sangat terjamin. Data disimpan dengan enkripsi kelas bank (AES-256), arsitektur multi-tenant terisolasi, backup harian otomatis, dan jaminan Uptime SLA 99.9%."
            }
        },
        {
            "@type": "Question",
            "name": "Bagaimana jika cabang atau bisnis kami bertambah?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Platform COOCA.ID sangat modular. Anda dapat menambah cabang, kasir, atau modul baru kapan saja dari dashboard tanpa perlu instalasi ulang."
            }
        },
        {
            "@type": "Question",
            "name": "Apakah ada garansi jika produk tidak sesuai?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Kami menyediakan uji coba gratis 14 hari penuh tanpa perlu kartu kredit. Anda dapat menguji semua fitur secara langsung sebelum memutuskan berlangganan."
            }
        },
        {
            "@type": "Question",
            "name": "Metode pembayaran apa saja yang didukung?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Kami mendukung transfer bank, QRIS, e-wallet (GoPay, OVO, ShopeePay), kartu kredit/debit, serta opsi invoicing bulanan untuk paket Enterprise."
            }
        }
    ]
}
</script>
@endpush

@section('content')

{{-- Hero --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="text-align: center; max-width: 640px; margin: 0 auto;">
            <span class="lp-eyebrow">PUSAT BANTUAN</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(40px,5vw,56px); margin-bottom: 16px;">
                FAQ & Pusat Bantuan <span class="gradient-text">ERP COOCA.ID</span>
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto;">Jawaban lengkap seputar setup, harga, keamanan data, dan integrasi cabang platform ERP COOCA.ID.</p>
        </div>
    </div>
</section>

{{-- FAQ List --}}
<section class="lp-section">
    <div class="lp-container">
        <div class="faq-list">
            @if(isset($faqs) && $faqs->count() > 0)
                @foreach($faqs as $index => $faq)
                <div class="faq-item {{ $index === 0 ? 'open' : '' }}" id="faq-page-{{ $faq->id }}">
                    <div class="faq-question" role="button" tabindex="0" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                        <span class="faq-question-text">{{ $faq->question }}</span>
                        <span class="faq-icon" aria-hidden="true">+</span>
                    </div>
                    <div class="faq-answer" role="region">
                        <div class="faq-answer-inner">{!! nl2br(e($faq->answer)) !!}</div>
                    </div>
                </div>
                @endforeach
            @else
            @php
            $defaultFaqs = [
                ['q'=>'Apakah saya perlu instalasi hardware khusus?','a'=>'Tidak. COOCA.ID sepenuhnya cloud-based dan dapat diakses dari browser mana saja (laptop, tablet, HP Android/iOS). Anda cukup memiliki koneksi internet.'],
                ['q'=>'Berapa lama proses onboarding dan setup awal?','a'=>'Setup akun selesai secara otomatis dalam hitungan menit. Konfigurasi awal dan pendampingan tim kami biasanya membutuhkan 1x24 jam kerja.'],
                ['q'=>'Apakah data bisnis kami terjamin keamanannya?','a'=>'Sangat terjamin. Data disimpan dengan enkripsi kelas bank (AES-256), arsitektur multi-tenant terisolasi, backup harian otomatis, dan jaminan Uptime SLA 99.9%.'],
                ['q'=>'Bagaimana jika cabang atau bisnis kami bertambah?','a'=>'Platform COOCA.ID sangat modular. Anda dapat menambah cabang, kasir, atau modul baru kapan saja dari dashboard tanpa perlu instalasi ulang.'],
                ['q'=>'Apakah ada garansi jika produk tidak sesuai?','a'=>'Kami menyediakan uji coba gratis 14 hari penuh tanpa kartu kredit. Anda dapat menguji semua fitur secara langsung sebelum memutuskan berlangganan.'],
                ['q'=>'Bagaimana jika internet di tempat kami terputus?','a'=>'Beberapa modul POS kami dilengkapi fitur offline mode. Transaksi dapat tetap berlangsung dan data akan tersinkronisasi otomatis begitu koneksi terhubung kembali.'],
                ['q'=>'Metode pembayaran apa saja yang didukung?','a'=>'Kami mendukung transfer bank, QRIS, e-wallet (GoPay, OVO, ShopeePay), kartu kredit/debit, serta opsi invoicing bulanan untuk paket Enterprise.'],
            ];
            @endphp
            @foreach($defaultFaqs as $i => $faq)
            <div class="faq-item {{ $i === 0 ? 'open' : '' }}" id="faq-default-page-{{ $i }}">
                <div class="faq-question" role="button" tabindex="0" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                    <span class="faq-question-text">{{ $faq['q'] }}</span>
                    <span class="faq-icon" aria-hidden="true">+</span>
                </div>
                <div class="faq-answer" role="region">
                    <div class="faq-answer-inner">{{ $faq['a'] }}</div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        {{-- Contact Prompt --}}
        <div style="text-align: center; margin-top: 56px; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px;" class="reveal">
            <h3 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 8px;">Masih Punya Pertanyaan Lain?</h3>
            <p style="font-size: 15px; color: var(--text-muted); margin-bottom: 24px;">Tim konsultan bisnis kami siap membantu memberikan solusi terbaik untuk usaha Anda.</p>
            <a href="{{ route('contact') }}" class="btn-primary-glow" style="display: inline-flex;"><i class="fa-solid fa-comments"></i> Hubungi Tim Support</a>
        </div>
    </div>
</section>

@endsection
