@extends('layouts.public')

@section('title', 'Konsultasi & Schedule Demo Gratis | COOCA.ID')
@section('description', 'Siap untuk Move Faster & Decide Better? Hubungi tim COOCA.ID dan jadwalkan demo gratis untuk transformasi operasional bisnis Anda hari ini.')
@section('keywords', 'kontak cooca id, jadwal demo software cooca, konsultasi software bisnis, customer support cooca, alamat kantor cooca')

@section('content')

{{-- Page Hero --}}
<section class="aurora-bg page-hero">
    <div class="lp-container">
        <div style="text-align: center; max-width: 640px; margin: 0 auto;">
            <span class="lp-eyebrow">HUBUNGI KAMI</span>
            <h1 class="lp-heading reveal" style="font-size: clamp(40px,5vw,60px); margin-bottom: 16px;">
                Ada yang Bisa <span class="gradient-text">Kami Bantu?</span>
            </h1>
            <p class="lp-subheading reveal" style="margin: 0 auto;">Tim kami siap merespons dalam 1 jam kerja. Tidak ada pertanyaan yang terlalu kecil.</p>
        </div>
    </div>
</section>

{{-- Contact Grid --}}
<section class="lp-section">
    <div class="lp-container">
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 64px; align-items: start;">

            {{-- Left: Info --}}
            <div>
                <h2 class="lp-heading reveal" style="font-size: 32px; margin-bottom: 32px;">Informasi Kontak</h2>

                @php
                $contacts = [
                    ['<i class="fa-solid fa-location-dot"></i>','Alamat Kantor','Jl. Jend. Sudirman No. 52, Jakarta Selatan, DKI Jakarta 12920'],
                    ['<i class="fa-solid fa-mobile-screen"></i>','WhatsApp Business','+62 821-3456-6667'],
                    ['<i class="fa-solid fa-envelope"></i>','Email Support','support@cooca.id'],
                    ['<i class="fa-regular fa-clock"></i>','Jam Operasional','Senin–Jumat: 08.00–20.00 WIB | Sabtu: 09.00–15.00 WIB'],
                    ['<i class="fa-solid fa-headset"></i>','Support Darurat','24/7 untuk pelanggan Pro & Enterprise'],
                ];
                @endphp

                <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 40px;">
                    @foreach($contacts as $i => $c)
                    <div class="reveal reveal-delay-{{ $i+1 }}" style="display: flex; gap: 16px; align-items: flex-start; padding: 20px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; transition: all .2s ease;">
                        <div class="clay-icon-sm" style="flex-shrink: 0; margin-top: 4px;">{!! $c[0] !!}</div>
                        <div>
                            <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px;">{{ $c[1] }}</div>
                            <div style="font-size: 15px; color: var(--text); font-weight: 500; line-height: 1.5;">{{ $c[2] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Social Links --}}
                <div class="reveal">
                    <p style="font-size: 13px; font-weight: 700; color: var(--text-muted); margin-bottom: 16px; text-transform: uppercase; letter-spacing: .06em;">Ikuti Kami</p>
                    <div style="display: flex; gap: 12px;">
                        @foreach(['Instagram','Facebook','Twitter/X','YouTube','LinkedIn'] as $social)
                        <a href="#" style="width: 44px; height: 44px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none; font-size: 18px; transition: all .2s;" title="{{ $social }}">
                            @if($social==='Instagram')<i class="fa-brands fa-instagram"></i>@elseif($social==='Facebook')<i class="fa-brands fa-facebook"></i>@elseif($social==='Twitter/X')<i class="fa-brands fa-twitter"></i>@elseif($social==='YouTube')<i class="fa-brands fa-youtube"></i>@else<i class="fa-solid fa-link"></i>@endif
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: Contact Form --}}
            <div class="reveal reveal-delay-2">
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 40px; box-shadow: var(--shadow-lg);">
                    <h3 style="font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 8px;">Kirim Pesan</h3>
                    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 28px;">Isi form di bawah dan tim kami akan menghubungi Anda segera.</p>

                    @if(session('status'))
                    <div style="padding: 14px 18px; background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.3); border-radius: 12px; color: #16a34a; font-size: 14px; font-weight: 600; margin-bottom: 20px;">✅ {{ session('status') }}</div>
                    @endif

                    <form action="{{ route('contact') }}" method="POST" id="contact-form">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="contact-name">Nama Lengkap *</label>
                                <input id="contact-name" type="text" name="name" required placeholder="John Doe"
                                    style="width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:10px;background:var(--bg);color:var(--text);font-size:14px;outline:none;font-family:inherit;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="contact-email">Email *</label>
                                <input id="contact-email" type="email" name="email" required placeholder="nama@perusahaan.com"
                                    style="width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:10px;background:var(--bg);color:var(--text);font-size:14px;outline:none;font-family:inherit;transition:border-color .2s;"
                                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                            </div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="contact-phone">No. WhatsApp</label>
                            <input id="contact-phone" type="tel" name="phone" placeholder="08xx-xxxx-xxxx"
                                style="width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:10px;background:var(--bg);color:var(--text);font-size:14px;outline:none;font-family:inherit;transition:border-color .2s;"
                                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="contact-topic">Topik</label>
                            <select id="contact-topic" name="topic"
                                style="width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:10px;background:var(--bg);color:var(--text);font-size:14px;outline:none;font-family:inherit;appearance:none;cursor:pointer;transition:border-color .2s;"
                                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                                <option value="">Pilih topik...</option>
                                <option>Demo & Presentasi</option>
                                <option>Informasi Harga</option>
                                <option>Support Teknis</option>
                                <option>Kemitraan / Affiliasi</option>
                                <option>Permintaan Fitur</option>
                                <option>Lainnya</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px;" for="contact-message">Pesan *</label>
                            <textarea id="contact-message" name="message" required rows="5" placeholder="Ceritakan kebutuhan bisnis Anda..."
                                style="width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:10px;background:var(--bg);color:var(--text);font-size:14px;outline:none;font-family:inherit;resize:vertical;transition:border-color .2s;line-height:1.6;"
                                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
                        </div>

                        <button type="submit" class="btn-primary-glow" style="width:100%;justify-content:center;padding:14px;font-size:15px;border-radius:12px;" id="contact-submit-btn">
                            📨 Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Quick Actions --}}
<section class="lp-section--sm section-bg-alt">
    <div class="lp-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            @php
            $waNum = preg_replace('/[^0-9]/', '', setting('contact.whatsapp', '6282134566667'));
            $waUrl = setting('contact.whatsapp_link') ?: ('https://wa.me/' . ($waNum ?: '6282134566667'));
            $email = setting('contact.email', 'support@cooca.id');

            $actions = [
                ['<i class="fa-brands fa-whatsapp"></i>','WhatsApp Langsung','Hubungi kami via WhatsApp untuk respons paling cepat.','Chat Sekarang', $waUrl, '#25D366'],
                ['<i class="fa-solid fa-envelope"></i>','Email Support','Kirim detail masalah untuk penanganan lebih terstruktur.','Kirim Email', 'mailto:'.$email, 'var(--primary)'],
                ['<i class="fa-regular fa-calendar-check"></i>','Jadwalkan Demo','Minta demo langsung dengan tim product specialist kami.','Book Demo','#','var(--accent)'],
            ];
            @endphp
            @foreach($actions as $i => $action)
            <div class="reveal reveal-delay-{{ $i+1 }}" style="background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 32px; text-align: center; transition: all .3s ease;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div class="clay-icon" style="margin-bottom: 24px;">{!! $action[0] !!}</div>
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 8px;">{{ $action[1] }}</h3>
                <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.6;">{{ $action[2] }}</p>
                <a href="{{ $action[4] }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 24px;border-radius:10px;background:{{ $action[5] }};color:#fff;text-decoration:none;font-size:14px;font-weight:600;transition:all .2s;" target="{{ $action[4] !== '#' ? '_blank' : '' }}">{{ $action[3] }}</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
