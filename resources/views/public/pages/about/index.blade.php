@extends('layouts.public')

@section('title', 'Tentang Kami - Move Faster. Decide Better | COOCA.ID')
@section('description', 'Pelajari bagaimana COOCA.ID membantu pengusaha bergerak lebih cepat dan mengambil keputusan lebih baik melalui teknologi software bisnis tepercaya.')
@section('keywords', 'tentang cooca id, profil perusahaan cooca, move faster decide better cooca, developer software bisnis, perusahaan saas indonesia')

@section('content')

{{-- Page Hero --}}
<section class="aurora-bg page-hero" style="position: relative; overflow: hidden;">
    <div class="lp-container">
        <div style="text-align: center; max-width: 760px; margin: 0 auto;">
            <span class="lp-eyebrow">TENTANG KAMI</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(40px, 6vw, 64px); margin-bottom: 20px;">
                Kami Membangun <span class="gradient-text">Masa Depan</span><br>Bisnis Indonesia
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto 40px; max-width: 560px;">
                COOCA.ID adalah platform ERP enterprise yang dirancang dari Indonesia, untuk bisnis Indonesia — dari UMKM hingga perusahaan multinasional.
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="lp-section section-bg-alt">
    <div class="lp-container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;">
            <div class="reveal">
                <span class="lp-eyebrow">MISI KAMI</span>
                <h2 class="lp-heading" style="font-size: 40px;">Digitalisasi Bisnis Indonesia Tanpa Hambatan</h2>
                <p style="font-size: 17px; color: var(--text-muted); line-height: 1.75; margin-bottom: 24px;">
                    Kami percaya bahwa setiap bisnis di Indonesia — dari warung kecil hingga perusahaan besar — berhak mendapatkan teknologi ERP kelas dunia dengan harga yang terjangkau.
                </p>
                <p style="font-size: 17px; color: var(--text-muted); line-height: 1.75;">
                    COOCA.ID hadir sebagai jembatan antara kebutuhan operasional bisnis modern dan teknologi cloud yang sebelumnya hanya bisa dijangkau oleh konglomerat.
                </p>
            </div>
            <div class="reveal reveal-delay-2">
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -40px; right: -40px; width: 150px; height: 150px; background: var(--primary-glow); border-radius: 50%; filter: blur(40px);"></div>
                    <div style="position: absolute; bottom: -40px; left: -40px; width: 100px; height: 100px; background: var(--accent-glow); border-radius: 50%; filter: blur(30px);"></div>
                    <div style="position: relative;">
                        <div class="clay-icon" style="margin-bottom: 24px;"><i class="fa-solid fa-bullseye"></i></div>
                        <h3 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 12px;">Visi 2030</h3>
                        <p style="font-size: 15px; color: var(--text-muted); line-height: 1.7;">
                            Menjadi platform ERP #1 di Indonesia yang melayani 100.000+ bisnis aktif, dengan lebih dari 50 jenis modul industri yang terintegasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Company Stats --}}
{{-- <section class="lp-section">
    <div class="lp-container">
        <div class="stats-grid reveal">
            @php
            $stats = [
                ['2022', 'Tahun Berdiri'],
                ['1.000+', 'Bisnis Terdaftar'],
                ['14+', 'Jenis Modul ERP'],
                ['50+', 'Tim Profesional'],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="stat-item">
                <div class="stat-number">{{ $s[0] }}</div>
                <div class="stat-label">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section> --}}

{{-- Our Values --}}
<section class="lp-section section-bg-alt">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">NILAI KAMI</span>
            <h2 class="lp-heading">Dibangun di Atas <span class="gradient-text">Fondasi yang Kuat</span></h2>
        </div>
        <div class="why-grid">
            @php
            $values = [
                ['<i class="fa-solid fa-flag"></i>','Indonesia First','Semua keputusan produk kami berpusat pada kebutuhan bisnis lokal Indonesia.'],
                ['<i class="fa-solid fa-magnifying-glass"></i>','Transparansi','Tidak ada biaya tersembunyi. Semua harga, fitur, dan SLA kami tulis dengan jelas.'],
                ['<i class="fa-solid fa-rocket"></i>','Inovasi Cepat','Kami merilis pembaruan setiap 2 minggu. Pelanggan kami selalu di garis terdepan.'],
                ['<i class="fa-solid fa-handshake"></i>','Kemitraan Jangka Panjang','Kami bukan vendor. Kami adalah partner pertumbuhan bisnis Anda.'],
                ['<i class="fa-solid fa-lock"></i>','Keamanan Tanpa Kompromi','Keamanan data bukan fitur tambahan — ini adalah fondasi dari setiap baris kode kami.'],
                ['<i class="fa-solid fa-comments"></i>','Dukungan Manusiawi','Tim support kami adalah manusia nyata yang peduli terhadap bisnis Anda.'],
                ['<i class="fa-solid fa-arrow-trend-up"></i>','Hasil Terukur','Setiap fitur kami lahir dari data dan feedback nyata pengguna, bukan asumsi.'],
                ['<i class="fa-solid fa-seedling"></i>','Skalabilitas','Mulai dari 1 outlet, tumbuh ke 1000. Infrastruktur kami siap bersama Anda.'],
            ];
            @endphp
            @foreach($values as $i => $v)
            <div class="why-card reveal reveal-delay-{{ ($i % 4) + 1 }}">
                <div class="clay-icon" style="margin-bottom: 16px;">{!! $v[0] !!}</div>
                <h3 class="why-title">{{ $v[1] }}</h3>
                <p class="why-desc">{{ $v[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Team Section --}}
{{-- <section class="lp-section">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">TIM KAMI</span>
            <h2 class="lp-heading">Orang-Orang di Balik <span class="gradient-text">COOCA.ID</span></h2>
            <p class="lp-subheading">Tim multidisiplin yang berpengalaman di teknologi, bisnis, dan desain produk.</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
            @php
            $team = [
                ['<i class="fa-solid fa-user-tie"></i>','Ahmad Rizky','Co-Founder & CEO','Mantan engineer Google dengan 10 tahun pengalaman di cloud infrastructure.','#4F46E5'],
                ['<i class="fa-solid fa-user-gear"></i>','Sari Dewanti','Co-Founder & COO','MBA dari NUS. Ex-McKinsey consultant dengan expertise di operasional bisnis Asia.','#7C3AED'],
                ['<i class="fa-solid fa-palette"></i>','Budi Setiawan','Head of Design','Mantan Lead Designer Tokopedia. Obsesi pada simplisitas dan estetika premium.','#06B6D4'],
                ['<i class="fa-solid fa-laptop-code"></i>','Maya Putri','Head of Engineering','Arsitektur cloud-native. Mantan Senior Engineer GoTo Group.','#8B5CF6'],
            ];
            @endphp
            @foreach($team as $i => $member)
            <div class="reveal reveal-delay-{{ $i+1 }}" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; transition: all .3s ease;">
                <div style="height: 160px; background: linear-gradient(135deg, {{ $member[4] }}22, {{ $member[4] }}44); display: flex; align-items: center; justify-content: center; font-size: 72px; position: relative;">
                    {{ $member[0] }}
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40px; background: linear-gradient(transparent, var(--surface));"></div>
                </div>
                <div style="padding: 20px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 4px;">{{ $member[1] }}</h3>
                    <p style="font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 10px; letter-spacing: .04em;">{{ $member[2] }}</p>
                    <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">{{ $member[3] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section> --}}

{{-- Testimonials --}}
@if($testimonials->count() > 0)
<section class="lp-section section-bg-alt">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">KATA MEREKA</span>
            <h2 class="lp-heading">Bisnis yang Tumbuh <span class="gradient-text">Bersama Kami</span></h2>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonials->take(3) as $i => $t)
            <div class="testimonial-card reveal reveal-delay-{{ $i+1 }}">
                <div class="testimonial-stars">{{ str_repeat('★', $t->rating ?? 5) }}</div>
                <p class="testimonial-content">"{{ $t->content }}"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                    <div>
                        <div class="testimonial-name">{{ $t->name }}</div>
                        <div class="testimonial-role">{{ $t->position }}{{ $t->company ? ' · '.$t->company : '' }}</div>
                    </div>
                </div>
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
            <h2 class="cta-title">Bergabunglah Bersama Kami</h2>
            <p class="cta-desc">Jadilah bagian dari gerakan digitalisasi bisnis Indonesia. Mulai gratis hari ini.</p>
            <div class="cta-actions">
                <a href="{{ route('customer.register') }}" class="btn-white"><i class="fa-solid fa-rocket"></i> Coba Gratis 14 Hari</a>
                <a href="{{ route('contact') }}" class="btn-white-outline">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

@endsection
