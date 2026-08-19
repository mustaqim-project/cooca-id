@extends('layouts.customer')
@section('title', 'Subscribe — Konfirmasi')
@section('breadcrumb')
    <a href="{{ route('customer.products.index') }}" class="crumb-link">My Services</a>
    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
    <span class="crumb-current">Subscribe</span>
@endsection

@section('content')
    @php
        $products = \App\Models\Product::with([
            'subscriptionPlans' => function ($q) {
                $q->where('is_active', true)->orderBy('price');
            },
        ])
            ->where('is_active', true)
            ->ordered()
            ->get();

        // Pre-fill from GET params (coming from products/show)
        $preProduct = request('product');
        $prePlan = request('plan');
        $preDomain = request('domain');

        $selectedProduct = $preProduct
            ? $products->first(fn($p) => $p->slug === $preProduct || $p->id === $preProduct)
            : null;
        $selectedPlan = null;
        if ($selectedProduct && $prePlan) {
            $selectedPlan = $selectedProduct->subscriptionPlans->firstWhere('id', $prePlan);
        }
        $waNumber = setting('contact.whatsapp', '6282134566667');
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fa-solid fa-cart-shopping" style="color:var(--primary);margin-right:10px;"></i>
                Konfirmasi Langganan
            </h1>
            <p class="page-subtitle">Verifikasi konfigurasi dan lanjutkan ke pembayaran.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <i class="fa-solid fa-circle-xmark"></i>
            <ul style="margin:0;padding-left:16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid-31" style="align-items:start;">

        <div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Konfigurasi Langganan</div>
                </div>
                <div class="card-body">
                    <form id="subscribeForm" method="POST" action="{{ route('customer.subscriptions.store') }}">
                        @csrf

                        {{-- Step 1: Product --}}
                        <div class="form-group">
                            <label class="form-label">Produk <span style="color:var(--danger);">*</span></label>
                            <select name="product_slug" id="productSelect" class="form-select" required
                                onchange="updatePlans()">
                                <option value="">— Pilih Produk —</option>
                                @foreach ($products as $prod)
                                    <option value="{{ $prod->slug }}"
                                        data-plans="{{ $prod->subscriptionPlans->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price, 'billing_cycle' => $p->billing_cycle, 'duration_months' => $p->duration_months, 'discount_percent' => $p->discount_percent ?? 0])->toJson() }}"
                                        {{ $selectedProduct && $selectedProduct->id === $prod->id ? 'selected' : '' }}>
                                        {{ $prod->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Step 2: Plan --}}
                        <div class="form-group" id="planGroup">
                            <label class="form-label">Paket <span style="color:var(--danger);">*</span></label>
                            <div id="planCards" style="display:flex;flex-direction:column;gap:8px;">
                                @if ($selectedProduct && $selectedProduct->subscriptionPlans->count() > 0)
                                    @foreach ($selectedProduct->subscriptionPlans as $idx => $plan)
                                        <label
                                            class="plan-card-select {{ ($selectedPlan ? $selectedPlan->id === $plan->id : $idx === 0) ? 'selected' : '' }}"
                                            for="plan_s_{{ $plan->id }}">
                                            <input type="radio" name="subscription_plan_id"
                                                id="plan_s_{{ $plan->id }}" value="{{ $plan->id }}"
                                                {{ ($selectedPlan ? $selectedPlan->id === $plan->id : $idx === 0) ? 'checked' : '' }}
                                                onchange="selectPlanCard(this)">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="font-bold">{{ $plan->name }}</div>
                                                    <div class="text-xs text-muted">{{ $plan->duration_months ?? 1 }} bulan
                                                        · {{ ucfirst($plan->billing_cycle) }}</div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="font-bold" style="color:var(--primary);">Rp
                                                        {{ number_format($plan->price, 0, ',', '.') }}</div>
                                                    <div class="text-xs text-muted">/{{ $plan->billing_cycle }}</div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-xs text-muted">Pilih produk untuk melihat paket yang tersedia.</p>
                                    <input type="hidden" name="subscription_plan_id" id="planHidden" value="">
                                @endif
                            </div>
                        </div>

                        {{-- Step 3: Domain --}}
                        @php
                            $waCustomDomainMsg = urlencode(
                                'Halo COOCA.ID, saya ingin request bantuan setup custom domain untuk langganan baru saya.',
                            );
                        @endphp

                        <div class="form-group mb-3">
                            <label class="form-label font-bold text-sm" style="display:flex; align-items:center; gap:8px;">
                                <i class="fa-solid fa-globe" style="color:var(--primary);"></i> Tipe Domain
                            </label>
                            <div class="flex gap-4" style="flex-wrap: wrap;">
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded" style="border:1px solid var(--border); background:var(--card-bg, var(--bg));">
                                    <input type="radio" name="domain_type" value="subdomain"
                                        {{ request('domain_type') !== 'custom' ? 'checked' : '' }}
                                        onchange="toggleDomainType()">
                                    <span class="font-medium text-sm">Subdomain COOCA.ID <span class="badge badge-success" style="font-size:10px; margin-left:4px;">Gratis</span></span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded" style="border:1px solid var(--border); background:var(--card-bg, var(--bg));">
                                    <input type="radio" name="domain_type" value="custom"
                                        {{ request('domain_type') === 'custom' ? 'checked' : '' }}
                                        onchange="toggleDomainType()">
                                    <span class="font-medium text-sm">Custom Domain Sendiri</span>
                                </label>
                            </div>
                        </div>

                        {{-- Subdomain Container --}}
                        <div id="subdomain_container" class="form-group mb-4"
                            style="{{ request('domain_type') === 'custom' ? 'display:none;' : '' }}">
                            <label class="form-label">Subdomain Instance <span style="color:var(--danger);">*</span></label>
                            <div class="flex items-center gap-2">
                                <input type="text"
                                    name="{{ request('domain_type') === 'custom' ? 'subdomain_ignore' : 'domain' }}"
                                    id="subdomain_input" class="form-input" placeholder="namabisnisanda"
                                    value="{{ request('domain_type') !== 'custom' ? old('domain', $preDomain) : '' }}"
                                    pattern="[a-zA-Z0-9\-]+" title="Huruf kecil, angka, tanda hubung" autocomplete="off"
                                    {{ request('domain_type') !== 'custom' ? 'required' : '' }}>
                                <span class="font-bold text-muted text-sm">.cooca.id</span>
                            </div>
                            <div class="form-hint" id="subdomain_hint">Akses ERP Anda di <code>namabisnis.cooca.id</code></div>
                        </div>

                        {{-- Custom Domain Container with Hostinger Integration --}}
                        <div id="custom_domain_container" class="form-group mb-4"
                            style="{{ request('domain_type') === 'custom' ? '' : 'display:none;' }}">
                            
                            {{-- Custom Domain Sub-Tabs --}}
                            <div class="flex gap-2 mb-3 p-1 rounded-lg" style="background:color-mix(in srgb, var(--primary) 6%, transparent); border:1px solid var(--border);">
                                <button type="button" id="tabBtnBuy" onclick="switchCustomDomainTab('buy')"
                                    class="btn btn-sm flex-1 font-bold"
                                    style="border-radius:6px; transition:all .2s; background:var(--primary); color:#fff; border:none;">
                                    <i class="fa-solid fa-cart-shopping"></i> Cari & Beli Domain Baru <span class="badge" style="background:rgba(255,255,255,0.2); font-size:9px; margin-left:4px;">Hostinger API</span>
                                </button>
                                <button type="button" id="tabBtnConnect" onclick="switchCustomDomainTab('connect')"
                                    class="btn btn-sm flex-1 font-medium"
                                    style="border-radius:6px; transition:all .2s; background:transparent; color:var(--text-muted, #666); border:none;">
                                    <i class="fa-solid fa-link"></i> Hubungkan Domain Sendiri
                                </button>
                            </div>

                            {{-- Hidden inputs for selected domain & custom domain mode --}}
                            <input type="hidden" name="custom_domain_action" id="custom_domain_action" value="buy">
                            <input type="hidden" name="domain_price" id="selected_domain_price" value="0">
                            <input type="hidden"
                                name="{{ request('domain_type') === 'custom' ? 'domain' : 'custom_domain_ignore' }}"
                                id="final_custom_domain"
                                value="{{ request('domain_type') === 'custom' ? old('domain', $preDomain) : '' }}">

                            {{-- TAB 1: Search & Buy Domain via Hostinger --}}
                            <div id="tab_buy_domain" style="display:block;">
                                <div class="p-3 rounded-lg mb-3" style="background:var(--card-bg, #fff); border:1px solid var(--border);">
                                    <label class="form-label text-xs font-semibold text-muted mb-2">Cari Ketersediaan & Harga Domain</label>
                                    <div class="flex gap-2">
                                        <div style="position:relative; flex:1;">
                                            <input type="text" id="hostinger_search_input" class="form-input w-full"
                                                placeholder="Ketik nama bisnis (contoh: tokoku, erpbinsis)"
                                                value="{{ preg_replace('/\..*$/', '', old('domain', $preDomain)) }}"
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
                                            <span id="hostinger_rate_badge" class="badge" style="background:var(--bg); border:1px solid var(--border); font-size:10px; color:var(--text-muted, #666); font-weight:normal;">
                                                <i class="fa-solid fa-coins" style="color:#F59E0B;"></i> Kurs: 1 USD = Rp 16.000
                                            </span>
                                            <span class="text-primary font-normal text-xs"><i class="fa-solid fa-bolt"></i> Realtime Catalog</span>
                                        </div>
                                    </div>
                                    <div id="hostinger_domain_list" style="display:flex; flex-direction:column; gap:8px; max-height:280px; overflow-y:auto; padding-right:4px;">
                                        {{-- Rendered via JS --}}
                                    </div>

                                    {{-- Selected Domain Alert Banner --}}
                                    <div id="selected_domain_banner" class="mt-3 p-3 rounded-lg"
                                        style="display:none; background:color-mix(in srgb, var(--success, #10B981) 12%, transparent); border:1px solid var(--success, #10B981);">
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
                                <div class="p-3 rounded-lg" style="background:var(--card-bg, #fff); border:1px solid var(--border);">
                                    <label class="form-label text-xs font-semibold mb-1">Nama Custom Domain yang Sudah Anda Miliki <span style="color:var(--danger);">*</span></label>
                                    <input type="text" id="manual_custom_domain_input" class="form-input w-full mb-3"
                                        placeholder="contoh: erp.perusahaananda.com atau bisnisanda.com"
                                        oninput="onManualDomainInput(this.value)">

                                    <div style="padding:12px; border-radius:8px; background:color-mix(in srgb, var(--primary) 5%, transparent); border:1px dashed var(--primary);">
                                        <div class="text-xs font-bold mb-1" style="color:var(--primary); display:flex; align-items:center; gap:6px;">
                                            <i class="fa-solid fa-server"></i> Panduan Pengaturan DNS (Arahkan ke COOCA.ID)
                                        </div>
                                        <div class="text-xs text-muted mb-2" style="line-height:1.5;">
                                            Arahkan DNS domain Anda di registrar domain Anda saat ini dengan salah satu konfigurasi berikut:
                                        </div>
                                        <div class="grid-2 gap-2 text-xs" style="font-family:monospace; background:var(--bg); padding:8px; border-radius:6px;">
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
                            let selectedHostingerDomain = {!! json_encode(request('custom_domain_action') === 'buy' ? request('domain') : null) !!};
                            let selectedHostingerPrice = {{ (int) request('domain_price', 0) }};

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
                                updateSummary();
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
                                    btnConnect.style.color = 'var(--text-muted, #666)';

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
                                    btnBuy.style.color = 'var(--text-muted, #666)';

                                    selectedHostingerPrice = 0;
                                    document.getElementById('selected_domain_price').value = 0;
                                    const manualVal = document.getElementById('manual_custom_domain_input').value.trim();
                                    document.getElementById('final_custom_domain').value = manualVal;
                                }
                                updateSummary();
                            }

                            function onManualDomainInput(val) {
                                document.getElementById('final_custom_domain').value = val.trim();
                                updateSummary();
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
                                        card.style.background = isSelected ? 'color-mix(in srgb, var(--primary) 8%, transparent)' : 'var(--bg)';
                                        card.style.transition = 'all .15s';

                                        let actionHtml = '';
                                        if (item.is_available) {
                                            actionHtml = isSelected
                                                ? `<button type="button" class="btn btn-sm btn-success" style="font-size:11px; padding:4px 10px;" disabled><i class="fa-solid fa-check"></i> Terpilih</button>`
                                                : `<button type="button" onclick="selectHostingerDomain('${item.domain}', ${item.price_idr}, '${item.price_formatted}')" class="btn btn-primary btn-sm" style="font-size:11px; padding:4px 10px;">Pilih Domain</button>`;
                                        } else {
                                            actionHtml = `<span class="badge" style="background:#EF4444; color:#fff; font-size:10px; padding:3px 8px;">Terpakai</span>`;
                                        }

                                        card.innerHTML = `
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <i class="fa-solid fa-${item.is_available ? 'circle-check text-success' : 'circle-xmark text-danger'}"></i>
                                                <div>
                                                    <span class="font-bold text-sm">${item.domain}</span>
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
                                            card.style.background = isSelected ? 'color-mix(in srgb, var(--primary) 8%, transparent)' : 'var(--bg)';

                                            card.innerHTML = `
                                                <div style="display:flex; align-items:center; gap:8px;">
                                                    <i class="fa-solid fa-sparkles text-primary"></i>
                                                    <div>
                                                        <span class="font-bold text-sm">${alt.domain}</span>
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
                                updateSummary();
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
                                updateSummary();
                            }
                        </script>

                        @if ($showCompanyFields)
                            <div class="divider mt-4 mb-4" style="border-top: 1px dashed var(--border); margin: 20px 0;">
                            </div>
                            <h3 class="font-bold text-sm mb-3"
                                style="color:var(--primary); display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-building"></i> Lengkapi Profil Perusahaan
                            </h3>
                            <p class="text-xs text-muted mb-4" style="line-height:1.4;">
                                Silakan lengkapi data profil perusahaan Anda terlebih dahulu. Data ini akan otomatis
                                memperbarui profil Anda dan digunakan untuk invoice/laporan.
                            </p>

                            <div class="grid-2">
                                <div class="form-group">
                                    <label class="form-label">Nama Perusahaan <span
                                            style="color:var(--danger);">*</span></label>
                                    <input type="text" name="company_name" class="form-input"
                                        value="{{ old('company_name', $companyProfile?->company_name ?? $customer->business_name) }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">NPWP / Tax ID</label>
                                    <input type="text" name="npwp" class="form-input"
                                        value="{{ old('npwp', $companyProfile?->npwp) }}"
                                        placeholder="e.g. 01.234.567.8-901.000">
                                </div>
                            </div>

                            <div class="grid-2">
                                <div class="form-group">
                                    <label class="form-label">Bidang Industri <span
                                            style="color:var(--danger);">*</span></label>
                                    <select name="industry" class="form-select" required>
                                        <option value="">— Pilih Industri —</option>
                                        <option value="retail"
                                            {{ old('industry', $companyProfile?->industry) === 'retail' ? 'selected' : '' }}>
                                            Retail & E-commerce</option>
                                        <option value="manufacturing"
                                            {{ old('industry', $companyProfile?->industry) === 'manufacturing' ? 'selected' : '' }}>
                                            Manufacturing & Production</option>
                                        <option value="services"
                                            {{ old('industry', $companyProfile?->industry) === 'services' ? 'selected' : '' }}>
                                            Professional Services & Consulting</option>
                                        <option value="technology"
                                            {{ old('industry', $companyProfile?->industry) === 'technology' ? 'selected' : '' }}>
                                            Technology, IT & Software</option>
                                        <option value="construction"
                                            {{ old('industry', $companyProfile?->industry) === 'construction' ? 'selected' : '' }}>
                                            Construction & Real Estate</option>
                                        <option value="healthcare"
                                            {{ old('industry', $companyProfile?->industry) === 'healthcare' ? 'selected' : '' }}>
                                            Healthcare & Medical</option>
                                        <option value="hospitality"
                                            {{ old('industry', $companyProfile?->industry) === 'hospitality' ? 'selected' : '' }}>
                                            Hospitality, Tourism & Food Services</option>
                                        <option value="education"
                                            {{ old('industry', $companyProfile?->industry) === 'education' ? 'selected' : '' }}>
                                            Education & Training</option>
                                        <option value="agriculture"
                                            {{ old('industry', $companyProfile?->industry) === 'agriculture' ? 'selected' : '' }}>
                                            Agriculture, Farming & Forestry</option>
                                        <option value="automotive"
                                            {{ old('industry', $companyProfile?->industry) === 'automotive' ? 'selected' : '' }}>
                                            Automotive, Workshop & Transportation</option>
                                        <option value="finance"
                                            {{ old('industry', $companyProfile?->industry) === 'finance' ? 'selected' : '' }}>
                                            Finance, Banking & Insurance</option>
                                        <option value="logistics"
                                            {{ old('industry', $companyProfile?->industry) === 'logistics' ? 'selected' : '' }}>
                                            Logistics & Supply Chain</option>
                                        <option value="wholesale"
                                            {{ old('industry', $companyProfile?->industry) === 'wholesale' ? 'selected' : '' }}>
                                            Wholesale & Distribution</option>
                                        <option value="creative"
                                            {{ old('industry', $companyProfile?->industry) === 'creative' ? 'selected' : '' }}>
                                            Entertainment, Media & Creative</option>
                                        <option value="other"
                                            {{ old('industry', $companyProfile?->industry) === 'other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ukuran Perusahaan <span
                                            style="color:var(--danger);">*</span></label>
                                    <select name="company_size" class="form-select" required>
                                        <option value="">— Pilih Ukuran —</option>
                                        <option value="1-10"
                                            {{ old('company_size', $companyProfile?->company_size) === '1-10' ? 'selected' : '' }}>
                                            1-10 Karyawan</option>
                                        <option value="11-50"
                                            {{ old('company_size', $companyProfile?->company_size) === '11-50' ? 'selected' : '' }}>
                                            11-50 Karyawan</option>
                                        <option value="51-200"
                                            {{ old('company_size', $companyProfile?->company_size) === '51-200' ? 'selected' : '' }}>
                                            51-200 Karyawan</option>
                                        <option value="201+"
                                            {{ old('company_size', $companyProfile?->company_size) === '201+' ? 'selected' : '' }}>
                                            200+ Karyawan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid-2">
                                <div class="form-group">
                                    <label class="form-label">Telepon Perusahaan <span
                                            style="color:var(--danger);">*</span></label>
                                    <input type="text" name="phone" class="form-input"
                                        value="{{ old('phone', $companyProfile?->phone ?? $customer->phone) }}"
                                        placeholder="e.g. +628123456789" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap <span
                                        style="color:var(--danger);">*</span></label>
                                <textarea name="address" class="form-textarea" rows="3" placeholder="Alamat lengkap perusahaan" required>{{ old('address', $companyProfile?->address) }}</textarea>
                            </div>

                            <div class="grid-3 mb-4">
                                <div class="form-group">
                                    <label class="form-label">Kota <span style="color:var(--danger);">*</span></label>
                                    <input type="text" name="city" class="form-input"
                                        value="{{ old('city', $companyProfile?->city) }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Provinsi <span style="color:var(--danger);">*</span></label>
                                    <input type="text" name="province" class="form-input"
                                        value="{{ old('province', $companyProfile?->province) }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kode Pos <span style="color:var(--danger);">*</span></label>
                                    <input type="text" name="postal_code" class="form-input"
                                        value="{{ old('postal_code', $companyProfile?->postal_code) }}" required>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary btn-lg w-full mt-2"
                            style="justify-content:center;">
                            <i class="fa-solid fa-credit-card"></i> Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Summary box --}}
            <div class="card" id="summaryBox">
                <div class="card-header">
                    <div class="card-title">Ringkasan Order</div>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:8px;" id="summaryContent">
                    @if ($selectedProduct && $selectedPlan)
                        <div class="stats-row"><span class="text-sm text-muted">Produk</span><span
                                class="font-bold text-sm">{{ $selectedProduct->name }}</span></div>
                        <div class="stats-row"><span class="text-sm text-muted">Paket</span><span
                                class="font-bold text-sm">{{ $selectedPlan->name }}</span></div>
                        <div class="stats-row"><span class="text-sm text-muted">Durasi</span><span
                                class="font-bold text-sm">{{ $selectedPlan->duration_months ?? 1 }} bulan</span></div>
                        <div class="divider"></div>
                        <div class="stats-row"><span class="font-bold">Total</span><span
                                class="font-bold text-primary">Rp
                                {{ number_format($selectedPlan->price, 0, ',', '.') }}</span></div>
                    @else
                        <p class="text-xs text-muted text-center py-2">Pilih produk & paket untuk melihat ringkasan.</p>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="card" style="background:linear-gradient(135deg,var(--primary),#7C3AED);border:none;color:#fff;">
                <div class="card-body">
                    <div class="font-bold text-sm mb-2">⚡ Auto-Provisioning</div>
                    <p class="text-xs" style="opacity:.9;line-height:1.6;">
                        Setelah pembayaran dikonfirmasi, instance ERP Anda akan aktif otomatis dalam ~60 detik.
                        Database, kredensial admin, dan SSL certificate disiapkan otomatis.
                    </p>
                </div>
            </div>

            <div style="text-align:center;padding:12px;border:1px solid var(--border);border-radius:var(--radius);">
                <div class="text-xs text-muted mb-2">Butuh paket custom atau enterprise?</div>
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo, saya ingin konsultasi paket enterprise COOCA.ID') }}"
                    target="_blank" class="btn btn-sm"
                    style="background:#25D366;color:#fff;border-color:#25D366;font-weight:600;width:100%;justify-content:center;">
                    <i class="fa-brands fa-whatsapp"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </div>

    <style>
        .plan-card-select {
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 12px 14px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            position: relative;
            display: block;
        }

        .plan-card-select:hover {
            border-color: var(--primary);
            background: var(--bg);
        }

        .plan-card-select.selected {
            border-color: var(--primary);
            background: color-mix(in srgb, var(--primary) 6%, transparent);
        }

        .plan-card-select input[type=radio] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
    </style>

    <script>
        function selectPlanCard(radio) {
            document.querySelectorAll('.plan-card-select').forEach(c => c.classList.remove('selected'));
            radio.closest('.plan-card-select').classList.add('selected');
            updateSummary();
        }

        function updatePlans() {
            const pSelect = document.getElementById('productSelect');
            const selected = pSelect.options[pSelect.selectedIndex];
            const planCards = document.getElementById('planCards');

            planCards.innerHTML = '<p class="text-xs text-muted">Memuat paket…</p>';

            if (!selected.value) {
                planCards.innerHTML = '<p class="text-xs text-muted">Pilih produk terlebih dahulu.</p>';
                updateSummary();
                return;
            }

            const plans = JSON.parse(selected.getAttribute('data-plans') || '[]');
            if (!plans.length) {
                planCards.innerHTML = '<p class="text-xs text-muted">Belum ada paket untuk produk ini.</p>';
                return;
            }

            planCards.innerHTML = '';
            plans.forEach((p, idx) => {
                const priceStr = `Rp ${new Intl.NumberFormat('id-ID').format(p.price)}`;
                const lbl = document.createElement('label');
                lbl.className = 'plan-card-select' + (idx === 0 ? ' selected' : '');
                lbl.setAttribute('for', 'plan_dyn_' + p.id);
                lbl.innerHTML = `
            <input type="radio" name="subscription_plan_id" id="plan_dyn_${p.id}" value="${p.id}"
                   ${idx === 0 ? 'checked' : ''} onchange="selectPlanCard(this)">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-bold">${p.name}</div>
                    <div class="text-xs text-muted">${p.duration_months || 1} bulan · ${p.billing_cycle}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold" style="color:var(--primary);">${priceStr}</div>
                    <div class="text-xs text-muted">/${p.billing_cycle}</div>
                </div>
            </div>`;
                planCards.appendChild(lbl);
            });
            updateSummary();
        }

        function updateSummary() {
            const pSelect = document.getElementById('productSelect');
            const checked = document.querySelector('input[name=subscription_plan_id]:checked');
            const box = document.getElementById('summaryContent');
            if (!pSelect.value || !checked) {
                box.innerHTML =
                    '<p class="text-xs text-muted text-center py-2">Pilih produk & paket untuk melihat ringkasan.</p>';
                return;
            }
            const pName = pSelect.options[pSelect.selectedIndex].text;
            const plans = JSON.parse(pSelect.options[pSelect.selectedIndex].getAttribute('data-plans') || '[]');
            const plan = plans.find(p => p.id === checked.value);
            if (!plan) return;

            const domainType = document.querySelector('input[name="domain_type"]:checked')?.value || 'subdomain';
            let domainInfo = '<span class="text-success font-semibold text-xs">Subdomain (Gratis)</span>';
            let domainCost = 0;

            if (domainType === 'custom') {
                const action = document.getElementById('custom_domain_action')?.value || 'buy';
                if (action === 'buy' && selectedHostingerDomain) {
                    domainCost = selectedHostingerPrice || 0;
                    domainInfo = `<span class="font-bold text-xs" style="color:var(--primary);">${selectedHostingerDomain}</span><br><span class="text-xs text-muted">+ Rp ${new Intl.NumberFormat('id-ID').format(domainCost)} / thn</span>`;
                } else if (action === 'connect') {
                    const manualVal = document.getElementById('manual_custom_domain_input')?.value.trim();
                    domainInfo = `<span class="font-bold text-xs">${manualVal || 'Custom Domain'}</span><br><span class="text-xs text-success">Setup DNS Mandiri (Gratis)</span>`;
                } else {
                    domainInfo = '<span class="text-muted text-xs">Pilih domain di atas</span>';
                }
            } else {
                const subVal = document.getElementById('subdomain_input')?.value.trim();
                if (subVal) {
                    domainInfo = `<span class="font-bold text-xs">${subVal}.cooca.id</span> <span class="badge badge-success" style="font-size:9px;">Gratis</span>`;
                }
            }

            const planPrice = Number(plan.price);
            const grandTotal = planPrice + domainCost;
            const planPriceStr = new Intl.NumberFormat('id-ID').format(planPrice);
            const grandTotalStr = new Intl.NumberFormat('id-ID').format(grandTotal);

            box.innerHTML =
                `
        <div class="stats-row"><span class="text-sm text-muted">Produk</span><span class="font-bold text-sm">${pName}</span></div>
        <div class="stats-row"><span class="text-sm text-muted">Paket</span><span class="font-bold text-sm">${plan.name}</span></div>
        <div class="stats-row"><span class="text-sm text-muted">Durasi Paket</span><span class="font-bold text-sm">${plan.duration_months || 1} bulan</span></div>
        <div class="stats-row"><span class="text-sm text-muted">Biaya Paket</span><span class="font-bold text-sm">Rp ${planPriceStr}</span></div>
        <div class="stats-row" style="align-items:flex-start;"><span class="text-sm text-muted">Domain</span><div class="text-right">${domainInfo}</div></div>
        <div class="divider"></div>
        <div class="stats-row"><span class="font-bold">Total Pembayaran</span><span class="font-bold text-primary" style="font-size:16px;">Rp ${grandTotalStr}</span></div>`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('subdomain_input');
            const hint = document.getElementById('subdomain_hint');
            let timeout = null;

            if (input) {
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const val = this.value.trim();

                    if (!val) {
                        hint.innerHTML = 'Akses ERP Anda di <code>namabisnis.cooca.id</code>';
                        hint.style.color = '';
                        return;
                    }

                    // Basic regex validation first
                    if (!/^[a-zA-Z0-9-]+$/.test(val)) {
                        hint.innerHTML =
                            '<i class="fa-solid fa-circle-xmark"></i> Hanya huruf, angka, dan strip yang diperbolehkan.';
                        hint.style.color = 'var(--danger)';
                        return;
                    }

                    hint.innerHTML =
                        '<i class="fa-solid fa-circle-notch fa-spin"></i> Checking availability...';
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
                                    hint.innerHTML =
                                        `<i class="fa-solid fa-circle-check"></i> ${data.message}`;
                                    hint.style.color = 'var(--success)';
                                } else {
                                    hint.innerHTML =
                                        `<i class="fa-solid fa-circle-xmark"></i> ${data.message}`;
                                    hint.style.color = 'var(--danger)';
                                }
                            })
                            .catch((err) => {
                                hint.innerHTML = 'Gagal mengecek subdomain (' + err.message +
                                    ').';
                                hint.style.color = 'var(--danger)';
                            });
                    }, 500); // 500ms debounce
                });

                // Trigger check on load if it has value
                if (input.value) {
                    input.dispatchEvent(new Event('input'));
                }
            }

            // Init summary on load
            updateSummary();
        });
    </script>
@endsection
