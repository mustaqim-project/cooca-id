@extends('layouts.customer')
@section('title', $product->name . ' — Subscribe')
@section('breadcrumb')
    <a href="{{ route('customer.products.index') }}" class="crumb-link">My Services</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">{{ $product->name }}</span>
@endsection

@section('content')
@php
    $customer   = auth('customer')->user();
    $waNumber   = setting('contact.whatsapp', '6282134566667');
    $isCustom   = in_array($product->product_type, ['custom_dev', 'project', 'maintenance']);
    $waMsg      = urlencode('Halo COOCA.ID, saya ingin info lebih lanjut tentang produk ' . $product->name);

    // Existing active subscription for this product
    $activeSub  = $customer?->subscriptions()
        ->whereHas('subscriptionPlan', fn($q) => $q->where('product_id', $product->id))
        ->where('status', 'active')
        ->with(['subscriptionPlan', 'license'])
        ->first();

    // Existing trial
    $activeTrial = $customer?->erpRequests()
        ->where('product_id', $product->id)
        ->whereIn('status', ['active_trial', 'submitted', 'waiting_approval', 'waiting_setup', 'in_setup', 'testing', 'domain_setup'])
        ->first();

    $features = is_array($product->features) ? $product->features : [];
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title" style="font-size:22px;">
            @if($product->thumbnail)
                <img src="{{ $product->thumbnail_url }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;vertical-align:middle;margin-right:10px;border:1px solid var(--border);">
            @endif
            {{ $product->name }}
        </h1>
        <p class="page-subtitle">{{ $product->short_description ?? $product->category?->name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('customer.products.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-4"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
@endif

{{-- ── ACTIVE SUBSCRIPTION NOTICE ── --}}
@if($activeSub)
<div class="alert alert-success mb-4" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <i class="fa-solid fa-circle-check"></i>
        <strong>Langganan Aktif</strong> — {{ $activeSub->subscriptionPlan?->name }}
        @if($activeSub->expires_at) · Berakhir {{ $activeSub->expires_at->format('d M Y') }} @endif
    </div>
    <div class="flex gap-2">
        @if($activeSub->license?->domain)
        <a href="https://{{ $activeSub->license->domain }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-rocket"></i> Launch
        </a>
        @endif
        <a href="{{ route('customer.subscriptions.checkout', $activeSub->id) }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-rotate"></i> Perpanjang
        </a>
    </div>
</div>
@endif

{{-- ── ACTIVE TRIAL NOTICE ── --}}
@if($activeTrial && !$activeSub)
<div class="alert alert-warning mb-4" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div>
        <i class="fa-solid fa-flask"></i>
        <strong>Trial Aktif</strong>
        @if($activeTrial->expires_at) · Berakhir {{ \Carbon\Carbon::parse($activeTrial->expires_at)->format('d M Y') }} @endif
    </div>
    @if($activeTrial->domain)
    <a href="https://{{ $activeTrial->domain }}" target="_blank" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-rocket"></i> Buka Trial
    </a>
    @endif
</div>
@endif

<div class="grid-31" style="align-items:start;">

    {{-- ── LEFT: Description + Plans ── --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- About --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tentang {{ $product->name }}</div>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted" style="line-height:1.8;">
                    {{ $product->description ?? 'Modul enterprise COOCA.ID dirancang untuk kebutuhan bisnis modern yang scalable.' }}
                </p>
                @if(count($features) > 0)
                <div class="divider mt-3 mb-3"></div>
                <div class="grid-2">
                    @foreach($features as $feature)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-check-circle" style="color:var(--success);font-size:13px;flex-shrink:0;"></i>
                        <span>{{ is_array($feature) ? ($feature['title'] ?? $feature['name'] ?? $feature[0] ?? '') : $feature }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Plan Selector / Subscribe Form --}}
        @if($isCustom)
        {{-- CUSTOM PRODUCT → WhatsApp CTA --}}
        <div class="card" style="border:2px solid #25D366;">
            <div class="card-header" style="background:linear-gradient(135deg,#25D366,#128C7E);border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                <div class="card-title" style="color:#fff;"><i class="fa-brands fa-whatsapp" style="margin-right:6px;"></i> Produk Custom — Hubungi Sales</div>
            </div>
            <div class="card-body">
                <p class="text-sm text-muted mb-4" style="line-height:1.7;">
                    Produk <strong>{{ $product->name }}</strong> adalah solusi custom yang dikerjakan sesuai kebutuhan spesifik bisnis Anda.
                    Tim kami akan memberikan estimasi harga, timeline, dan proposal teknis secara gratis.
                </p>
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}"
                   target="_blank" class="btn btn-primary w-full" style="justify-content:center;font-size:15px;padding:14px;background:#25D366;border-color:#25D366;">
                    <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i>
                    Chat WhatsApp — Konsultasi Gratis
                </a>
                @if($product->demo_url)
                <a href="{{ $product->demo_url }}" target="_blank" class="btn btn-outline w-full mt-2" style="justify-content:center;">
                    <i class="fa-solid fa-desktop"></i> Lihat Demo
                </a>
                @endif
            </div>
        </div>

        @else
        {{-- REGULAR PRODUCT → Plan Picker --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fa-solid fa-tags" style="color:var(--primary);margin-right:6px;"></i>
                    Pilih Paket Langganan
                </div>
            </div>
            <div class="card-body">
                @if($plans->count() === 0)
                    <div class="text-center text-muted text-sm py-4">
                        <i class="fa-solid fa-circle-info" style="font-size:24px;margin-bottom:8px;display:block;"></i>
                        Belum ada paket tersedia. Hubungi kami untuk informasi harga.
                    </div>
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank"
                       class="btn btn-primary w-full" style="justify-content:center;">
                        <i class="fa-brands fa-whatsapp"></i> Tanya Harga via WhatsApp
                    </a>
                @else
                <form method="GET" action="{{ route('customer.subscriptions.create') }}" id="subscribeForm">
                    <input type="hidden" name="product" value="{{ $product->slug ?? $product->id }}">

                    {{-- Plan cards --}}
                    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                        @foreach($plans as $idx => $plan)
                        <label class="plan-card-select {{ $idx === 0 ? 'selected' : '' }}" for="plan_{{ $plan->id }}">
                            <input type="radio" name="plan" id="plan_{{ $plan->id }}" value="{{ $plan->id }}"
                                   {{ $idx === 0 ? 'checked' : '' }}
                                   onchange="selectPlan(this)">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-bold text-base">{{ $plan->name }}</div>
                                    <div class="text-xs text-muted">
                                        @if(($plan->duration_months ?? 1) >= 999)
                                            Lifetime ·
                                        @else
                                            {{ $plan->duration_months ?? 1 }} bulan ·
                                        @endif
                                        {{ ucfirst($plan->billing_cycle) }}
                                        @if($plan->description) · {{ $plan->description }} @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($plan->discount_percent > 0)
                                    <div class="text-xs text-muted line-through">Rp {{ number_format($plan->price, 0, ',', '.') }}</div>
                                    @php $discounted = $plan->price * (1 - $plan->discount_percent / 100); @endphp
                                    <div class="font-bold" style="color:var(--primary);">Rp {{ number_format($discounted, 0, ',', '.') }}</div>
                                    <div class="text-xs" style="color:var(--success);">Hemat {{ $plan->discount_percent }}%</div>
                                    @else
                                    <div class="font-bold" style="color:var(--primary);">Rp {{ number_format($plan->price, 0, ',', '.') }}</div>
                                    @endif
                                    <div class="text-xs text-muted">
                                        @if(($plan->duration_months ?? 1) >= 999)
                                            Sekali Bayar
                                        @else
                                            /{{ $plan->billing_cycle }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Domain input --}}
                    @php
                        $trialSubdomain = $activeTrial ? $activeTrial->requested_subdomain : null;
                        $waCustomDomainMsg = urlencode("Halo COOCA.ID, saya ingin request setup custom domain untuk langganan {$product->name} saya.");
                    @endphp
                    
                    {{-- Step 3: Domain Selection with Hostinger Integration --}}
                    <div class="form-group mb-3">
                        <label class="form-label font-bold text-sm" style="display:flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-globe" style="color:var(--primary);"></i> Tipe Domain
                        </label>
                        <div class="flex gap-4" style="flex-wrap: wrap;">
                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded" style="border:1px solid var(--border); background:var(--card); color:var(--text);">
                                <input type="radio" name="domain_type" value="subdomain" checked onchange="toggleDomainType()">
                                <span class="font-medium text-sm">Subdomain COOCA.ID <span class="badge badge-success" style="font-size:10px; margin-left:4px;">Gratis</span></span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded" style="border:1px solid var(--border); background:var(--card); color:var(--text);">
                                <input type="radio" name="domain_type" value="custom" onchange="toggleDomainType()">
                                <span class="font-medium text-sm">Custom Domain Sendiri</span>
                            </label>
                        </div>
                    </div>

                    {{-- Subdomain Container --}}
                    <div id="subdomain_container" class="form-group mb-4">
                        <label class="form-label">Subdomain Instance <span style="color:var(--danger);">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="domain" id="subdomain_input" class="form-input" placeholder="namabisnisanda"
                                   pattern="[a-zA-Z0-9\-]+" title="Huruf kecil, angka, dan tanda hubung" autocomplete="off" required
                                   value="{{ $trialSubdomain ?? '' }}">
                            <span class="font-bold text-muted text-sm">.cooca.id</span>
                        </div>
                        @if($trialSubdomain)
                        <div class="form-hint" style="color:var(--success); margin-top: 6px;">
                            <i class="fa-solid fa-check-circle"></i> Terisi otomatis dari subdomain trial Anda sebelumnya.
                        </div>
                        @else
                        <div class="form-hint" id="subdomain_hint">Instance Anda akan dapat diakses di <code>namabisnis.cooca.id</code></div>
                        @endif
                    </div>

                    {{-- Custom Domain Container with Hostinger Integration --}}
                    <div id="custom_domain_container" class="form-group mb-4" style="display:none;">
                        
                        {{-- Custom Domain Sub-Tabs --}}
                        <div class="flex gap-2 mb-3 p-1 rounded-lg" style="background:var(--primary-soft); border:1px solid var(--border);">
                            <button type="button" id="tabBtnBuy" onclick="switchCustomDomainTab('buy')"
                                class="btn btn-sm flex-1 font-bold"
                                style="border-radius:6px; transition:all .2s; background:var(--primary); color:#fff; border:none;">
                                <i class="fa-solid fa-cart-shopping"></i> Cari & Beli Domain Baru <span class="badge" style="background:rgba(255,255,255,0.2); font-size:9px; margin-left:4px;">Hostinger API</span>
                            </button>
                            <button type="button" id="tabBtnConnect" onclick="switchCustomDomainTab('connect')"
                                class="btn btn-sm flex-1 font-medium"
                                style="border-radius:6px; transition:all .2s; background:transparent; color:var(--text-muted); border:none;">
                                <i class="fa-solid fa-link"></i> Hubungkan Domain Sendiri
                            </button>
                        </div>

                        {{-- Hidden inputs for selected domain & custom domain mode --}}
                        <input type="hidden" name="custom_domain_action" id="custom_domain_action" value="buy">
                        <input type="hidden" name="domain_price" id="selected_domain_price" value="0">
                        <input type="hidden" name="custom_domain_ignore" id="final_custom_domain" value="">

                        {{-- TAB 1: Search & Buy Domain via Hostinger --}}
                        <div id="tab_buy_domain" style="display:block;">
                            <div class="p-3 rounded-lg mb-3" style="background:var(--surface); border:1px solid var(--border);">
                                <label class="form-label text-xs font-semibold text-muted mb-2">Cari Ketersediaan & Harga Domain (Live Kurs)</label>
                                <div class="flex gap-2">
                                    <div style="position:relative; flex:1;">
                                        <input type="text" id="hostinger_search_input" class="form-input w-full"
                                            placeholder="Ketik nama bisnis (contoh: tokoku, erpbinsis)"
                                            onkeypress="if(event.key === 'Enter'){ event.preventDefault(); searchHostingerDomain(); }">
                                    </div>
                                    <button type="button" id="btnCheckHostinger" onclick="searchHostingerDomain()"
                                        class="btn btn-primary btn-sm px-4" style="font-weight:600; white-space:nowrap;">
                                        <i class="fa-solid fa-magnifying-glass"></i> Cek Domain
                                    </button>
                                </div>
                                <div class="text-xs text-muted mt-2" style="display:flex; align-items:center; gap:6px;">
                                    <span>Populer:</span>
                                    <span class="badge cursor-pointer" onclick="setSearchQuery('bisnisku')">.com</span>
                                    <span class="badge cursor-pointer" onclick="setSearchQuery('bisnisku')">.id</span>
                                    <span class="badge cursor-pointer" onclick="setSearchQuery('bisnisku')">.co.id</span>
                                    <span class="badge cursor-pointer" onclick="setSearchQuery('bisnisku')">.shop</span>
                                    <span class="badge cursor-pointer" onclick="setSearchQuery('bisnisku')">.online</span>
                                </div>
                            </div>

                            {{-- Search Results / State --}}
                            <div id="hostinger_loading" style="display:none; text-align:center; padding:20px; color:var(--primary);">
                                <i class="fa-solid fa-circle-notch fa-spin fa-2x mb-2"></i>
                                <div class="text-xs font-semibold">Mengecek ketersediaan & harga domain di Hostinger...</div>
                            </div>

                            <div id="hostinger_results_container" style="display:none;">
                                <div class="text-xs font-bold mb-2 text-muted uppercase tracking-wider" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:4px;">
                                    <span>Hasil Pengecekan Domain:</span>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span id="hostinger_rate_badge" class="badge" style="background:var(--bg-secondary); border:1px solid var(--border); font-size:10px; color:var(--text-muted); font-weight:normal;">
                                            <i class="fa-solid fa-coins" style="color:#F59E0B;"></i> Kurs Live
                                        </span>
                                        <span class="text-primary font-normal text-xs"><i class="fa-solid fa-bolt"></i> Realtime Catalog</span>
                                    </div>
                                </div>
                                <div id="hostinger_domain_list" style="display:flex; flex-direction:column; gap:8px; max-height:280px; overflow-y:auto; padding-right:4px;">
                                    {{-- Rendered via JS --}}
                                </div>

                                {{-- Selected Domain Alert Banner --}}
                                <div id="selected_domain_banner" class="mt-3 p-3 rounded-lg"
                                    style="display:none; background:var(--success-soft); border:1px solid var(--success);">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <div>
                                            <div class="text-xs text-muted">Domain yang dipilih untuk didaftarkan:</div>
                                            <div class="font-bold text-sm text-success" id="selected_domain_text">tokosaya.com</div>
                                            <div class="text-xs font-semibold" style="color:var(--text);" id="selected_domain_price_text">+ Rp 159.000 / tahun</div>
                                        </div>
                                        <button type="button" onclick="clearSelectedDomain()" class="btn btn-outline btn-sm text-danger" style="border-color:var(--danger); padding:4px 8px; font-size:11px;">
                                            <i class="fa-solid fa-xmark"></i> Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 2: Connect Existing Domain --}}
                        <div id="tab_connect_domain" style="display:none;">
                            <div class="p-3 rounded-lg" style="background:var(--surface); border:1px solid var(--border);">
                                <label class="form-label text-xs font-semibold mb-1">Nama Custom Domain yang Sudah Anda Miliki <span style="color:var(--danger);">*</span></label>
                                <input type="text" id="manual_custom_domain_input" class="form-input w-full mb-3"
                                    placeholder="contoh: erp.perusahaananda.com atau bisnisanda.com"
                                    oninput="onManualDomainInput(this.value)">

                                <div style="padding:12px; border-radius:8px; background:var(--primary-soft); border:1px dashed var(--primary);">
                                    <div class="text-xs font-bold mb-1" style="color:var(--primary); display:flex; align-items:center; gap:6px;">
                                        <i class="fa-solid fa-server"></i> Panduan Pengaturan DNS (Arahkan ke COOCA.ID)
                                    </div>
                                    <div class="text-xs text-muted mb-2" style="line-height:1.5;">
                                        Arahkan DNS domain Anda di registrar domain Anda saat ini dengan salah satu konfigurasi berikut:
                                    </div>
                                    <div class="grid-2 gap-2 text-xs" style="font-family:monospace; background:var(--bg-secondary); padding:8px; border-radius:6px; border:1px solid var(--border);">
                                        <div><strong>Type:</strong> CNAME<br><strong>Host:</strong> @ atau subdomain<br><strong>Value:</strong> <code>cname.cooca.id</code></div>
                                        <div><strong>Type:</strong> A Record<br><strong>Host:</strong> @<br><strong>Value:</strong> <code>103.187.146.10</code></div>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waCustomDomainMsg }}" target="_blank"
                                            class="btn btn-outline btn-sm" style="border-color:#25D366; color:#25D366; font-size:11px; padding:4px 10px;">
                                            <i class="fa-brands fa-whatsapp"></i> Butuh bantuan CS WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        let selectedHostingerDomain = null;
                        let selectedHostingerPrice = 0;

                        function toggleDomainType() {
                            const type = document.querySelector('input[name="domain_type"]:checked').value;
                            const subInput = document.getElementById('subdomain_input');
                            const finalCustom = document.getElementById('final_custom_domain');
                            
                            if (type === 'subdomain') {
                                document.getElementById('subdomain_container').style.display = 'block';
                                document.getElementById('custom_domain_container').style.display = 'none';
                                subInput.required = true;
                                subInput.name = 'domain';
                                finalCustom.required = false;
                                finalCustom.name = 'custom_domain_ignore';
                                selectedHostingerPrice = 0;
                            } else {
                                document.getElementById('subdomain_container').style.display = 'none';
                                document.getElementById('custom_domain_container').style.display = 'block';
                                subInput.required = false;
                                subInput.name = 'subdomain_ignore';
                                finalCustom.required = true;
                                finalCustom.name = 'domain';
                            }
                        }

                        function switchCustomDomainTab(tab) {
                            const btnBuy = document.getElementById('tabBtnBuy');
                            const btnConnect = document.getElementById('tabBtnConnect');
                            const tabBuy = document.getElementById('tab_buy_domain');
                            const tabConnect = document.getElementById('tab_connect_domain');
                            const actionInput = document.getElementById('custom_domain_action');

                            if (tab === 'buy') {
                                actionInput.value = 'buy';
                                tabBuy.style.display = 'block';
                                tabConnect.style.display = 'none';

                                btnBuy.style.background = 'var(--primary)';
                                btnBuy.style.color = '#fff';
                                btnConnect.style.background = 'transparent';
                                btnConnect.style.color = 'var(--text-muted)';

                                if (selectedHostingerDomain) {
                                    document.getElementById('final_custom_domain').value = selectedHostingerDomain;
                                    document.getElementById('selected_domain_price').value = selectedHostingerPrice;
                                }
                            } else {
                                actionInput.value = 'connect';
                                tabBuy.style.display = 'none';
                                tabConnect.style.display = 'block';

                                btnConnect.style.background = 'var(--primary)';
                                btnConnect.style.color = '#fff';
                                btnBuy.style.background = 'transparent';
                                btnBuy.style.color = 'var(--text-muted)';

                                selectedHostingerPrice = 0;
                                document.getElementById('selected_domain_price').value = 0;
                                const manualVal = document.getElementById('manual_custom_domain_input').value.trim();
                                document.getElementById('final_custom_domain').value = manualVal;
                            }
                        }

                        function onManualDomainInput(val) {
                            document.getElementById('final_custom_domain').value = val.trim();
                        }

                        function setSearchQuery(q) {
                            const input = document.getElementById('hostinger_search_input');
                            input.value = q;
                            searchHostingerDomain();
                        }

                        function searchHostingerDomain() {
                            const input = document.getElementById('hostinger_search_input');
                            const query = input.value.trim();
                            if (!query) {
                                alert('Silakan masukkan nama domain terlebih dahulu.');
                                input.focus();
                                return;
                            }

                            const loading = document.getElementById('hostinger_loading');
                            const container = document.getElementById('hostinger_results_container');
                            const list = document.getElementById('hostinger_domain_list');
                            const btn = document.getElementById('btnCheckHostinger');

                            loading.style.display = 'block';
                            container.style.display = 'none';
                            btn.disabled = true;

                            fetch(`{{ route('customer.subscriptions.check-hostinger-domain', [], false) }}?domain=${encodeURIComponent(query)}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                loading.style.display = 'none';
                                btn.disabled = false;
                                container.style.display = 'block';
                                list.innerHTML = '';

                                if (data.rate_info) {
                                    const rateBadge = document.getElementById('hostinger_rate_badge');
                                    if (rateBadge) {
                                        rateBadge.innerHTML = `<i class="fa-solid fa-coins" style="color:#F59E0B;"></i> Kurs: ${data.rate_info}`;
                                    }
                                }

                                if (!data.success || !data.results || data.results.length === 0) {
                                    list.innerHTML = `<div class="p-3 text-xs text-muted text-center">${data.message || 'Tidak ada data domain ditemukan.'}</div>`;
                                    return;
                                }

                                data.results.forEach(item => {
                                    const isSelected = selectedHostingerDomain === item.domain;
                                    const card = document.createElement('div');
                                    card.className = 'p-2 rounded-lg flex items-center justify-between';
                                    card.style.border = isSelected ? '2px solid var(--primary)' : '1px solid var(--border)';
                                    card.style.background = isSelected ? 'var(--primary-soft)' : 'var(--surface)';
                                    card.style.transition = 'all .15s';

                                    let actionHtml = '';
                                    if (item.is_available) {
                                        actionHtml = isSelected
                                            ? `<button type="button" class="btn btn-sm btn-success" style="font-size:11px; padding:4px 10px;" disabled><i class="fa-solid fa-check"></i> Terpilih</button>`
                                            : `<button type="button" onclick="selectHostingerDomain('${item.domain}', ${item.price_idr}, '${item.price_formatted}')" class="btn btn-primary btn-sm" style="font-size:11px; padding:4px 10px;">Pilih Domain</button>`;
                                    } else {
                                        actionHtml = `<span class="badge badge-danger" style="font-size:10px; padding:3px 8px;">Terpakai</span>`;
                                    }

                                    card.innerHTML = `
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <i class="fa-solid fa-${item.is_available ? 'circle-check text-success' : 'circle-xmark text-danger'}"></i>
                                            <div>
                                                <span class="font-bold text-sm" style="color:var(--text);">${item.domain}</span>
                                                <div class="text-xs text-muted">${item.is_available ? '<span class="text-success font-semibold">Tersedia untuk didaftarkan</span>' : '<span class="text-danger">Sudah digunakan orang lain</span>'}</div>
                                            </div>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <div class="text-right">
                                                <div class="font-bold text-sm" style="color:var(--primary);">${item.price_formatted}</div>
                                                <div class="text-xs text-muted" style="font-size:10px;">/${item.period}</div>
                                            </div>
                                            ${actionHtml}
                                        </div>
                                    `;
                                    list.appendChild(card);
                                });

                                // Render Alternative suggestions if available
                                if (data.alternatives && data.alternatives.length > 0) {
                                    const altHeader = document.createElement('div');
                                    altHeader.className = 'text-xs font-bold text-muted uppercase mt-2 mb-1';
                                    altHeader.innerHTML = '<i class="fa-solid fa-lightbulb text-warning"></i> Alternatif Rekomendasi Nama:';
                                    list.appendChild(altHeader);

                                    data.alternatives.forEach(alt => {
                                        const isSelected = selectedHostingerDomain === alt.domain;
                                        const card = document.createElement('div');
                                        card.className = 'p-2 rounded-lg flex items-center justify-between';
                                        card.style.border = isSelected ? '2px solid var(--primary)' : '1px solid var(--border)';
                                        card.style.background = isSelected ? 'var(--primary-soft)' : 'var(--surface)';

                                        card.innerHTML = `
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <i class="fa-solid fa-sparkles text-primary"></i>
                                                <div>
                                                    <span class="font-bold text-sm" style="color:var(--text);">${alt.domain}</span>
                                                    <span class="badge badge-success" style="font-size:9px;">Tersedia</span>
                                                </div>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <div class="text-right">
                                                    <div class="font-bold text-sm" style="color:var(--primary);">${alt.price_formatted}</div>
                                                </div>
                                                <button type="button" onclick="selectHostingerDomain('${alt.domain}', ${alt.price_idr}, '${alt.price_formatted}')" class="btn btn-outline btn-sm" style="font-size:11px; padding:4px 10px;">Pilih</button>
                                            </div>
                                        `;
                                        list.appendChild(card);
                                    });
                                }
                            })
                            .catch(err => {
                                loading.style.display = 'none';
                                btn.disabled = false;
                                container.style.display = 'block';
                                list.innerHTML = `<div class="p-3 text-xs text-danger text-center">Gagal menghubungi API Hostinger: ${err.message}</div>`;
                            });
                        }

                        function selectHostingerDomain(domain, priceIdr, priceFormatted) {
                            selectedHostingerDomain = domain;
                            selectedHostingerPrice = priceIdr;

                            document.getElementById('final_custom_domain').value = domain;
                            document.getElementById('selected_domain_price').value = priceIdr;

                            const banner = document.getElementById('selected_domain_banner');
                            document.getElementById('selected_domain_text').innerText = domain;
                            document.getElementById('selected_domain_price_text').innerText = `+ ${priceFormatted} / tahun (Biaya Pendaftaran Domain)`;
                            banner.style.display = 'block';

                            // Refresh list cards active states
                            const searchInput = document.getElementById('hostinger_search_input');
                            if (searchInput.value.trim()) {
                                searchHostingerDomain();
                            }
                        }

                        function clearSelectedDomain() {
                            selectedHostingerDomain = null;
                            selectedHostingerPrice = 0;

                            document.getElementById('final_custom_domain').value = '';
                            document.getElementById('selected_domain_price').value = 0;
                            document.getElementById('selected_domain_banner').style.display = 'none';

                            const searchInput = document.getElementById('hostinger_search_input');
                            if (searchInput.value.trim()) {
                                searchHostingerDomain();
                            }
                        }
                    </script>

                    <button type="submit" class="btn btn-primary w-full" style="justify-content:center;font-size:15px;padding:14px;margin-top:4px;">
                        <i class="fa-solid fa-credit-card"></i>
                        Lanjut ke Pembayaran
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ── RIGHT SIDEBAR ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Product meta card --}}
        <div class="card">
            <div class="card-body text-center">
                @if($product->thumbnail)
                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                         style="width:80px;height:80px;object-fit:contain;border-radius:16px;margin:0 auto 12px;border:1px solid var(--border);padding:6px;">
                @else
                    <div class="product-logo-placeholder" style="width:80px;height:80px;font-size:32px;border-radius:16px;margin:0 auto 12px;">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </div>
                @endif
                <div class="font-bold text-lg">{{ $product->name }}</div>
                <div class="text-xs text-muted mb-3">v{{ $product->version ?? '5.0' }} · {{ $product->product_type_label }}</div>

                @if($product->demo_url)
                <a href="{{ $product->demo_url }}" target="_blank" class="btn btn-outline btn-sm w-full mb-2" style="justify-content:center;">
                    <i class="fa-solid fa-desktop"></i> Live Demo
                </a>
                @endif
            </div>
        </div>

        {{-- Trial CTA (only if not custom, not already trialing, not subscribed) --}}
        @if(!$isCustom && !$activeTrial && !$activeSub)
        <div class="card" style="background:linear-gradient(135deg,#6366f1,#8B5CF6);border:none;color:#fff;">
            <div class="card-body">
                <div class="font-bold text-base mb-2"><i class="fa-solid fa-flask" style="margin-right:6px;"></i>Coba 14 Hari Gratis</div>
                <p class="text-xs mb-3" style="opacity:.9;line-height:1.6;">
                    Akses penuh semua fitur tanpa kartu kredit. Upgrade ke paid kapan saja.
                </p>
                <a href="{{ route('customer.trials.create', ['product_id' => $product->id]) }}"
                   class="btn btn-sm w-full" style="justify-content:center;background:#fff;color:#6366f1;font-weight:700;">
                    <i class="fa-solid fa-rocket"></i> Request Trial Gratis
                </a>
            </div>
        </div>
        @endif

        {{-- WhatsApp CTA --}}
        <div class="card" style="border:1px solid #25D366;">
            <div class="card-body">
                <div class="font-bold text-sm mb-2"><i class="fa-brands fa-whatsapp" style="color:#25D366;margin-right:6px;"></i>Butuh Bantuan?</div>
                <p class="text-xs text-muted mb-3" style="line-height:1.6;">Tim kami siap membantu konsultasi, demo, atau pertanyaan teknis.</p>
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsg }}" target="_blank"
                   class="btn btn-sm w-full" style="justify-content:center;background:#25D366;color:#fff;border-color:#25D366;font-weight:600;">
                    <i class="fa-brands fa-whatsapp"></i> Chat Sekarang
                </a>
            </div>
        </div>

        {{-- Info points --}}
        <div style="border:1px solid var(--border);border-radius:var(--radius);padding:14px;">
            <div class="text-sm font-bold mb-2"><i class="fa-solid fa-shield-halved" style="color:var(--success);margin-right:6px;"></i>Kenapa COOCA.ID?</div>
            <div style="display:flex;flex-direction:column;gap:7px;">
                @foreach(['Cloud-hosted & auto-provisioned', 'SLA 99.9% uptime', 'Multi-tenant enterprise', 'Support via WhatsApp & ticket', 'Data backup harian'] as $p)
                <div class="flex items-center gap-2 text-xs text-muted">
                    <i class="fa-solid fa-check" style="color:var(--success);flex-shrink:0;"></i> {{ $p }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.plan-card-select {
    border: 2px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 14px 16px;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    display: block;
    background: var(--card);
    color: var(--text);
}
.plan-card-select:hover { border-color: var(--primary); background: var(--surface-hover); }
.plan-card-select.selected { border-color: var(--primary); background: var(--primary-soft); }
.plan-card-select input[type=radio] { position: absolute; opacity: 0; pointer-events: none; }
</style>

<script>
function selectPlan(radio) {
    document.querySelectorAll('.plan-card-select').forEach(c => c.classList.remove('selected'));
    radio.closest('.plan-card-select').classList.add('selected');
    // Update form hidden plan input
    document.querySelector('#subscribeForm input[name=plan]') &&
        (document.querySelector('#subscribeForm input[name=plan]').value = radio.value);
}

// Handle form redirect — pass plan into subscriptions/create
const form = document.getElementById('subscribeForm');
if (form) {
    form.addEventListener('submit', function(e) {
        const checked = form.querySelector('input[name=plan]:checked');
        if (checked) {
            // plan already in name=plan, just let it through as GET params
        }
    });

    const input = document.getElementById('subdomain_input');
    const hint = document.getElementById('subdomain_hint');
    let timeout = null;

    if (input) {
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const val = this.value.trim();
            
            if (!val) {
                hint.innerHTML = 'Instance Anda akan dapat diakses di <code>namabisnis.cooca.id</code>';
                hint.style.color = '';
                return;
            }

            // Basic regex validation first
            if (!/^[a-zA-Z0-9-]+$/.test(val)) {
                hint.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Hanya huruf, angka, dan strip yang diperbolehkan.';
                hint.style.color = 'var(--danger)';
                return;
            }

            hint.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Checking availability...';
            hint.style.color = 'var(--muted)';

            timeout = setTimeout(() => {
                fetch(`{{ route('customer.subscriptions.check-domain', [], false) }}?domain=${val}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => {
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        if (data.available) {
                            hint.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message}`;
                            hint.style.color = 'var(--success)';
                        } else {
                            hint.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> ${data.message}`;
                            hint.style.color = 'var(--danger)';
                        }
                    })
                    .catch((err) => {
                        hint.innerHTML = 'Gagal mengecek subdomain (' + err.message + ').';
                        hint.style.color = 'var(--danger)';
                    });
            }, 500); // 500ms debounce
        });
        
        // Trigger check on load if it has value
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    }
}
</script>
@endsection
