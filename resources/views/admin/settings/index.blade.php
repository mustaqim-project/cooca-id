@extends('layouts.admin')

@section('title', 'System Settings — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Settings</span>
        </div>
        <h1 class="page-title">System Settings & Configuration</h1>
        <p class="page-subtitle">Platform branding, Light/Dark mode logos, support contacts, affiliate commission defaults, and SEO.</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="tabs">
        <div class="tab-item active" onclick="switchTab('general', this)">General Branding</div>
        <div class="tab-item" onclick="switchTab('payment', this)">Bank & Manual Transfer</div>
        <div class="tab-item" onclick="switchTab('contact', this)">Contact & Social</div>
        <div class="tab-item" onclick="switchTab('affiliate', this)">Affiliate Rules</div>
        <div class="tab-item" onclick="switchTab('seo', this)">Global SEO</div>
    </div>

    {{-- GENERAL TAB --}}
    <div id="tab-general" class="tab-content">
        <div class="card mb-6">
            <div class="card-header">
                <div class="card-title">Branding & Platform Logos</div>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Platform Name</label>
                        <input type="text" name="platform_name" class="form-input" value="{{ $settings['platform_name'] ?? 'COOCA.ID' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Preloader Text</label>
                        <input type="text" name="preloader_text" class="form-input" value="{{ $settings['preloader_text'] ?? 'COOCA' }}">
                    </div>
                </div>

                <div class="grid-2 mt-4">
                    <div class="form-group">
                        <label class="form-label">Light Theme Logo</label>
                        <input type="file" name="logo_light" class="form-input">
                        @if(!empty($settings['logo_light_url']))
                            <div class="mt-2 text-xs text-muted">Current: <code>{{ $settings['logo_light_url'] }}</code></div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dark Theme Logo</label>
                        <input type="file" name="logo_dark" class="form-input">
                        @if(!empty($settings['logo_dark_url']))
                            <div class="mt-2 text-xs text-muted">Current: <code>{{ $settings['logo_dark_url'] }}</code></div>
                        @endif
                    </div>
                </div>

                <div class="grid-2 mt-4">
                    <div class="form-group">
                        <label class="form-label">Favicon (32x32 ICO / PNG)</label>
                        <input type="file" name="favicon" class="form-input">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAYMENT & BANK TRANSFER TAB --}}
    <div id="tab-payment" class="tab-content" style="display: none;">
        {{-- Card CMS Rekening Bank --}}
        <div class="card mb-6" style="border:1px solid var(--border);">
            <div class="card-header flex justify-between items-center" style="background:var(--bg);">
                <div>
                    <div class="card-title font-bold text-base"><i class="fa-solid fa-building-columns" style="color:var(--primary);margin-right:8px;"></i> Daftar Rekening Bank Perusahaan (CMS)</div>
                    <div class="text-xs text-muted mt-1">Daftar rekening bank yang tampil secara otomatis di halaman checkout dan invoice pelanggan.</div>
                </div>
                <div>
                    <a href="{{ route('admin.bank-accounts.index') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-gear"></i> Buka CMS Rekening Lengkap
                    </a>
                </div>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="data-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Atas Nama</th>
                                <th>QRIS / Barcode</th>
                                <th>Status</th>
                                <th>Utama</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bankAccounts as $bAcc)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            @if($bAcc->logo_url)
                                                <img src="{{ $bAcc->logo_url }}" alt="{{ $bAcc->bank_name }}" style="height:22px;max-width:50px;object-fit:contain;">
                                            @else
                                                <span class="badge badge-primary">{{ $bAcc->bank_name }}</span>
                                            @endif
                                            <span class="font-bold text-xs">{{ $bAcc->bank_name }}</span>
                                        </div>
                                    </td>
                                    <td><code class="font-bold text-primary">{{ $bAcc->account_number }}</code></td>
                                    <td class="text-xs">{{ $bAcc->account_holder }}</td>
                                    <td>
                                        @if($bAcc->qr_code_url)
                                            <span class="badge badge-success" style="font-size:10px;">Ada QRIS</span>
                                        @else
                                            <span class="text-xs text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $bAcc->is_active ? 'badge-success' : 'badge-muted' }}" style="font-size:10px;">
                                            {{ $bAcc->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bAcc->is_primary)
                                            <span class="badge badge-warning" style="font-size:10px;"><i class="fa-solid fa-star"></i> Utama</span>
                                        @else
                                            <span class="text-xs text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding:20px;">
                                        Belum ada rekening di database CMS. <a href="{{ route('admin.bank-accounts.index') }}" class="text-primary font-bold">Klik di sini untuk menambah rekening</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-header">
                <div class="card-title">Pengaturan Umum Transfer Bank Manual</div>
            </div>
            <div class="card-body">
                <div class="form-group mb-4" style="background:var(--bg);padding:16px;border-radius:var(--radius);border:1px solid var(--border);">
                    <label class="flex items-center gap-2 cursor-pointer" style="display:flex;align-items:center;gap:10px;">
                        <input type="checkbox" name="bank_transfer_active" value="1" {{ !empty($settings['bank_transfer_active']) ? 'checked' : '' }}>
                        <span class="font-bold" style="font-size:14px;">🟢 Aktifkan Pilihan Transfer Bank Manual</span>
                    </label>
                    <div class="text-xs text-muted" style="margin-top:6px;margin-left:25px;">Jika aktif, pelanggan dapat memilih pembayaran via transfer manual ke rekening perusahaan selain menggunakan payment gateway Midtrans dan diwajibkan mengunggah bukti transfer.</div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Bank Utama (Fallback)</label>
                        <input type="text" name="bank_transfer_bank_name" class="form-input" placeholder="Contoh: Bank Central Asia (BCA)" value="{{ $settings['bank_transfer_bank_name'] ?? 'Bank Central Asia (BCA)' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Rekening Utama (Fallback)</label>
                        <input type="text" name="bank_transfer_account_number" class="form-input font-mono" placeholder="Contoh: 8830-8899-8800" value="{{ $settings['bank_transfer_account_number'] ?? '8830-8899-8800' }}">
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label">Nama Pemilik Rekening (Atas Nama)</label>
                    <input type="text" name="bank_transfer_account_name" class="form-input" placeholder="Contoh: PT COOCA TECHNOLOGIES INDONESIA" value="{{ $settings['bank_transfer_account_name'] ?? 'PT COOCA TECHNOLOGIES INDONESIA' }}">
                </div>

                <div class="form-group mt-4">
                    <label class="form-label">Petunjuk / Instruksi Pembayaran Transfer Global</label>
                    <textarea name="bank_transfer_instructions" class="form-textarea" rows="3" placeholder="Petunjuk langkah transfer untuk pelanggan...">{{ $settings['bank_transfer_instructions'] ?? 'Silakan transfer sesuai nominal tagihan hingga digit terakhir. Setelah transfer, wajib upload bukti pembayaran agar dapat diverifikasi.' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTACT TAB --}}
    <div id="tab-contact" class="tab-content" style="display: none;">
        <div class="card mb-6">
            <div class="card-header">
                <div class="card-title">Support Channels & Floating WhatsApp</div>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Support Email</label>
                        <input type="email" name="email_support" class="form-input" value="{{ $settings['email_support'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp Number (e.g. 6281234567890)</label>
                        <input type="text" name="whatsapp_number" class="form-input" value="{{ $settings['whatsapp_number'] ?? '' }}">
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label">WhatsApp Direct Link</label>
                    <input type="text" name="whatsapp_link" class="form-input" value="{{ $settings['whatsapp_link'] ?? '' }}">
                </div>

                <div class="form-group mt-4" style="background:var(--bg);padding:15px;border-radius:var(--radius);border:1px solid var(--border);">
                    <label class="flex items-center gap-2 cursor-pointer" style="display:flex;align-items:center;gap:10px;">
                        <input type="checkbox" name="whatsapp_notifications_active" value="1" {{ !empty($settings['whatsapp_notifications_active']) ? 'checked' : '' }}>
                        <span class="font-bold" style="font-size:14px;">🟢 Aktifkan Notifikasi WhatsApp Secara Global</span>
                    </label>
                    <div class="text-xs text-muted" style="margin-top:5px;margin-left:25px;">Jika dinonaktifkan, pengiriman pesan WhatsApp untuk pendaftaran, status trial, subscription, invoice, dll. akan ditangguhkan secara global.</div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label">Office Address</label>
                    <textarea name="contact_address" class="form-textarea" rows="3">{{ $settings['contact_address'] ?? '' }}</textarea>
                </div>

                <div class="section-divider"></div>

                <div class="card-title mb-4">Social Media Links</div>
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label">Instagram</label>
                        <input type="text" name="social_instagram" class="form-input" value="{{ $settings['social_instagram'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="social_facebook" class="form-input" value="{{ $settings['social_facebook'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">YouTube</label>
                        <input type="text" name="social_youtube" class="form-input" value="{{ $settings['social_youtube'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AFFILIATE TAB --}}
    <div id="tab-affiliate" class="tab-content" style="display: none;">
        <div class="card mb-6">
            <div class="card-header">
                <div class="card-title">Commission Rates & Payout Rules</div>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Level 1 Commission Rate (%)</label>
                        <input type="number" step="0.1" name="affiliate_commission_l1" class="form-input" value="{{ $settings['affiliate_commission_l1'] ?? 25 }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level 2 Commission Rate (%)</label>
                        <input type="number" step="0.1" name="affiliate_commission_l2" class="form-input" value="{{ $settings['affiliate_commission_l2'] ?? 5 }}">
                    </div>
                </div>

                <div class="grid-2 mt-4">
                    <div class="form-group">
                        <label class="form-label">Minimum Withdrawal Amount (IDR)</label>
                        <input type="number" name="minimum_withdrawal" class="form-input" value="{{ $settings['minimum_withdrawal'] ?? 50000 }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bank Payout Admin Fee (IDR)</label>
                        <input type="number" name="withdrawal_fee_bank" class="form-input" value="{{ $settings['withdrawal_fee_bank'] ?? 2500 }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEO TAB --}}
    <div id="tab-seo" class="tab-content" style="display: none;">
        <div class="card mb-6">
            <div class="card-header">
                <div class="card-title">Search Engine Optimization</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="google_no_follow" value="1" {{ !empty($settings['google_no_follow']) ? 'checked' : '' }}>
                        <span class="font-bold">Discourage search engines from indexing this site (NoIndex)</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
        <button type="submit" class="btn btn-primary btn-lg">
            <span>💾</span> Save All Settings
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
function switchTab(name, el) {
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).style.display = 'block';
    el.classList.add('active');
}
</script>
@endpush
