<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 10px;
        color: #1e293b;
        background: #fff;
    }
    .page { padding: 40px 50px; }

    /* ── HEADER ───────────────────────────────── */
    .header {
        border-bottom: 3px solid #2563eb;
        padding-bottom: 20px;
        margin-bottom: 24px;
    }
    .header-table { width: 100%; }
    .logo-cell { width: 60%; }
    .logo-text {
        font-size: 24px;
        font-weight: 700;
        color: #2563eb;
        letter-spacing: -1px;
    }
    .logo-sub { font-size: 9px; color: #64748b; margin-top: 2px; }
    .contract-no-cell { width: 40%; text-align: right; }
    .contract-no-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
    .contract-no-value { font-size: 13px; font-weight: 700; color: #1e293b; }
    .contract-date { font-size: 9px; color: #64748b; margin-top: 3px; }

    /* ── TITLE BLOCK ──────────────────────────── */
    .title-block {
        text-align: center;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .title-id { font-size: 14px; font-weight: 700; color: #1e3a8a; }
    .title-en { font-size: 11px; color: #2563eb; margin-top: 4px; }
    .title-type { font-size: 9px; color: #64748b; margin-top: 6px; font-style: italic; }

    /* ── PARTIES ──────────────────────────────── */
    .parties-section { margin-bottom: 20px; }
    .section-heading {
        font-size: 10px;
        font-weight: 700;
        color: #2563eb;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 4px;
        margin-bottom: 10px;
    }
    .parties-table { width: 100%; border-collapse: collapse; }
    .party-box {
        width: 48%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 10px 12px;
        vertical-align: top;
    }
    .party-spacer { width: 4%; }
    .party-role { font-size: 8px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
    .party-name { font-size: 11px; font-weight: 700; color: #1e293b; margin-top: 3px; }
    .party-detail { font-size: 9px; color: #475569; margin-top: 2px; line-height: 1.5; }

    /* ── PRODUCT INFO ─────────────────────────── */
    .product-section { margin-bottom: 20px; }
    .product-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .product-table th {
        background: #1e40af;
        color: #fff;
        font-size: 9px;
        font-weight: 600;
        padding: 7px 10px;
        text-align: left;
    }
    .product-table td {
        padding: 7px 10px;
        font-size: 9px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }
    .product-table tr:nth-child(even) td { background: #f8fafc; }

    /* ── DUAL LANGUAGE ARTICLES ───────────────── */
    .articles-section { margin-bottom: 20px; }
    .lang-columns-table { width: 100%; border-collapse: collapse; }
    .lang-header-id {
        width: 50%;
        background: #1e40af;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        padding: 6px 10px;
        text-align: center;
        border-radius: 4px 0 0 0;
    }
    .lang-header-en {
        width: 50%;
        background: #0369a1;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        padding: 6px 10px;
        text-align: center;
        border-radius: 0 4px 0 0;
    }
    .article-row-id { width: 50%; vertical-align: top; padding: 8px 10px; border: 1px solid #e2e8f0; border-right: none; }
    .article-row-en { width: 50%; vertical-align: top; padding: 8px 10px; border: 1px solid #e2e8f0; }
    .article-title { font-size: 9px; font-weight: 700; color: #1e40af; margin-bottom: 4px; }
    .article-body { font-size: 8.5px; color: #334155; line-height: 1.6; }
    .article-list { margin: 3px 0 0 12px; }
    .article-list li { margin-bottom: 2px; }

    /* ── SIGNATURES ───────────────────────────── */
    .signatures-section { margin-top: 24px; border-top: 2px solid #e2e8f0; padding-top: 20px; }
    .sig-table { width: 100%; }
    .sig-box {
        width: 45%;
        text-align: center;
        padding: 0 10px;
        vertical-align: top;
    }
    .sig-spacer { width: 10%; }
    .sig-role { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #64748b; }
    .sig-representing { font-size: 9px; color: #1e293b; margin: 3px 0 10px; }
    .sig-img-area {
        border: 1px solid #bfdbfe;
        border-radius: 4px;
        background: #f0f9ff;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 8px 0;
        overflow: hidden;
    }
    .sig-img { max-height: 55px; max-width: 160px; }
    .sig-cursive { font-size: 22px; color: #1e40af; font-style: italic; line-height: 60px; }
    .sig-name { font-size: 10px; font-weight: 700; color: #1e293b; margin-top: 6px; border-top: 1px solid #1e40af; padding-top: 4px; }
    .sig-title { font-size: 8px; color: #64748b; }

    /* ── FOOTER ───────────────────────────────── */
    .footer {
        margin-top: 24px;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
        text-align: center;
        font-size: 7.5px;
        color: #94a3b8;
    }
    .seal-box {
        display: inline-block;
        border: 2px solid #2563eb;
        border-radius: 50%;
        width: 70px;
        height: 70px;
        line-height: 1.3;
        padding: 12px 5px;
        font-size: 7px;
        font-weight: 700;
        color: #2563eb;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 8px auto;
    }
    .watermark {
        position: fixed;
        top: 38%;
        left: 15%;
        opacity: 0.04;
        font-size: 90px;
        font-weight: 900;
        color: #2563eb;
        transform: rotate(-35deg);
        pointer-events: none;
    }
</style>
</head>
<body>
<div class="watermark">COOCA.ID</div>

<div class="page">

    {{-- ── HEADER ─────────────────────────────────── --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div class="logo-text">COOCA.id</div>
                    <div class="logo-sub">PT Cooca Digital Indonesia &bull; cooca.id</div>
                </td>
                <td class="contract-no-cell">
                    <div class="contract-no-label">No. Kontrak / Contract No.</div>
                    <div class="contract-no-value">{{ $contract->contract_number }}</div>
                    <div class="contract-date">
                        Tanggal / Date: {{ now()->translatedFormat('d F Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── TITLE ──────────────────────────────────── --}}
    <div class="title-block">
        <div class="title-id">PERJANJIAN KERJASAMA LISENSI PERANGKAT LUNAK</div>
        <div class="title-en">SOFTWARE LICENSE COOPERATION AGREEMENT</div>
        <div class="title-type">
            Dokumen Resmi yang Mengikat Secara Hukum &bull; Legally Binding Official Document
        </div>
    </div>

    {{-- ── PARTIES ─────────────────────────────────── --}}
    <div class="parties-section">
        <div class="section-heading">Para Pihak / Contracting Parties</div>
        <table class="parties-table">
            <tr>
                <td class="party-box">
                    <div class="party-role">Pihak Pertama / First Party</div>
                    <div class="party-name">PT Cooca Digital Indonesia</div>
                    <div class="party-detail">
                        Nama Merek / Brand: <strong>COOCA.id</strong><br>
                        Website: cooca.id<br>
                        Peran: Penyedia Lisensi Software / License Provider
                    </div>
                </td>
                <td class="party-spacer"></td>
                <td class="party-box">
                    <div class="party-role">Pihak Kedua / Second Party</div>
                    <div class="party-name">{{ $customer->name }}</div>
                    <div class="party-detail">
                        Email: {{ $customer->email }}<br>
                        @if($customer->phone)Phone: {{ $customer->phone }}<br>@endif
                        @if($customer->company_name)Perusahaan: {{ $customer->company_name }}<br>@endif
                        Peran: Pemegang Lisensi / License Holder
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── PRODUCT INFO ────────────────────────────── --}}
    <div class="product-section">
        <div class="section-heading">Detail Produk & Lisensi / Product & License Details</div>
        <table class="product-table">
            <tr>
                <th>Keterangan / Item</th>
                <th>Detail</th>
            </tr>
            <tr>
                <td>Nama Produk / Product Name</td>
                <td><strong>{{ $license->product->name }}</strong></td>
            </tr>
            <tr>
                <td>Kode Lisensi / License Code</td>
                <td><strong>{{ $license->license_code }}</strong></td>
            </tr>
            <tr>
                <td>Paket Berlangganan / Subscription Plan</td>
                <td>{{ $license->subscriptionPlan->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Domain Terdaftar / Registered Domain</td>
                <td>{{ $license->domain }}</td>
            </tr>
            <tr>
                <td>Berlaku Mulai / Valid From</td>
                <td>{{ $license->starts_at ? $license->starts_at->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Berakhir / Expires</td>
                <td>{{ $license->expires_at ? $license->expires_at->format('d F Y') : 'Lifetime (Seumur Hidup)' }}</td>
            </tr>
            <tr>
                <td>Status Lisensi / License Status</td>
                <td><strong>{{ strtoupper($license->status) }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- ── BILINGUAL ARTICLES ──────────────────────── --}}
    <div class="articles-section">
        <div class="section-heading">Ketentuan Perjanjian / Agreement Terms</div>
        <table class="lang-columns-table">
            <tr>
                <td class="lang-header-id">&#127470;&#127465; Bahasa Indonesia</td>
                <td class="lang-header-en">&#127468;&#127463; English</td>
            </tr>

            {{-- Pasal 1 --}}
            <tr>
                <td class="article-row-id">
                    <div class="article-title">Pasal 1 — Definisi</div>
                    <div class="article-body">
                        Dalam perjanjian ini, <strong>"Lisensi"</strong> berarti hak terbatas, non-eksklusif, dan tidak dapat dipindahtangankan untuk menggunakan Perangkat Lunak COOCA sesuai paket yang dipilih.<br><br>
                        <strong>"Perangkat Lunak"</strong> berarti sistem {{ $license->product->name }} beserta seluruh modul, pembaruan, dan dokumentasinya.
                    </div>
                </td>
                <td class="article-row-en">
                    <div class="article-title">Article 1 — Definitions</div>
                    <div class="article-body">
                        In this agreement, <strong>"License"</strong> means the limited, non-exclusive, non-transferable right to use the COOCA Software under the selected plan.<br><br>
                        <strong>"Software"</strong> means the {{ $license->product->name }} system including all modules, updates, and documentation.
                    </div>
                </td>
            </tr>

            {{-- Pasal 2 --}}
            <tr>
                <td class="article-row-id">
                    <div class="article-title">Pasal 2 — Pemberian Lisensi</div>
                    <div class="article-body">
                        Pihak Pertama memberikan kepada Pihak Kedua lisensi penggunaan Perangkat Lunak yang terikat pada domain <strong>{{ $license->domain }}</strong>. Lisensi ini bersifat eksklusif untuk satu domain dan tidak dapat digunakan pada infrastruktur pihak lain.
                    </div>
                </td>
                <td class="article-row-en">
                    <div class="article-title">Article 2 — License Grant</div>
                    <div class="article-body">
                        First Party grants to Second Party a license to use the Software bound to domain <strong>{{ $license->domain }}</strong>. This license is exclusive to one domain and cannot be deployed on third-party infrastructure.
                    </div>
                </td>
            </tr>

            {{-- Pasal 3 --}}
            <tr>
                <td class="article-row-id">
                    <div class="article-title">Pasal 3 — Larangan</div>
                    <div class="article-body">
                        Pihak Kedua dilarang:
                        <ul class="article-list">
                            <li>Mendistribusikan atau menjual kembali Perangkat Lunak</li>
                            <li>Merekayasa balik (reverse engineer) kode sumber</li>
                            <li>Menghapus atribusi atau merek COOCA</li>
                            <li>Menggunakan Perangkat Lunak di luar domain yang terdaftar</li>
                        </ul>
                    </div>
                </td>
                <td class="article-row-en">
                    <div class="article-title">Article 3 — Restrictions</div>
                    <div class="article-body">
                        Second Party shall not:
                        <ul class="article-list">
                            <li>Distribute or resell the Software</li>
                            <li>Reverse engineer the source code</li>
                            <li>Remove COOCA attribution or branding</li>
                            <li>Use the Software outside the registered domain</li>
                        </ul>
                    </div>
                </td>
            </tr>

            {{-- Pasal 4 --}}
            <tr>
                <td class="article-row-id">
                    <div class="article-title">Pasal 4 — Dukungan & Pembaruan</div>
                    <div class="article-body">
                        Pihak Pertama menyediakan dukungan teknis dan pembaruan sistem sesuai dengan paket yang dipilih. Pembaruan keamanan kritis akan diberikan tanpa biaya tambahan selama masa lisensi aktif.
                    </div>
                </td>
                <td class="article-row-en">
                    <div class="article-title">Article 4 — Support & Updates</div>
                    <div class="article-body">
                        First Party provides technical support and system updates according to the selected plan. Critical security updates are provided at no additional cost during the active license period.
                    </div>
                </td>
            </tr>

            {{-- Pasal 5 --}}
            <tr>
                <td class="article-row-id">
                    <div class="article-title">Pasal 5 — Kerahasiaan Data</div>
                    <div class="article-body">
                        Seluruh data bisnis Pihak Kedua berjalan pada infrastruktur yang terisolasi dan didedikasikan. Pihak Pertama tidak akan mengakses, membagikan, atau menggunakan data Pihak Kedua untuk keperluan apapun tanpa persetujuan tertulis.
                    </div>
                </td>
                <td class="article-row-en">
                    <div class="article-title">Article 5 — Data Confidentiality</div>
                    <div class="article-body">
                        All business data of the Second Party runs on isolated, dedicated infrastructure. First Party shall not access, share, or use Second Party's data for any purpose without written consent.
                    </div>
                </td>
            </tr>

            {{-- Pasal 6 --}}
            <tr>
                <td class="article-row-id">
                    <div class="article-title">Pasal 6 — Penyelesaian Sengketa</div>
                    <div class="article-body">
                        Sengketa yang timbul dari perjanjian ini diselesaikan secara musyawarah. Apabila tidak tercapai kesepakatan, para pihak sepakat untuk menyelesaikan melalui Pengadilan Negeri yang berwenang di wilayah hukum Indonesia.
                    </div>
                </td>
                <td class="article-row-en">
                    <div class="article-title">Article 6 — Dispute Resolution</div>
                    <div class="article-body">
                        Disputes arising from this agreement shall be resolved through deliberation. If no agreement is reached, the parties agree to resolve through the competent District Court under Indonesian jurisdiction.
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── SIGNATURES ───────────────────────────────── --}}
    <div class="signatures-section">
        <div class="section-heading">Tanda Tangan Elektronik / Electronic Signatures</div>
        <table class="sig-table">
            <tr>
                <td class="sig-box">
                    <div class="sig-role">Pihak Pertama / First Party</div>
                    <div class="sig-representing">PT Cooca Digital Indonesia</div>
                    <div class="sig-img-area">
                        @if($coocaSignaturePath && file_exists(storage_path('app/public/' . $coocaSignaturePath)))
                            <img src="{{ storage_path('app/public/' . $coocaSignaturePath) }}" class="sig-img"/>
                        @else
                            <span class="sig-cursive">Cooca.id</span>
                        @endif
                    </div>
                    <div class="sig-name">Direktur Utama / CEO</div>
                    <div class="sig-title">PT Cooca Digital Indonesia</div>
                    <div style="font-size:7px; color:#94a3b8; margin-top:4px;">
                        Ditandatangani secara elektronik / Electronically signed<br>
                        {{ now()->format('d M Y, H:i') }} WIB
                    </div>
                </td>
                <td class="sig-spacer"></td>
                <td class="sig-box">
                    <div class="sig-role">Pihak Kedua / Second Party</div>
                    <div class="sig-representing">{{ $customer->name }}</div>
                    <div class="sig-img-area">
                        @if($contract->customer_signature_data)
                            <img src="{{ $contract->customer_signature_data }}" class="sig-img"/>
                        @else
                            <span class="sig-cursive">{{ $customer->name }}</span>
                        @endif
                    </div>
                    <div class="sig-name">{{ $customer->name }}</div>
                    <div class="sig-title">{{ $customer->company_name ?? 'Pemegang Lisensi / License Holder' }}</div>
                    <div style="font-size:7px; color:#94a3b8; margin-top:4px;">
                        Ditandatangani secara elektronik / Electronically signed<br>
                        {{ $contract->signed_at ? $contract->signed_at->format('d M Y, H:i') . ' WIB' : '-' }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- Seal --}}
        <div style="text-align:center; margin-top:16px;">
            <div class="seal-box">
                COOCA<br>DIGITAL<br>&#9679;<br>RESMI<br>OFFICIAL
            </div>
            <div style="font-size:7.5px; color:#94a3b8; margin-top:6px;">
                Dokumen ini ditandatangani secara elektronik dan memiliki kekuatan hukum yang sama dengan tanda tangan basah.<br>
                This document is electronically signed and has the same legal force as a wet signature.
            </div>
        </div>
    </div>

    {{-- ── FOOTER ───────────────────────────────────── --}}
    <div class="footer">
        <strong>COOCA.id</strong> &mdash; PT Cooca Digital Indonesia &mdash; cooca.id<br>
        Kontrak No. {{ $contract->contract_number }} &bull; Diterbitkan: {{ now()->format('d/m/Y') }}<br>
        Dokumen ini dibuat secara otomatis oleh sistem COOCA dan sah secara hukum sesuai UU ITE Indonesia.
    </div>

</div>
</body>
</html>
