@extends('layouts.public')

@section('title', 'Program Afiliasi Software Bisnis | COOCA.ID')
@section('description', 'Bantu para pengusaha Move Faster & Decide Better. Bergabunglah menjadi mitra afiliasi COOCA.ID dan dapatkan komisi berkelanjutan.')
@section('keywords', 'program afiliasi software, affiliate saas indonesia, komisi reseller software, mitra bisnis cooca, peluang bisnis teknologi')

@section('content')

{{-- Hero --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="text-align: center; max-width: 720px; margin: 0 auto;">
            <span class="lp-eyebrow">PROGRAM PARTNER &amp; AFFILIATE</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(40px,6vw,64px); margin-bottom: 20px;">
                Penghasilan <span class="gradient-text">Pasif 2 Level</span><br>Bersama COOCA.ID
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto 36px;">
                Rekomendasikan platform ERP terbaik ke jaringan Anda. Dapatkan komisi <strong>25% (Level 1)</strong> dan <strong>5% (Level 2)</strong> dari setiap tagihan bulanan klien.
            </p>

            <div class="reveal" style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('affiliator.register') }}" class="btn-primary-glow btn-hero">
                    <i class="fa-solid fa-rocket"></i> Daftar Sebagai Partner
                </a>
                <a href="{{ route('affiliator.login') }}" class="btn-ghost btn-hero">
                    Masuk ke Dashboard Partner
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Program Highlights --}}
<section class="lp-section section-bg-alt">
    <div class="lp-container">
        <div class="stats-grid reveal">
            <div class="stat-item">
                <div class="stat-number">25% + 5%</div>
                <div class="stat-label">Komisi Level 1 & Level 2</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Recurring</div>
                <div class="stat-label">Komisi Dibayar Tiap Bulan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Realtime</div>
                <div class="stat-label">Tracking Link & Dashboard</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">14 Hari</div>
                <div class="stat-label">Proses Pencairan Cepat</div>
            </div>
        </div>
    </div>
</section>

{{-- Calculator Section --}}
<section class="lp-section">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">KALKULATOR PENDAPATAN</span>
            <h2 class="lp-heading">Simulasi <span class="gradient-text">Pendapatan Pasif</span></h2>
            <p class="lp-subheading">Hitung potensi komisi recurring bulanan Anda dengan sistem 2-level (25% Direct + 5% Sub-affiliate).</p>
        </div>

        <div class="reveal calc-card" style="max-width: 800px; margin: 0 auto; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; box-shadow: var(--shadow-xl); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: var(--primary); filter: blur(80px); opacity: 0.3;"></div>
            <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; background: var(--accent); filter: blur(80px); opacity: 0.3;"></div>
            
            <div style="position: relative; z-index: 10;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                    
                    {{-- Input Area --}}
                    <div>
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 14px; font-weight: 700; color: var(--text-color); margin-bottom: 8px;">1. Klien Langsung (Level 1 - 25%)</label>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <input type="range" id="calc-level1" min="1" max="100" value="10" style="flex: 1; accent-color: var(--primary);">
                                <span id="val-level1" style="font-weight: 700; font-size: 18px; color: var(--primary); min-width: 40px; text-align: center;">10</span>
                            </div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 14px; font-weight: 700; color: var(--text-color); margin-bottom: 8px;">2. Klien dari Sub-Affiliate (Level 2 - 5%)</label>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <input type="range" id="calc-level2" min="0" max="500" value="50" style="flex: 1; accent-color: var(--accent);">
                                <span id="val-level2" style="font-weight: 700; font-size: 18px; color: var(--accent); min-width: 40px; text-align: center;">50</span>
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; color: var(--text-color); margin-bottom: 8px;">3. Rata-rata Tagihan per Klien</label>
                            <select id="calc-price" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg-body); color: var(--text-color); font-size: 15px; outline: none; font-family: inherit;">
                                <option value="200000">Rp 200.000 / bulan</option>
                                <option value="350000" selected>Rp 350.000 / bulan</option>
                                <option value="500000">Rp 500.000 / bulan</option>
                                <option value="1000000">Rp 1.000.000 / bulan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Result Area --}}
                    <div class="calc-result" style="background: linear-gradient(135deg, rgba(79,70,229,0.1), rgba(6,182,212,0.1)); border-radius: 20px; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 1px solid rgba(79,70,229,0.2);">
                        <div style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; text-align: center;">Estimasi Komisi Tiap Bulan</div>
                        
                        <div id="calc-total" style="font-size: clamp(32px, 5vw, 48px); font-weight: 900; color: var(--text-color); letter-spacing: -0.03em; margin-bottom: 24px; text-align: center; line-height: 1.1;">
                            Rp 0
                        </div>

                        <div style="width: 100%; display: grid; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; padding-bottom: 12px; border-bottom: 1px dashed var(--border);">
                                <span style="color: var(--text-muted);">Komisi Level 1 (25%)</span>
                                <strong id="calc-res-l1" style="color: var(--primary);">Rp 0</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
                                <span style="color: var(--text-muted);">Komisi Level 2 (5%)</span>
                                <strong id="calc-res-l2" style="color: var(--accent);">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const elLevel1 = document.getElementById('calc-level1');
    const elLevel2 = document.getElementById('calc-level2');
    const elPrice = document.getElementById('calc-price');
    
    const valL1 = document.getElementById('val-level1');
    const valL2 = document.getElementById('val-level2');
    
    const resL1 = document.getElementById('calc-res-l1');
    const resL2 = document.getElementById('calc-res-l2');
    const resTotal = document.getElementById('calc-total');

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(number);
    }

    function calculate() {
        const l1 = parseInt(elLevel1.value) || 0;
        const l2 = parseInt(elLevel2.value) || 0;
        const price = parseInt(elPrice.value) || 0;

        valL1.textContent = l1;
        valL2.textContent = l2;

        const incomeL1 = l1 * price * 0.25;
        const incomeL2 = l2 * price * 0.05;
        const total = incomeL1 + incomeL2;

        resL1.textContent = formatRupiah(incomeL1);
        resL2.textContent = formatRupiah(incomeL2);
        resTotal.textContent = formatRupiah(total);
    }

    elLevel1.addEventListener('input', calculate);
    elLevel2.addEventListener('input', calculate);
    elPrice.addEventListener('change', calculate);

    // Initial calculation
    calculate();
});
</script>

{{-- Why Join Affiliate --}}
<section class="lp-section">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">KEUNTUNGAN PARTNER</span>
            <h2 class="lp-heading">Mengapa Bergabung dengan <span class="gradient-text">COOCA.ID Affiliate?</span></h2>
        </div>

        <div class="why-grid">
            @php
            $benefits = [
                ['<i class="fa-solid fa-sack-dollar"></i>','Komisi Tinggi','Dapatkan komisi hingga 30% dari transaksi langganan awal dan recurring komisi.'],
                ['<i class="fa-solid fa-chart-line"></i>','Dashboard Transparan','Pantau klik, pendaftaran, dan komisi Anda secara realtime melalui dashboard partner.'],
                ['<i class="fa-solid fa-bullseye"></i>','Materi Pemasaran Siap Pakai','Kami sediakan banner, template pesan WhatsApp, dan brosur digital.'],
                ['<i class="fa-solid fa-credit-card"></i>','Pencairan Mudah','Proses penarikan komisi yang mudah dan cepat langsung ke rekening bank lokal Anda.'],
                ['<i class="fa-solid fa-handshake"></i>','Tim Support Khusus','Setiap partner mendapat akses langsung ke Dedicated Affiliate Manager.'],
                ['<i class="fa-solid fa-rocket"></i>','Produk Mudah Dijual','Software ERP yang sangat dibutuhkan bisnis Indonesia dengan rasio konversi tinggi.'],
            ];
            @endphp
            @foreach($benefits as $i => $b)
            <div class="why-card reveal reveal-delay-{{ ($i % 3) + 1 }}">
                <div class="why-icon clay-icon" style="margin-bottom: 20px;">{!! $b[0] !!}</div>
                <h3 class="why-title">{{ $b[1] }}</h3>
                <p class="why-desc">{{ $b[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- How it Works --}}
<section class="lp-section section-bg-alt">
    <div class="lp-container">
        <div class="lp-section-header reveal">
            <span class="lp-eyebrow">CARA KERJA</span>
            <h2 class="lp-heading">3 Langkah Menjadi <span class="gradient-text">Partner Sukses</span></h2>
        </div>

        <div class="how-grid">
            <div class="how-step reveal reveal-delay-1">
                <div class="how-step-num"><span>01</span></div>
                <h3 class="how-step-title">Daftar Akun Partner</h3>
                <p class="how-step-desc">Isi formulir pendaftaran gratis dalam 1 menit dan langsung dapatkan kode referral unik Anda.</p>
            </div>
            <div class="how-step reveal reveal-delay-2">
                <div class="how-step-num" style="background: linear-gradient(135deg, #7C3AED, var(--accent));"><span>02</span></div>
                <h3 class="how-step-title">Bagikan Link Referral</h3>
                <p class="how-step-desc">Sebarkan link unik Anda ke pemilik toko, klinik, restoran, atau jaringan profesional Anda.</p>
            </div>
            <div class="how-step reveal reveal-delay-3">
                <div class="how-step-num" style="background: linear-gradient(135deg, var(--accent), #06B6D4);"><span>03</span></div>
                <h3 class="how-step-title">Terima Komisi</h3>
                <p class="how-step-desc">Dapatkan komisi untuk setiap pendaftaran dan perpanjangan paket dari referral Anda.</p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 56px;" class="reveal">
            <a href="{{ route('affiliator.register') }}" class="btn-primary-glow btn-hero">
                <i class="fa-solid fa-rocket"></i> Daftar Partner Sekarang
            </a>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="lp-section--sm">
    <div class="lp-container">
        <div class="cta-section">
            <div class="cta-glow"></div>
            <h2 class="cta-title">Siap Menambah Penghasilan Pasif?</h2>
            <p class="cta-desc">Bergabunglah bersama ratusan partner yang telah menghasilkan pendapatan rutin bersama COOCA.ID.</p>
            <div class="cta-actions">
                <a href="{{ route('affiliator.register') }}" class="btn-white">Daftar Sekarang</a>
                <a href="{{ route('affiliator.login') }}" class="btn-white-outline">Login Partner</a>
            </div>
        </div>
    </div>
</section>

@endsection
