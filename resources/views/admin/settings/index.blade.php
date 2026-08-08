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
